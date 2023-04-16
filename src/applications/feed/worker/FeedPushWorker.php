<?php

abstract class FeedPushWorker extends PhorgeWorker {

  protected function loadFeedStory() {
    $task_data = $this->getTaskData();
    $key = $task_data['key'];

    $story = id(new PhorgeFeedQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withChronologicalKeys(array($key))
      ->executeOne();

    if (!$story) {
      throw new PhorgeWorkerPermanentFailureException(
        pht(
          'Feed story (with key "%s") does not exist or could not be loaded.',
          $key));
    }

    return $story;
  }

}
