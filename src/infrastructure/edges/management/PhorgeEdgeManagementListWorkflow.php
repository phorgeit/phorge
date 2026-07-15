<?php

final class PhorgeEdgeManagementListWorkflow
  extends PhorgeEdgeManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('list')
      ->setSynopsis(pht('List edges relating to an object'))
      ->setExamples(array(
        '**list** T123 T532',
        '**list** T123 --destination @user',
      ))
      ->setArguments(
        array(
          id(new PhutilArgumentSpecification())
            ->setName('source')
            ->setHelp(pht('Filter edges by Source'))
            ->setWildcard(true),
          id(new PhutilArgumentSpecification())
            ->setName('destination')
            ->setHelp(
              pht(
                'Filter edges by Destination.'))
            ->setParamName('dest')
            ->setRepeatable(true),
          id(new PhutilArgumentSpecification())
            ->setName('type')
            ->setHelp(
              pht(
                'Filter edges by Edge Type.'))
            ->setParamName('type')
            ->setRepeatable(true),
          // TODO arguments to order the output (by destination/type).
        ));
  }


  public function execute(PhutilArgumentParser $args) {
    $viewer = $this->getViewer();

    $sources = $args->getArg('source');
    $dests = $args->getArg('destination');

    $obj_names = array_merge($sources, $dests);

    $object_query = id(new PhabricatorObjectQuery())
      ->setViewer($viewer)
      ->withNames($obj_names);
    $object_query->execute();

    $named_objects = $object_query->getNamedResults();
    unset($object_query);

    $named_objects = mpull($named_objects, 'getPHID');
    $source_phids = array_select_keys($named_objects, $sources);
    $dest_phids = array_select_keys($named_objects, $dests);

    if (!$source_phids) {
      throw new PhutilArgumentUsageException(
        pht(
          'No source name was resolved to a phid! '.
          'A source destination is required.'));
    }

    $query = new PhabricatorEdgeQuery();

    if ($source_phids) {
      $query->withSourcePHIDs($source_phids);
    }
    if ($dest_phids) {
      $query->withDestinationPHIDs($dest_phids);
    }

    $type_filter = $this->buildEdgeTypeFilter($args->getArg('type'));
    if ($type_filter) {
      $query->withEdgeTypes($type_filter);
    }

    $edges = $query->execute();

    $table = id(new PhutilConsoleTable())
      ->addColumns(array(
          'src' => array('title' => pht('Source')),
          'dst' => array('title' => pht('Destination')),
          'type' => array('title' => pht('')),
          'typename' => array('title' => pht('Edge Type')),
          'data?' => array('title' => pht('Has Data?')),
        ));

    $edge_types = PhabricatorEdgeType::getAllTypes();
    $edge_types = array_map('get_class', $edge_types);

    $edges = array_mergev($edges);
    $edges = array_mergev($edges);

    foreach ($edges as &$edge) {
      $edge['typename'] = idx($edge_types, $edge['type']);
      $edge['data?'] = $edge['dataID'] !== null ? pht('Yes'): pht('No');
    }
    unset($edge);

    $table->drawRows($edges);
  }

  /** @return array<int> */
  private function buildEdgeTypeFilter(array $types_input) {

    $types = array();

    foreach ($types_input as $name) {
      if (is_numeric($name)) {
        $types[] = (int)$name;
        continue;
      }

      if (class_exists($name)) {
        if (is_subclass_of($name, PhabricatorEdgeType::class)) {
          $edge = newv($name, array());
          $types[] = $edge->getEdgeConstant();
        }
      }

    }

    return $types;
  }

}
