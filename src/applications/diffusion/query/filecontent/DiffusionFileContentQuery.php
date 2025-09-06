<?php

abstract class DiffusionFileContentQuery
  extends DiffusionFileFutureQuery {

  final public static function newFromDiffusionRequest(
    DiffusionRequest $request) {
    return parent::newQueryObject(self::class, $request);
  }

}
