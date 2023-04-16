<?php

final class PhorgeProjectSchemaSpec extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgeProject());

    $this->buildRawSchema(
      id(new PhorgeProject())->getApplicationName(),
      PhorgeProject::TABLE_DATASOURCE_TOKEN,
      array(
        'id' => 'auto',
        'projectID' => 'id',
        'token' => 'text128',
      ),
      array(
        'PRIMARY' => array(
          'columns' => array('id'),
          'unique' => true,
        ),
        'token' => array(
          'columns' => array('token', 'projectID'),
          'unique' => true,
        ),
        'projectID' => array(
          'columns' => array('projectID'),
        ),
      ));


  }

}
