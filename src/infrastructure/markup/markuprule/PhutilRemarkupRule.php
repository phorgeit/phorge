<?php

abstract class PhutilRemarkupRule extends Phobject {

  private $engine;
  private $replaceCallback;

  public function setEngine(PhutilRemarkupEngine $engine) {
    $this->engine = $engine;
    return $this;
  }

  public function getEngine() {
    return $this->engine;
  }

  public function getPriority() {
    return 500.0;
  }

  /**
   * Check input whether to apply RemarkupRule. If true, apply formatting.
   * @param  string|PhutilSafeHTML $text String to check and potentially
   *   format.
   * @return string|PhutilSafeHTML Unchanged input if no match, or input after
   *   matching the formatting rule and applying the formatting.
   */
  abstract public function apply($text);

  public function getPostprocessKey() {
    return spl_object_hash($this);
  }

  public function didMarkupText() {
    return;
  }

  protected function replaceHTML($pattern, $callback, $text) {
    $this->replaceCallback = $callback;
    return phutil_safe_html(preg_replace_callback(
      $pattern,
      array($this, 'replaceHTMLCallback'),
      phutil_escape_html($text)));
  }

  private function replaceHTMLCallback(array $match) {
    return phutil_escape_html(call_user_func(
      $this->replaceCallback,
      array_map('phutil_safe_html', $match)));
  }


  /**
   * Safely generate a tag.
   *
   * In Remarkup contexts, it's not safe to use arbitrary text in tag
   * attributes: even though it will be escaped, it may contain replacement
   * tokens which are then replaced with markup.
   *
   * This method acts as @{function:phutil_tag}, but checks attributes before
   * using them.
   *
   * @param   string               $name Tag name.
   * @param   array<string, wild>  $attrs Dictionary of tag attributes.
   * @param   wild                 $content (optional) Tag content.
   * @return  PhutilSafeHTML       Tag object.
   */
  protected function newTag($name, array $attrs, $content = null) {
    foreach ($attrs as $key => $attr) {
      if ($attr !== null) {
        $attrs[$key] = $this->assertFlatText($attr);
      }
    }

    return phutil_tag($name, $attrs, $content);
  }

  /**
   * Assert that a text token is flat (it contains no replacement tokens).
   *
   * Because tokens can be replaced with markup, it is dangerous to use
   * arbitrary input text in tag attributes. Normally, rule precedence should
   * prevent this. Asserting that text is flat before using it as an attribute
   * provides an extra layer of security.
   *
   * Normally, you can call @{method:newTag} rather than calling this method
   * directly. @{method:newTag} will check attributes for you.
   *
   * @param   wild    $text Ostensibly flat text.
   * @return  string  Flat text.
   */
  protected function assertFlatText($text) {
    $text = (string)hsprintf('%s', phutil_safe_html($text));
    $rich = (strpos($text, PhutilRemarkupBlockStorage::MAGIC_BYTE) !== false);
    if ($rich) {
      throw new Exception(
        pht(
          'Remarkup rule precedence is dangerous: rendering text with tokens '.
          'as flat text!'));
    }

    return $text;
  }

  /**
   * Check whether text is flat (contains no replacement tokens) or not.
   *
   * @param   wild  $text Ostensibly flat text.
   * @return  bool  True if the text is flat.
   */
  protected function isFlatText($text) {
    $text = (string)hsprintf('%s', phutil_safe_html($text));
    return (strpos($text, PhutilRemarkupBlockStorage::MAGIC_BYTE) === false);
  }

  /**
   * Get the CSS class="" attribute for a Remarkup link.
   * It's just "remarkup-link" for all cases, plus the possibility for
   * designers to style external links differently.
   * @param  boolean $is_internal Whenever the link was internal or not.
   * @return string
   */
  protected function getRemarkupLinkClass($is_internal) {
    // Allow developers to style external links differently
    $classes = array('remarkup-link');
    if (!$is_internal) {
      $classes[] = 'remarkup-link-ext';
    }
    return implode(' ', $classes);
  }

}
