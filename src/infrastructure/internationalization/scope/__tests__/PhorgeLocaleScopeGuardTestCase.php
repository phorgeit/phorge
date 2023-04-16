<?php

final class PhorgeLocaleScopeGuardTestCase
  extends PhorgeTestCase {

  public function testLocaleScopeGuard() {
    $original = PhorgeEnv::getLocaleCode();

    // Set a guard; it should change the locale, then revert it when destroyed.
    $guard = PhorgeEnv::beginScopedLocale('en_GB');
    $this->assertEqual('en_GB', PhorgeEnv::getLocaleCode());
    unset($guard);
    $this->assertEqual($original, PhorgeEnv::getLocaleCode());

    // Nest guards, then destroy them out of order.
    $guard1 = PhorgeEnv::beginScopedLocale('en_GB');
    $this->assertEqual('en_GB', PhorgeEnv::getLocaleCode());
    $guard2 = PhorgeEnv::beginScopedLocale('en_A*');
    $this->assertEqual('en_A*', PhorgeEnv::getLocaleCode());
    unset($guard1);
    $this->assertEqual('en_A*', PhorgeEnv::getLocaleCode());
    unset($guard2);
    $this->assertEqual($original, PhorgeEnv::getLocaleCode());

    // If you push `null`, that should mean "the default locale", not
    // "the current locale".
    $guard3 = PhorgeEnv::beginScopedLocale('en_GB');
    $this->assertEqual('en_GB', PhorgeEnv::getLocaleCode());
    $guard4 = PhorgeEnv::beginScopedLocale(null);
    $this->assertEqual($original, PhorgeEnv::getLocaleCode());
    unset($guard4);
    $this->assertEqual('en_GB', PhorgeEnv::getLocaleCode());
    unset($guard3);
    $this->assertEqual($original, PhorgeEnv::getLocaleCode());

  }

}
