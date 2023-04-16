<?php

final class PhorgeConfigSchemaQuery extends Phobject {

  private $refs;
  private $apis;

  public function setRefs(array $refs) {
    $this->refs = $refs;
    return $this;
  }

  public function getRefs() {
    if (!$this->refs) {
      return PhorgeDatabaseRef::getMasterDatabaseRefs();
    }
    return $this->refs;
  }

  public function setAPIs(array $apis) {
    $map = array();
    foreach ($apis as $api) {
      $map[$api->getRef()->getRefKey()] = $api;
    }
    $this->apis = $map;
    return $this;
  }

  private function getDatabaseNames(PhorgeDatabaseRef $ref) {
    $api = $this->getAPI($ref);
    $patches = PhorgeSQLPatchList::buildAllPatches();
    return $api->getDatabaseList(
      $patches,
      $only_living = true);
  }

  private function getAPI(PhorgeDatabaseRef $ref) {
    $key = $ref->getRefKey();

    if (isset($this->apis[$key])) {
      return $this->apis[$key];
    }

    return id(new PhorgeStorageManagementAPI())
      ->setUser($ref->getUser())
      ->setHost($ref->getHost())
      ->setPort($ref->getPort())
      ->setNamespace(PhorgeLiskDAO::getDefaultStorageNamespace())
      ->setPassword($ref->getPass());
  }

  public function loadActualSchemata() {
    $refs = $this->getRefs();

    $schemata = array();
    foreach ($refs as $ref) {
      $schema = $this->loadActualSchemaForServer($ref);
      $schemata[$schema->getRef()->getRefKey()] = $schema;
    }

    return $schemata;
  }

