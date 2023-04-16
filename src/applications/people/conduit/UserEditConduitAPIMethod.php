<?php

final class UserEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'user.edit';
  }

  public function newEditEngine() {
    return new PhorgeUserEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to edit a user. (Users can not be created via '.
      'the API.)');
  }

}
