<?php

final class PhabricatorFulltextResultSet extends Phobject {

  private $phids;
  private $fulltextTokens;

  public function setPHIDs($phids) {
    $this->phids = $phids;
    return $this;
  }

  public function getPHIDs() {
    return $this->phids;
  }

  /**
   * @param array<PhabricatorFulltextToken> $fulltext_tokens
   */
  public function setFulltextTokens($fulltext_tokens) {
    $this->fulltextTokens = $fulltext_tokens;
    return $this;
  }

  /**
   * @return array<PhabricatorFulltextToken>
   */
  public function getFulltextTokens() {
    return $this->fulltextTokens;
  }

}
