<?php

abstract class PhorgeLiskSerializer extends Phobject {

  abstract public function willReadValue($value);
  abstract public function willWriteValue($value);

}
