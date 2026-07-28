<?php

final class PhabricatorImagemagickSetupCheck extends PhabricatorSetupCheck {

  public function getDefaultGroup() {
    return self::GROUP_OTHER;
  }

  /**
   * Get the name of the ImageMagick binary. Since ImageMagick version 7, the
   * "magick" command is replacing the old "convert" command.
   *
   * @return string|null
   */
  public function getImageMagickBinaryName() {
    if (Filesystem::binaryExists('magick')) {
      return 'magick';
    } else if (Filesystem::binaryExists('convert')) {
      return 'convert';
    } else {
      return null;
    }
  }

  protected function executeChecks() {
    $imagemagick = PhabricatorEnv::getEnvConfig('files.enable-imagemagick');
    if ($imagemagick && $this->getImageMagickBinaryName() === null) {
      $message = pht(
        "You have enabled Imagemagick in your config, but the '%s' or '%s' ".
        "binary is not in the webserver's %s. Disable imagemagick ".
        "or make it available to the webserver.",
        'magick',
        'convert',
        '$PATH');

      $this->newIssue('files.enable-imagemagick')
      ->setName(pht(
        "'%s' or '%s' binary not found or Imagemagick is not installed.",
        'magick',
        'convert'))
      ->setMessage($message)
      ->addRelatedPhabricatorConfig('files.enable-imagemagick')
      ->addPhabricatorConfig('environment.append-paths');
    }
  }
}
