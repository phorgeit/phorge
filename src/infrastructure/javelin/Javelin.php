<?php

final class Javelin extends Phobject {

  public static function initBehavior(
    $behavior,
    array $config = array(),
    $source_name = 'phorge') {

    $response = CelerityAPI::getStaticResourceResponse();

    $response->initBehavior($behavior, $config, $source_name);
  }

}
