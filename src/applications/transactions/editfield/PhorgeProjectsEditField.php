<?php

final class PhorgeProjectsEditField
  extends PhorgeTokenizerEditField {

  protected function newDatasource() {
    return new PhorgeProjectDatasource();
  }

  protected function newHTTPParameterType() {
    return new AphrontProjectListHTTPParameterType();
  }

  protected function newConduitParameterType() {
    return new ConduitProjectListParameterType();
  }

}
