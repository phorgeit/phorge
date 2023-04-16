<?php

final class PhorgeRepositorySvnCommitMessageParserWorker
  extends PhorgeRepositoryCommitMessageParserWorker {

  protected function getFollowupTaskClass() {
    return 'PhorgeRepositorySvnCommitChangeParserWorker';
  }

}
