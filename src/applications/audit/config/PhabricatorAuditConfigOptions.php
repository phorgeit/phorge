<?php

final class PhabricatorAuditConfigOptions
  extends PhabricatorApplicationConfigOptions {

  public function getName() {
    return pht('Audit');
  }

  public function getDescription() {
    return pht('Audit configuration.');
  }

  public function getIcon() {
    return 'fa-check-circle-o';
  }

  public function getGroup() {
    return 'apps';
  }

  public function getOptions() {
    return array(
      $this->newOption(
        'audit.can-author-close-audit',
        'bool',
        false)
        ->setBoolOptions(
          array(
            pht('Enable Self-Accept'),
            pht('Disable Self-Accept'),
          ))
        ->setDescription(
          pht(
            'Allows the author of a commit to be an auditor and accept their '.
            'own commits. Note that this behavior is different from the '.
            'behavior implied by the name of the option: long ago, it did '.
            'something else.')),
    );
  }

}
