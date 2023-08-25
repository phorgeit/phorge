<?php

final class PhabricatorNotificationStatusView extends AphrontTagView {

  protected function getTagAttributes() {
    if (!$this->getID()) {
      $this->setID(celerity_generate_unique_node_id());
    }

    Javelin::initBehavior(
      'aphlict-status',
      array(
        'nodeID' => $this->getID(),
        'pht' => array(
          'setup' => pht('Setting Up Client'),
          'open' => pht('Connected'),
          'closed' => pht('Disconnected'),
        ),
        'icon' => array(
          'open' => array(
            'icon' => 'fa-circle',
            'color' => 'green',
          ),
          'setup' => array(
            'icon' => 'fa-circle',
            'color' => 'yellow',
          ),
          'closed' => array(
            'icon' => 'fa-circle',
            'color' => 'red',
          ),
        ),
      ));

    return array(
      'class' => 'aphlict-connection-status',
    );
  }

  protected function getTagContent() {
    $have = PhabricatorEnv::getEnvConfig('notification.servers');
    if ($have) {
      return $this->buildMessageView(
        'aphlict-connection-status-connecting',
        'fa-circle-o yellow',
        pht('Connecting...'));
    } else {
      return $this->buildMessageView(
        'aphlict-connection-status-notenabled',
        'fa-circle-o grey',
        pht('Notification server not enabled'));
    }
  }

  /**
   * Create an icon and a message.
   *
   * @param  string $class_name Raw CSS class name(s) space separated
   * @param  string $icon_name  Icon name
   * @param  string $text       Text to be shown
   * @return array
   */
  private function buildMessageView($class_name, $icon_name, $text) {
    $icon = id(new PHUIIconView())
      ->setIcon($icon_name);

    $message = phutil_tag(
      'span',
      array(
        'class' => 'connection-status-text '.$class_name,
      ),
      $text);

    return array(
      $icon,
      $message,
    );
  }

}
