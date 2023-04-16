<?php

final class PhorgeCountFact extends PhorgeFact {

  protected function newTemplateDatapoint() {
    return new PhorgeFactIntDatapoint();
  }

}
