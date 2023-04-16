<?php

final class PhortuneChargeSearchEngine
  extends PhorgeApplicationSearchEngine {

  private $account;

  public function canUseInPanelContext() {
    // These only make sense in an account context.
    return false;
  }

  public function setAccount(PhortuneAccount $account) {
    $this->account = $account;
    return $this;
  }

  public function getAccount() {
    return $this->account;
  }

  public function getResultTypeDescription() {
    return pht('Phortune Charges');
  }

  public function getApplicationClassName() {
    return 'PhorgePhortuneApplication';
  }

  public function buildSavedQueryFromRequest(AphrontRequest $request) {
    $saved = new PhorgeSavedQuery();

    return $saved;
  }

  public function buildQueryFromSavedQuery(PhorgeSavedQuery $saved) {
    $query = id(new PhortuneChargeQuery());

    $viewer = $this->requireViewer();

    $account = $this->getAccount();
    if ($account) {
      $query->withAccountPHIDs(array($account->getPHID()));
    } else {
      $accounts = id(new PhortuneAccountQuery())
        ->withMemberPHIDs(array($viewer->getPHID()))
        ->execute();
      if ($accounts) {
        $query->withAccountPHIDs(mpull($accounts, 'getPHID'));
      } else {
        throw new Exception(pht('You have no accounts!'));
      }
    }

    return $query;
  }

  public function buildSearchForm(
    AphrontFormView $form,
    PhorgeSavedQuery $saved_query) {}

  protected function getURI($path) {
    $account = $this->getAccount();
    if ($account) {
      return $account->getChargeListURI($path);
    } else {
      return '/phortune/charge/'.$path;
    }
  }

  protected function getBuiltinQueryNames() {
    $names = array(
      'all' => pht('All Charges'),
    );

    return $names;
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
    array $charges,
    PhorgeSavedQuery $query,
    array $handles) {
    assert_instances_of($charges, 'PhortuneCharge');

    $viewer = $this->requireViewer();

    $table = id(new PhortuneChargeTableView())
      ->setUser($viewer)
      ->setCharges($charges);

    $result = new PhorgeApplicationSearchResultView();
    $result->setTable($table);

    return $result;
  }
}
