<?php

final class PhabricatorRemarkupControl
  extends AphrontFormTextAreaControl {

  private $disableFullScreen = false;
  private $canPin;
  private $sendOnEnter = false;
  private $remarkupMetadata = array();
  private $surroundingObject;

  public function setDisableFullScreen($disable) {
    $this->disableFullScreen = $disable;
    return $this;
  }

  /**
   * Set whether the form can be pinned on the screen
   * @param bool $can_pin True if the form can be pinned on the screen by the
   *   user
   * @return $this
   */
  public function setCanPin($can_pin) {
    $this->canPin = $can_pin;
    return $this;
  }

  public function getCanPin() {
    return $this->canPin;
  }

  public function setSendOnEnter($soe) {
    $this->sendOnEnter = $soe;
    return $this;
  }

  public function getSendOnEnter() {
    return $this->sendOnEnter;
  }

  public function setRemarkupMetadata(array $value) {
    $this->remarkupMetadata = $value;
    return $this;
  }

  public function getRemarkupMetadata() {
    return $this->remarkupMetadata;
  }

  /**
   * Set the type of object in which the control is rendered
   * @param $object Object class, e.g. 'ManiphestTask'
   */
  public function setSurroundingObject($object) {
    $this->surroundingObject = $object;
    return $this;
  }

  /**
   * Return the type of object in which this control is rendered
   * @return object Object class, e.g. 'ManiphestTask'
   */
  public function getSurroundingObject() {
    return $this->surroundingObject;
  }

  public function setValue($value) {
    if ($value instanceof RemarkupValue) {
      $this->setRemarkupMetadata($value->getMetadata());
      $value = $value->getCorpus();
    }

    return parent::setValue($value);
  }

  protected function renderInput() {
    $id = $this->getID();
    if (!$id) {
      $id = celerity_generate_unique_node_id();
      $this->setID($id);
    }

    $viewer = $this->getUser();
    if (!$viewer) {
      throw new PhutilInvalidStateException('setUser');
    }

    // NOTE: Metadata is passed to Javascript in a structured way, and also
    // dumped directly into the form as an encoded string. This makes it less
    // likely that we'll lose server-provided metadata (for example, from a
    // saved draft) if there is a client-side error.

    $metadata_name = $this->getName().'_metadata';
    $metadata_value = (object)$this->getRemarkupMetadata();
    $metadata_string = phutil_json_encode($metadata_value);

    $metadata_id = celerity_generate_unique_node_id();
    $metadata_input = phutil_tag(
      'input',
      array(
        'type' => 'hidden',
        'id' => $metadata_id,
        'name' => $metadata_name,
        'value' => $metadata_string,
      ));

    // We need to have this if previews render images, since Ajax can not
    // currently ship JS or CSS.
    require_celerity_resource('phui-lightbox-css');

    if (!$this->getDisabled()) {
      Javelin::initBehavior(
        'aphront-drag-and-drop-textarea',
        array(
          'target' => $id,
          'remarkupMetadataID' => $metadata_id,
          'remarkupMetadataValue' => $metadata_value,
          'activatedClass' => 'aphront-textarea-drag-and-drop',
          'uri' => '/file/dropupload/',
          'chunkThreshold' => PhabricatorFileStorageEngine::getChunkThreshold(),
        ));
    }

    $root_id = celerity_generate_unique_node_id();

    $user_datasource = new PhabricatorPeopleDatasource();
    $emoji_datasource = new PhabricatorEmojiDatasource();
    $proj_datasource = id(new PhabricatorProjectDatasource())
      ->setParameters(
        array(
          'autocomplete' => 1,
        ));

    $phriction_datasource = new PhrictionDocumentDatasource();
    $phurl_datasource = new PhabricatorPhurlURLDatasource();

    // Get users involved in surrounding object (if available)
    $involved_users = null;
    if ($this->getSurroundingObject() instanceof
        PhabricatorInvolveeInterface) {
      $involved_users = $this->getSurroundingObject()->getInvolvedUsers();
    }

    Javelin::initBehavior(
      'phabricator-remarkup-assist',
      array(
        'pht' => array(
          'bold text' => pht('bold text'),
          'italic text' => pht('italic text'),
          'monospaced text' => pht('monospaced text'),
          'List Item' => pht('List Item'),
          'Quoted Text' => pht('Quoted Text'),
          'data' => pht('data'),
          'name' => pht('name'),
          'URL' => pht('URL'),
          'key-help' => pht('Pin or unpin the comment form.'),
        ),
        'canPin' => $this->getCanPin(),
        'disabled' => $this->getDisabled(),
        'sendOnEnter' => $this->getSendOnEnter(),
        'rootID' => $root_id,
        'remarkupMetadataID' => $metadata_id,
        'remarkupMetadataValue' => $metadata_value,
        'autocompleteMap' => (object)array(
          64 => array( // "@"
            'datasourceURI' => $user_datasource->getDatasourceURI(),
            'headerIcon' => 'fa-user',
            'headerText' => pht('Find User:'),
            'hintText' => $user_datasource->getPlaceholderText(),
            'involvedUsers' => $involved_users,
          ),
          35 => array( // "#"
            'datasourceURI' => $proj_datasource->getDatasourceURI(),
            'headerIcon' => 'fa-briefcase',
            'headerText' => pht('Find Project:'),
            'hintText' => $proj_datasource->getPlaceholderText(),
          ),
          58 => array( // ":"
            'datasourceURI' => $emoji_datasource->getDatasourceURI(),
            'headerIcon' => 'fa-smile-o',
            'headerText' => pht('Find Emoji:'),
            'hintText' => $emoji_datasource->getPlaceholderText(),

            // Cancel on emoticons like ":3".
            'ignore' => array(
              '3',
              '\'', // crying :'(
              ')',
              '(',
              '-',
              '/',
              '<',
              '>',
              '[',
              ']',
              '|',
              'D',
//            'p', // Too risky, many emojis starting with lowercase p
              'P',
            ),
          ),
          91 => array( // "["
            'datasourceURI' => $phriction_datasource->getDatasourceURI(),
            'headerIcon' => 'fa-book',
            'headerText' => pht('Find Document:'),
            'hintText' => $phriction_datasource->getPlaceholderText(),
            'cancel' => array(
              ':', // Cancel on "http:" and similar.
              '|',
              ']',
            ),
            'prefix' => '^\\[',
          ),
          40 => array( // "("
            'datasourceURI' => $phurl_datasource->getDatasourceURI(),
            'headerIcon' => 'fa-compress',
            'headerText' => pht('Find Phurl:'),
            'hintText' => $phurl_datasource->getPlaceholderText(),
            'cancel' => array(
              ')',
            ),
            'prefix' => '^\\(',
          ),
        ),
      ));
    Javelin::initBehavior('phabricator-tooltips', array());

    $actions = array(
      id(new PhorgeRemarkupControlAction())
        ->setTooltip(pht('Bold'))
        ->setNodevice(true)
        ->setActionCode('fa-bold')
        ->setIcon('fa-bold'),
      id(new PhorgeRemarkupControlAction())
        ->setTooltip(pht('Italics'))
        ->setNodevice(true)
        ->setActionCode('fa-italic')
        ->setIcon('fa-italic'),
      id(new PhorgeRemarkupControlAction())
        ->setTooltip(pht('Monospaced'))
        ->setNodevice(true)
        ->setActionCode('fa-text-width')
        ->setIcon('fa-text-width'),
      id(new PhorgeRemarkupControlAction())
        ->setTooltip(pht('Link'))
        ->setNodevice(true)
        ->setActionCode('fa-link')
        ->setIcon('fa-link'),
      PhorgeRemarkupControlAction::newSpacer()
        ->setNodevice(true)
        ->setActionCode('0')
        ->setIcon('0'),
      id(new PhorgeRemarkupControlAction())
        ->setTooltip(pht('Bulleted List'))
        ->setNodevice(true)
        ->setActionCode('fa-list-ul')
        ->setIcon('fa-list-ul'),
      id(new PhorgeRemarkupControlAction())
        ->setTooltip(pht('Numbered List'))
        ->setNodevice(true)
        ->setActionCode('fa-list-ol')
        ->setIcon('fa-list-ol'),
      id(new PhorgeRemarkupControlAction())
        ->setTooltip(pht('Code Block'))
        ->setNodevice(true)
        ->setActionCode('fa-code')
        ->setIcon('fa-code'),
      id(new PhorgeRemarkupControlAction())
        ->setTooltip(pht('Quote'))
        ->setNodevice(true)
        ->setActionCode('fa-quote-right')
        ->setIcon('fa-quote-right'),
      id(new PhorgeRemarkupControlAction())
        ->setTooltip(pht('Table'))
        ->setNodevice(true)
        ->setActionCode('fa-table')
        ->setIcon('fa-table'),
      id(new PhorgeRemarkupControlAction())
        ->setTooltip(pht('Upload File'))
        ->setActionCode('fa-cloud-upload')
        ->setIcon('fa-cloud-upload'),
      id(new PhorgeRemarkupControlAction())
        ->setTooltip(pht('Preview'))
        ->setAlign('right')
        ->setActionCode('fa-eye')
        ->setIcon('fa-eye'),
      id(new PhorgeRemarkupControlAction())
        ->setTooltip(pht('Help'))
        ->setAlign('right')
        ->setHref('/remarkup/')
        ->setIcon('fa-book'),
    );

    if (!$this->disableFullScreen) {
      $actions[] = id(new PhorgeRemarkupControlAction())
        ->setTooltip(pht('Fullscreen Mode'))
        ->setAlign('right')
        ->setActionCode('fa-arrows-alt')
        ->setIcon('fa-arrows-alt');
      }

    if ($this->getCanPin()) {
      $actions[] = id(new PhorgeRemarkupControlAction())
        ->setTooltip(pht('Pin Form On Screen'))
        ->setAlign('right')
        ->setActionCode('fa-thumb-tack')
        ->setIcon('fa-thumb-tack');
    }

    $extension_actions =
      PhorgeRemarkupControlExtension::buildExtensionActions(
        $viewer,
        $this->getSurroundingObject());
    /** @var PhorgeRemarkupControlAction[] */
    $actions = array_merge($actions, $extension_actions);

    $buttons = array();
    foreach ($actions as $spec) {

      $classes = array();

      if ($spec->getAlign() == 'right') {
        $classes[] = 'remarkup-assist-right';
      }

      if ($spec->getNodevice()) {
        $classes[] = 'remarkup-assist-nodevice';
      }

      if ($spec->getIsSpacer()) {
        $classes[] = 'remarkup-assist-separator';
        $buttons[] = phutil_tag(
          'span',
          array(
            'class' => implode(' ', $classes),
          ),
          '');
        continue;
      }

      $classes[] = 'remarkup-assist-button';

      if ($spec->getActionCode() == 'fa-cloud-upload') {
        $classes[] = 'remarkup-assist-upload';
      }

      $href = $spec->getHref();
      if ($href == '#') {
        $meta = array('action' => $spec->getActionCode());
        $mustcapture = true;
        $target = null;
      } else {
        $meta = array();
        $mustcapture = null;
        $target = '_blank';
      }

      $content = null;

      $tip = $spec->getTooltip();
      if ($tip) {
        $meta['tip'] = $tip;
        $content = javelin_tag(
          'span',
          array(
            'aural' => true,
          ),
          $tip);
      }

      $sigils = array();
      $sigils[] = 'remarkup-assist';
      if (!$this->getDisabled()) {
        $sigils[] = 'has-tooltip';
      }

      $icon = $spec->getIcon();

      $buttons[] = javelin_tag(
        'a',
        array(
          'class'       => implode(' ', $classes),
          'href'        => $href,
          'sigil'       => implode(' ', $sigils),
          'meta'        => $meta,
          'mustcapture' => $mustcapture,
          'target'      => $target,
          'tabindex'    => -1,
        ),
        phutil_tag(
          'div',
          array(
            'class' =>
              'remarkup-assist phui-icon-view phui-font-fa bluegrey '.$icon,
          ),
          $content));
    }

    $buttons = phutil_tag(
      'div',
      array(
        'class' => 'remarkup-assist-bar',
      ),
      $buttons);

    $use_monospaced = $viewer->compareUserSetting(
      PhabricatorMonospacedTextareasSetting::SETTINGKEY,
      PhabricatorMonospacedTextareasSetting::VALUE_TEXT_MONOSPACED);

    if ($use_monospaced) {
      $monospaced_textareas_class = 'PhabricatorMonospaced';
    } else {
      $monospaced_textareas_class = null;
    }

    $this->setCustomClass(
      'remarkup-assist-textarea '.$monospaced_textareas_class);

    return javelin_tag(
      'div',
      array(
        'sigil' => 'remarkup-assist-control',
        'class' => $this->getDisabled() ? 'disabled-control' : null,
        'id' => $root_id,
      ),
      array(
        $buttons,
        parent::renderInput(),
        $metadata_input,
      ));
  }

}
