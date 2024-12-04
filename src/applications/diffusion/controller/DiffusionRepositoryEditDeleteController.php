<?php

final class DiffusionRepositoryEditDeleteController
  extends DiffusionRepositoryManageController {

  public function handleRequest(AphrontRequest $request) {
    // This is just an information box, telling admins to use CLI for destroy.
    // To increase shared knowledge about how Phorge works, we can safely show
    // it to those who see the repository, not just those who can edit it.
    $response = $this->loadDiffusionContext();
    if ($response) {
      return $response;
    }

    $viewer = $this->getViewer();
    $drequest = $this->getDiffusionRequest();
    $repository = $drequest->getRepository();

    $panel_uri = id(new DiffusionRepositoryBasicsManagementPanel())
      ->setRepository($repository)
      ->getPanelURI();

    $doc_uri = PhabricatorEnv::getDoclink(
      'Permanently Destroying Data');

    return $this->newDialog()
      ->setTitle(pht('Delete Repository'))
      ->appendParagraph(
        pht(
          'To permanently destroy this repository, run this command from '.
          'the command line:'))
      ->appendCommand(
        csprintf(
          '%s $ ./bin/remove destroy %R',
          PlatformSymbols::getPlatformServerPath(),
          $repository->getMonogram()))
      ->appendParagraph(
        pht(
          'Repositories can not be permanently destroyed from the web '.
          'interface. See %s in the documentation for more information.',
          phutil_tag(
            'a',
            array(
              'href' => $doc_uri,
              'target' => '_blank',
            ),
            pht('Permanently Destroying Data'))))
      ->addCancelButton($panel_uri, pht('Close'));
  }

}
