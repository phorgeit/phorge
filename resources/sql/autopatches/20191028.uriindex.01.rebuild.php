<?php

// @phase worker

PhorgeRebuildIndexesWorker::rebuildObjectsWithQuery(
  'PhorgeRepositoryQuery');