  private function loadActualSchemaForServer(PhorgeDatabaseRef $ref) {
    $databases = $this->getDatabaseNames($ref);

    $conn = $ref->newManagementConnection();

    $tables = queryfx_all(
      $conn,
      'SELECT TABLE_SCHEMA, TABLE_NAME, TABLE_COLLATION, ENGINE
        FROM INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA IN (%Ls)',
      $databases);

    $database_info = queryfx_all(
      $conn,
      'SELECT SCHEMA_NAME, DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME
        FROM INFORMATION_SCHEMA.SCHEMATA
        WHERE SCHEMA_NAME IN (%Ls)',
      $databases);
    $database_info = ipull($database_info, null, 'SCHEMA_NAME');

    // Find databases which exist, but which the user does not have permission
    // to see.
    $invisible_databases = array();
    foreach ($databases as $database_name) {
      if (isset($database_info[$database_name])) {
        continue;
      }

      try {
        queryfx($conn, 'SHOW TABLES IN %T', $database_name);
      } catch (AphrontAccessDeniedQueryException $ex) {
        // This database exists, the user just doesn't have permission to
        // see it.
        $invisible_databases[] = $database_name;
      } catch (AphrontSchemaQueryException $ex) {
        // This database is legitimately missing.
      }
    }

    $sql = array();
    foreach ($tables as $table) {
      $sql[] = qsprintf(
        $conn,
        '(TABLE_SCHEMA = %s AND TABLE_NAME = %s)',
        $table['TABLE_SCHEMA'],
        $table['TABLE_NAME']);
    }

    if ($sql) {
      $column_info = queryfx_all(
        $conn,
        'SELECT TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME, CHARACTER_SET_NAME,
            COLLATION_NAME, COLUMN_TYPE, IS_NULLABLE, EXTRA
          FROM INFORMATION_SCHEMA.COLUMNS
          WHERE %LO',
        $sql);
      $column_info = igroup($column_info, 'TABLE_SCHEMA');
    } else {
      $column_info = array();
    }

    // NOTE: Tables like KEY_COLUMN_USAGE and TABLE_CONSTRAINTS only contain
    // primary, unique, and foreign keys, so we can't use them here. We pull
    // indexes later on using SHOW INDEXES.

    $server_schema = id(new PhorgeConfigServerSchema())
      ->setRef($ref);

    $tables = igroup($tables, 'TABLE_SCHEMA');
    foreach ($tables as $database_name => $database_tables) {
      $info = $database_info[$database_name];

      $database_schema = id(new PhorgeConfigDatabaseSchema())
        ->setName($database_name)
        ->setCharacterSet($info['DEFAULT_CHARACTER_SET_NAME'])
        ->setCollation($info['DEFAULT_COLLATION_NAME']);

      $database_column_info = idx($column_info, $database_name, array());
      $database_column_info = igroup($database_column_info, 'TABLE_NAME');

      foreach ($database_tables as $table) {
        $table_name = $table['TABLE_NAME'];

        $table_schema = id(new PhorgeConfigTableSchema())
          ->setName($table_name)
          ->setCollation($table['TABLE_COLLATION'])
          ->setEngine($table['ENGINE']);

        $columns = idx($database_column_info, $table_name, array());
        foreach ($columns as $column) {
          if (strpos($column['EXTRA'], 'auto_increment') === false) {
            $auto_increment = false;
          } else {
            $auto_increment = true;
          }

          $column_schema = id(new PhorgeConfigColumnSchema())
            ->setName($column['COLUMN_NAME'])
            ->setCharacterSet($column['CHARACTER_SET_NAME'])
            ->setCollation($column['COLLATION_NAME'])
            ->setColumnType($column['COLUMN_TYPE'])
            ->setNullable($column['IS_NULLABLE'] == 'YES')
            ->setAutoIncrement($auto_increment);

          $table_schema->addColumn($column_schema);
        }

        $key_parts = queryfx_all(
          $conn,
          'SHOW INDEXES FROM %T.%T',
          $database_name,
          $table_name);
        $keys = igroup($key_parts, 'Key_name');
        foreach ($keys as $key_name => $key_pieces) {
          $key_pieces = isort($key_pieces, 'Seq_in_index');
          $head = head($key_pieces);

          // This handles string indexes which index only a prefix of a field.
          $column_names = array();
          foreach ($key_pieces as $piece) {
            $name = $piece['Column_name'];
            if ($piece['Sub_part']) {
              $name = $name.'('.$piece['Sub_part'].')';
            }
            $column_names[] = $name;
          }

          $key_schema = id(new PhorgeConfigKeySchema())
            ->setName($key_name)
            ->setColumnNames($column_names)
            ->setUnique(!$head['Non_unique'])
            ->setIndexType($head['Index_type']);

          $table_schema->addKey($key_schema);
        }

        $database_schema->addTable($table_schema);
      }

      $server_schema->addDatabase($database_schema);
    }

    foreach ($invisible_databases as $database_name) {
      $server_schema->addDatabase(
        id(new PhorgeConfigDatabaseSchema())
          ->setName($database_name)
          ->setAccessDenied(true));
    }

    return $server_schema;
  }

  public function loadExpectedSchemata() {
    $refs = $this->getRefs();

    $schemata = array();
    foreach ($refs as $ref) {
      $schema = $this->loadExpectedSchemaForServer($ref);
      $schemata[$schema->getRef()->getRefKey()] = $schema;
    }

    return $schemata;
  }

  public function loadExpectedSchemaForServer(PhorgeDatabaseRef $ref) {
    $databases = $this->getDatabaseNames($ref);
    $info = $this->getAPI($ref)->getCharsetInfo();

    $specs = id(new PhutilClassMapQuery())
      ->setAncestorClass('PhorgeConfigSchemaSpec')
      ->execute();

    $server_schema = id(new PhorgeConfigServerSchema())
      ->setRef($ref);

    foreach ($specs as $spec) {
      $spec
        ->setUTF8Charset(
          $info[PhorgeStorageManagementAPI::CHARSET_DEFAULT])
        ->setUTF8BinaryCollation(
          $info[PhorgeStorageManagementAPI::COLLATE_TEXT])
        ->setUTF8SortingCollation(
          $info[PhorgeStorageManagementAPI::COLLATE_SORT])
        ->setServer($server_schema)
        ->buildSchemata($server_schema);
    }

    return $server_schema;
  }

