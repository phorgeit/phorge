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
              pht('Store to search in and query (Ignoring configuration).')),
        ));
  }

  public function execute(PhutilArgumentParser $args) {
    $console = PhutilConsole::getConsole();
    $argv = $args->getArg('argv');

    $table = id(new PhutilConsoleTable())
      ->setConsole($console)
      ->setShowHeader(false)
      ->setBorders(true)
      ->addColumn('param')
      ->addColumn('value');

    foreach ($argv as $input) {
      if (!$this->isExtensionKey($input)) {
        $console->writeLog(pht("Not an ext key? %s\n", $input));
        continue;
      }

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

        $item = array(
          'Extension Key' => $data->getExtensionKey(),
          'Library' => $data->getPhutilLibName(),
          'Version' => $data->getVersion(),
          'Download URI' => $data->getDownloadUri(),
          'Store URI' => $store['uri'],
        );

        $rows = array();
        foreach ($item as $key => $value) {
          $rows[] = array('param' => $key, 'value' => $value);
        }
        $table->drawRows($rows);
      }
    }
  }

}
