<?php

abstract class PhabricatorApplicationConfigOptions extends Phobject {

  abstract public function getName();

  abstract public function getDescription();

  abstract public function getGroup();

  /**
   * @return array<PhabricatorConfigOption>
   */
  abstract public function getOptions();

  public function getIcon() {
    return 'fa-sliders';
  }

  /**
   * Get corresponding application class for configuration options. Child
   * classes returning a classname should also have getGroup() return 'apps'.
   *
   * @return class-string Application class name
   */
  public function getApplicationClassName() {
    phlog(
      pht(
        'Warning - %s must implement `%s`. This method will become abstract '.
        'in the near future.',
        get_class($this),
        'getApplicationClassName()'));
    return PhabricatorSystemApplication::class;
  }

  public function validateOption(PhabricatorConfigOption $option, $value) {
    if ($value === $option->getDefault()) {
      return;
    }

    if ($value === null) {
      return;
    }

    $type = $option->newOptionType();
    if ($type) {
      try {
        $type->validateStoredValue($option, $value);
        $this->didValidateOption($option, $value);
      } catch (PhabricatorConfigValidationException $ex) {
        throw $ex;
      } catch (Throwable $ex) {
        // If custom validators threw exceptions other than validation
        // exceptions, convert them to validation exceptions so we repair the
        // configuration and raise an error.
        throw new PhabricatorConfigValidationException($ex->getMessage());
      }

      return;
    }

    if ($option->isCustomType()) {
      try {
        return $option->getCustomObject()->validateOption($option, $value);
      } catch (Throwable $ex) {
        throw new PhabricatorConfigValidationException($ex->getMessage());
      }
    } else {
      throw new Exception(
        pht(
          'Unknown configuration option type "%s".',
          $option->getType()));
    }
  }

  protected function didValidateOption(
    PhabricatorConfigOption $option,
    $value) {

    // Hook for subclasses to do complex validation.
    return;
  }

  /**
   * Hook to render additional hints based on, e.g., the viewing user, request,
   * or other context. For example, this is used to show workspace IDs when
   * configuring `asana.workspace-id`.
   *
   * @param   PhabricatorConfigOption   $option Option being rendered.
   * @param   AphrontRequest            $request Active request.
   * @return  PhutilSafeHTMLProducerInterface|array|null Additional contextual
   *                                                    description information.
   */
  public function renderContextualDescription(
    PhabricatorConfigOption $option,
    AphrontRequest $request) {

    return null;
  }

  public function getKey() {
    $class = get_class($this);
    $matches = null;
    if (preg_match('/^Phabricator(.*)ConfigOptions$/', $class, $matches)) {
      return strtolower($matches[1]);
    }
    return strtolower(get_class($this));
  }

  /**
   * @return PhabricatorConfigOption
   */
  final protected function newOption($key, $type, $default) {
    return id(new PhabricatorConfigOption())
      ->setKey($key)
      ->setType($type)
      ->setDefault($default)
      ->setGroup($this);
  }

  /**
   * @return array<string, PhabricatorApplicationConfigOptions>
   */
  final public static function loadAll($external_only = false) {
    $symbols = id(new PhutilSymbolLoader())
      ->setAncestorClass(self::class)
      ->setConcreteOnly(true)
      ->selectAndLoadSymbols();

    $groups = array();
    foreach ($symbols as $symbol) {
      if ($external_only && $symbol['library'] == 'phabricator') {
        continue;
      }

      $obj = newv($symbol['name'], array());
      $key = $obj->getKey();
      if (isset($groups[$key])) {
        $pclass = get_class($groups[$key]);
        $nclass = $symbol['name'];

        throw new Exception(
          pht(
            "Multiple %s subclasses have the same key ('%s'): %s, %s.",
            self::class,
            $key,
            $pclass,
            $nclass));
      }
      $groups[$key] = $obj;
    }

    return $groups;
  }

  final public static function loadAllOptions($external_only = false) {
    $groups = self::loadAll($external_only);

    return self::extractOptions($groups);
  }

  /**
   * @param array<string> $applications list of application classes
   * @return array<PhabricatorConfigOption>
   */
  final public static function loadOptionsForApplications($applications) {
    $groups = self::loadAll();

    $applicable = array_fill_keys($applications, true);

    foreach ($groups as $key => $group) {
      if (!idx($applicable, $group->getApplicationClassName())) {
        unset($groups[$key]);
      }
    }

    return self::extractOptions($groups);
  }

  /**
   * @param array<PhabricatorApplicationConfigOptions> $groups
   */
  private static function extractOptions($groups) {
    $options = array();
    foreach ($groups as $group) {
      foreach ($group->getOptions() as $option) {
        $key = $option->getKey();
        if (isset($options[$key])) {
          throw new Exception(
            pht(
              "Multiple %s subclasses contain an option named '%s'!",
              self::class,
              $key));
        }
        $options[$key] = $option;
      }
    }

    return $options;
  }

  /**
   * Deformat a HEREDOC for use in remarkup by converting line breaks to
   * spaces.
   */
  final protected function deformat($string) {
    return preg_replace('/(?<=\S)\n(?=\S)/', ' ', $string);
  }

}
