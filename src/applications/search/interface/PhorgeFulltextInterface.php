<?php

interface PhorgeFulltextInterface
  extends PhorgeIndexableInterface {

  public function newFulltextEngine();

}
