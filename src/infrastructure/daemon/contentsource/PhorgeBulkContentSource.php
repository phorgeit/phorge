<?php

final class PhorgeBulkContentSource
  extends PhorgeContentSource {

  const SOURCECONST = 'bulk';

  public function getSourceName() {
    return pht('Bulk Update');
  }

  public function getSourceDescription() {
    return pht('Changes made by bulk update.');
  }

}
