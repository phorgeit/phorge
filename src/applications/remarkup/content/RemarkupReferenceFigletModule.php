<?php

final class RemarkupReferenceFigletModule
  extends PhorgeRemarkupReferenceModule {

  // TODO when PhutilRemarkupBlockInterpreter are supported by the reference
  // app, this class should be replaced by that.

  public function getModuleKey() {
    return 'figlet';
  }

  public function getModuleOrder() {
    return 5300;
  }

  public function getTitle() {
    return 'Figlet';
  }

  public function getContent() {
    $content = <<<EOTEXT
= Figlet
The `figlet` interpreter allows you to write some large text.
For example, this:

```figlet{{{Some big text!}}}```

...produces this:

figlet{{{Some big text!}}}

== Available Fonts
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
