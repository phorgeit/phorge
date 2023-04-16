<?php

final class PhabricatorConfigIssuePanelController
  extends PhabricatorConfigController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();

    $engine = new PhabricatorSetupEngine();
    $response = $engine->execute();
    if ($response) {
      return $response;
    }
    $issues = $engine->getIssues();
    $unresolved_count = count($engine->getUnresolvedIssues());

    if ($issues) {
      require_celerity_resource('phorge-notification-menu-css');

      $items = array();
      foreach ($issues as $issue) {
        $classes = array();
        $classes[] = 'phorge-notification';
        if ($issue->getIsIgnored()) {
          $classes[] = 'phorge-notification-read';
        } else {
          $classes[] = 'phorge-notification-unread';
        }
        $uri = '/config/issue/'.$issue->getIssueKey().'/';
        $title = $issue->getName();
        $summary = $issue->getSummary();
        $items[] = javelin_tag(
          'div',
          array(
            'class' => implode(' ', $classes),
            'sigil' => 'notification',
            'meta' => array(
              'href' => $uri,
            ),
          ),
          $title);
      }
      $content = phutil_tag_div('setup-issue-menu', $items);
    } else {
      $content = phutil_tag_div(
        'phorge-notification no-notifications',
        pht('You have no unresolved setup issues.'));
    }

    $content = hsprintf(
      '<div class="phorge-notification-header">%s</div>'.
      '%s',
      phutil_tag(
        'a',
        array(
          'href' => '/config/issue/',
        ),
        pht('Unresolved Setup Issues')),
      $content);

    $json = array(
      'content' => $content,
      'number'  => (int)$unresolved_count,
    );

    return id(new AphrontAjaxResponse())->setContent($json);
  }

}
