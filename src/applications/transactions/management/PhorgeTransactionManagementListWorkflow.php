<?php

final class PhorgeTransactionManagementListWorkflow
  extends PhorgeTransactionManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('list')
      ->setSynopsis('List transactions of a particular object')
      ->setExamples('**list** T123')
      ->setArguments(
        array(
          id(new PhutilArgumentSpecification())
            ->setName('object')
            ->setHelp(pht('Object to show transactions for.'))
            ->setWildcard(true)
            ->setRepeatable(false),
          id(new PhutilArgumentSpecification())
            ->setName('user')
            ->setParamName('user')
            ->setHelp(pht('Show transaction history by a user')),
          id(new PhutilArgumentSpecification())
            ->setName('output')
            ->setParamName('format')
            ->setHelp(pht('Output format. `json` or `table`.'))
            ->setDefault('table'),
          // TODO arguments to order the output.
          id(new PhutilArgumentSpecification())
            ->setName('type')
            ->setHelp(pht('Filter by transaction type'))
            ->setParamName('const')
            ->setRepeatable(true),
          id(new PhutilArgumentSpecification())
            ->setName('limit')
            ->setParamName('count'),
          id(new PhutilArgumentSpecification())
            ->setName('groupid')
            ->setHelp(pht('Show Group ID column (table only).')),
          id(new PhutilArgumentSpecification())
            ->setName('epoch')
            ->setHelp(pht('Show dates in `epoch` format (table only).')),
        ));

  }


  public function execute(PhutilArgumentParser $args) {
    $viewer = $this->getViewer();

    $output_format = $args->getArg('output');

    if ($args->getArg('user')) {
      $query = $this->transactionQueryForUser($args);
    } else {
      $object_name = $args->getArg('object');

      if (!$object_name) {
        throw new PhutilArgumentUsageException(
          pht('Specify object to inspect'));
      }

      $object_phid = $this->resolveObjectPHID($object_name);
      $object_phid_type = phid_get_type($object_phid);

      $query = $this->transactionQueryForObject($object_phid_type)
        ->withObjectPHIDs(array($object_phid));

      $types = $args->getArg('type');
      if ($types) {
        $query->withTransactionTypes($types);
      }
    }

    $query->setViewer($viewer);


    $limit = $args->getArgAsInteger('limit');
    if ($limit > 0) {
      $query->setLimit($limit);
    }


    /** @var array<PhabricatorApplicationTransaction> */
    $xactions = $query->execute();

    $table_columns = array(
      'phid' => array('title' => pht('Transaction PHID')),
      'date' => array('title' => pht('Date')),
      'groupId' => array('title' => pht('Group ID')),
      'author' => array('title' => pht('Actor')),
      'type' => array('title' => pht('Type')),
      'title' => array('title' => pht('Title')),
      'comment?' => array('title' => pht('Has Comment?')),
    );

    if (!$args->getArg('groupid')) {
      unset($table_columns['groupId']);
    }

    $table_keys = array_keys($table_columns);
    $rows = array();
    foreach ($xactions as $xaction) {

      $data = $this->transactionData($xaction);
      $data += array(
        'details?' => $xaction->hasChangeDetails() ? pht('Yes'): pht('No'),
        'comment?' => (bool)$xaction->hasComment() ? pht('Yes'): pht('No'),
      );

      if ($output_format == 'table') {
        $xaction_date = $data['epoch'];
        if (!$args->getArg('epoch')) {
          $xaction_date = phabricator_datetime($xaction_date, $viewer);
        }
        $data['date'] = $xaction_date;

        if (array_key_exists('groupId', $table_columns)) {
          $data['groupId'] = $xaction->getMetadataValue('core.groupID');
        }
        $data = array_select_keys($data, $table_keys);
      }
      $rows[] = $data;
    }

    switch ($output_format) {
      case 'json':
        $json = new PhutilJSON();
        PhutilConsole::getConsole()
          ->writeOut($json->encodeAsList($rows));
        break;

      case 'table':
        $table = id(new PhutilConsoleTable())
          ->addColumns($table_columns);

        $table->drawRows($rows);
        break;

      default:
        throw new Exception(pht('Unknown output format `%s`', $output_format));
    }
  }

  private function transactionQueryForUser($args) {
    $user_name = $args->getArg('user');
    $user_phid = $this->resolveObjectPHID($user_name);
    if (phid_get_type($user_phid) != PhabricatorPeopleUserPHIDType::TYPECONST) {
      throw new PhutilArgumentUsageException(
        pht('User "%s" was not found', $user_name));
    }

    return id(new PhabricatorFeedTransactionQuery())
      ->withAuthorPHIDs(array($user_phid));
  }

}
