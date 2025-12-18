<?php

final class PhabricatorAuthHighSecurityRequiredException extends Exception {

  private $cancelURI;
  private $factors;
  private $factorValidationResults;
  private $isSessionUpgrade;

  /**
   * @param array<PhabricatorAuthFactorResult> $results
   */
  public function setFactorValidationResults(array $results) {
    assert_instances_of($results, PhabricatorAuthFactorResult::class);
    $this->factorValidationResults = $results;
    return $this;
  }

  public function getFactorValidationResults() {
    return $this->factorValidationResults;
  }

  /**
   * @param array<PhabricatorAuthFactorConfig> $factors
   */
  public function setFactors(array $factors) {
    assert_instances_of($factors, PhabricatorAuthFactorConfig::class);
    $this->factors = $factors;
    return $this;
  }

  public function getFactors() {
    return $this->factors;
  }

  public function setCancelURI($cancel_uri) {
    $this->cancelURI = $cancel_uri;
    return $this;
  }

  public function getCancelURI() {
    return $this->cancelURI;
  }

  public function setIsSessionUpgrade($is_upgrade) {
    $this->isSessionUpgrade = $is_upgrade;
    return $this;
  }

  public function getIsSessionUpgrade() {
    return $this->isSessionUpgrade;
  }

}
