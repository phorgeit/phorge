<?php

final class PhabricatorDifferentialRevisionTestDataGenerator
  extends PhabricatorTestDataGenerator {

  const GENERATORKEY = 'revisions';

  public function getGeneratorName() {
    return pht('Differential Revisions');
  }

  public function generateObject() {
    $author = $this->loadPhabricatorUser();

    $revision = DifferentialRevision::initializeNewRevision($author);

    $diff = $this->generateDiff($author);
    $type_create = PhabricatorTransactions::TYPE_CREATE;
    $type_update = DifferentialRevisionUpdateTransaction::TRANSACTIONTYPE;
    $type_title = DifferentialRevisionTitleTransaction::TRANSACTIONTYPE;
    $type_summary = DifferentialRevisionSummaryTransaction::TRANSACTIONTYPE;
    $type_testplan = DifferentialRevisionTestPlanTransaction::TRANSACTIONTYPE;

    // Create associative array of transaction types and their new value.
    $changes = array();
    $changes[$type_create] = null; // Value not needed here.

    // This could be a bit richer and more formal than it is.
    $changes[$type_update] = $diff->getPHID();
    $changes[$type_title] = $this->generateTitle();
    $changes[$type_summary] = $this->generateDescription();
    $changes[$type_testplan] = $this->generateDescription();

    // Create transactions.
    $xactions = array();
    foreach ($changes as $type => $new_value) {
      $xaction = new DifferentialTransaction();
      $xaction->setTransactionType($type);
      if ($new_value !== null) {
        $xaction->setNewValue($new_value);
      }

      $xactions[] = $xaction;
    }

    id(new DifferentialTransactionEditor())
      ->setActor($author)
      ->setContentSource($this->getLipsumContentSource())
      ->applyTransactions($revision, $xactions);

    return $revision;
  }

  public function getCCPHIDs() {
    $ccs = array();
    for ($i = 0; $i < rand(1, 4);$i++) {
      $ccs[] = $this->loadPhabricatorUserPHID();
    }
    return $ccs;
  }

  public function generateDiff($author) {
    $paste_generator = new PhabricatorPasteTestDataGenerator();
    $languages = $paste_generator->getSupportedLanguages();
    $language = array_rand($languages);
    $spec = $languages[$language];

    $code = $paste_generator->generateContent($spec);
    $altcode = $paste_generator->generateContent($spec);
    $newcode = $this->randomlyModify($code, $altcode);
    $diff = id(new PhabricatorDifferenceEngine())
      ->generateRawDiffFromFileContent($code, $newcode);
     $call = new ConduitCall(
      'differential.createrawdiff',
      array(
        'diff' => $diff,
      ));
    $call->setUser($author);
    $result = $call->execute();
    $thediff = id(new DifferentialDiff())->load(
      $result['id']);
    $thediff->setDescription($this->generateTitle())->save();
    return $thediff;
  }

  public function generateDescription() {
    return id(new PhutilLipsumContextFreeGrammar())
      ->generateSeveral(rand(10, 20), "\n");
  }

  public function generateTitle() {
    return id(new PhutilLipsumContextFreeGrammar())
      ->generate();
  }

  public function randomlyModify($code, $altcode) {
    $codearr = explode("\n", $code);
    $altcodearr = explode("\n", $altcode);
    $no_lines_to_delete = rand(1,
      min(count($codearr) - 2, 5));
    $randomlines = array_rand($codearr,
      count($codearr) - $no_lines_to_delete);
    $newcode = array();
    foreach ($randomlines as $lineno) {
      $newcode[] = $codearr[$lineno];
    }
    $newlines_count = rand(2,
      min(count($codearr) - 2, count($altcodearr) - 2, 5));
    $randomlines_orig = array_rand($codearr, $newlines_count);
    $randomlines_new = array_rand($altcodearr, $newlines_count);
    $newcode2 = array();
    $c = 0;
    for ($i = 0; $i < count($newcode);$i++) {
      $newcode2[] = $newcode[$i];
      if (in_array($i, $randomlines_orig)) {
        $newcode2[] = $altcodearr[$randomlines_new[$c++]];
      }
    }
    return implode("\n", $newcode2);
  }

}
