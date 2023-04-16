<?php

// @phase worker

PhorgeRebuildIndexesWorker::rebuildObjectsWithQuery(
  'PhorgeDashboardQuery');

PhorgeRebuildIndexesWorker::rebuildObjectsWithQuery(
  'PhorgeDashboardPanelQuery');
