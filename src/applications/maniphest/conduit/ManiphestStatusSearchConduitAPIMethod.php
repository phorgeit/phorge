<?php

final class ManiphestStatusSearchConduitAPIMethod
  extends ManiphestConduitAPIMethod {

  public function getAPIMethodName() {
    return 'maniphest.status.search';
  }

  public function getMethodSummary() {
    return pht('Read information about task statuses.');
  }

  public function getMethodDescription() {
    return pht(
      'Returns information about the possible statuses for Maniphest '.
      'tasks.');
  }

  protected function defineParamTypes() {
    return array();
  }

  protected function defineReturnType() {
    return 'map<string, wild>';
  }

  public function getRequiredScope() {
    return self::SCOPE_ALWAYS;
  }

  protected function execute(ConduitAPIRequest $request) {
    $config = PhabricatorEnv::getEnvConfig('maniphest.statuses');
    $is_serious = PhabricatorEnv::getEnvConfig('phabricator.serious-business');
    $results = array();
    foreach ($config as $code => $status) {
      // Hide silly statuses not available in serious business mode - T16607.
      if ($is_serious && !empty($status['silly'])) {
        continue;
      }
      $stripped_status = array(
        'name' => $status['name'],
        'value' => $code,
        'closed' => !empty($status['closed']),
      );

      if (isset($status['special'])) {
        $stripped_status['special'] = $status['special'];
      }

      $results[] = $stripped_status;
    }

    return array('data' => $results);
  }

}
