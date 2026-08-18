<?php

final class ExtensionInstallGit extends PhorgeExtensionInstallerStrategy {

  protected function fetchContent($source): string {

    $source = new PhutilURI($source);

    $extension_dir = $this->getInstallDir();

    $future = id(new ExecFuture('git clone --depth=1 -- %s', $source))
      ->setCWD($extension_dir);

    if ($this->isDryRun()) {
      $console = PhutilConsole::getConsole();
      $console->writeOut(
        pht(
          "Would run: \n $ cd %s\n$ %s\n",
          $future->getCommand(),
          $future->getCWD()));
    } else {
      $future->resolvex();
    }

    $actual_dir = $extension_dir.'/'.$this->basename($source).'/src/';

    return $actual_dir;
  }

  private function basename($uri) {
    $name = basename($uri);
    if (strrpos($name, '.git')) {
      return substr($name, 0, -4);
    }
    return $name;
  }

}
