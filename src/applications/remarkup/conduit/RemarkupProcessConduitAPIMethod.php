<?php

final class RemarkupProcessConduitAPIMethod extends ConduitAPIMethod {

  public function getAPIMethodName() {
    return 'remarkup.process';
  }

  public function getMethodDescription() {
    return pht('Process text through remarkup.');
  }

  protected function defineReturnType() {
    return 'nonempty dict';
  }

  protected function defineErrorTypes() {
    return array(
      'ERR-INVALID-CONTENTS' => pht('Contents must be a list of strings.'),
      'ERR-INVALID-ENGINE' => pht('Invalid markup engine.'),
    );
  }

  protected function defineParamTypes() {
    $available_contexts = array_keys($this->getEngineContexts());
    $available_const = $this->formatStringConstants($available_contexts);

    return array(
      'context' => 'required '.$available_const,
      'contents' => 'required list<string>',
    );
  }

  protected function execute(ConduitAPIRequest $request) {
    $contents = $request->getValue('contents');
    if (!is_array($contents)) {
      throw new ConduitException('ERR-INVALID-CONTENTS');
    }

    $context = $request->getValue('context');
    $engine_class = idx($this->getEngineContexts(), $context);
    if (!$engine_class) {
      throw new ConduitException('ERR-INVALID-ENGINE');
    }

    $engine = PhabricatorMarkupEngine::$engine_class();
    $engine->setConfig('viewer', $request->getUser());

    $results = array();
    foreach ($contents as $content) {
      $text = $engine->markupText($content);
      if ($text) {
        $content = hsprintf('%s', $text)->getHTMLContent();
      } else {
        $content = '';
      }
      $results[] = array(
        'content' => $content,
      );
    }
    return $results;
  }

  private function getEngineContexts() {
    return array(
      'phriction' => 'newPhrictionMarkupEngine',
      'maniphest' => 'newManiphestMarkupEngine',
      'differential' => 'newDifferentialMarkupEngine',
      'phame' => 'newPhameMarkupEngine',
      'feed' => 'newFeedMarkupEngine',
      'diffusion' => 'newDiffusionMarkupEngine',
    );
  }

}
