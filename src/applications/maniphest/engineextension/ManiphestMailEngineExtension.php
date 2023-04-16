<?php

final class ManiphestMailEngineExtension
  extends PhorgeMailEngineExtension {

  const EXTENSIONKEY = 'maniphest';

  public function supportsObject($object) {
    return ($object instanceof ManiphestTask);
  }

  public function newMailStampTemplates($object) {
    return array(
      id(new PhorgePHIDMailStamp())
        ->setKey('author')
        ->setLabel(pht('Author')),
      id(new PhorgePHIDMailStamp())
        ->setKey('task-owner')
        ->setLabel(pht('Task Owner')),
      id(new PhorgeBoolMailStamp())
        ->setKey('task-unassigned')
        ->setLabel(pht('Task Unassigned')),
      id(new PhorgeStringMailStamp())
        ->setKey('task-priority')
        ->setLabel(pht('Task Priority')),
      id(new PhorgeStringMailStamp())
        ->setKey('task-status')
        ->setLabel(pht('Task Status')),
      id(new PhorgeStringMailStamp())
        ->setKey('subtype')
        ->setLabel(pht('Subtype')),
    );
  }

  public function newMailStamps($object, array $xactions) {
    $editor = $this->getEditor();
    $viewer = $this->getViewer();

    $this->getMailStamp('author')
      ->setValue($object->getAuthorPHID());

    $this->getMailStamp('task-owner')
      ->setValue($object->getOwnerPHID());

    $this->getMailStamp('task-unassigned')
      ->setValue(!$object->getOwnerPHID());

    $this->getMailStamp('task-priority')
      ->setValue($object->getPriority());

    $this->getMailStamp('task-status')
      ->setValue($object->getStatus());

    $this->getMailStamp('subtype')
      ->setValue($object->getSubtype());
  }

}