  public function buildComparisonSchemata(
    array $expect_servers,
    array $actual_servers) {

    $schemata = array();
    foreach ($actual_servers as $key => $actual_server) {
      $schemata[$key] = $this->buildComparisonSchemaForServer(
        $expect_servers[$key],
        $actual_server);
    }

    return $schemata;
  }

  private function buildComparisonSchemaForServer(
    PhorgeConfigServerSchema $expect,
    PhorgeConfigServerSchema $actual) {

    $comp_server = $actual->newEmptyClone();

    $all_databases = $actual->getDatabases() + $expect->getDatabases();
    foreach ($all_databases as $database_name => $database_template) {
      $actual_database = $actual->getDatabase($database_name);
      $expect_database = $expect->getDatabase($database_name);

      $issues = $this->compareSchemata($expect_database, $actual_database);

      $comp_database = $database_template->newEmptyClone()
        ->setIssues($issues);

      if (!$actual_database) {
        $actual_database = $expect_database->newEmptyClone();
      }

      if (!$expect_database) {
        $expect_database = $actual_database->newEmptyClone();
      }

      $all_tables =
        $actual_database->getTables() +
        $expect_database->getTables();
      foreach ($all_tables as $table_name => $table_template) {
        $actual_table = $actual_database->getTable($table_name);
        $expect_table = $expect_database->getTable($table_name);

        $issues = $this->compareSchemata($expect_table, $actual_table);

        $comp_table = $table_template->newEmptyClone()
          ->setIssues($issues);

        if (!$actual_table) {
          $actual_table = $expect_table->newEmptyClone();
        }
        if (!$expect_table) {
          $expect_table = $actual_table->newEmptyClone();
        }

        $all_columns =
          $actual_table->getColumns() +
          $expect_table->getColumns();
        foreach ($all_columns as $column_name => $column_template) {
          $actual_column = $actual_table->getColumn($column_name);
          $expect_column = $expect_table->getColumn($column_name);

          $issues = $this->compareSchemata($expect_column, $actual_column);

          $comp_column = $column_template->newEmptyClone()
            ->setIssues($issues);

          $comp_table->addColumn($comp_column);
        }

        $all_keys =
          $actual_table->getKeys() +
          $expect_table->getKeys();
        foreach ($all_keys as $key_name => $key_template) {
          $actual_key = $actual_table->getKey($key_name);
          $expect_key = $expect_table->getKey($key_name);

          $issues = $this->compareSchemata($expect_key, $actual_key);

          $comp_key = $key_template->newEmptyClone()
            ->setIssues($issues);

          $comp_table->addKey($comp_key);
        }

        $comp_table->setPersistenceType($expect_table->getPersistenceType());

        $comp_database->addTable($comp_table);
      }
      $comp_server->addDatabase($comp_database);
    }

    return $comp_server;
  }

  private function compareSchemata(
    PhorgeConfigStorageSchema $expect = null,
    PhorgeConfigStorageSchema $actual = null) {

    $expect_is_key = ($expect instanceof PhorgeConfigKeySchema);
    $actual_is_key = ($actual instanceof PhorgeConfigKeySchema);

    if ($expect_is_key || $actual_is_key) {
      $missing_issue = PhorgeConfigStorageSchema::ISSUE_MISSINGKEY;
      $surplus_issue = PhorgeConfigStorageSchema::ISSUE_SURPLUSKEY;
    } else {
      $missing_issue = PhorgeConfigStorageSchema::ISSUE_MISSING;
      $surplus_issue = PhorgeConfigStorageSchema::ISSUE_SURPLUS;
    }

    if (!$expect && !$actual) {
      throw new Exception(pht('Can not compare two missing schemata!'));
    } else if ($expect && !$actual) {
      $issues = array($missing_issue);
    } else if ($actual && !$expect) {
      $issues = array($surplus_issue);
    } else {
      $issues = $actual->compareTo($expect);
    }

    return $issues;
  }


}
