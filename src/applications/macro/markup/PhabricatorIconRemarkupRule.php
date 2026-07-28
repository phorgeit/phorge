<?php

final class PhabricatorIconRemarkupRule extends PhutilRemarkupRule {

  public function getPriority() {
    return 200.0;
  }

  public function apply($text) {
    return preg_replace_callback(
      '@{icon\b((?:[^}\\\\]+|\\\\.)*)}@m',
      array($this, 'markupIcon'),
      $text);
  }

  public function markupIcon(array $matches) {
    $engine = $this->getEngine();
    $text_mode = $engine->isTextMode();
    $mail_mode = $engine->isHTMLMailMode();

    if (!$this->isFlatText($matches[0]) || $text_mode || $mail_mode) {
      return $matches[0];
    }

    $extra = idx($matches, 1);

    // We allow various forms, like these:
    //
    //   {icon}
    //   {icon camera}
    //   {icon,camera}
    //   {icon camera color=red}
    //   {icon, camera, color=red}

    $extra = ltrim($extra, ", \n");
    $extra = preg_split('/[\s,]+/', $extra, 2);

    // Choose some arbitrary default icon so that previews render in a mostly
    // reasonable way as you're typing the syntax.
    $icon = idx($extra, 0, 'paw');

    $defaults = array(
      'color' => null,
      'spin' => false,
    );

    $options = idx($extra, 1, '');
    $parser = new PhutilSimpleOptions();
    $options = $parser->parse($options) + $defaults;

    // NOTE: We're validating icon and color names to prevent users from
    // adding arbitrary CSS classes to the document. Although this probably
    // isn't dangerous, it's safer to validate.

    static $icon_names;
    if (!$icon_names) {
      $icon_names = array_fuse(PHUIIconView::getIcons());
    }

    static $color_names;
    if (!$color_names) {
      $color_names = array_fuse(PHUIIconView::getIconColors());
    }

    if (empty($icon_names['fa-'.$icon])) {
      $icon = 'paw';
    }

    $color = $options['color'];
    if ($color !== null && empty($color_names[$color])) {
      $color = null;
    }

    $classes = array();
    $classes[] = $color;

    $spin = $options['spin'];
    if ($spin) {
      $classes[] = 'ph-spin';
    }

    $icon_view = id(new PHUIIconView())
      ->setIcon('fa-'.$icon, implode(' ', $classes));

    return $this->getEngine()->storeText($icon_view);
  }

  public function getRemarkupDocumentationObject() {
    $content = <<<EOTEXT
You can add icons to comments using the `{icon ...}` syntax. For example:

  {icon camera}

This renders: {icon camera}

You can select a color for icons:

  {icon camera color=blue}

This renders: {icon camera color=blue}

For a list of available icons and colors, check the
[[ /uiexample/view/PHUIIconExample/ | UIExamples application]]. (The icons are
sourced from [[ https://fontawesome.com/v4.7.0/icons/ | FontAwesome ]], so you
can also browse the collection there.)

You can add `spin` to make the icon spin{icon asterisk spin}!
EOTEXT;

    return id(new PhorgeRemarkupSimpleDocumentation())
      ->setRuleInstance($this)
      ->setTitle(pht('Icons'))
      ->setContent($content)
      ->addExample('{icon cog spin}')
      ->addExample('{icon battery-3 color=green}')
      ->addExample('{icon camera color=red, spin}')
      // Counter-example: doesn't spin.
      ->addExample('{icon camera color=red spin}');
  }

}
