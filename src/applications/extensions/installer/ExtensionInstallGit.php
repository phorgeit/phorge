<?php

final class ExtensionInstallGit extends PhorgeExtensionInstallerStrategy {

  public function install($source) {

    $extension_dir = $this->getInstallDir();

    $future = id(new ExecFuture('git clone -- %s', $source))
      ->setCWD($extension_dir);

    if ($this->isDryRun()) {
      $console = PhutilConsole::getConsole();
      $console->writeOut(
        pht(
          "Would run: \n $ cd %s\n$ %s\n",
          $future->getCommand(),
          $future->getCWD()));
      return;
    }

    $future->resolvex();

    $actual_dir = $extension_dir.'/'.basename($source).'/src/';

    $this->addToLoadLibraries($actual_dir);
  }

}
