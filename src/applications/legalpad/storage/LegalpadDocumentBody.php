<?php

final class LegalpadDocumentBody extends LegalpadDAO
  implements
    PhorgeMarkupInterface {

  const MARKUP_FIELD_TEXT = 'markup:text ';

  protected $phid;
  protected $creatorPHID;
  protected $documentPHID;
  protected $version;
  protected $title;
  protected $text;

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'version' => 'uint32',
        'title' => 'text255',
        'text' => 'text?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_document' => array(
          'columns' => array('documentPHID', 'version'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgePHIDConstants::PHID_TYPE_LEGB);
  }

/* -(  PhorgeMarkupInterface  )----------------------------------------- */


  public function getMarkupFieldKey($field) {
    $content = $this->getMarkupText($field);
    return PhorgeMarkupEngine::digestRemarkupContent($this, $content);
  }

  public function newMarkupEngine($field) {
    return PhorgeMarkupEngine::newMarkupEngine(array());
  }

  public function getMarkupText($field) {
    switch ($field) {
      case self::MARKUP_FIELD_TEXT:
        $text = $this->getText();
        break;
      default:
        throw new Exception(pht('Unknown field: %s', $field));
        break;
    }

    return $text;
  }

  public function didMarkupText($field, $output, PhutilMarkupEngine $engine) {
    require_celerity_resource('phorge-remarkup-css');
    return phutil_tag(
      'div',
      array(
        'class' => 'phorge-remarkup',
      ),
      $output);
  }

  public function shouldUseMarkupCache($field) {
    return (bool)$this->getID();
  }

}
