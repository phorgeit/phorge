<?php

abstract class PHIDConduitAPIMethod extends ConduitAPIMethod {

  protected function buildHandleInformationDictionary(
    PhorgeObjectHandle $handle) {

    return array(
      'phid'      => $handle->getPHID(),
      'uri'       => PhorgeEnv::getProductionURI($handle->getURI()),

      'typeName'  => $handle->getTypeName(),
      'type'      => $handle->getType(),

      'name'      => $handle->getName(),
      'fullName'  => $handle->getFullName(),

      'status'    => $handle->getStatus(),
    );
  }

}
