<?php

final class FigletReferenceController extends ReferenceController {
  public function getTitle() {
    return 'Figlet reference';
  }

  public function getContent() {
    $content = <<<EOTEXT
= Figlet reference
== Fonts
EOTEXT;

    $root = dirname(phutil_get_library_root('phorge'));

    $dirs = array(
      $root.'/externals/figlet/fonts/',
      $root.'/externals/pear-figlet/fonts/',
      $root.'/resources/figlet/custom/',
    );

    foreach ($dirs as $dir) {
      foreach (Filesystem::listDirectory($dir, false) as $file) {
        if (preg_match('/\.flf$/', $file)) {
          $name = phutil_utf8_strtolower($file);
          $name = preg_replace('/\.flf$/', '', $name);
          $content .= "\n=== ".$name;
          $content .= "\n```";
          $content .= "\nfiglet (font=".$name."){{{Great work!}}}";
          $content .= "\n```";
          $content .= "\nfiglet (font=".$name."){{{Great work!}}}";
          $content .= "\n";
        }
      }
    }

    return $content;
  }
}
