<?php

final class PhorgeClusterImproperWriteException
  extends PhorgeClusterException {

  public function getExceptionTitle() {
    return pht('Improper Cluster Write');
  }

}
