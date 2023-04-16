<?php

final class PhorgeUserSchemaSpec extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgeUser());

    $this->buildRawSchema(
      id(new PhorgeUser())->getApplicationName(),
      PhorgeUser::NAMETOKEN_TABLE,
      array(
        'token' => 'sort255',
        'userID' => 'id',
      ),
      array(
        'token' => array(
          'columns' => array('token(128)'),
        ),
        'userID' => array(
          'columns' => array('userID'),
        ),
      ));

  }

}
