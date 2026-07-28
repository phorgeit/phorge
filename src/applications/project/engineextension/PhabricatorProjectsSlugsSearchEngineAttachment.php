<?php

final class PhabricatorProjectsSlugsSearchEngineAttachment
  extends PhabricatorSearchEngineAttachment {

  public function getAttachmentName() {
    return pht('Hashtags');
  }

  public function getAttachmentDescription() {
    return pht('Get all hashtags for the project.');
  }

  public function willLoadAttachmentData($query, $spec) {
    $query->needSlugs(true);
  }

  public function getAttachmentForObject($object, $data, $spec) {
    $slugs = array();
    foreach ($object->getSlugs() as $slug) {
      $slugs[] = array(
        'slug' => $slug->getSlug(),
      );
    }

    return array(
      'slugs' => $slugs,
    );
  }

}
