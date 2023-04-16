<?php

final class PhorgeRepositoryPushLogSearchEngine
  extends PhorgeApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Push Logs');
  }

  public function getApplicationClassName() {
    return 'PhorgeDiffusionApplication';
  }

  public function newQuery() {
    return new PhorgeRepositoryPushLogQuery();
  }

  protected function buildQueryFromParameters(array $map) {
    $query = $this->newQuery();

    if ($map['repositoryPHIDs']) {
      $query->withRepositoryPHIDs($map['repositoryPHIDs']);
    }

    if ($map['pusherPHIDs']) {
      $query->withPusherPHIDs($map['pusherPHIDs']);
    }

    if ($map['createdStart'] || $map['createdEnd']) {
      $query->withEpochBetween(
        $map['createdStart'],
        $map['createdEnd']);
    }

    if ($map['blockingHeraldRulePHIDs']) {
      $query->withBlockingHeraldRulePHIDs($map['blockingHeraldRulePHIDs']);
    }

    return $query;
  }

  protected function buildCustomSearchFields() {
    return array(
      id(new PhorgeSearchDatasourceField())
        ->setDatasource(new DiffusionRepositoryDatasource())
        ->setKey('repositoryPHIDs')
        ->setAliases(array('repository', 'repositories', 'repositoryPHID'))
        ->setLabel(pht('Repositories'))
        ->setDescription(
          pht('Search for push logs for specific repositories.')),
      id(new PhorgeUsersSearchField())
        ->setKey('pusherPHIDs')
        ->setAliases(array('pusher', 'pushers', 'pusherPHID'))
        ->setLabel(pht('Pushers'))
        ->setDescription(
          pht('Search for push logs by specific users.')),
      id(new PhorgeSearchDatasourceField())
        ->setDatasource(new HeraldRuleDatasource())
        ->setKey('blockingHeraldRulePHIDs')
        ->setLabel(pht('Blocked By'))
        ->setDescription(
          pht('Search for pushes blocked by particular Herald rules.')),
      id(new PhorgeSearchDateField())
        ->setLabel(pht('Created After'))
        ->setKey('createdStart'),
      id(new PhorgeSearchDateField())
        ->setLabel(pht('Created Before'))
        ->setKey('createdEnd'),
    );
  }

  protected function getURI($path) {
    return '/diffusion/pushlog/'.$path;
  }

  protected function getBuiltinQueryNames() {
    return array(
      'all' => pht('All Push Logs'),
    );
  }

  public function buildSavedQueryFromBuiltin($query_key) {
    $query = $this->newSavedQuery();
    $query->setQueryKey($query_key);

    switch ($query_key) {
      case 'all':
        return $query;
    }

    return parent::buildSavedQueryFromBuiltin($query_key);
  }

  protected function renderResultList(
    array $logs,
    PhorgeSavedQuery $query,
    array $handles) {

    $table = id(new DiffusionPushLogListView())
      ->setViewer($this->requireViewer())
      ->setLogs($logs);

    return id(new PhorgeApplicationSearchResultView())
      ->setTable($table);
  }

  protected function newExportFields() {
    $viewer = $this->requireViewer();

    $fields = array(
      id(new PhorgeIDExportField())
        ->setKey('pushID')
        ->setLabel(pht('Push ID')),
      id(new PhorgeStringExportField())
        ->setKey('unique')
        ->setLabel(pht('Unique')),
      id(new PhorgeStringExportField())
        ->setKey('protocol')
        ->setLabel(pht('Protocol')),
      id(new PhorgePHIDExportField())
        ->setKey('repositoryPHID')
        ->setLabel(pht('Repository PHID')),
      id(new PhorgeStringExportField())
        ->setKey('repository')
        ->setLabel(pht('Repository')),
      id(new PhorgePHIDExportField())
        ->setKey('pusherPHID')
        ->setLabel(pht('Pusher PHID')),
      id(new PhorgeStringExportField())
        ->setKey('pusher')
        ->setLabel(pht('Pusher')),
      id(new PhorgePHIDExportField())
        ->setKey('devicePHID')
        ->setLabel(pht('Device PHID')),
      id(new PhorgeStringExportField())
        ->setKey('device')
        ->setLabel(pht('Device')),
      id(new PhorgeStringExportField())
        ->setKey('type')
        ->setLabel(pht('Ref Type')),
      id(new PhorgeStringExportField())
        ->setKey('name')
        ->setLabel(pht('Ref Name')),
      id(new PhorgeStringExportField())
        ->setKey('old')
        ->setLabel(pht('Ref Old')),
      id(new PhorgeStringExportField())
        ->setKey('new')
        ->setLabel(pht('Ref New')),
      id(new PhorgeIntExportField())
        ->setKey('flags')
        ->setLabel(pht('Flags')),
      id(new PhorgeStringListExportField())
        ->setKey('flagNames')
        ->setLabel(pht('Flag Names')),
      id(new PhorgeIntExportField())
        ->setKey('result')
        ->setLabel(pht('Result')),
      id(new PhorgeStringExportField())
        ->setKey('resultName')
        ->setLabel(pht('Result Name')),
      id(new PhorgeStringExportField())
        ->setKey('resultDetails')
        ->setLabel(pht('Result Details')),
      id(new PhorgeIntExportField())
        ->setKey('hostWait')
        ->setLabel(pht('Host Wait (us)')),
      id(new PhorgeIntExportField())
        ->setKey('writeWait')
        ->setLabel(pht('Write Wait (us)')),
      id(new PhorgeIntExportField())
        ->setKey('readWait')
        ->setLabel(pht('Read Wait (us)')),
      id(new PhorgeIntExportField())
        ->setKey('hookWait')
        ->setLabel(pht('Hook Wait (us)')),
    );

    if ($viewer->getIsAdmin()) {
      $fields[] = id(new PhorgeStringExportField())
        ->setKey('remoteAddress')
        ->setLabel(pht('Remote Address'));
    }

    return $fields;
  }

  protected function newExportData(array $logs) {
    $viewer = $this->requireViewer();

    $phids = array();
    foreach ($logs as $log) {
      $phids[] = $log->getPusherPHID();
      $phids[] = $log->getDevicePHID();
      $phids[] = $log->getPushEvent()->getRepositoryPHID();
    }
    $handles = $viewer->loadHandles($phids);

    $flag_map = PhorgeRepositoryPushLog::getFlagDisplayNames();
    $reject_map = PhorgeRepositoryPushLog::getRejectCodeDisplayNames();

    $export = array();
    foreach ($logs as $log) {
      $event = $log->getPushEvent();

      $repository_phid = $event->getRepositoryPHID();
      if ($repository_phid) {
        $repository_name = $handles[$repository_phid]->getName();
      } else {
        $repository_name = null;
      }

      $pusher_phid = $log->getPusherPHID();
      if ($pusher_phid) {
        $pusher_name = $handles[$pusher_phid]->getName();
      } else {
        $pusher_name = null;
      }

      $device_phid = $log->getDevicePHID();
      if ($device_phid) {
        $device_name = $handles[$device_phid]->getName();
      } else {
        $device_name = null;
      }

      $flags = $log->getChangeFlags();
      $flag_names = array();
      foreach ($flag_map as $flag_key => $flag_name) {
        if (($flags & $flag_key) === $flag_key) {
          $flag_names[] = $flag_name;
        }
      }

      $result = $event->getRejectCode();
      $result_name = idx($reject_map, $result, pht('Unknown ("%s")', $result));

      $map = array(
        'pushID' => $event->getID(),
        'unique' => $event->getRequestIdentifier(),
        'protocol' => $event->getRemoteProtocol(),
        'repositoryPHID' => $repository_phid,
        'repository' => $repository_name,
        'pusherPHID' => $pusher_phid,
        'pusher' => $pusher_name,
        'devicePHID' => $device_phid,
        'device' => $device_name,
        'type' => $log->getRefType(),
        'name' => $log->getRefName(),
        'old' => $log->getRefOld(),
        'new' => $log->getRefNew(),
        'flags' => $flags,
        'flagNames' => $flag_names,
        'result' => $result,
        'resultName' => $result_name,
        'resultDetails' => $event->getRejectDetails(),
        'hostWait' => $event->getHostWait(),
        'writeWait' => $event->getWriteWait(),
        'readWait' => $event->getReadWait(),
        'hookWait' => $event->getHookWait(),
      );

      if ($viewer->getIsAdmin()) {
        $map['remoteAddress'] = $event->getRemoteAddress();
      }

      $export[] = $map;
    }

    return $export;
  }

}
