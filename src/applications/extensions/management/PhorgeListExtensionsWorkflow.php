<?php

final class PhorgeListExtensionsWorkflow
  extends PhorgeExtensionsManagementWorkflow {


  protected function didConstruct() {
    $this
      ->setName('list')
      ->setSynopsis(pht('Lists installed extensions. Experimental.'))
      ->setExamples(
        '**list**')
      ->setArguments(
        array(
          array(
            'name'    => 'format',
            'param'   => 'format',
            'default' => 'table',
            'help'    => pht('Output format. `json` or `table`.'),
          ),
        ));
  }

  public function execute(PhutilArgumentParser $args) {

    $known_extensions = array();

    $all_libs = $this->loadAllLibrariesAndExtensions();

    foreach ($all_libs as $lib) {
      if ($lib->isCoreLibrary()) {
        $status = 'core';
      } else {
        $status = 'extension';
      }


      if (Filesystem::isPharPath($lib->getLocation())) {
        $format = 'phar';
      } else {
        $format = 'git';
      }

      $known_extensions[$lib->getName()] = array(
        'name' => $lib->getName(),
        'location' => $lib->getLocation(),
        'status' => $status,
        'format' => $format,
      );
    }

    $output_format = $args->getArg('format');
    switch ($output_format) {
      case 'json':
        $json = new PhutilJSON();
        PhutilConsole::getConsole()
          ->writeOut($json->encodeFormatted($known_extensions));
        break;

      case 'table':
        $table = id(new PhutilConsoleTable())
          ->setBorders(true)
          ->addColumn('name', array('title' => 'Extension Name'))
          ->addColumn('status', array('title' => pht('')))
          ->addColumn('format', array('title' => pht('Format')))
          ->addColumn('version', array('title' => pht('Version')))
          ->addColumn('location',  array('title' => pht('Location')));

        $table->drawRows($known_extensions);
        break;

      default:
        throw new Exception(pht('Unknown output format `%s`', $output_format));
    }

  }

}
