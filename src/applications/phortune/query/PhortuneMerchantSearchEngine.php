<?php

final class PhortuneMerchantSearchEngine
  extends PhorgeApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Phortune Merchants');
  }

  public function getApplicationClassName() {
    return 'PhorgePhortuneApplication';
  }

  public function buildSavedQueryFromRequest(AphrontRequest $request) {
    $saved = new PhorgeSavedQuery();

    return $saved;
  }

  public function buildQueryFromSavedQuery(PhorgeSavedQuery $saved) {
    $query = id(new PhortuneMerchantQuery())
      ->needProfileImage(true);

    return $query;
  }

  public function buildSearchForm(
    AphrontFormView $form,
    PhorgeSavedQuery $saved_query) {}

  protected function getURI($path) {
    return '/phortune/merchant/'.$path;
  }

  protected function getBuiltinQueryNames() {
    $names = array(
      'all' => pht('All Merchants'),
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

  protected function getRequiredHandlePHIDsForResultList(
    array $merchants,
    PhorgeSavedQuery $query) {
    return array();
  }

  protected function renderResultList(
    array $merchants,
    PhorgeSavedQuery $query,
    array $handles) {
    assert_instances_of($merchants, 'PhortuneMerchant');

    $viewer = $this->requireViewer();

    $list = new PHUIObjectItemListView();
    $list->setUser($viewer);
    foreach ($merchants as $merchant) {
      $item = id(new PHUIObjectItemView())
        ->setSubhead(pht('Merchant %d', $merchant->getID()))
        ->setHeader($merchant->getName())
        ->setHref('/phortune/merchant/'.$merchant->getID().'/')
        ->setObject($merchant)
        ->setImageURI($merchant->getProfileImageURI());

      $list->addItem($item);
    }

    $result = new PhorgeApplicationSearchResultView();
    $result->setObjectList($list);
    $result->setNoDataString(pht('No merchants found.'));

    return $result;
  }
}
