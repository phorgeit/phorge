<?php

final class PhorgeSourceDocumentEngine
  extends PhorgeTextDocumentEngine {

  const ENGINEKEY = 'source';

  public function getViewAsLabel(PhorgeDocumentRef $ref) {
    return pht('View as Source');
  }

  public function canConfigureHighlighting(PhorgeDocumentRef $ref) {
    return true;
  }

  public function canBlame(PhorgeDocumentRef $ref) {
    return true;
  }

  protected function getDocumentIconIcon(PhorgeDocumentRef $ref) {
    return 'fa-code';
  }

  protected function getContentScore(PhorgeDocumentRef $ref) {
    return 1500;
  }

  protected function newDocumentContent(PhorgeDocumentRef $ref) {
    $content = $this->loadTextData($ref);

    $messages = array();

    $highlighting = $this->getHighlightingConfiguration();
    if ($highlighting !== null) {
      $content = PhorgeSyntaxHighlighter::highlightWithLanguage(
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
        $content = PhorgeSyntaxHighlighter::highlightWithFilename(
          $ref->getName(),
          $content);
      }
    }

    $options = array();
    if ($ref->getBlameURI() && $this->getBlameEnabled()) {
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
