<?php

/**
 * Trigger Rule that Removes Subscribers
 *
 * This may be useful to have a column that simplify
 * Task handovers. You can remove both Users or Project Tags
 * from the list of Task Subscribers.
 *
 * This class was adapted from these classes:
 *  - PhabricatorProjectTriggerRemoveProjectsRule
 *  - PhabricatorProjectTriggerManiphestOwnerRule
 *
 * https://we.phorge.it/T15162
 */
final class PhabricatorProjectTriggerRemoveSubscribersRule
  extends PhabricatorProjectTriggerRule {

  const TRIGGERTYPE = 'task.subscriber.remove';

  public function getSelectControlName() {
    return pht('Remove subscribers');
  }

  protected function getValueForEditorField() {
    return $this->getDatasource()->getWireTokens($this->getValue());
  }

  protected function assertValidRuleRecordFormat($value) {
    if (!is_array($value)) {
      throw new Exception(
        pht(
          'Remove subscribers rule value should be a list, but is not '.
          '(value is "%s").',
          phutil_describe_type($value)));
    }
  }

  protected function assertValidRuleRecordValue($value) {
    if (!$value) {
      throw new Exception(
        pht(
          'You must select at least one user or project tag to remove.'));
    }
  }

  protected function newDropTransactions($object, $value) {
    $subscriber_edge_type = PhabricatorObjectHasSubscriberEdgeType::EDGECONST;

    $xaction = $object->getApplicationTransactionTemplate()
      ->setTransactionType(PhabricatorTransactions::TYPE_EDGE)
      ->setMetadataValue('edge:type', $subscriber_edge_type)
      ->setNewValue(
        array(
          '-' => array_fuse($value),
        ));

    return array($xaction);
  }

  protected function newDropEffects($value) {
    return array(
      $this->newEffect()
        ->setIcon('fa-briefcase')
        ->setContent($this->getRuleViewDescription($value)),
    );
  }

  protected function getDefaultValue() {
    return null;
  }

  protected function getPHUIXControlType() {
    return 'tokenizer';
  }

  private function getDatasource() {
    $datasource = new PhabricatorProjectOrUserDatasource();

    if ($this->getViewer()) {
      $datasource->setViewer($this->getViewer());
    }

    return $datasource;
  }

  protected function getPHUIXControlSpecification() {
    $template = id(new AphrontTokenizerTemplateView())
      ->setViewer($this->getViewer());

    $template_markup = $template->render();
    $datasource = $this->getDatasource();

    return array(
      'markup' => (string)hsprintf('%s', $template_markup),
      'config' => array(
        'src' => $datasource->getDatasourceURI(),
        'browseURI' => $datasource->getBrowseURI(),
        'placeholder' => $datasource->getPlaceholderText(),
        'limit' => $datasource->getLimit(),
      ),
      'value' => null,
    );
  }

  public function getRuleViewLabel() {
    return pht('Remove subscribers');
  }

  public function getRuleViewDescription($value) {
    return pht(
      'Remove subscribers: %s.',
      phutil_tag(
        'strong',
        array(),
        $this->getViewer()
          ->renderHandleList($value)
          ->setAsInline(true)
          ->render()));
  }

  public function getRuleViewIcon($value) {
    return id(new PHUIIconView())
      ->setIcon('fa-users', 'red');
  }

}
