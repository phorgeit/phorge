<?php

final class PhorgeNamedPolicyEditEngine
  extends PhabricatorEditEngine {

  const ENGINECONST = 'policy.named';

  public function getEngineName() {
    return pht('Named Policies');
  }

  public function getSummaryHeader() {
    return pht('Configure Named Policy Forms');
  }

  public function getSummaryText() {
    return pht('Configure creating and editing Named Policies.');
  }

  public function getEngineApplicationClass() {
    return PhabricatorPolicyApplication::class;
  }

  protected function newEditableObject() {
    return new PhorgeNamedPolicy();
  }

  protected function newObjectQuery() {
    return new PhorgeNamedPolicyQuery();
  }

  protected function getObjectCreateCancelURI($object) {
    return $this->getApplication()->getApplicationURI('named/');
  }

  protected function getEditorURI() {
    return $this->getApplication()->getApplicationURI('named/edit/');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit %s: %s', $this->getObjectName(), $object->getName());
  }

  protected function getObjectName() {
    return pht('Named Policy');
  }

  protected function getCreateNewObjectPolicy() {
    return $this->getApplication()->getPolicy(
      PhorgePolicyCanCreateNamedPolicyCapability::CAPABILITY);
  }

  protected function getObjectViewURI($object) {
    $id = $object->getID();
    return $this->getApplication()->getApplicationURI("named/{$id}");
  }

  /**
   * @param PhorgeNamedPolicy $object
   */
  protected function buildCustomEditFields($object) {
    $fields = array(
      id(new PhabricatorTextEditField())
        ->setKey('name')
        ->setLabel(pht('Name'))
        ->setDescription(pht('Name of the Policy.'))
        ->setConduitDescription(pht('Rename the policy.'))
        ->setConduitTypeDescription(pht('New policy name.'))
        ->setTransactionType(
          PhorgePolicyNamedPolicyNameTransaction::TRANSACTIONTYPE)
        ->setIsRequired(true)
        ->setValue($object->getName()),
      id(new PhabricatorRemarkupEditField())
        ->setKey('description')
        ->setLabel(pht('Description'))
        ->setConduitDescription(pht('Edit the description of the policy.'))
        ->setConduitTypeDescription(pht('New description of the policy.'))
        ->setTransactionType(
          PhorgePolicyNamedPolicyDescriptionTransaction::TRANSACTIONTYPE)
        ->setValue($object->getDescription()),
      );


      $target_object_type_instructions = pht(
        'The Type of objects this policy can apply to. If set, this policy '.
        'can only be applied to that type of objects, but can use '.
        'Object Rules that apply to that object.'.
        "\n\n".
        'If not set, this policy can be applied to any object.'.
        "\n\n".
        'You may need to save the Named Policy and edit it again for changes '.
        'to take effect.');

      $fields[] = id(new PhabricatorDatasourceEditField())
        ->setKey('targetobjecttype')
        ->setLabel(pht('Target Object Type'))
        ->setConduitDescription(
          pht('Set the type of object this policy can apply to.'))
        ->setConduitTypeDescription(pht('A four-letter object type.'))
        ->setControlInstructions($target_object_type_instructions)
        // TODO make custom datasource - filter to implementing PolicyInterface
        ->setDatasource(new PhabricatorSearchDocumentTypeDatasource())
        ->setTransactionType(
          PhorgePolicyNamedPolicyTargetObjectTypeTransaction::TRANSACTIONTYPE)
        ->setSingleValue($object->getTargetObjectType());

    $effective_policy_instructions = pht(
      'When selected as the policy for other objects, this policy will behave '.
      'like the **Effective Policy**.'.
      "\n\n".
      '**Visible To** and **Editable By** are the policies controlling access '.
      'to the Named Policy itself.');

    $reference_object = $object->getReferenceObject();
    $policies = $this->selectPoliciesForEffectiveSelection($reference_object);

    $fields[] = id(new PhabricatorPolicyEditField())
      ->setKey('namedpolicy.policy')
      ->setLabel(pht('Effective Policy'))
      ->setConduitDescription(
        pht('The effective policy which this policy will behave like when '.
          'selected as the policy for other objects.'))
      ->setConduitTypeDescription(pht('Existing policy PHID or constant.'))
      ->setControlInstructions($effective_policy_instructions)
      ->setCapability(PhorgeNamedPolicyEffectivePolicyCapability::CAPABILITY)
      ->setTemplateObject($reference_object)
      ->setTemplatePHIDType($object->getReferenceObjectPHIDType())
      ->setPolicies($policies)
      ->setTransactionType(
        PhorgePolicyNamedPolicyEffectivePolicyTransaction::TRANSACTIONTYPE)
      ->setValue($object->getEffectivePolicy());

    return $fields;
  }

  private function selectPoliciesForEffectiveSelection($reference_object) {

    $policies = id(new PhabricatorPolicyQuery())
      ->setViewer($this->getViewer())
      ->setObject($reference_object)
      ->execute();

    return $policies;
  }

}
