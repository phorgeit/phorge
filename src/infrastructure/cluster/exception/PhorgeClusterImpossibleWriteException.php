<?php

final class PhorgeClusterImpossibleWriteException
  extends PhorgeClusterException {

  public function getExceptionTitle() {
    return pht('Impossible Cluster Write');
  }

}
