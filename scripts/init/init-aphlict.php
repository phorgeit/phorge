<?php

require_once dirname(__FILE__).'/lib.php';

init_vixon_script(
  array(
    // NOTE: We allow aphlict to start with no database, because it's
    // reasonable to isolate the notification server from other hosts.
    'config.optional' => true,
  ));
