<?php

final class PhorgePDFFontObject
  extends PhorgePDFObject {

  protected function writeObject() {
    $this->writeLine('/Type /Font');

    $this->writeLine('/BaseFont /Helvetica-Bold');
    $this->writeLine('/Subtype /Type1');
    $this->writeLine('/Encoding /WinAnsiEncoding');
  }

}
