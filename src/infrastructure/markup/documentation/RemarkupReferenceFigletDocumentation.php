<?php

final class RemarkupReferenceFigletDocumentation
  extends PhorgeRemarkupDocumentation {

  public function getModuleKey() {
    return 'figlet';
  }

  protected function getOrder() {
    return 5300;
  }

  public function getTitle() {
    return 'Figlet';
  }

  private function availableFonts() {
    static $fonts = null;
    if ($fonts === null) {
      $fonts = array();
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
            $fonts[] = $name;
          }
        }
      }
    }
    return $fonts;
  }

  public function getContent() {
    $content = <<<EOTEXT
= Figlet
The `figlet` interpreter allows you to write some large text.
For example, this:

```figlet{{{Some big text!}}}```

...produces this:

figlet{{{Some big text!}}}

EOTEXT;

    return $content;
  }

  public function getExamples() {
    $examples = array();
    foreach ($this->availableFonts() as $font) {
      $examples[] = 'figlet (font='.$font.'){{{Great work!}}}';
    }
    return $examples;
  }

}
