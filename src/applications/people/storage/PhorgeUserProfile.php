<?php

final class PhorgeUserProfile extends PhorgeUserDAO {

  protected $userPHID;
  protected $title;
  protected $blurb;
  protected $profileImagePHID;
  protected $icon;

  public static function initializeNewProfile(PhorgeUser $user) {
    $default_icon = PhorgePeopleIconSet::getDefaultIconKey();

    return id(new self())
      ->setUserPHID($user->getPHID())
      ->setIcon($default_icon)
      ->setTitle('')
      ->setBlurb('');
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_COLUMN_SCHEMA => array(
        'title' => 'text255',
        'blurb' => 'text',
        'profileImagePHID' => 'phid?',
        'icon' => 'text32',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'userPHID' => array(
          'columns' => array('userPHID'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function getDisplayTitle() {
    $title = $this->getTitle();
    if (strlen($title)) {
      return $title;
    }

    $icon_key = $this->getIcon();
    return PhorgePeopleIconSet::getIconName($icon_key);
  }

}
