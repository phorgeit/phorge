<?php

final class PhorgePolicyManagementShowWorkflow
  extends PhorgePolicyManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('show')
      ->setSynopsis(pht('Show policy information about an object.'))
      ->setExamples('**show** D123')
      ->setArguments(
        array(
          array(
            'name'      => 'objects',
            'wildcard'  => true,
          ),
        ));
  }

  public function execute(PhutilArgumentParser $args) {
    $console = PhutilConsole::getConsole();
    $viewer = $this->getViewer();

    $obj_names = $args->getArg('objects');
    if (!$obj_names) {
      throw new PhutilArgumentUsageException(
        pht('Specify the name of an object to show policy information for.'));
    } else if (count($obj_names) > 1) {
      throw new PhutilArgumentUsageException(
        pht(
          'Specify the name of exactly one object to show policy information '.
          'for.'));
    }

    $object = id(new PhorgeObjectQuery())
      ->setViewer($viewer)
      ->withNames($obj_names)
      ->executeOne();

    if (!$object) {
      $name = head($obj_names);
      throw new PhutilArgumentUsageException(
        pht(
          "No such object '%s'!",
          $name));
    }

    $handle = id(new PhorgeHandleQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($object->getPHID()))
      ->executeOne();

    $policies = PhorgePolicyQuery::loadPolicies(
      $viewer,
      $object);

    $console->writeOut("__%s__\n\n", pht('OBJECT'));
    $console->writeOut("  %s\n", $handle->getFullName());
    $console->writeOut("\n");

    $console->writeOut("__%s__\n\n", pht('CAPABILITIES'));
    foreach ($policies as $capability => $policy) {
      $ref = $policy->newRef($viewer);

      $console->writeOut("  **%s**\n", $capability);
      $console->writeOut("    %s\n", $ref->getPolicyDisplayName());
      $console->writeOut("    %s\n",
        PhorgePolicy::getPolicyExplanation($viewer, $policy->getPHID()));
      $console->writeOut("\n");
    }

    if ($object instanceof PhorgePolicyCodexInterface) {
      $codex = PhorgePolicyCodex::newFromObject($object, $viewer);

      $rules = $codex->getPolicySpecialRuleDescriptions();
      foreach ($rules as $rule) {
        echo tsprintf(
          "  - %s\n",
          $rule->getDescription());
      }

      echo "\n";
    }
  }

}
