<?php

abstract class PhorgeClusterException
  extends Exception {

  abstract public function getExceptionTitle();

}
