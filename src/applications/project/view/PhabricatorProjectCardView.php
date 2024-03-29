<?php

final class PhabricatorProjectCardView extends AphrontTagView {

  private $project;
  private $viewer;
  private $tag;

  public function setProject(PhabricatorProject $project) {
    $this->project = $project;
    return $this;
  }

  public function setViewer(PhabricatorUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  public function setTag($tag) {
    $this->tag = $tag;
    return $this;
  }

  protected function getTagName() {
    if ($this->tag) {
      return $this->tag;
    }
    return 'div';
  }

  protected function getTagAttributes() {
    $classes = array();
    $classes[] = 'project-card-view';

    $color = $this->project->getColor();
    $classes[] = 'project-card-'.$color;

    return array(
      'class' => implode(' ', $classes),
    );
  }

  protected function getTagContent() {

    $project = $this->project;
    $viewer = $this->viewer;
    require_celerity_resource('project-card-view-css');

    $icon = $project->getDisplayIconIcon();
    $icon_name = $project->getDisplayIconName();
    $tag = id(new PHUITagView())
      ->setIcon($icon)
      ->setName($icon_name)
      ->addClass('project-view-header-tag')
      ->setType(PHUITagView::TYPE_SHADE);

    $header = id(new PHUIHeaderView())
      ->setHeader(array($project->getDisplayName(), $tag))
      ->setUser($viewer)
      ->setPolicyObject($project)
      ->setImage($project->getProfileImageURI());

    if ($project->getStatus() == PhabricatorProjectStatus::STATUS_ACTIVE) {
      $header->setStatus('fa-check', 'bluegrey', pht('Active'));
    } else {
      $header->setStatus('fa-ban', 'red', pht('Archived'));
    }

    $description = null;

    // This getProxy() feels hacky - see also PhabricatorProjectDatasource:67
    $description_field = PhabricatorCustomField::getObjectField(
      $project,
      PhabricatorCustomField::ROLE_VIEW,
      'std:project:internal:description');

    if ($description_field !== null) {
      $description_field = $description_field->getProxy();

      $description = $description_field->getFieldValue();
      if (phutil_nonempty_string($description)) {
        $description = PhabricatorMarkupEngine::summarizeSentence($description);
        $description = id(new PHUIRemarkupView($viewer, $description));

        $description = phutil_tag(
          'div',
          array('class' => 'project-card-body phui-header-shell'),
          $description);
      }
    }

    $card = phutil_tag(
      'div',
      array(
        'class' => 'project-card-inner',
      ),
      array(
        $header,
        $description,
      ));

    return $card;
  }

}
