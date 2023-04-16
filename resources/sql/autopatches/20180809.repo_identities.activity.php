<?php

// Advise installs to rebuild the repository identities.

// If the install has no commits (or no commits that lack an
// authorIdentityPHID), don't require a rebuild.
$commits = id(new PhorgeRepositoryCommit())
  ->loadAllWhere('authorIdentityPHID IS NULL LIMIT 1');

if (!$commits) {
  return;
}

try {
  id(new PhorgeConfigManualActivity())
    ->setActivityType(PhorgeConfigManualActivity::TYPE_IDENTITIES)
    ->save();
} catch (AphrontDuplicateKeyQueryException $ex) {
  // If we've already noted that this activity is required, just move on.
}
