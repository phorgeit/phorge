<?php

final class PhorgeAuditTransactionView
  extends PhorgeApplicationTransactionView {

  private $pathMap = array();

  public function setPathMap(array $path_map) {
    $this->pathMap = $path_map;
    return $this;
  }

  public function getPathMap() {
    return $this->pathMap;
  }

  // TODO: This shares a lot of code with Differential and Pholio and should
  // probably be merged up.

  protected function shouldGroupTransactions(
    PhorgeApplicationTransaction $u,
    PhorgeApplicationTransaction $v) {

    if ($u->getAuthorPHID() != $v->getAuthorPHID()) {
      // Don't group transactions by different authors.
      return false;
    }

    if (($v->getDateCreated() - $u->getDateCreated()) > 60) {
      // Don't group if transactions that happened more than 60s apart.
      return false;
    }

    switch ($u->getTransactionType()) {
      case PhorgeTransactions::TYPE_COMMENT:
      case PhorgeAuditActionConstants::INLINE:
        break;
      default:
        return false;
    }

    switch ($v->getTransactionType()) {
      case PhorgeAuditActionConstants::INLINE:
        return true;
    }

    return parent::shouldGroupTransactions($u, $v);
  }

  protected function renderTransactionContent(
    PhorgeApplicationTransaction $xaction) {

    $out = array();

    $type_inline = PhorgeAuditActionConstants::INLINE;

    $group = $xaction->getTransactionGroup();

    if ($xaction->getTransactionType() == $type_inline) {
      array_unshift($group, $xaction);
    } else {
      $out[] = parent::renderTransactionContent($xaction);
    }

    if ($this->getIsPreview()) {
      return $out;
    }

    if (!$group) {
      return $out;
    }

    $inlines = array();
    foreach ($group as $xaction) {
      switch ($xaction->getTransactionType()) {
        case PhorgeAuditActionConstants::INLINE:
          $inlines[] = $xaction;
          break;
        default:
          throw new Exception(pht('Unknown grouped transaction type!'));
      }
    }

    $structs = array();
    foreach ($inlines as $key => $inline) {
      $comment = $inline->getComment();
      if (!$comment) {
        // TODO: Migrate these away? They probably do not exist on normal
        // non-development installs.
        unset($inlines[$key]);
        continue;
      }

      $path_id = $comment->getPathID();
      $path = idx($this->pathMap, $path_id);
      if ($path === null) {
        continue;
      }

      $structs[] = array(
        'inline' => $inline,
        'path' => $path,
        'sort' => (string)id(new PhutilSortVector())
          ->addString($path)
          ->addInt($comment->getLineNumber())
          ->addInt($comment->getLineLength())
          ->addInt($inline->getID()),
      );
    }

    if (!$structs) {
      return $out;
    }

    $structs = isort($structs, 'sort');
    $structs = igroup($structs, 'path');

    $inline_view = new PhorgeInlineSummaryView();
    foreach ($structs as $path => $group) {
      $inlines = ipull($group, 'inline');
      $items = array();
      foreach ($inlines as $inline) {
        $comment = $inline->getComment();
        $items[] = array(
          'id' => $comment->getID(),
          'line' => $comment->getLineNumber(),
          'length' => $comment->getLineLength(),
          'content' => parent::renderTransactionContent($inline),
        );
      }
      $inline_view->addCommentGroup($path, $items);
    }

    $out[] = $inline_view;

    return $out;
  }

}
