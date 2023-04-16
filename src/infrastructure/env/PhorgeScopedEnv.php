<?php

/**
 * Scope guard to hold a temporary environment. See @{class:PhorgeEnv} for
 * instructions on use.
 *
 * @task internal Internals
 * @task override Overriding Environment Configuration
 */
final class PhorgeScopedEnv extends Phobject {

  private $key;
  private $isPopped = false;

/* -(  Overriding Environment Configuration  )------------------------------- */

  /**
   * Override a configuration key in this scope, setting it to a new value.
   *
   * @param  string Key to override.
   * @param  wild   New value.
   * @return this
   *
   * @task override
   */
  public function overrideEnvConfig($key, $value) {
    PhorgeEnv::overrideTestEnvConfig(
      $this->key,
      $key,
      $value);
    return $this;
  }


/* -(  Internals  )---------------------------------------------------------- */


  /**
   * @task internal
   */
  public function __construct($stack_key) {
    $this->key = $stack_key;
  }


  /**
   * Release the scoped environment.
   *
   * @return void
   * @task internal
   */
  public function __destruct() {
    if (!$this->isPopped) {
      PhorgeEnv::popTestEnvironment($this->key);
      $this->isPopped = true;
    }
  }

}
