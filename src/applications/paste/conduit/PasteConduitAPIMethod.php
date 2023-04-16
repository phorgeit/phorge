<?php

abstract class PasteConduitAPIMethod extends ConduitAPIMethod {

  final public function getApplication() {
    return PhorgeApplication::getByClass('PhorgePasteApplication');
  }

  protected function buildPasteInfoDictionary(PhorgePaste $paste) {
    return array(
      'id'          => $paste->getID(),
      'objectName'  => 'P'.$paste->getID(),
      'phid'        => $paste->getPHID(),
      'authorPHID'  => $paste->getAuthorPHID(),
      'filePHID'    => $paste->getFilePHID(),
      'title'       => $paste->getTitle(),
      'dateCreated' => $paste->getDateCreated(),
      'language'    => $paste->getLanguage(),
      'uri'         => PhorgeEnv::getProductionURI('/P'.$paste->getID()),
      'parentPHID'  => $paste->getParentPHID(),
      'content'     => $paste->getRawContent(),
    );
  }

}
