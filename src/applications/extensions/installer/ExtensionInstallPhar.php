<?php

final class ExtensionInstallPhar extends PhorgeExtensionInstallerStrategy {

  public function install($source) {
    $extension_dir = $this->getInstallDir();

    $actual_dir = 'phar://';
    $actual_dir .= Filesystem::resolvePath(basename($source), $extension_dir);
    $actual_dir .= '/src/';



    if ($this->isDryRun()) {
      $console = PhutilConsole::getConsole();
      $console->writeOut(
        pht(
          "Would copy `%s` to `%s` and add `%s` to LoadLibraries\n",
          $source,
          $extension_dir,
          $actual_dir));
      return;
    }


    Filesystem::copyFile($source, $extension_dir);

    $this->addToLoadLibraries($actual_dir);
  }

}
