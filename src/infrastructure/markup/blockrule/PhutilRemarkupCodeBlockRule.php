<?php

final class PhutilRemarkupCodeBlockRule extends PhutilRemarkupBlockRule {

  public function getMatchingLineCount(array $lines, $cursor) {
    $num_lines = 0;
    $match_ticks = null;
    if (preg_match('/^(\s{2,}).+/', $lines[$cursor])) {
      $match_ticks = false;
    } else if (preg_match('/^\s*(```)/', $lines[$cursor])) {
      $match_ticks = true;
    } else {
      return $num_lines;
    }

    $num_lines++;

    if ($match_ticks &&
        preg_match('/^\s*(```)(.*)(```)\s*$/', $lines[$cursor])) {
      return $num_lines;
    }

    $cursor++;

    while (isset($lines[$cursor])) {
      if ($match_ticks) {
        if (preg_match('/```\s*$/', $lines[$cursor])) {
          $num_lines++;
          break;
        }
        $num_lines++;
      } else {
        if (strlen(trim($lines[$cursor]))) {
          if (!preg_match('/^\s{2,}/', $lines[$cursor])) {
            break;
          }
        }
        $num_lines++;
      }
      $cursor++;
    }

    return $num_lines;
  }

  public function markupText($text, $children) {
    // Header/footer eventually useful to be nice with "flavored markdown".
    // When it starts with ```stuff    the header is 'stuff' (->language)
    // When it ends with      stuff``` the footer is 'stuff' (->garbage)
    $header_line = null;
    $footer_line = null;

    $matches = null;
    if (preg_match('/^\s*```(.*)/', $text, $matches)) {
      if (isset($matches[1])) {
        $header_line = $matches[1];
      }

      // If this is a ```-style block, trim off the backticks and any leading
      // blank line.
      $text = preg_replace('/^\s*```(\s*\n)?/', '', $text);
      $text = preg_replace('/```\s*$/', '', $text);
    }

    $lines = explode("\n", $text);

    // If we have a flavored header, it has sense to look for the footer.
    if ($header_line !== null && $lines) {
      $footer_line = $lines[last_key($lines)];
    }

    // Strip final empty lines
    while ($lines && !strlen(last($lines))) {
      unset($lines[last_key($lines)]);
    }

    $options = array(
      'counterexample'  => false,
      'lang'            => null,
      'name'            => null,
      'lines'           => null,
    );

    $parser = new PhutilSimpleOptions();
    $custom = $parser->parse(head($lines));
    $valid_options = null;
    if ($custom) {
      $valid_options = true;
      foreach ($custom as $key => $value) {
        if (!array_key_exists($key, $options)) {
          $valid_options = false;
          break;
        }
      }
      if ($valid_options) {
        array_shift($lines);
        $options = $custom + $options;
      }
    }

    // Parse flavored markdown strictly to don't eat legitimate Remarkup.
    // Proceed only if we tried to parse options and we failed
    // (no options also mean no language).
    // For example this is not a valid option: ```php
    // Proceed only if the footer exists and it is not: blabla```
    // Accept only 2 lines or more. First line: header; then content.
    if (
      $valid_options === false &&
      $header_line !== null &&
      $footer_line === '' &&
      count($lines) > 1
    ) {
      if (self::isKnownLanguageCode($header_line)) {
        array_shift($lines);
        $options['lang'] = $header_line;
      }
    }

    // Normalize the text back to a 0-level indent.
    $min_indent = 80;
    foreach ($lines as $line) {
      for ($ii = 0; $ii < strlen($line); $ii++) {
        if ($line[$ii] != ' ') {
          $min_indent = min($ii, $min_indent);
          break;
        }
      }
    }

    $text = implode("\n", $lines);
    if ($min_indent) {
      $indent_string = str_repeat(' ', $min_indent);
      $text = preg_replace('/^'.$indent_string.'/m', '', $text);
    }

    if ($this->getEngine()->isTextMode()) {
      $out = array();

      $header = array();
      if ($options['counterexample']) {
        $header[] = 'counterexample';
      }
      if ($options['name'] != '') {
        $header[] = 'name='.$options['name'];
      }
      if ($header) {
        $out[] = implode(', ', $header);
      }

      $text = preg_replace('/^/m', '  ', $text);
      $out[] = $text;

      return implode("\n", $out);
    }

    // The name is usually a sufficient source of information for file ext.
    if (empty($options['lang']) && isset($options['name'])) {
      $options['lang'] = $this->guessFilenameExtension($options['name']);
    }

    if (empty($options['lang'])) {
      // If the user hasn't specified "lang=..." explicitly, try to guess the
      // language. If we fail, fall back to configured defaults.
      $lang = PhutilLanguageGuesser::guessLanguage($text);
      if (!$lang) {
        $lang = nonempty(
          $this->getEngine()->getConfig('phutil.codeblock.language-default'),
          'text');
      }
      $options['lang'] = $lang;
    }

    $code_body = $this->highlightSource($text, $options);

    $name_header = null;
    $block_style = null;
    if ($this->getEngine()->isHTMLMailMode()) {
      $map = $this->getEngine()->getConfig('phutil.codeblock.style-map');

      if ($map) {
        $raw_body = id(new PhutilPygmentizeParser())
          ->setMap($map)
          ->parse((string)$code_body);
        $code_body = phutil_safe_html($raw_body);
      }

      $style_rules = array(
        'padding: 6px 12px;',
        'font-size: 13px;',
        'font-weight: bold;',
        'display: inline-block;',
        'border-top-left-radius: 3px;',
        'border-top-right-radius: 3px;',
        'color: rgba(0,0,0,.75);',
      );

      if ($options['counterexample']) {
        $style_rules[] = 'background: #f7e6e6';
      } else {
        $style_rules[] = 'background: rgba(71, 87, 120, 0.08);';
      }

      $header_attributes = array(
        'style' => implode(' ', $style_rules),
      );

      $block_style = 'margin: 12px 0;';
    } else {
      $header_attributes = array(
        'class' => 'remarkup-code-header',
      );
    }

    if ($options['name']) {
      $name_header = phutil_tag(
        'div',
        $header_attributes,
        $options['name']);
    }

    $class = 'remarkup-code-block';
    if ($options['counterexample']) {
      $class = 'remarkup-code-block code-block-counterexample';
    }

    $attributes = array(
      'class' => $class,
      'style' => $block_style,
      'data-code-lang' => $options['lang'],
      'data-sigil' => 'remarkup-code-block',
    );

    return phutil_tag(
      'div',
      $attributes,
      array($name_header, $code_body));
  }

