<?php

final class PhorgeUsersEditField
  extends PhorgeTokenizerEditField {

  protected function newDatasource() {
    return new PhorgePeopleDatasource();
  }

  protected function newHTTPParameterType() {
    return new AphrontUserListHTTPParameterType();
  }

  protected function newConduitParameterType() {
    if ($this->getIsSingleValue()) {
      return new ConduitUserParameterType();
    } else {
      return new ConduitUserListParameterType();
    }
  }

}
