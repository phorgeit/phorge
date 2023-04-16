<?php

final class PhorgeConfigEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeConfigApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Configuration');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeConfigTransaction::TYPE_EDIT;

    return $types;
  }

  protected function getCustomTransactionOldValue(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeConfigTransaction::TYPE_EDIT:
        return array(
          'deleted' => (int)$object->getIsDeleted(),
          'value'   => $object->getValue(),
        );
    }
  }

  protected function getCustomTransactionNewValue(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeConfigTransaction::TYPE_EDIT:
        return $xaction->getNewValue();
    }
  }

  protected function applyCustomInternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeConfigTransaction::TYPE_EDIT:
        $v = $xaction->getNewValue();

        // If this is a defined configuration option (vs a straggler from an
        // old version of Phorge or a configuration file misspelling)
        // submit it to the validation gauntlet.
        $key = $object->getConfigKey();
        $all_options = PhorgeApplicationConfigOptions::loadAllOptions();
        $option = idx($all_options, $key);
        if ($option) {
          $option->getGroup()->validateOption(
            $option,
            $v['value']);
        }

        $object->setIsDeleted((int)$v['deleted']);
        $object->setValue($v['value']);
        break;
    }
  }

  protected function applyCustomExternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {
    return;
  }

  protected function mergeTransactions(
    PhorgeApplicationTransaction $u,
    PhorgeApplicationTransaction $v) {

    $type = $u->getTransactionType();
    switch ($type) {
      case PhorgeConfigTransaction::TYPE_EDIT:
        return $v;
    }

    return parent::mergeTransactions($u, $v);
  }

  protected function transactionHasEffect(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    $old = $xaction->getOldValue();
    $new = $xaction->getNewValue();

    $type = $xaction->getTransactionType();
    switch ($type) {
      case PhorgeConfigTransaction::TYPE_EDIT:
        // If an edit deletes an already-deleted entry, no-op it.
        if (idx($old, 'deleted') && idx($new, 'deleted')) {
          return false;
        }
        break;
    }

    return parent::transactionHasEffect($object, $xaction);
  }

  protected function didApplyTransactions($object, array $xactions) {
    // Force all the setup checks to run on the next page load.
    PhorgeSetupCheck::deleteSetupCheckCache();

    return $xactions;
  }

  public static function storeNewValue(
    PhorgeUser $user,
    PhorgeConfigEntry $config_entry,
    $value,
    PhorgeContentSource $source,
    $acting_as_phid = null) {

    $xaction = id(new PhorgeConfigTransaction())
      ->setTransactionType(PhorgeConfigTransaction::TYPE_EDIT)
      ->setNewValue(
        array(
           'deleted' => false,
           'value' => $value,
        ));

    $editor = id(new PhorgeConfigEditor())
      ->setActor($user)
      ->setContinueOnNoEffect(true)
      ->setContentSource($source);

    if ($acting_as_phid) {
      $editor->setActingAsPHID($acting_as_phid);
    }

    $editor->applyTransactions($config_entry, array($xaction));
  }

  public static function deleteConfig(
    PhorgeUser $user,
    PhorgeConfigEntry $config_entry,
    PhorgeContentSource $source) {

    $xaction = id(new PhorgeConfigTransaction())
      ->setTransactionType(PhorgeConfigTransaction::TYPE_EDIT)
      ->setNewValue(
        array(
          'deleted' => true,
          'value' => null,
        ));

    $editor = id(new PhorgeConfigEditor())
      ->setActor($user)
      ->setContinueOnNoEffect(true)
      ->setContentSource($source);

    $editor->applyTransactions($config_entry, array($xaction));
  }

}
