UPDATE {$NAMESPACE}_differential.differential_transaction
  SET authorPHID = 'PHID-APPS-PhorgeHeraldApplication'
  WHERE authorPHID = 'PHID-APPS-PhorgeApplicationHerald';
UPDATE {$NAMESPACE}_maniphest.maniphest_transaction
  SET authorPHID = 'PHID-APPS-PhorgeHeraldApplication'
  WHERE authorPHID = 'PHID-APPS-PhorgeApplicationHerald';
UPDATE {$NAMESPACE}_pholio.pholio_transaction
  SET authorPHID = 'PHID-APPS-PhorgeHeraldApplication'
  WHERE authorPHID = 'PHID-APPS-PhorgeApplicationHerald';

UPDATE {$NAMESPACE}_differential.differential_transaction
  SET authorPHID = 'PHID-APPS-PhorgeHarbormasterApplication'
  WHERE authorPHID = 'PHID-APPS-PhorgeApplicationHarbormaster';
