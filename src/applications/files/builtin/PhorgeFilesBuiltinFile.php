<?php

abstract class PhorgeFilesBuiltinFile extends Phobject {

  abstract public function getBuiltinFileKey();
  abstract public function getBuiltinDisplayName();
  abstract public function loadBuiltinFileData();

}
