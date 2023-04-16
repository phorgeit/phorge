<?php

final class PhorgeStandardCustomFieldUsers
  extends PhorgeStandardCustomFieldTokenizer {

  public function getFieldType() {
    return 'users';
  }

  public function getDatasource() {
    return new PhorgePeopleDatasource();
  }

  protected function getHTTPParameterType() {
    return new AphrontUserListHTTPParameterType();
  }

  protected function newConduitSearchParameterType() {
    return new ConduitUserListParameterType();
  }

  protected function newConduitEditParameterType() {
    return new ConduitUserListParameterType();
  }

}
