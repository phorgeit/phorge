<?php

$table = new HarbormasterBuildPlan();
$conn_w = $table->establishConnection('w');

$view_policy = PhorgePolicies::getMostOpenPolicy();
queryfx(
  $conn_w,
  'UPDATE %T SET viewPolicy = %s WHERE viewPolicy = %s',
  $table->getTableName(),
  $view_policy,
  '');

$edit_policy = id(new PhorgeHarbormasterApplication())
  ->getPolicy(HarbormasterCreatePlansCapability::CAPABILITY);
queryfx(
  $conn_w,
  'UPDATE %T SET editPolicy = %s WHERE editPolicy = %s',
  $table->getTableName(),
  $edit_policy,
  '');
