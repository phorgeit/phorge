<?php

final class PhorgeProjectTestDataGenerator
  extends PhorgeTestDataGenerator {

  const GENERATORKEY = 'projects';

  public function getGeneratorName() {
    return pht('Projects');
  }

  public function generateObject() {
    $author = $this->loadRandomUser();
    $project = PhorgeProject::initializeNewProject($author);

    $xactions = array();

    $xactions[] = $this->newTransaction(
      PhorgeProjectNameTransaction::TRANSACTIONTYPE,
      $this->newProjectTitle());

    $xactions[] = $this->newTransaction(
      PhorgeProjectStatusTransaction::TRANSACTIONTYPE,
      $this->newProjectStatus());

    // Almost always make the author a member.
    $members = array();
    if ($this->roll(1, 20) > 2) {
      $members[] = $author->getPHID();
    }

    // Add a few other members.
    $size = $this->roll(2, 6, -2);
    for ($ii = 0; $ii < $size; $ii++) {
      $members[] = $this->loadRandomUser()->getPHID();
    }

    $xactions[] = $this->newTransaction(
      PhorgeTransactions::TYPE_EDGE,
      array(
        '+' => array_fuse($members),
      ),
      array(
        'edge:type' => PhorgeProjectProjectHasMemberEdgeType::EDGECONST,
      ));

    $editor = id(new PhorgeProjectTransactionEditor())
      ->setActor($author)
      ->setContentSource($this->getLipsumContentSource())
      ->setContinueOnNoEffect(true)
      ->applyTransactions($project, $xactions);

    return $project;
  }

  protected function newEmptyTransaction() {
    return new PhorgeProjectTransaction();
  }

  public function newProjectTitle() {
    return id(new PhorgeProjectNameContextFreeGrammar())
      ->generate();
  }

  public function newProjectStatus() {
    if ($this->roll(1, 20) > 5) {
      return PhorgeProjectStatus::STATUS_ACTIVE;
    } else {
      return PhorgeProjectStatus::STATUS_ARCHIVED;
    }
  }
}
