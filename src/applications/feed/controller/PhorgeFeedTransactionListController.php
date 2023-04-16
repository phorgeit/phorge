<?php

final class PhorgeFeedTransactionListController
  extends PhorgeFeedController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeFeedTransactionSearchEngine())
      ->setController($this)
      ->buildResponse();
  }

}
