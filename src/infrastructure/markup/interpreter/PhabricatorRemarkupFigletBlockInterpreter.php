<?php

final class PhabricatorRemarkupFigletBlockInterpreter
  extends PhutilRemarkupBlockInterpreter
  implements RemarkupSyntaxDocumentationProvider {

  public function getInterpreterName() {
    return 'figlet';
  }

  /**
   * @phutil-external-symbol class Text_Figlet
   */
  public function markupContent($content, array $argv) {
    $map = self::getFigletMap();

    $font = idx($argv, 'font');
    $font = phutil_utf8_strtolower($font);
    if (empty($map[$font])) {
      $font = 'standard';
    }

    $root = dirname(phutil_get_library_root('phabricator'));
    require_once $root.'/externals/pear-figlet/Text/Figlet.php';

    $figlet = new Text_Figlet();
    $figlet->loadFont($map[$font]);

    $result = $figlet->lineEcho($content);

    $engine = $this->getEngine();

    if ($engine->isTextMode()) {
      return $result;
    }

    if ($engine->isHTMLMailMode()) {
      return phutil_tag('pre', array(), $result);
    }

    return phutil_tag(
      'div',
      array(
        'class' => 'PhabricatorMonospaced remarkup-figlet',
      ),
      $result);
  }

  private static function getFigletMap() {
    $root = dirname(phutil_get_library_root('phabricator'));

    $dirs = array(
      $root.'/externals/figlet/fonts/',
      $root.'/externals/pear-figlet/fonts/',
      $root.'/resources/figlet/custom/',
    );

    $map = array();
    foreach ($dirs as $dir) {
      foreach (Filesystem::listDirectory($dir, false) as $file) {
        if (preg_match('/\.flf\z/', $file)) {
          $name = phutil_utf8_strtolower($file);
          $name = preg_replace('/\.flf\z/', '', $name);
          $map[$name] = $dir.$file;
        }
      }
    }

    return $map;
  }

  public function getDocumentation() {
    return <<<EOT
= Figlet
The `figlet` interpreter allows you to write some large text.
For example, this:

```figlet{{{Some big text!}}}```

...produces this:

figlet{{{Some big text!}}}

More information about Figlet can be found [[/reference/figlet/ | here]]
EOT;
  }
}
