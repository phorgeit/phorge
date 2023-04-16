<?php

$table = new PhorgeDashboard();
$conn = $table->establishConnection('r');
$table_name = 'dashboard_install';

$search_table = new PhorgeProfileMenuItemConfiguration();
$search_conn = $search_table->establishConnection('w');
$search_table_name = 'search_profilepanelconfiguration';

$viewer = PhorgeUser::getOmnipotentUser();
$profile_phid = id(new PhorgeHomeApplication())->getPHID();
$menu_item_key = PhorgeDashboardProfileMenuItem::MENUITEMKEY;

foreach (new LiskRawMigrationIterator($conn, $table_name) as $install) {

  $dashboard_phid = $install['dashboardPHID'];
  $new_phid = id(new PhorgeProfileMenuItemConfiguration())->generatePHID();
  $menu_item_properties = json_encode(
    array('dashboardPHID' => $dashboard_phid, 'name' => ''));

  $custom_phid = $install['objectPHID'];
  if ($custom_phid == 'dashboard:default') {
    $custom_phid = null;
  }

  $menu_item_order = 0;

  queryfx(
    $search_conn,
    'INSERT INTO %T (phid, profilePHID, menuItemKey, menuItemProperties, '.
    'visibility, dateCreated, dateModified, menuItemOrder, customPHID) VALUES '.
    '(%s, %s, %s, %s, %s, %d, %d, %d, %ns)',
    $search_table_name,
    $new_phid,
    $profile_phid,
    $menu_item_key,
    $menu_item_properties,
    'visible',
    PhorgeTime::getNow(),
    PhorgeTime::getNow(),
    $menu_item_order,
    $custom_phid);

}
