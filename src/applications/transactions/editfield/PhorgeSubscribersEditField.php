<?php

final class PhorgeSubscribersEditField
  extends PhorgeTokenizerEditField {

  protected function newDatasource() {
    return new PhorgeMetaMTAMailableDatasource();
  }

  protected function newHTTPParameterType() {
    // TODO: Implement a more expansive "Mailable" parameter type which
    // accepts users or projects.
    return new AphrontUserListHTTPParameterType();
  }

  protected function newConduitParameterType() {
    return new ConduitUserListParameterType();
  }

}
