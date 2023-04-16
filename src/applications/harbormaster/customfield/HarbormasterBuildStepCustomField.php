<?php

abstract class HarbormasterBuildStepCustomField
  extends PhorgeCustomField {

  abstract public function getBuildTargetFieldValue();

}
