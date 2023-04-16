<?php

abstract class PhorgeAuthFactorProviderTransactionType
  extends PhorgeModularTransactionType {

  final protected function isDuoProvider(
    PhorgeAuthFactorProvider $provider) {
    $duo_key = id(new PhorgeDuoAuthFactor())->getFactorKey();
    return ($provider->getProviderFactorKey() === $duo_key);
  }

}
