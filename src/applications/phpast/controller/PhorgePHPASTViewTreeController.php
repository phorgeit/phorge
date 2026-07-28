<?php

/**
 * @phutil-external-symbol class PhpParser\Node
 */
final class PhorgePHPASTViewTreeController
  extends PhorgePHPASTViewPanelController {

  public function handleRequest(AphrontRequest $request) {
    $storage = $this->getStorageTree();
    $err = $storage->getError();
    $ast = $storage->getTree();

    if ($err) {
      return $this->buildPHPASTViewPanelResponse($err);
    }

    return $this->buildPHPASTViewPanelResponse($this->buildTree($ast));
  }

  protected function buildTree(array $nodes) {
    $tree = array();

    foreach ($nodes as $node) {
      $tree[] = phutil_tag(
      'li',
      array(),
      phutil_tag(
        'span',
        array(
          'title' => $node->getType(),
        ),
        $node->getType()));

      foreach ($node->getSubNodeNames() as $sub_node_name) {
        $sub_node = $node->{$sub_node_name};

        if (is_array($sub_node) && $sub_node) {
          $tree[] = $this->buildTree($sub_node);
        } else if ($sub_node instanceof PhpParser\Node) {
          $tree[] = $this->buildTree(array($sub_node));
        }
      }
    }

    return phutil_tag(
      'ul',
      array(),
      phutil_implode_html("\n", $tree));
  }

}
