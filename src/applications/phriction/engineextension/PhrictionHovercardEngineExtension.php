<?php

final class PhrictionHovercardEngineExtension
  extends PhabricatorHovercardEngineExtension {

  const EXTENSIONKEY = 'phriction';

  public function isExtensionEnabled() {
    return PhabricatorApplication::isClassInstalled(
      'PhabricatorPhrictionApplication');
  }

  public function getExtensionName() {
    return pht('Wiki Documents');
  }

  public function canRenderObjectHovercard($object) {
    return ($object instanceof PhrictionDocument);
  }

  public function willRenderHovercards(array $objects) {
    return array(
      'projects' => $this->getProjectHandlesOfDocuments($objects),
      'ancestors' => $this->getAncestorHandlesOfDocuments($objects),
    );
  }

  public function renderHovercard(
    PHUIHovercardView $hovercard,
    PhabricatorObjectHandle $handle,
    $object,
    $data) {

    $viewer = $this->getViewer();
    $phid = $object->getPHID();

    $detail_content = array(
      id(new PHUIIconView())->setIcon('fa-book'),
    );

    $ancestor_handles = $data['ancestors'][$phid];
    if ($ancestor_handles) {
      foreach ($ancestor_handles as $ancestor_handle) {
        $detail_content[] = phutil_tag(
          'a',
          array(
            'href' => $ancestor_handle->getUri(),
          ),
          $ancestor_handle->getName());

        $detail_content[] = id(new PHUIIconView())
          ->setIcon('fa-angle-right')
          ->addClass('phui-crumb-divider');
      }
      array_pop($detail_content);
    } else {
      $detail_content[] = pht('Wiki Document');
    }

    $project_handles = $data['projects'][$phid];
    if ($project_handles) {
      $list = id(new PHUIHandleTagListView())
        ->setHandles($project_handles)
        ->setSlim(true)
        ->setShowHovercards(false);

      $detail_content[] = $list;
    }

    $hovercard->setDetail(
      phutil_tag(
        'div',
        array(
          'class' => 'phui-hovercard-object-type',
        ),
        $detail_content));

    $content = $object->getContent();

    if ($content) {
      $hovercard->addField(
        pht('Last Author'),
        $viewer->renderHandle($content->getAuthorPHID()));

      $hovercard->addField(
        pht('Last Edited'),
        phabricator_dual_datetime($content->getDateCreated(), $viewer));
    }
  }

  private function getProjectHandlesOfDocuments($documents) {
    $viewer = $this->getViewer();
    $project_edge_type = PhabricatorProjectObjectHasProjectEdgeType::EDGECONST;
    $project_phids = array();
    $project_map = array();

    $project_edges = id(new PhabricatorEdgeQuery())
      ->withSourcePHIDs(mpull($documents, 'getPHID'))
      ->withEdgeTypes(array($project_edge_type))
      ->execute();

    foreach ($project_edges as $document_phid => $edge_types) {
      $document_project_phids = array_keys($edge_types[$project_edge_type]);

      $project_map[$document_phid] = array_reverse($document_project_phids);
      foreach ($document_project_phids as $project_phid) {
        if (!in_array($project_phid, $project_phids, true)) {
          $project_phids[] = $project_phid;
        }
      }
    }

    if ($project_phids) {
      $project_handles = $viewer->loadHandles($project_phids);
      $project_handles = iterator_to_array($project_handles);
      $project_handles = mpull($project_handles, null, 'getPHID');

      foreach ($project_map as $key => $document_project_phids) {
        $project_map[$key] = array_select_keys(
          $project_handles,
          $document_project_phids);
      }
    }

    return $project_map;
  }

  private function getAncestorHandlesOfDocuments($documents) {
    $viewer = $this->getViewer();
    $ancestor_slugs = array();
    $ancestor_map = array();

    foreach ($documents as $document) {
      $document_phid = $document->getPHID();
      $document_ancestor_slugs = PhabricatorSlug::getAncestry(
        $document->getSlug());

      $ancestor_map[$document_phid] = $document_ancestor_slugs;
      foreach ($document_ancestor_slugs as $slug) {
        if (!in_array($slug, $ancestor_slugs, true)) {
          $ancestor_slugs[] = $slug;
        }
      }
    }

    if ($ancestor_slugs) {
      $ancestors = id(new PhrictionDocumentQuery())
        ->setViewer($viewer)
        ->withSlugs($ancestor_slugs)
        ->execute();
      $ancestor_phids = mpull($ancestors, 'getPHID', 'getSlug');
      $ancestor_handles = $viewer->loadHandles($ancestor_phids);
      $ancestor_handles = iterator_to_array($ancestor_handles);
      $ancestor_handles = mpull($ancestor_handles, null, 'getPHID');

      foreach ($ancestor_map as $key => $document_ancestor_slugs) {
        $document_ancestor_phids = array_select_keys(
          $ancestor_phids,
          $document_ancestor_slugs);
        $ancestor_map[$key] = array_select_keys(
          $ancestor_handles,
          $document_ancestor_phids);
      }
    }

    return $ancestor_map;
  }

}
