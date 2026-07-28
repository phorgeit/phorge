<?php

interface PhorgeRemarkupDocumentationProducer {

  /**
   * @return PhorgeRemarkupDocumentation|null
   */
  public function getRemarkupDocumentationObject();

}
