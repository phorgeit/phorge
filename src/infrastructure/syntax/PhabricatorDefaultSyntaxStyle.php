<?php

final class PhabricatorDefaultSyntaxStyle
  extends PhabricatorSyntaxStyle {

  const STYLEKEY = 'default';

  public function getStyleName() {
    return pht('Default');
  }

  public function getStyleMap() {
    return array(
      'hll' => 'color: {$syntax.highlighted-line}',
      'c' => 'color: {$syntax.comment}',
      'cm' => 'color: {$syntax.comment-multiline}',
      'dc' => 'color: {$syntax.comment-multiline}',
      'c1' => 'color: {$syntax.comment-single}',
      'cs' => 'color: {$syntax.comment-special}',
      'sd' => 'color: {$syntax.string-doc}',
      'sh' => 'color: {$syntax.string-heredoc}',
      's' => 'color: {$syntax.string}',
      'sb' => 'color: {$syntax.string-backtick}',
      'sc' => 'color: {$syntax.literal-string-char}',
      's2' => 'color: {$syntax.string-double}',
      's1' => 'color: {$syntax.string-single}',
      'sx' => 'color: {$syntax.string-other}',
      'sr' => 'color: {$syntax.string-regex}',
      'nv' => 'color: {$syntax.name-variable}',
      'vi' => 'color: {$syntax.variable-instance}',
      'vg' => 'color: {$syntax.variable-global}',
      'na' => 'color: {$syntax.name-attribute}',
      'kc' => 'color: {$syntax.keyword-constant}',
      'no' => 'color: {$syntax.name-operator}',
      'k' => 'color: {$syntax.keyword}',
      'kd' => 'color: {$syntax.keyword-declaration}',
      'kn' => 'color: {$syntax.keyword-namespace}',
      'kt' => 'color: {$syntax.keyword-type}',
      'cp' => 'color: {$syntax.comment-preproc}',
      'kp' => 'color: {$syntax.keyword-preproc}',
      'kr' => 'color: {$syntax.keyword-reserved}',
      'nb' => 'color: {$syntax.name-builtin}',
      'bp' => 'color: {$syntax.builtin-pseudo}',
      'nc' => 'color: {$syntax.name-class}',
      'nt' => 'color: {$syntax.name-tag}',
      'vc' => 'color: {$syntax.name-variable-class}',
      'nf' => 'color: {$syntax.name-function}',
      'nx' => 'color: {$syntax.name-exception}',
      'o' => 'color: {$syntax.operator}',
      'p' => 'color: {$syntax.punctuation}',
      'ss' => 'color: {$syntax.literal-string-symbol}',
      'm' => 'color: {$syntax.literal-number}',
      'mf' => 'color: {$syntax.literal-number-float}',
      'mh' => 'color: {$syntax.literal-number-hex}',
      'mi' => 'color: {$syntax.literal-number-integer}',
      'mo' => 'color: {$syntax.literal-number-octal}',
      'il' => 'color: {$syntax.literal-number-integer-long}',
      'gd' => 'color: {$syntax.generic-deleted}',
      'gr' => 'color: {$syntax.generic-red}',
      'gh' => 'color: {$syntax.generic-heading}',
      'gi' => 'color: {$syntax.generic-inserted}',
      'go' => 'color: {$syntax.generic-output}',
      'gp' => 'color: {$syntax.generic-prompt}',
      'gu' => 'color: {$syntax.generic-underline}',
      'gt' => 'color: {$syntax.generic-traceback}',
      'nd' => 'color: {$syntax.name-decorator}',
      'ni' => 'color: {$syntax.name-identifier}',
      'ne' => 'color: {$syntax.name-entity}',
      'nl' => 'color: {$syntax.name-label}',
      'nn' => 'color: {$syntax.name-namespace}',
      'ow' => 'color: {$syntax.operator-word}',
      'w' => 'color: {$syntax.text-whitespace}',
      'se' => 'color: {$syntax.literal-string-escape}',
      'si' => 'color: {$syntax.literal-string-interpol}',
    );
  }

}
