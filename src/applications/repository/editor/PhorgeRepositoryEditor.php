<?php

final class PhorgeRepositoryEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeDiffusionApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Repositories');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_EDGE;
    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;

    return $types;
  }

  protected function didCatchDuplicateKeyException(
    PhorgeLiskDAO $object,
    array $xactions,
    Exception $ex) {

    $errors = array();

    $errors[] = new PhorgeApplicationTransactionValidationError(
      null,
      pht('Invalid'),
      pht(
        'The chosen callsign or repository short name is already in '.
        'use by another repository.'),
      null);

    throw new PhorgeApplicationTransactionValidationException($errors);
  }

  protected function supportsSearch() {
    return true;
  }

  protected function applyFinalEffects(
    PhorgeLiskDAO $object,
    array $xactions) {

    // If the repository does not have a local path yet, assign it one based
    // on its ID. We can't do this earlier because we won't have an ID yet.
    $local_path = $object->getLocalPath();
    if (!strlen($local_path)) {
      $local_key = 'repository.default-local-path';

      $local_root = PhorgeEnv::getEnvConfig($local_key);
      $local_root = rtrim($local_root, '/');

      $id = $object->getID();
      $local_path = "{$local_root}/{$id}/";

      $object->setLocalPath($local_path);
      $object->save();
    }

    if ($this->getIsNewObject()) {
      // The default state of repositories is to be hosted, if they are
      // enabled without configuring any "Observe" URIs.
      $object->setHosted(true);
      $object->save();

      // Create this repository's builtin URIs.
      $builtin_uris = $object->newBuiltinURIs();
      foreach ($builtin_uris as $uri) {
        $uri->save();
      }

      id(new DiffusionRepositoryClusterEngine())
        ->setViewer($this->getActor())
        ->setRepository($object)
        ->synchronizeWorkingCopyAfterCreation();
    }

    $object->writeStatusMessage(
      PhorgeRepositoryStatusMessage::TYPE_NEEDS_UPDATE,
      null);

    return $xactions;
  }

}