  private function highlightSource($text, array $options) {
    if ($options['counterexample']) {
      $aux_class = ' remarkup-counterexample';
    } else {
      $aux_class = null;
    }

    $aux_style = null;

    if ($this->getEngine()->isHTMLMailMode()) {
      $aux_style = array(
        'font: 11px/15px "Menlo", "Consolas", "Monaco", monospace;',
        'padding: 12px;',
        'margin: 0;',
      );

      if ($options['counterexample']) {
        $aux_style[] = 'background: #f7e6e6;';
      } else {
        $aux_style[] = 'background: rgba(71, 87, 120, 0.08);';
      }

      $aux_style = implode(' ', $aux_style);
    }

    if ($options['lines']) {
      // Put a minimum size on this because the scrollbar is otherwise
      // unusable.
      $height = max(6, (int)$options['lines']);
      $aux_style = $aux_style
        .' '
        .'max-height: '
        .(2 * $height)
        .'em; overflow: auto;';
    }

    $engine = $this->getEngine()->getConfig('syntax-highlighter.engine');
    if (!$engine) {
      $engine = 'PhutilDefaultSyntaxHighlighterEngine';
    }
    $engine = newv($engine, array());
    $engine->setConfig(
      'pygments.enabled',
      $this->getEngine()->getConfig('pygments.enabled'));
    return phutil_tag(
      'pre',
      array(
        'class' => 'remarkup-code'.$aux_class,
        'style' => $aux_style,
      ),
      PhutilSafeHTML::applyFunction(
        'rtrim',
        $engine->highlightSource($options['lang'], $text)));
  }

  /**
   * Check if a language code can be used in a generic flavored markdown.
   * @param  string $lang Language code
   * @return bool
   */
  private static function isKnownLanguageCode($lang) {
    $languages = self::knownLanguageCodes();
    return isset($languages[$lang]);
  }

  /**
   * Get the available languages for a generic flavored markdown.
   * @return array Languages as array keys. Ignore the value.
   */
  private static function knownLanguageCodes() {
    // This is a friendly subset from https://pygments.org/languages/
    static $map = array(
      'arduino' => 1,
      'assembly' => 1,
      'awk' => 1,
      'bash' => 1,
      'bat' => 1,
      'c' => 1,
      'cmake' => 1,
      'cobol' => 1,
      'cpp' => 1,
      'css' => 1,
      'csharp' => 1,
      'dart' => 1,
      'delphi' => 1,
      'fortran' => 1,
      'go' => 1,
      'groovy' => 1,
      'haskell' => 1,
      'java' => 1,
      'javascript' => 1,
      'kotlin' => 1,
      'lisp' => 1,
      'lua' => 1,
      'matlab' => 1,
      'make' => 1,
      'perl' => 1,
      'php' => 1,
      'powershell' => 1,
      'python' => 1,
      'r' => 1,
      'ruby' => 1,
      'rust' => 1,
      'scala' => 1,
      'sh' => 1,
      'sql' => 1,
      'typescript' => 1,
      'vba' => 1,
    );
    return $map;
  }

  /**
   * Get the extension from a filename.
   * @param  string "/path/to/something.name"
   * @return null|string ".name"
   */
  private function guessFilenameExtension($name) {
    $name = basename($name);
    $pos = strrpos($name, '.');
    if ($pos !== false) {
      return substr($name, $pos + 1);
    }
    return null;
  }

}
