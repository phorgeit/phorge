<?php

final class PhorgeSystemActionRateLimitException extends Exception {

  private $action;
  private $score;

  public function __construct(PhorgeSystemAction $action, $score) {
    $this->action = $action;
    $this->score = $score;
    parent::__construct($action->getLimitExplanation());
  }

  public function getRateExplanation() {
    return $this->action->getRateExplanation($this->score);
  }

}
