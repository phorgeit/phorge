<?php

abstract class PhabricatorEditEngineAPIMethod
  extends ConduitAPIMethod {

  abstract public function newEditEngine();

  public function getApplication() {
    $engine = $this->newEditEngine();
    $class = $engine->getEngineApplicationClass();
    return PhabricatorApplication::getByClass($class);
  }

  final protected function defineParamTypes() {
    return array(
      'transactions' => 'list<map<string, wild>>',
      'objectIdentifier' => 'optional id|phid|string',
    );
  }

  final protected function defineReturnType() {
    return 'map<string, wild>';
  }

  final protected function execute(ConduitAPIRequest $request) {
    $engine = $this->newEditEngine()
      ->setViewer($request->getUser());

    return $engine->buildConduitResponse($request);
  }

  final public function getMethodDescription() {
    return pht(
      'This is a standard **ApplicationEditor** method which allows you to '.
      'create and modify objects by applying transactions. For documentation '.
      'on these endpoints, see '.
      '**[[ %s | Conduit API: Using Edit Endpoints ]]**.',
      PhabricatorEnv::getDoclink('Conduit API: Using Edit Endpoints'));
  }

  final protected function newDocumentationPages(PhabricatorUser $viewer) {
    $engine = $this->newEditEngine()
      ->setViewer($viewer);

    $types = $engine->getConduitEditTypes();

    return $this->buildEditTypesDocumentationPages($viewer, $engine, $types);
  }

  private function buildEditTypesDocumentationPages(
    PhabricatorUser $viewer,
    PhabricatorEditEngine $engine,
    array $types) {

    $pages = array();

    $summary_info = pht(
      'This endpoint supports these types of transactions. See below for '.
      'detailed information about each transaction type.');

    $rows = array();
    foreach ($types as $type) {
      $rows[] = array(
        $type->getEditType(),
        $type->getConduitDescription(),
      );
    }

    $summary_table = id(new AphrontTableView($rows))
      ->setHeaders(
        array(
          pht('Key'),
          pht('Description'),
        ))
      ->setColumnClasses(
        array(
          'prewrap',
          'wide',
        ));

    $title = pht('Transaction Summary');
    $content = array(
      $this->buildRemarkup($summary_info),
      $summary_table,
    );

    $pages[] = $this->newDocumentationBoxPage($viewer, $title, $content)
      ->setAnchor('types');

    $fields = $engine->getEditFieldsForConduit();
    $select_edit_fields = array();
    foreach ($fields as $key => $type) {
      if ($type instanceof PhabricatorSelectEditField) {
        $select_edit_fields[$type->getKey()] = $type;
      }
    }
    $fields = $select_edit_fields;

    foreach ($types as $type) {

      // If we match on keys, set a footer with available values
      $key = $type->getEditType();
      $constants_list = array();
      if (array_key_exists($key, $fields)) {
        $field = $fields[$key];
        $constants = $field->newConduitConstants();
        $constants_list[] = $this->newRemarkupDocumentationView(
          pht('Supported values:'));

        $constants_rows = array();
        foreach ($constants as $constant) {
          $constants_rows[] = array(
            $constant->getKey(),
            $constant->getValue(),
          );
        }

        $constants_table = id(new AphrontTableView($constants_rows))
          ->setHeaders(
            array(
              pht('Value'),
              pht('Description'),
            ))
          ->setColumnClasses(
            array(
              'mono',
              'wide',
            ));

        $constants_list[] = $constants_table;
      }

      $rows[] = array(
        $type->getEditType(),
        $type->getConduitDescription(),
      );

      $section = array();

      $section[] = $type->getConduitDescription();

      $type_documentation = $type->getConduitDocumentation();
      if (phutil_nonempty_string($type_documentation)) {
        $section[] = $type_documentation;
      }

      $section = implode("\n\n", $section);

      $rows = array();

      $rows[] = array(
        'type',
        'const',
        $type->getEditType(),
      );

      $rows[] = array(
        'value',
        $type->getConduitType(),
        $type->getConduitTypeDescription(),
      );

      $type_table = id(new AphrontTableView($rows))
        ->setHeaders(
          array(
            pht('Key'),
            pht('Type'),
            pht('Description'),
          ))
        ->setColumnClasses(
          array(
            'prewrap',
            'prewrap',
            'wide',
          ));

      $title = $type->getEditType();
      $content = array(
        $this->buildRemarkup($section),
        $type_table,
        $constants_list,
      );

      $pages[] = $this->newDocumentationBoxPage($viewer, $title, $content)
        ->setAnchor($type->getEditType())
        ->setIconIcon('fa-pencil');
    }

    return $pages;
  }


  private function buildRemarkup($remarkup) {
    $viewer = $this->getViewer();

    $view = new PHUIRemarkupView($viewer, $remarkup);

    $view->setRemarkupOptions(
      array(
        PHUIRemarkupView::OPTION_PRESERVE_LINEBREAKS => false,
      ));

    return id(new PHUIBoxView())
      ->appendChild($view)
      ->addPadding(PHUI::PADDING_LARGE);
  }

}
