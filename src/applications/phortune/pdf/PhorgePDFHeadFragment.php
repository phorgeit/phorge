<?php

final class PhorgePDFHeadFragment
  extends PhorgePDFFragment {

  protected function writeFragment() {
    $this->writeLine('%s', '%PDF-1.3');
  }

}
