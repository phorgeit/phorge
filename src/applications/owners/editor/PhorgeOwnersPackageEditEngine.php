<?php

final class PhorgeOwnersPackageEditEngine
  extends PhorgeEditEngine {

  const ENGINECONST = 'owners.package';

  public function getEngineName() {
    return pht('Owners Packages');
  }

  public function getSummaryHeader() {
    return pht('Configure Owners Package Forms');
  }

  public function getSummaryText() {
    return pht('Configure forms for creating and editing packages in Owners.');
  }

  public function getEngineApplicationClass() {
    return 'PhorgeOwnersApplication';
  }

  protected function newEditableObject() {
    return PhorgeOwnersPackage::initializeNewPackage($this->getViewer());
  }

  protected function newObjectQuery() {
    return id(new PhorgeOwnersPackageQuery())
      ->needPaths(true);
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create New Package');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Package: %s', $object->getName());
  }

  protected function getObjectEditShortText($object) {
    return pht('Package %d', $object->getID());
  }

  protected function getObjectCreateShortText() {
    return pht('Create Package');
  }

  protected function getObjectName() {
    return pht('Package');
  }

  protected function getObjectViewURI($object) {
    return $object->getURI();
  }

  protected function buildCustomEditFields($object) {

    $paths_help = pht(<<<EOTEXT
When updating the paths for a package, pass a list of dictionaries like
this as the `value` for the transaction:

```lang=json, name="Example Paths Value"
[
  {
    "repositoryPHID": "PHID-REPO-1234",
    "path": "/path/to/directory/",
    "excluded": false
  },
  {
    "repositoryPHID": "PHID-REPO-1234",
    "path": "/another/example/path/",
    "excluded": false
  }
]
```

This transaction will set the paths to the list you provide, overwriting any
previous paths.

Generally, you will call `owners.search` first to get a list of current paths
(which are provided in the same format), make changes, then update them by
applying a transaction of this type.
EOTEXT
      );

    $autoreview_map = PhorgeOwnersPackage::getAutoreviewOptionsMap();
    $autoreview_map = ipull($autoreview_map, 'name');

    $dominion_map = PhorgeOwnersPackage::getDominionOptionsMap();
    $dominion_map = ipull($dominion_map, 'name');

    $authority_map = PhorgeOwnersPackage::getAuthorityOptionsMap();
    $authority_map = ipull($authority_map, 'name');

    return array(
      id(new PhorgeTextEditField())
        ->setKey('name')
        ->setLabel(pht('Name'))
        ->setDescription(pht('Name of the package.'))
        ->setTransactionType(
          PhorgeOwnersPackageNameTransaction::TRANSACTIONTYPE)
        ->setIsRequired(true)
        ->setValue($object->getName()),
      id(new PhorgeDatasourceEditField())
        ->setKey('owners')
        ->setLabel(pht('Owners'))
        ->setDescription(pht('Users and projects which own the package.'))
        ->setTransactionType(
          PhorgeOwnersPackageOwnersTransaction::TRANSACTIONTYPE)
        ->setDatasource(new PhorgeProjectOrUserDatasource())
        ->setIsCopyable(true)
        ->setValue($object->getOwnerPHIDs()),
      id(new PhorgeSelectEditField())
        ->setKey('dominion')
        ->setLabel(pht('Dominion'))
        ->setDescription(
          pht('Change package dominion rules.'))
        ->setTransactionType(
          PhorgeOwnersPackageDominionTransaction::TRANSACTIONTYPE)
        ->setIsCopyable(true)
        ->setValue($object->getDominion())
        ->setOptions($dominion_map),
      id(new PhorgeSelectEditField())
        ->setKey('authority')
        ->setLabel(pht('Authority'))
        ->setDescription(
          pht('Change package authority rules.'))
        ->setTransactionType(
          PhorgeOwnersPackageAuthorityTransaction::TRANSACTIONTYPE)
        ->setIsCopyable(true)
        ->setValue($object->getAuthorityMode())
        ->setOptions($authority_map),
      id(new PhorgeSelectEditField())
        ->setKey('autoReview')
        ->setLabel(pht('Auto Review'))
        ->setDescription(
          pht(
            'Automatically trigger reviews for commits affecting files in '.
            'this package.'))
        ->setTransactionType(
          PhorgeOwnersPackageAutoreviewTransaction::TRANSACTIONTYPE)
        ->setIsCopyable(true)
        ->setValue($object->getAutoReview())
        ->setOptions($autoreview_map),
      id(new PhorgeSelectEditField())
        ->setKey('auditing')
        ->setLabel(pht('Auditing'))
        ->setDescription(
          pht(
            'Automatically trigger audits for commits affecting files in '.
            'this package.'))
        ->setTransactionType(
          PhorgeOwnersPackageAuditingTransaction::TRANSACTIONTYPE)
        ->setIsCopyable(true)
        ->setValue($object->getAuditingState())
        ->setOptions(PhorgeOwnersAuditRule::newSelectControlMap()),
      id(new PhorgeRemarkupEditField())
        ->setKey('description')
        ->setLabel(pht('Description'))
        ->setDescription(pht('Human-readable description of the package.'))
        ->setTransactionType(
          PhorgeOwnersPackageDescriptionTransaction::TRANSACTIONTYPE)
        ->setValue($object->getDescription()),
      id(new PhorgeSelectEditField())
        ->setKey('status')
        ->setLabel(pht('Status'))
        ->setDescription(pht('Archive or enable the package.'))
        ->setTransactionType(
          PhorgeOwnersPackageStatusTransaction::TRANSACTIONTYPE)
        ->setIsFormField(false)
        ->setValue($object->getStatus())
        ->setOptions($object->getStatusNameMap()),
      id(new PhorgeCheckboxesEditField())
        ->setKey('ignored')
        ->setLabel(pht('Ignored Attributes'))
        ->setDescription(pht('Ignore paths with any of these attributes.'))
        ->setTransactionType(
          PhorgeOwnersPackageIgnoredTransaction::TRANSACTIONTYPE)
        ->setValue(array_keys($object->getIgnoredPathAttributes()))
        ->setOptions(
          array(
            'generated' => pht('Ignore generated files (review only).'),
          )),
      id(new PhorgeConduitEditField())
        ->setKey('paths.set')
        ->setLabel(pht('Paths'))
        ->setIsFormField(false)
        ->setTransactionType(
          PhorgeOwnersPackagePathsTransaction::TRANSACTIONTYPE)
        ->setConduitDescription(
          pht('Overwrite existing package paths with new paths.'))
        ->setConduitTypeDescription(
          pht('List of dictionaries, each describing a path.'))
        ->setConduitDocumentation($paths_help),
    );
  }

}
