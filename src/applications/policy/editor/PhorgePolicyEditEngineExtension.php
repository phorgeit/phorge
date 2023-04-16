<?php

final class PhorgePolicyEditEngineExtension
  extends PhorgeEditEngineExtension {

  const EXTENSIONKEY = 'policy.policy';

  public function getExtensionPriority() {
    return 250;
  }

  public function isExtensionEnabled() {
    return true;
  }

  public function getExtensionName() {
    return pht('Policies');
  }

  public function supportsObject(
    PhorgeEditEngine $engine,
    PhorgeApplicationTransactionInterface $object) {
    return ($object instanceof PhorgePolicyInterface);
  }

  public function buildCustomEditFields(
    PhorgeEditEngine $engine,
    PhorgeApplicationTransactionInterface $object) {

    $viewer = $engine->getViewer();

    $editor = $object->getApplicationTransactionEditor();
    $types = $editor->getTransactionTypesForObject($object);
    $types = array_fuse($types);

    $policies = id(new PhorgePolicyQuery())
      ->setViewer($viewer)
      ->setObject($object)
      ->execute();

    $map = array(
      PhorgeTransactions::TYPE_VIEW_POLICY => array(
        'key' => 'policy.view',
        'aliases' => array('view'),
        'capability' => PhorgePolicyCapability::CAN_VIEW,
        'label' => pht('View Policy'),
        'description' => pht('Controls who can view the object.'),
        'description.conduit' => pht('Change the view policy of the object.'),
        'edit' => 'view',
      ),
      PhorgeTransactions::TYPE_EDIT_POLICY => array(
        'key' => 'policy.edit',
        'aliases' => array('edit'),
        'capability' => PhorgePolicyCapability::CAN_EDIT,
        'label' => pht('Edit Policy'),
        'description' => pht('Controls who can edit the object.'),
        'description.conduit' => pht('Change the edit policy of the object.'),
        'edit' => 'edit',
      ),
      PhorgeTransactions::TYPE_JOIN_POLICY => array(
        'key' => 'policy.join',
        'aliases' => array('join'),
        'capability' => PhorgePolicyCapability::CAN_JOIN,
        'label' => pht('Join Policy'),
        'description' => pht('Controls who can join the object.'),
        'description.conduit' => pht('Change the join policy of the object.'),
        'edit' => 'join',
      ),
      PhorgeTransactions::TYPE_INTERACT_POLICY => array(
        'key' => 'policy.interact',
        'aliases' => array('interact'),
        'capability' => PhorgePolicyCapability::CAN_INTERACT,
        'label' => pht('Interact Policy'),
        'description' => pht('Controls who can interact with the object.'),
        'description.conduit'
          => pht('Change the interaction policy of the object.'),
        'edit' => 'interact',
      ),
    );

    if ($object instanceof PhorgePolicyCodexInterface) {
      $codex = PhorgePolicyCodex::newFromObject(
        $object,
        $viewer);
    } else {
      $codex = null;
    }

    $fields = array();
    foreach ($map as $type => $spec) {
      if (empty($types[$type])) {
        continue;
      }

      $capability = $spec['capability'];
      $key = $spec['key'];
      $aliases = $spec['aliases'];
      $label = $spec['label'];
      $description = $spec['description'];
      $conduit_description = $spec['description.conduit'];
      $edit = $spec['edit'];

      // Objects may present a policy value to the edit workflow that is
      // different from their nominal policy value: for example, when tasks
      // are locked, they appear as "Editable By: No One" to other applications
      // but we still want to edit the actual policy stored in the database
      // when we show the user a form with a policy control in it.

      if ($codex) {
        $policy_value = $codex->getPolicyForEdit($capability);
      } else {
        $policy_value = $object->getPolicy($capability);
      }

      $policy_field = id(new PhorgePolicyEditField())
        ->setKey($key)
        ->setLabel($label)
        ->setAliases($aliases)
        ->setIsCopyable(true)
        ->setCapability($capability)
        ->setPolicies($policies)
        ->setTransactionType($type)
        ->setEditTypeKey($edit)
        ->setDescription($description)
        ->setConduitDescription($conduit_description)
        ->setConduitTypeDescription(pht('New policy PHID or constant.'))
        ->setValue($policy_value);
      $fields[] = $policy_field;

      if ($object instanceof PhorgeSpacesInterface) {
        if ($capability == PhorgePolicyCapability::CAN_VIEW) {
          $type_space = PhorgeTransactions::TYPE_SPACE;
          if (isset($types[$type_space])) {
            $space_phid = PhorgeSpacesNamespaceQuery::getObjectSpacePHID(
              $object);

            $space_field = id(new PhorgeSpaceEditField())
              ->setKey('spacePHID')
              ->setLabel(pht('Space'))
              ->setEditTypeKey('space')
              ->setIsCopyable(true)
              ->setIsLockable(false)
              ->setIsReorderable(false)
              ->setAliases(array('space', 'policy.space'))
              ->setTransactionType($type_space)
              ->setDescription(pht('Select a space for the object.'))
              ->setConduitDescription(
                pht('Shift the object between spaces.'))
              ->setConduitTypeDescription(pht('New space PHID.'))
              ->setValue($space_phid);
            $fields[] = $space_field;

            $space_field->setPolicyField($policy_field);
            $policy_field->setSpaceField($space_field);
          }
        }
      }
    }

    return $fields;
  }

}
