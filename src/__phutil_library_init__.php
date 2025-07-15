<?php

phutil_register_library('phorge', __FILE__);

// Register the Composer autoloader.
if (!@include_once __DIR__.'/../vendor/autoload.php') {
  throw new Exception(
    pht(
      'Dependencies are not installed. Please run `%s` to install dependencies.',
      'composer install'));
}
