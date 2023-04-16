<?php

final class PhorgePDFInfoObject
  extends PhorgePDFObject {

  final protected function writeObject() {
    $this->writeLine('/Producer (Phorge 20190801)');
    $this->writeLine('/CreationDate (D:%s)', date('YmdHis'));
  }

}
