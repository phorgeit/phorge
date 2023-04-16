<?php

final class PhorgeRepositoryMercurialCommitMessageParserWorker
  extends PhorgeRepositoryCommitMessageParserWorker {

  protected function getFollowupTaskClass() {
    return 'PhorgeRepositoryMercurialCommitChangeParserWorker';
  }

}
