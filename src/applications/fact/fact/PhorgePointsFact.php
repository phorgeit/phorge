<?php

final class PhorgePointsFact extends PhorgeFact {

  protected function newTemplateDatapoint() {
    return new PhorgeFactIntDatapoint();
  }

}
