<?php

final class DiffusionPullLogSearchEngine
  extends PhorgeApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Pull Logs');
  }

  public function getApplicationClassName() {
    return 'PhorgeDiffusionApplication';
  }

  public function newQuery() {
    return new PhorgeRepositoryPullEventQuery();
  }

  protected function buildQueryFromParameters(array $map) {
    $query = $this->newQuery();

    if ($map['repositoryPHIDs']) {
      $query->withRepositoryPHIDs($map['repositoryPHIDs']);
    }

    if ($map['pullerPHIDs']) {
      $query->withPullerPHIDs($map['pullerPHIDs']);
    }

    if ($map['createdStart'] || $map['createdEnd']) {
      $query->withEpochBetween(
        $map['createdStart'],
        $map['createdEnd']);
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
          pht('Search for pull logs for specific repositories.')),
      id(new PhorgeUsersSearchField())
        ->setKey('pullerPHIDs')
        ->setAliases(array('puller', 'pullers', 'pullerPHID'))
        ->setLabel(pht('Pullers'))
        ->setDescription(
          pht('Search for pull logs by specific users.')),
      id(new PhorgeSearchDateField())
        ->setLabel(pht('Created After'))
        ->setKey('createdStart'),
      id(new PhorgeSearchDateField())
        ->setLabel(pht('Created Before'))
        ->setKey('createdEnd'),
    );
  }

  protected function newExportFields() {
    $viewer = $this->requireViewer();

    $fields = array(
      id(new PhorgePHIDExportField())
        ->setKey('repositoryPHID')
        ->setLabel(pht('Repository PHID')),
      id(new PhorgeStringExportField())
        ->setKey('repository')
        ->setLabel(pht('Repository')),
      id(new PhorgePHIDExportField())
        ->setKey('pullerPHID')
        ->setLabel(pht('Puller PHID')),
      id(new PhorgeStringExportField())
        ->setKey('puller')
        ->setLabel(pht('Puller')),
      id(new PhorgeStringExportField())
        ->setKey('protocol')
        ->setLabel(pht('Protocol')),
      id(new PhorgeStringExportField())
        ->setKey('result')
        ->setLabel(pht('Result')),
      id(new PhorgeIntExportField())
        ->setKey('code')
        ->setLabel(pht('Code')),
      id(new PhorgeEpochExportField())
        ->setKey('date')
        ->setLabel(pht('Date')),
    );

    if ($viewer->getIsAdmin()) {
      $fields[] = id(new PhorgeStringExportField())
        ->setKey('remoteAddress')
        ->setLabel(pht('Remote Address'));
    }

    return $fields;
  }

  protected function newExportData(array $events) {
    $viewer = $this->requireViewer();

    $phids = array();
    foreach ($events as $event) {
      if ($event->getPullerPHID()) {
        $phids[] = $event->getPullerPHID();
      }
    }
    $handles = $viewer->loadHandles($phids);

    $export = array();
    foreach ($events as $event) {
      $repository = $event->getRepository();
      if ($repository) {
        $repository_phid = $repository->getPHID();
        $repository_name = $repository->getDisplayName();
      } else {
        $repository_phid = null;
        $repository_name = null;
      }

      $puller_phid = $event->getPullerPHID();
      if ($puller_phid) {
        $puller_name = $handles[$puller_phid]->getName();
      } else {
        $puller_name = null;
      }

      $map = array(
        'repositoryPHID' => $repository_phid,
        'repository' => $repository_name,
        'pullerPHID' => $puller_phid,
        'puller' => $puller_name,
        'protocol' => $event->getRemoteProtocol(),
        'result' => $event->getResultType(),
        'code' => $event->getResultCode(),
        'date' => $event->getEpoch(),
      );

      if ($viewer->getIsAdmin()) {
        $map['remoteAddress'] = $event->getRemoteAddress();
      }

      $export[] = $map;
    }

    return $export;
  }

  protected function getURI($path) {
    return '/diffusion/pulllog/'.$path;
  }

  protected function getBuiltinQueryNames() {
    return array(
      'all' => pht('All Pull Logs'),
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

    $table = id(new DiffusionPullLogListView())
      ->setViewer($this->requireViewer())
      ->setLogs($logs);

    return id(new PhorgeApplicationSearchResultView())
      ->setTable($table);
  }

}
