<?php

final class PhabricatorChartDataQuery
  extends Phobject {

  private $limit;
  private $minimumValue;
  private $maximumValue;

  /**
   * @param int $minimum_value Epoch timestamp of the first input data
   */
  public function setMinimumValue($minimum_value) {
    $this->minimumValue = $minimum_value;
    return $this;
  }

  public function getMinimumValue() {
    return $this->minimumValue;
  }

  /**
   * @param int $maximum_value Epoch timestamp of the last input data
   */
  public function setMaximumValue($maximum_value) {
    $this->maximumValue = $maximum_value;
    return $this;
  }

  public function getMaximumValue() {
    return $this->maximumValue;
  }

  /**
   * @param int $limit Maximum amount of data points to handle. If there are
   *   more data points, some in-between data will be ignored.
   */
  public function setLimit($limit) {
    $this->limit = $limit;
    return $this;
  }

  public function getLimit() {
    return $this->limit;
  }

  /**
   * Filter input data: Remove values lower resp. higher than the minimum
   * resp. maximum values; remove some data if amount of data above the limit.
   *
   * @param array<int> $xv Epoch timestamps of unfitlered input data
   * @return array<int> Epoch timestamps of fitlered input data
   */
  public function selectInputValues(array $xv) {
    $result = array();

    $x_min = $this->getMinimumValue();
    $x_max = $this->getMaximumValue();
    $limit = $this->getLimit();

    if ($x_min !== null) {
      foreach ($xv as $key => $x) {
        if ($x < $x_min) {
          unset($xv[$key]);
        }
      }
    }

    if ($x_max !== null) {
      foreach ($xv as $key => $x) {
        if ($x > $x_max) {
          unset($xv[$key]);
        }
      }
    }

    // If we have too many data points, throw away some of the data.

    // TODO: This doesn't work especially well right now.

    if ($limit !== null) {
      $count = count($xv);
      if ($count > $limit) {
        $ii = 0;
        $every = ceil($count / $limit);
        foreach ($xv as $key => $x) {
          $ii++;
          if (($ii % $every) && ($ii != $count)) {
            unset($xv[$key]);
          }
        }
      }
    }

    return array_values($xv);
  }

}
