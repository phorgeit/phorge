<?php

final class FileUploadHashConduitAPIMethod extends FileConduitAPIMethod {

  public function getAPIMethodName() {
    return 'file.uploadhash';
  }

  public function getMethodStatus() {
    return self::METHOD_STATUS_DEPRECATED;
  }

  public function getMethodStatusDescription() {
    return pht(
      'This method has been deprecated since %s in favor of %s.',
      '04/2017',
      'file.allocate');
  }

  public function getMethodDescription() {
    return pht('Obsolete. Has no effect.');
  }

  protected function defineParamTypes() {
    return array(
      'hash' => 'required nonempty string',
      'name' => 'required nonempty string',
    );
  }

  protected function defineReturnType() {
    return 'null';
  }

  protected function execute(ConduitAPIRequest $request) {
    return null;
  }

}
