<?php

final class PhorgeCalendarInviteeUserDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Users');
  }

  public function getPlaceholderText() {
    return pht('Type a user name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeCalendarApplication';
  }

  public function getComponentDatasources() {
    return array(
      new PhorgePeopleDatasource(),
    );
  }

  protected function evaluateValues(array $values) {
    return PhorgeCalendarInviteeDatasource::expandInvitees(
      $this->getViewer(),
      $values);
  }

}
