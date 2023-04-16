<?php

final class DiffusionURIEditEngine
  extends PhorgeEditEngine {

  const ENGINECONST = 'diffusion.uri';

  private $repository;

  public function setRepository(PhorgeRepository $repository) {
    $this->repository = $repository;
    return $this;
  }

  public function getRepository() {
    return $this->repository;
  }

  public function isEngineConfigurable() {
    return false;
  }

  public function getEngineName() {
    return pht('Repository URIs');
  }

  public function getSummaryHeader() {
    return pht('Edit Repository URI');
  }

  public function getSummaryText() {
    return pht('Creates and edits repository URIs.');
  }

  public function getEngineApplicationClass() {
    return 'PhorgeDiffusionApplication';
  }

  protected function newEditableObject() {
    $uri = PhorgeRepositoryURI::initializeNewURI();

    $repository = $this->getRepository();
    if ($repository) {
      $uri->setRepositoryPHID($repository->getPHID());
      $uri->attachRepository($repository);
    }

    return $uri;
  }

  protected function newObjectQuery() {
    return new PhorgeRepositoryURIQuery();
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create Repository URI');
  }

  protected function getObjectCreateButtonText($object) {
    return pht('Create Repository URI');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Repository URI %d', $object->getID());
  }

  protected function getObjectEditShortText($object) {
    return pht('URI %d', $object->getID());
  }

  protected function getObjectCreateShortText() {
    return pht('Create Repository URI');
  }

  protected function getObjectName() {
    return pht('Repository URI');
  }

  protected function getObjectViewURI($object) {
    return $object->getViewURI();
  }

  protected function buildCustomEditFields($object) {
    $viewer = $this->getViewer();

    $uri_instructions = null;
    if ($object->isBuiltin()) {
      $is_builtin = true;
      $uri_value = (string)$object->getDisplayURI();

      switch ($object->getBuiltinProtocol()) {
        case PhorgeRepositoryURI::BUILTIN_PROTOCOL_SSH:
          $uri_instructions = pht(
            "  - Configure [[ %s | %s ]] to change the SSH username.\n".
            "  - Configure [[ %s | %s ]] to change the SSH host.\n".
            "  - Configure [[ %s | %s ]] to change the SSH port.",
            '/config/edit/diffusion.ssh-user/',
            'diffusion.ssh-user',
            '/config/edit/diffusion.ssh-host/',
            'diffusion.ssh-host',
            '/config/edit/diffusion.ssh-port/',
            'diffusion.ssh-port');
          break;
      }
    } else {
      $is_builtin = false;
      $uri_value = $object->getURI();

      if ($object->getRepositoryPHID()) {
        $repository = $object->getRepository();
        if ($repository->isGit()) {
          $uri_instructions = pht(
            "Provide the URI of a Git repository. It should usually look ".
            "like one of these examples:\n".
            "\n".
            "| Example Git URIs\n".
            "| -----------------------\n".
            "| `git@github.com:example/example.git`\n".
            "| `ssh://user@host.com/git/example.git`\n".
            "| `https://example.com/repository.git`");
        } else if ($repository->isHg()) {
          $uri_instructions = pht(
            "Provide the URI of a Mercurial repository. It should usually ".
            "look like one of these examples:\n".
            "\n".
            "| Example Mercurial URIs\n".
            "|-----------------------\n".
            "| `ssh://hg@bitbucket.org/example/repository`\n".
            "| `https://bitbucket.org/example/repository`");
        } else if ($repository->isSVN()) {
          $uri_instructions = pht(
            "Provide the **Repository Root** of a Subversion repository. ".
            "You can identify this by running `svn info` in a working ".
            "copy. It should usually look like one of these examples:\n".
            "\n".
            "| Example Subversion URIs\n".
            "|-----------------------\n".
            "| `http://svn.example.org/svnroot/`\n".
            "| `svn+ssh://svn.example.com/svnroot/`\n".
            "| `svn://svn.example.net/svnroot/`\n\n".
            "You **MUST** specify the root of the repository, not a ".
            "subdirectory.");
        }
      }
    }

    return array(
      id(new PhorgeHandlesEditField())
        ->setKey('repository')
        ->setAliases(array('repositoryPHID'))
        ->setLabel(pht('Repository'))
        ->setIsRequired(true)
        ->setIsFormField(false)
        ->setTransactionType(
          PhorgeRepositoryURITransaction::TYPE_REPOSITORY)
        ->setDescription(pht('The repository this URI is associated with.'))
        ->setConduitDescription(
          pht(
            'Create a URI in a given repository. This transaction type '.
            'must be present when creating a new URI and must not be '.
            'present when editing an existing URI.'))
        ->setConduitTypeDescription(
          pht('Repository PHID to create a new URI for.'))
        ->setSingleValue($object->getRepositoryPHID()),
      id(new PhorgeTextEditField())
        ->setKey('uri')
        ->setLabel(pht('URI'))
        ->setTransactionType(PhorgeRepositoryURITransaction::TYPE_URI)
        ->setDescription(pht('The repository URI.'))
        ->setConduitDescription(pht('Change the repository URI.'))
        ->setConduitTypeDescription(pht('New repository URI.'))
        ->setIsRequired(!$is_builtin)
        ->setIsLocked($is_builtin)
        ->setValue($uri_value)
        ->setControlInstructions($uri_instructions),
      id(new PhorgeSelectEditField())
        ->setKey('io')
        ->setLabel(pht('I/O Type'))
        ->setTransactionType(PhorgeRepositoryURITransaction::TYPE_IO)
        ->setDescription(pht('URI I/O behavior.'))
        ->setConduitDescription(pht('Adjust I/O behavior.'))
        ->setConduitTypeDescription(pht('New I/O behavior.'))
        ->setValue($object->getIOType())
        ->setOptions($object->getAvailableIOTypeOptions()),
      id(new PhorgeSelectEditField())
        ->setKey('display')
        ->setLabel(pht('Display Type'))
        ->setTransactionType(PhorgeRepositoryURITransaction::TYPE_DISPLAY)
        ->setDescription(pht('URI display behavior.'))
        ->setConduitDescription(pht('Change display behavior.'))
        ->setConduitTypeDescription(pht('New display behavior.'))
        ->setValue($object->getDisplayType())
        ->setOptions($object->getAvailableDisplayTypeOptions()),
      id(new PhorgeHandlesEditField())
        ->setKey('credential')
        ->setAliases(array('credentialPHID'))
        ->setLabel(pht('Credential'))
        ->setIsFormField(false)
        ->setTransactionType(
          PhorgeRepositoryURITransaction::TYPE_CREDENTIAL)
        ->setDescription(
          pht('The credential to use when interacting with this URI.'))
        ->setConduitDescription(pht('Change the credential for this URI.'))
        ->setConduitTypeDescription(pht('New credential PHID, or null.'))
        ->setSingleValue($object->getCredentialPHID()),
      id(new PhorgeBoolEditField())
        ->setKey('disable')
        ->setLabel(pht('Disabled'))
        ->setIsFormField(false)
        ->setTransactionType(PhorgeRepositoryURITransaction::TYPE_DISABLE)
        ->setDescription(pht('Active status of the URI.'))
        ->setConduitDescription(pht('Disable or activate the URI.'))
        ->setConduitTypeDescription(pht('True to disable the URI.'))
        ->setOptions(pht('Enable'), pht('Disable'))
        ->setValue($object->getIsDisabled()),
    );
  }

}
