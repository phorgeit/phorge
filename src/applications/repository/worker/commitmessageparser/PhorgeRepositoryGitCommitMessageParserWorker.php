<?php

final class PhorgeRepositoryGitCommitMessageParserWorker
  extends PhorgeRepositoryCommitMessageParserWorker {

  protected function getFollowupTaskClass() {
    return 'PhorgeRepositoryGitCommitChangeParserWorker';
  }

}
