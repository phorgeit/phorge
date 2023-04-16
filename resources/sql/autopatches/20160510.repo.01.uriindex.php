<?php

$repos = id(new PhorgeRepositoryQuery())
  ->setViewer(PhorgeUser::getOmnipotentUser())
  ->needURIs(true)
  ->execute();

foreach ($repos as $repo) {
  $repo->updateURIIndex();
}
