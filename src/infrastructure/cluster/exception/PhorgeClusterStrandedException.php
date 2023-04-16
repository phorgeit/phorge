<?php

final class PhorgeClusterStrandedException
  extends PhorgeClusterException {

  public function getExceptionTitle() {
    return pht('Unable to Reach Any Database');
  }

}
