<?php

final class PhabricatorSourceDocumentEngine
  extends PhabricatorTextDocumentEngine {

  const ENGINEKEY = 'source';

  public function getViewAsLabel(PhabricatorDocumentRef $ref) {
    return pht('View as Source');
  }

  public function canConfigureHighlighting(PhabricatorDocumentRef $ref) {
    return true;
  }

  public function canBlame(PhabricatorDocumentRef $ref) {
    // Since 2025, the features 'blame' and 'coverage' require login,
    // due to aggressive LLM/AI web scrapers following all these links,
    // decreasing server performance.
    // Scrapers are invited to pull code repos via a command line checkout.
    return $this->getViewer()->isLoggedIn();
  }

  protected function getDocumentIconIcon(PhabricatorDocumentRef $ref) {
    return 'fa-code';
  }

  protected function getContentScore(PhabricatorDocumentRef $ref) {
    return 1500;
  }

  protected function newDocumentContent(PhabricatorDocumentRef $ref) {
    $content = $this->loadTextData($ref);

    $messages = array();

    $highlighting = $this->getHighlightingConfiguration();
    if ($highlighting !== null) {
      $content = PhabricatorSyntaxHighlighter::highlightWithLanguage(
        $highlighting,
        $content);
    } else {
      $highlight_limit = DifferentialChangesetParser::HIGHLIGHT_BYTE_LIMIT;
      if (strlen($content) > $highlight_limit) {
        $messages[] = $this->newMessage(
          pht(
            'This file is larger than %s, so syntax highlighting was skipped.',
            phutil_format_bytes($highlight_limit)));
      } else {
        $content = PhabricatorSyntaxHighlighter::highlightWithFilename(
          $ref->getName(),
          $content);
      }
    }

    $options = array();
    if ($ref->getBlameURI() && $this->getBlameEnabled()
      && $this->canBlame($ref)) {
      $content = phutil_split_lines($content);
      $blame = range(1, count($content));
      $blame = array_fuse($blame);
      $options['blame'] = $blame;
    }

    if ($ref->getCoverage()) {
      $options['coverage'] = $ref->getCoverage();
    }

    return array(
      $messages,
      $this->newTextDocumentContent($ref, $content, $options),
    );
  }

}
