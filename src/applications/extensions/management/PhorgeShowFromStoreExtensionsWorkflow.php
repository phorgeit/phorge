<?php

final class PhorgeShowFromStoreExtensionsWorkflow
  extends PhorgeExtensionsManagementWorkflow {


  protected function didConstruct() {
    $this
      ->setName('show')
      ->setSynopsis(pht('Shows information about a prospective Extension.'))
      ->setExamples(
        array(
          '**show** __extension_key__',
          '**show** __uri__',
        ))
      ->setArguments(
        array(
          id(new PhutilArgumentSpecification())
            ->setName('argv')
            ->setWildcard(true),
          id(new PhutilArgumentSpecification())
            ->setName('store-uri')
            ->setParamName('store_uri')
            ->setHelp(
              pht(
                'Store to search in and query (Ignoring configuration).')),
        ));
  }

  public function execute(PhutilArgumentParser $args) {
    $console = PhutilConsole::getConsole();
    $argv = $args->getArg('argv');

    foreach ($argv as $input) {

      if ($this->isExtensionKey($input)) {
        $key = $input;
        $console->writeLog(pht("Treating %s as an extension key\n", $key));
        foreach ($this->getExtensionStores() as $store) {
          $console->writeLog(
            "Looking for %s in store %s\n",
            $key,
            $store['uri']);
          $client = new ExtensionStoreClient($store['uri']);
          $data = $client->queryExtension($key);

          if (!$data) {
            $console->writeLog(pht("Extension %s not found.\n", $key));
            continue;
          }
          $console->writeOut(
            pht(
              "Extension key: %s\nLibrary: %s\nVersion: %s\nURI: %s\n",
              $data->getExtensionKey(),
              $data->getPhutilLibName(),
              $data->getVersion(),
              $data->getDownloadUri()));
        }
      } else {
        $console->writeLog(pht("Not an ext key? %s\n", $input));
      }
    }
  }


}
