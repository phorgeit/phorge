#!/usr/bin/env php
<?php

require_once dirname(dirname(__FILE__)).'/__init_script__.php';

echo pht('This script was replaced with `%s`!.', './bin/celerity sprites');
echo "\n";
exit(13);
