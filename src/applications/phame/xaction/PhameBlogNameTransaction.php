<?php

final class PhameBlogNameTransaction
  extends PhameBlogTransactionType {

  use PhorgeNameTransactionTrait;

  const TRANSACTIONTYPE = 'phame.blog.name';

  public function getIcon() {
    return 'fa-rss';
  }

}
