<?php


final class DiffusionCloneURIView extends AphrontView {

  private $repository;
  private $repositoryURI;
  private $displayURI;

  public function setRepository(PhabricatorRepository $repository) {
    $this->repository = $repository;
    return $this;
  }

  public function getRepository() {
    return $this->repository;
  }

  public function setRepositoryURI(PhabricatorRepositoryURI $repository_uri) {
    $this->repositoryURI = $repository_uri;
    return $this;
  }

  public function getRepositoryURI() {
    return $this->repositoryURI;
  }

  public function setDisplayURI($display_uri) {
    $this->displayURI = $display_uri;
    return $this;
  }

  public function getDisplayURI() {
    return $this->displayURI;
  }

  public function render() {
    $viewer = $this->getViewer();

    require_celerity_resource('diffusion-icons-css');

    Javelin::initBehavior('select-content');
    Javelin::initBehavior('phabricator-clipboard-copy');

    $uri_id = celerity_generate_unique_node_id();

    $display = $this->getDisplayURI();

    $input = javelin_tag(
      'input',
      array(
        'id' => $uri_id,
        'type' => 'text',
        'value' => $display,
        'class' => 'diffusion-clone-uri',
        'readonly' => 'true',
        'sigil' => 'select-content',
        'meta' => array(
          'selectID' => $uri_id,
          'once' => true,
        ),
      ));

    $uri = $this->getRepositoryURI();
    switch ($uri->getEffectiveIOType()) {
      case PhabricatorRepositoryURI::IO_READ:
        $io_icon = 'fa-eye';
        $io_tip = pht('Read-Only');
        break;
      case PhabricatorRepositoryURI::IO_READWRITE:
        $io_icon = 'fa-download';
        $io_tip = pht('Read / Write');
        break;
      default:
        $io_icon = 'fa-cloud';
        $io_tip = pht('External');
        break;
    }

    $io = javelin_tag(
      'span',
      array(
        'class' => 'diffusion-clone-uri-io',
        'sigil' => 'has-tooltip',
        'meta' => array(
          'tip' => $io_tip,
        ),
      ),
      id(new PHUIIconView())->setIcon($io_icon));

    $copy = id(new PHUIButtonView())
      ->setTag('a')
      ->setColor(PHUIButtonView::GREY)
      ->setIcon('fa-clipboard')
      ->setHref('#')
      ->addSigil('clipboard-copy')
      ->addSigil('has-tooltip')
      ->setMetadata(
        array(
          'tip' => pht('Copy repository URI'),
          'text' => $display,
          'successMessage' => pht('Repository URI copied.'),
          'errorMessage' => pht('Copy of Repository URI failed.'),
        ));

    switch ($uri->getEffectiveIOType()) {
      case PhabricatorRepositoryURI::IO_READ:
      case PhabricatorRepositoryURI::IO_READWRITE:
        switch ($uri->getBuiltinProtocol()) {
          case PhabricatorRepositoryURI::BUILTIN_PROTOCOL_SSH:
            $auth_uri = id(new PhabricatorSSHKeysSettingsPanel())
              ->setViewer($viewer)
              ->setUser($viewer)
              ->getPanelURI();
            $auth_tip = pht('Manage SSH Keys');
            $auth_disabled = false;
            break;
          default:
            $auth_uri = id(new DiffusionSetPasswordSettingsPanel())
              ->setViewer($viewer)
              ->setUser($viewer)
              ->getPanelURI();
            $auth_tip = pht('Manage Password');
            $auth_disabled = false;
            break;
        }
        break;
      default:
        $auth_disabled = true;
        $auth_tip = pht('External');
        $auth_uri = '#';
        break;
    }

    $credentials = id(new PHUIButtonView())
      ->setTag('a')
      ->setColor(PHUIButtonView::GREY)
      ->setIcon('fa-key')
      ->setTooltip($auth_tip)
      ->setHref($auth_uri)
      ->setDisabled($auth_disabled);

    $elements = array();
    $elements[] = $io;
    $elements[] = $input;
    $elements[] = $copy;
    $elements[] = $credentials;

    return phutil_tag(
      'div',
      array(
        'class' => 'diffusion-clone-uri-wrapper',
      ),
      $elements);
  }

}
