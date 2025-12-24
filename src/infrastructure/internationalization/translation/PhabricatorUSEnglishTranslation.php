<?php

final class PhabricatorUSEnglishTranslation
  extends PhutilTranslation {

  public function getLocaleCode() {
    return 'en_US';
  }

  protected function getTranslations() {
    return array(
      'These %d configuration value(s) are related:' => array(
        'This configuration value is related:',
        'These configuration values are related:',
      ),
      '%s Answer(s)' => array('%s Answer', '%s Answers'),
      'Show %d Comment(s)' => array('Show %d Comment', 'Show %d Comments'),

      '%s DIFF LINK(S)' => array('DIFF LINK', 'DIFF LINKS'),
      'You successfully created %d diff(s).' => array(
        'You successfully created %d diff.',
        'You successfully created %d diffs.',
      ),
      'Diff creation failed; see body for %s error(s).' => array(
        'Diff creation failed; see body for error.',
        'Diff creation failed; see body for errors.',
      ),

      '%s Action(s) Have No Effect' => array(
        'Action Has No Effect',
        'Actions Have No Effect',
      ),

      '%s Action(s) With No Effect' => array(
        'Action With No Effect',
        'Actions With No Effect',
      ),

      '%s of your actions have no effect:' => array(
        'One of your actions has no effect:',
        'Some of your actions have no effect:',
      ),

      'The %s action(s) you are taking have no effect:' => array(
        'The action you are taking has no effect:',
        'The actions you are taking have no effect:',
      ),

      '%s added %s member(s): %s.' => array(
        array(
          '%s added a member: %3$s.',
          '%s added members: %3$s.',
        ),
      ),

      '%s removed %s member(s): %s.' => array(
        array(
          '%s removed a member: %3$s.',
          '%s removed members: %3$s.',
        ),
      ),

      '%s edited project(s), added %s: %s; removed %s: %s.' =>
        '%s edited projects, added: %3$s; removed: %5$s.',

      '%s edited project(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited projects for %s, added: %4$s; removed: %6$s.',

      '%s added %s project(s): %s.' => array(
        array(
          '%s added a project: %3$s.',
          '%s added projects: %3$s.',
        ),
      ),
      '%s added %s project(s) for %s: %s.' => array(
        array(
          '%s added a project for %3$s: %4$s.',
          '%s added projects for %3$s: %4$s.',
        ),
      ),

      '%s removed %s project(s): %s.' => array(
        array(
          '%s removed a project: %3$s.',
          '%s removed projects: %3$s.',
        ),
      ),

      '%s removed %s project(s) for %s: %s.' => array(
        array(
          '%s removed a project for %3$s: %4$s.',
          '%s removed projects for %3$s: %4$s.',
        ),
      ),

      '%s merged %s task(s): %s.' => array(
        array(
          '%s merged a task: %3$s.',
          '%s merged tasks: %3$s.',
        ),
      ),

      '%s merged %s task(s) %s into %s.' => array(
        array(
          '%s merged %3$s into %4$s.',
          '%s merged tasks %3$s into %4$s.',
        ),
      ),
      '%s added %s subtask(s): %s.' => array(
        array(
          '%s added a subtask: %3$s.',
          '%s added subtasks: %3$s.',
        ),
      ),

      '%s added %s parent task(s): %s.' => array(
        array(
          '%s added a parent task: %3$s.',
          '%s added parent tasks: %3$s.',
        ),
      ),

      '%s removed %s subtask(s): %s.' => array(
        array(
          '%s removed a subtask: %3$s.',
          '%s removed subtasks: %3$s.',
        ),
      ),

      '%s removed %s parent task(s): %s.' => array(
        array(
          '%s removed a parent task: %3$s.',
          '%s removed parent tasks: %3$s.',
        ),
      ),

      '%s added %s subtask(s) for %s: %s.' => array(
        array(
          '%s added a subtask for %3$s: %4$s.',
          '%s added subtasks for %3$s: %4$s.',
        ),
      ),

      '%s added %s parent task(s) for %s: %s.' => array(
        array(
          '%s added a parent task for %3$s: %4$s.',
          '%s added parent tasks for %3$s: %4$s.',
        ),
      ),

      '%s removed %s subtask(s) for %s: %s.' => array(
        array(
          '%s removed a subtask for %3$s: %4$s.',
          '%s removed subtasks for %3$s: %4$s.',
        ),
      ),

      '%s removed %s parent task(s) for %s: %s.' => array(
        array(
          '%s removed a parent task for %3$s: %4$s.',
          '%s removed parent tasks for %3$s: %4$s.',
        ),
      ),

      '%s edited subtask(s), added %s: %s; removed %s: %s.' =>
        '%s edited subtasks, added: %3$s; removed: %5$s.',

      '%s edited subtask(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited subtasks for %s, added: %4$s; removed: %6$s.',

      '%s edited parent task(s), added %s: %s; removed %s: %s.' =>
        '%s edited parent tasks, added: %3$s; removed: %5$s.',

      '%s edited parent task(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited parent tasks for %s, added: %4$s; removed: %6$s.',

      '%s edited mock(s), added %s: %s; removed %s: %s.' =>
        '%s edited mocks, added: %3$s; removed: %5$s.',

      '%s added %s mock(s): %s.' => array(
        array(
          '%s added a mock: %3$s.',
          '%s added mocks: %3$s.',
        ),
      ),
      '%s added %s mock(s) for %s: %s.' => array(
        array(
          '%s added a mock for %3$s: %4$s.',
          '%s added mocks for %3$s: %4$s.',
        ),
      ),

      '%s removed %s mock(s) for %s: %s.' => array(
        array(
          '%s removed a mock for %3$s: %4$s.',
          '%s removed mocks for %3$s: %4$s.',
        ),
      ),

      '%s edited mock(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited mocks for %s, added: %4$s; removed: %6$s.',

      '%s removed %s mock(s): %s.' => array(
        array(
          '%s removed a mock: %3$s.',
          '%s removed mocks: %3$s.',
        ),
      ),

      '%s added %s task(s): %s.' => array(
        array(
          '%s added a task: %3$s.',
          '%s added tasks: %3$s.',
        ),
      ),

      '%s removed %s task(s): %s.' => array(
        array(
          '%s removed a task: %3$s.',
          '%s removed tasks: %3$s.',
        ),
      ),

      '%s edited contributor(s), added %s: %s; removed %s: %s.' =>
        '%s edited contributors, added: %3$s; removed: %5$s.',

      '%s added %s contributor(s): %s.' => array(
        array(
          '%s added a contributor: %3$s.',
          '%s added contributors: %3$s.',
        ),
      ),
      '%s added %s contributor(s) for %s: %s.' => array(
        array(
          '%s added a contributor for %3$s: %4$s.',
          '%s added contributors for %3$s: %4$s.',
        ),
      ),

      '%s removed %s contributor(s): %s.' => array(
        array(
          '%s removed a contributor: %3$s.',
          '%s removed contributors: %3$s.',
        ),
      ),

      '%s removed %s contributor(s) for %s: %s.' => array(
        array(
          '%s removed a contributor for %3$s: %4$s.',
          '%s removed contributors for %3$s: %4$s.',
        ),
      ),

      '%s edited contributor(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited contributors for %s, added: %4$s; removed: %6$s.',

      '%s edited %s reviewer(s), added %s: %s; removed %s: %s.' =>
        '%s edited reviewers, added: %4$s; removed: %6$s.',

      '%s edited %s reviewer(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited reviewers for %3$s, added: %5$s; removed: %7$s.',

      '%s added %s reviewer(s): %s.' => array(
        array(
          '%s added a reviewer: %3$s.',
          '%s added reviewers: %3$s.',
        ),
      ),

      '%s added %s reviewer(s) for %s: %s.' => array(
        array(
          '%s added a reviewer for %3$s: %4$s.',
          '%s added reviewers for %3$s: %4$s.',
        ),
      ),

      '%s removed %s reviewer(s): %s.' => array(
        array(
          '%s removed a reviewer: %3$s.',
          '%s removed reviewers: %3$s.',
        ),
      ),

      '%s removed %s reviewer(s) for %s: %s.' => array(
        array(
          '%s removed a reviewer for %3$s: %4$s.',
          '%s removed reviewers for %3$s: %4$s.',
        ),
      ),

      '%d other(s)' => array(
        '%d other',
        '%d others',
      ),

      '%s edited subscriber(s), added %d: %s; removed %d: %s.' =>
        '%s edited subscribers, added: %3$s; removed: %5$s.',

      '%s edited subscriber(s), added %s: %s; removed %s: %s.' =>
        '%s edited subscribers, added: %3$s; removed: %5$s.',

      '%s edited subscriber(s) for %s, added %s: %s; removed %s: %s.' =>
          '%s edited subscribers for %s, added: %4$s; removed: %6$s.',

      '%s added %s subscriber(s): %s.' => array(
        array(
          '%s added a subscriber: %3$s.',
          '%s added subscribers: %3$s.',
        ),
      ),

      '%s added %d subscriber(s): %s.' => array(
        array(
          '%s added a subscriber: %3$s.',
          '%s added subscribers: %3$s.',
        ),
      ),

      '%s added %s subscriber(s) for %s: %s.' => array(
        array(
          '%s added a subscriber for %3$s: %4$s.',
          '%s added subscribers for %3$s: %4$s.',
        ),
      ),

      '%s removed %d subscriber(s): %s.' => array(
        array(
          '%s removed a subscriber: %3$s.',
          '%s removed subscribers: %3$s.',
        ),
      ),

      '%s removed %s subscriber(s): %s.' => array(
        array(
          '%s removed a subscriber: %3$s.',
          '%s removed subscribers: %3$s.',
        ),
      ),

      '%s removed %s subscriber(s) for %s: %s.' => array(
        array(
          '%s removed a subscriber for %3$s: %4$s.',
          '%s removed subscribers for %3$s: %4$s.',
        ),
      ),

      '%s edited unsubscriber(s), added %s: %s; removed %s: %s.' =>
        '%s edited unsubscribers, added: %3$s; removed: %5$s.',

      '%s edited unsubscriber(s) for %s, added %s: %s; removed %s: %s.' =>
          '%s edited unsubscribers for %s, added: %4$s; removed: %6$s.',

      '%s added %s unsubscriber(s): %s.' => array(
        array(
          '%s added an unsubscriber: %3$s.',
          '%s added unsubscribers: %3$s.',
        ),
      ),
      '%s added %s unsubscriber(s) for %s: %s.' => array(
        array(
          '%s added an unsubscriber for %3$s: %4$s.',
          '%s added unsubscribers for %3$s: %4$s.',
        ),
      ),

      '%s removed %s unsubscriber(s): %s.' => array(
        array(
          '%s removed an unsubscriber: %3$s.',
          '%s removed unsubscribers: %3$s.',
        ),
      ),

      '%s removed %s unsubscriber(s) for %s: %s.' => array(
        array(
          '%s removed an subscriber for %3$s: %4$s.',
          '%s removed unsubscribers for %3$s: %4$s.',
        ),
      ),

      '%s edited watcher(s), added %s: %s; removed %s: %s.' =>
        '%s edited watchers, added: %3$s; removed: %5$s.',

      '%s edited watcher(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited watchers for %s, added: %4$s; removed: %6$s.',

      '%s added %s watcher(s): %s.' => array(
        array(
          '%s added a watcher: %3$s.',
          '%s added watchers: %3$s.',
        ),
      ),

      '%s removed %s watcher(s): %s.' => array(
        array(
          '%s removed a watcher: %3$s.',
          '%s removed watchers: %3$s.',
        ),
      ),

      '%s edited participant(s), added %d: %s; removed %d: %s.' =>
        '%s edited participants, added: %3$s; removed: %5$s.',

      '%s added %d participant(s): %s.' => array(
        array(
          '%s added a participant: %3$s.',
          '%s added participants: %3$s.',
        ),
      ),

      '%s removed %d participant(s): %s.' => array(
        array(
          '%s removed a participant: %3$s.',
          '%s removed participants: %3$s.',
        ),
      ),

      '%s edited image(s), added %d: %s; removed %d: %s.' =>
        '%s edited images, added: %3$s; removed: %5$s',

      '%s added %d image(s): %s.' => array(
        array(
          '%s added an image: %3$s.',
          '%s added images: %3$s.',
        ),
      ),

      '%s removed %d image(s): %s.' => array(
        array(
          '%s removed an image: %3$s.',
          '%s removed images: %3$s.',
        ),
      ),

      '%s Line(s)' => array(
        '%s Line',
        '%s Lines',
      ),

      'Run these %d command(s):' => array(
        'Run this command:',
        'Run these commands:',
      ),

      'Install these %d PHP extension(s):' => array(
        'Install this PHP extension:',
        'Install these PHP extensions:',
      ),

      'The current MySQL configuration has these %d value(s):' => array(
        'The current MySQL configuration has this value:',
        'The current MySQL configuration has these values:',
      ),

      'You can update these %d value(s) here:' => array(
        'You can update this value here:',
        'You can update these values here:',
      ),

      'The current PHP configuration has these %d value(s):' => array(
        'The current PHP configuration has this value:',
        'The current PHP configuration has these values:',
      ),

      'To update these %d value(s), edit your PHP configuration file.' => array(
        'To update this %d value, edit your PHP configuration file.',
        'To update these %d values, edit your PHP configuration file.',
      ),

      'To update these %d value(s), edit your PHP configuration file, located '.
      'here:' => array(
        'To update this value, edit your PHP configuration file, located '.
        'here:',
        'To update these values, edit your PHP configuration file, located '.
        'here:',
      ),

      'PHP also loaded these %s configuration file(s):' => array(
        'PHP also loaded this configuration file:',
        'PHP also loaded these configuration files:',
      ),

      'This configuration value is defined in these %d '.
      'configuration source(s): %s.' => array(
        'This configuration value is defined in this '.
        'configuration source: %2$s.',
        'This configuration value is defined in these %d '.
        'configuration sources: %s.',
      ),

      '%s Commit(s)' => array(
        '%s Commit',
        '%s Commits',
      ),

      '%s added %s parent revision(s): %s.' => array(
        array(
          '%s added a parent revision: %3$s.',
          '%s added parent revisions: %3$s.',
        ),
      ),

      '%s added %s parent revision(s) for %s: %s.' => array(
        array(
          '%s added a parent revision for %3$s: %4$s.',
          '%s added parent revisions for %3$s: %4$s.',
        ),
      ),

      '%s removed %s parent revision(s): %s.' => array(
        array(
          '%s removed a parent revision: %3$s.',
          '%s removed parent revisions: %3$s.',
        ),
      ),

      '%s removed %s parent revision(s) for %s: %s.' => array(
        array(
          '%s removed a parent revision for %3$s: %4$s.',
          '%s removed parent revisions for %3$s: %4$s.',
        ),
      ),

      '%s edited parent revision(s), added %s: %s; removed %s: %s.' =>
        '%s edited parent revisions, added: %3$s; removed: %5$s.',

      '%s edited parent revision(s) for %s, '.
      'added %s: %s; removed %s: %s.' =>
        '%s edited parent revisions for %s, added: %4$s; removed: %6$s.',

      '%s added %s child revision(s): %s.' => array(
        array(
          '%s added a child revision: %3$s.',
          '%s added child revisions: %3$s.',
        ),
      ),

      '%s added %s child revision(s) for %s: %s.' => array(
        array(
          '%s added a child revision for %3$s: %4$s.',
          '%s added child revisions for %3$s: %4$s.',
        ),
      ),

      '%s removed %s child revision(s): %s.' => array(
        array(
          '%s removed a child revision: %3$s.',
          '%s removed child revisions: %3$s.',
        ),
      ),

      '%s removed %s child revision(s) for %s: %s.' => array(
        array(
          '%s removed a child revision for %3$s: %4$s.',
          '%s removed child revisions for %3$s: %4$s.',
        ),
      ),

      '%s edited child revision(s), added %s: %s; removed %s: %s.' =>
        '%s edited child revisions, added: %3$s; removed: %5$s.',

      '%s edited child revision(s) for %s, '.
      'added %s: %s; removed %s: %s.' =>
        '%s edited child revisions for %s, added: %4$s; removed: %6$s.',


      '%s added %s commit(s): %s.' => array(
        array(
          '%s added a commit: %3$s.',
          '%s added commits: %3$s.',
        ),
      ),

      '%s added %s commit(s) for %s: %s.' => array(
        array(
          '%s added a commit for %3$s: %4$s',
          '%s added commits for %3$s: %4$s.',
        ),
      ),

      '%s removed %s commit(s): %s.' => array(
        array(
          '%s removed a commit: %3$s.',
          '%s removed commits: %3$s.',
        ),
      ),

      '%s removed %s commit(s) for %s: %s.' => array(
        array(
          '%s removed a commit for %3$s: %4$s',
          '%s removed commits for %3$s: %4$s.',
        ),
      ),

      '%s edited commit(s), added %s: %s; removed %s: %s.' =>
        '%s edited commits, added: %3$s; removed: %5$s.',

      '%s edited commit(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited commits for %s, added: %4$s; removed: %6$s.',

      '%s added %s reverted change(s): %s.' => array(
        array(
          '%s added a reverted change: %3$s.',
          '%s added reverted changes: %3$s.',
        ),
      ),

      '%s removed %s reverted change(s): %s.' => array(
        array(
          '%s removed a reverted change: %3$s.',
          '%s removed reverted changes: %3$s.',
        ),
      ),

      '%s edited reverted change(s), added %s: %s; removed %s: %s.' =>
        '%s edited reverted changes, added: %3$s; removed: %5$s.',

      '%s added %s reverted change(s) for %s: %s.' => array(
        array(
          '%s added a reverted change for %3$s: %4$s.',
          '%s added reverted changes for %3$s: %4$s.',
        ),
      ),

      '%s removed %s reverted change(s) for %s: %s.' => array(
        array(
          '%s removed a reverted change for %3$s: %4$s.',
          '%s removed reverted changes for %3$s: %4$s.',
        ),
      ),

      '%s edited reverted change(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited reverted changes for %s, added: %4$s; removed: %6$s.',

      '%s added %s reverting change(s): %s.' => array(
        array(
          '%s added a reverting change: %3$s.',
          '%s added reverting changes: %3$s.',
        ),
      ),

      '%s removed %s reverting change(s): %s.' => array(
        array(
          '%s removed a reverting change: %3$s.',
          '%s removed reverting changes: %3$s.',
        ),
      ),

      '%s edited reverting change(s), added %s: %s; removed %s: %s.' =>
        '%s edited reverting changes, added: %3$s; removed: %5$s.',

      '%s added %s reverting change(s) for %s: %s.' => array(
        array(
          '%s added a reverting change for %3$s: %4$s.',
          '%s added reverting changes for %3$s: %4$s.',
        ),
      ),

      '%s removed %s reverting change(s) for %s: %s.' => array(
        array(
          '%s removed a reverting change for %3$s: %4$s.',
          '%s removed reverting changes for %3$s: %4$s.',
        ),
      ),

      '%s edited reverting change(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited reverting changes for %s, added: %4$s; removed: %6$s.',

      '%s changed project member(s), added %d: %s; removed %d: %s.' =>
        '%s changed project members, added: %3$s; removed: %5$s.',

      '%s added %d project member(s): %s.' => array(
        array(
          '%s added a member: %3$s.',
          '%s added members: %3$s.',
        ),
      ),

      '%s removed %d project member(s): %s.' => array(
        array(
          '%s removed a member: %3$s.',
          '%s removed members: %3$s.',
        ),
      ),

      '%s project hashtag(s) are already used by other projects: %s.' => array(
        'Project hashtag "%2$s" is already used by another project.',
        'Some project hashtags are already used by other projects: %2$s.',
      ),

      '%s changed project hashtag(s), added %d: %s; removed %d: %s.' =>
        '%s changed project hashtags, added: %3$s; removed: %5$s.',

      'Hashtags must contain at least one letter or number. %s '.
      'project hashtag(s) are invalid: %s.' => array(
        'Hashtags must contain at least one letter or number. The '.
        'hashtag "%2$s" is not valid.',
        'Hashtags must contain at least one letter or number. These '.
        'hashtags are invalid: %2$s.',
      ),

      '%s added %d project hashtag(s): %s.' => array(
        array(
          '%s added a hashtag: %3$s.',
          '%s added hashtags: %3$s.',
        ),
      ),

      '%s removed %d project hashtag(s): %s.' => array(
        array(
          '%s removed a hashtag: %3$s.',
          '%s removed hashtags: %3$s.',
        ),
      ),

      '%s changed %s hashtag(s), added %d: %s; removed %d: %s.' =>
        '%s changed hashtags for %s, added: %4$s; removed: %6$s.',

      '%s added %d %s hashtag(s): %s.' => array(
        array(
          '%s added a hashtag to %3$s: %4$s.',
          '%s added hashtags to %3$s: %4$s.',
        ),
      ),

      '%s removed %d %s hashtag(s): %s.' => array(
        array(
          '%s removed a hashtag from %3$s: %4$s.',
          '%s removed hashtags from %3$s: %4$s.',
        ),
      ),

      '%s pushed %d commit(s) to %s.' => array(
        array(
          '%s pushed a commit to %3$s.',
          '%s pushed %d commits to %s.',
        ),
      ),

      '%s commit(s)' => array(
        '%s commit',
        '%s commits',
      ),

      '%s added %s required legal document(s): %s.' => array(
        array(
          '%s added a required legal document: %3$s.',
          '%s added required legal documents: %3$s.',
        ),
      ),

      '%s removed %s required legal document(s) from %s: %s.' => array(
        array(
          '%s removed a required legal document from %3$s: %4$s.',
          '%s removed required legal documents from %3$s: %4$s.',
        ),
      ),

      '%s added %s required legal document(s) to %s: %s.' => array(
        array(
          '%s added a required legal document to %3$s: %4$s.',
          '%s added required legal documents to %3$s: %4$s.',
        ),
      ),
      '%s removed %s required legal document(s): %s.' => array(
        array(
          '%s removed a required legal document: %3$s.',
          '%s removed required legal documents: %3$s.',
        ),
      ),
      '%s edited %s required legal document(s),'.
        ' added %s: %s; removed %s: %s.' =>
       '%s edited required legal documents, added: %4$s; removed: %6$s.',

      '%s edited %s required legal document(s) for %s, '.
         'added %s: %s; removed %s: %s.' =>
        '%s edited required legal documents for %3$s, '.
          'added %5$s; removed %7$s.',

      '%s edited %s task(s), added %s: %s; removed %s: %s.' =>
        '%s edited tasks, added: %4$s; removed: %6$s.',

      '%s added %s task(s) for %s: %s.' => array(
        array(
          '%s added a task for %3$s: %4$s',
          '%s added tasks for %3$s: %4$s',
        ),
      ),

      '%s removed %s task(s) for %s: %s.' => array(
        array(
          '%s removed a task for %3$s: %4$s',
          '%s removed tasks for %3$s: %4$s',
       ),
      ),

      '%s edited task(s), added %s: %s; removed %s: %s.' =>
        '%s edited tasks, added: %3$s; removed: $5$s',

      '%s edited task(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited tasks for %s: added: %4$s; removed: %6$s.',


      '%s added %s task(s) to %s: %s.' => array(
        array(
          '%s added a task to %3$s: %4$s.',
          '%s added tasks to %3$s: %4$s.',
        ),
      ),

      '%s removed %s task(s) from %s: %s.' => array(
        array(
          '%s removed a task from %3$s: %4$s.',
          '%s removed tasks from %3$s: %4$s.',
        ),
      ),

      '%s edited %s task(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited tasks for %3$s, added: %5$s; removed %7$s.',

      '%s edited %s commit(s), added %s: %s; removed %s: %s.' =>
        '%s edited commits, added: %4$s; removed: %6$s.',

      '%s added %s commit(s) to %s: %s.' => array(
        array(
          '%s added a commit to %3$s: %4$s.',
          '%s added commits to %3$s: %4$s.',
        ),
      ),

      '%s removed %s commit(s) from %s: %s.' => array(
        array(
          '%s removed a commit from %3$s: %4$s.',
          '%s removed commits from %3$s: %4$s.',
        ),
      ),

      '%s edited %s commit(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited commits for %3$s, added: %5$s; removed %7$s.',

      '%s added %s revision(s): %s.' => array(
        array(
          '%s added a revision: %3$s.',
          '%s added revisions: %3$s.',
        ),
      ),

      '%s removed %s revision(s): %s.' => array(
        array(
          '%s removed a revision: %3$s.',
          '%s removed revisions: %3$s.',
        ),
      ),

      '%s edited %s revision(s), added %s: %s; removed %s: %s.' =>
        '%s edited revisions, added: %4$s; removed: %6$s.',
      '%s edited revision(s), added %s: %s; removed %s: %s.' =>
        '%s edited revision, added: %3$s; removed: %5$s.',


      '%s added %s revision(s) to %s: %s.' => array(
        array(
          '%s added a revision to %3$s: %4$s.',
          '%s added revisions to %3$s: %4$s.',
        ),
      ),

      '%s removed %s revision(s) from %s: %s.' => array(
        array(
          '%s removed a revision from %3$s: %4$s.',
          '%s removed revisions from %3$s: %4$s.',
        ),
      ),

      '%s edited %s revision(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited revisions for %3$s, added: %5$s; removed %7$s.',

      '%s edited %s project(s), added %s: %s; removed %s: %s.' =>
        '%s edited projects, added: %4$s; removed: %6$s.',

      '%s added %s project(s) to %s: %s.' => array(
        array(
          '%s added a project to %3$s: %4$s.',
          '%s added projects to %3$s: %4$s.',
        ),
      ),

      '%s removed %s project(s) from %s: %s.' => array(
        array(
          '%s removed a project from %3$s: %4$s.',
          '%s removed projects from %3$s: %4$s.',
        ),
      ),

      '%s edited %s project(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited projects for %3$s, added: %5$s; removed %7$s.',

      '%s added %s edge(s): %s.' => array(
        array(
          '%s added an edge: %3$s.',
          '%s added edges: %3$s.',
        ),
      ),

      '%s added %s edge(s) to %s: %s.' => array(
        array(
          '%s added an edge to %3$s: %4$s.',
          '%s added edges to %3$s: %4$s.',
        ),
      ),

      '%s removed %s edge(s): %s.' => array(
        array(
          '%s removed an edge: %3$s.',
          '%s removed edges: %3$s.',
        ),
      ),

      '%s removed %s edge(s) from %s: %s.' => array(
        array(
          '%s removed an edge from %3$s: %4$s.',
          '%s removed edges from %3$s: %4$s.',
        ),
      ),

      '%s edited %s edge(s), added %s: %s; removed %s: %s.' =>
       '%s edited edges, added: %4$s; removed: %6$s',

      '%s edited %s edge(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited edges for %3$s, added: %5$s; removed %7$s.',

      '%s added %s member(s) for %s: %s.' => array(
        array(
          '%s added a member for %3$s: %4$s.',
          '%s added members for %3$s: %4$s.',
        ),
      ),

      '%s removed %s member(s) for %s: %s.' => array(
        array(
          '%s removed a member for %3$s: %4$s.',
          '%s removed members for %3$s: %4$s.',
        ),
      ),

      '%d related link(s):' => array(
        'Related link:',
        'Related links:',
      ),

      'You have %d unpaid invoice(s).' => array(
        'You have an unpaid invoice.',
        'You have unpaid invoices.',
      ),

      'This server is configured with an email domain whitelist (in %s), so '.
      'only users with a verified email address at one of these %s '.
      'allowed domain(s) will be able to register an account: %s' => array(
        array(
          'This server is configured with an email domain whitelist (in %s), '.
          'so only users with a verified email address at %3$s will be '.
          'allowed to register an account.',
          'This server is configured with an email domain whitelist (in %s), '.
          'so only users with a verified email address at one of these '.
          'allowed domains will be able to register an account: %3$s',
        ),
      ),

      'Show First %s Line(s)' => array(
        'Show First Line',
        'Show First %s Lines',
      ),

      'Show First %s Block(s)' => array(
        'Show First Block',
        'Show First %s Blocks',
      ),

      "\xE2\x96\xB2 Show %s Line(s)" => array(
        "\xE2\x96\xB2 Show Line",
        "\xE2\x96\xB2 Show %s Lines",
      ),

      "\xE2\x96\xB2 Show %s Block(s)" => array(
        "\xE2\x96\xB2 Show Block",
        "\xE2\x96\xB2 Show %s Blocks",
      ),

      'Show All %s Line(s)' => array(
        'Show Line',
        'Show All %s Lines',
      ),

      'Show All %s Block(s)' => array(
        'Show Block',
        'Show All %s Blocks',
      ),

      "\xE2\x96\xBC Show %s Line(s)" => array(
        "\xE2\x96\xBC Show Line",
        "\xE2\x96\xBC Show %s Lines",
      ),

      "\xE2\x96\xBC Show %s Block(s)" => array(
        "\xE2\x96\xBC Show Block",
        "\xE2\x96\xBC Show %s Blocks",
      ),

      'Show Last %s Line(s)' => array(
        'Show Last Line',
        'Show Last %s Lines',
      ),

      'Show Last %s Block(s)' => array(
        'Show Last Block',
        'Show Last %s Blocks',
      ),

      '%s marked %s inline comment(s) as done and %s inline comment(s) as '.
      'not done.' => array(
        array(
          array(
            '%s marked an inline comment as done and an inline comment '.
            'as not done.',
            '%s marked an inline comment as done and %3$s inline comments '.
            'as not done.',
          ),
          array(
            '%s marked %s inline comments as done and an inline comment '.
            'as not done.',
            '%s marked %s inline comments as done and %s inline comments '.
            'as not done.',
          ),
        ),
      ),

      '%s marked %s inline comment(s) as done.' => array(
        array(
          '%s marked an inline comment as done.',
          '%s marked %s inline comments as done.',
        ),
      ),

      '%s marked %s inline comment(s) as not done.' => array(
        array(
          '%s marked an inline comment as not done.',
          '%s marked %s inline comments as not done.',
        ),
      ),

      'These %s object(s) will be destroyed forever:' => array(
        'This object will be destroyed forever:',
        'These objects will be destroyed forever:',
      ),

      'Are you absolutely certain you want to destroy these %s '.
      'object(s)?' => array(
        'Are you absolutely certain you want to destroy this object?',
        'Are you absolutely certain you want to destroy these objects?',
      ),

      '%s added %s owner(s): %s.' => array(
        array(
          '%s added an owner: %3$s.',
          '%s added owners: %3$s.',
        ),
      ),

      '%s removed %s owner(s): %s.' => array(
        array(
          '%s removed an owner: %3$s.',
          '%s removed owners: %3$s.',
        ),
      ),

      '%s changed %s package owner(s), added %s: %s; removed %s: %s.' =>
        '%s changed package owners, added: %4$s; removed: %6$s.',

      'Found %s book(s).' => array(
        'Found %s book.',
        'Found %s books.',
      ),

      'Found %s file(s) in project.' => array(
        'Found %s file in project.',
        'Found %s files in project.',
      ),
      'Found %s unatomized, uncached file(s).' => array(
        'Found %s unatomized, uncached file.',
        'Found %s unatomized, uncached files.',
      ),
      'Found %s file(s) to atomize.' => array(
        'Found %s file to atomize.',
        'Found %s files to atomize.',
      ),
      'Atomizing %s file(s).' => array(
        'Atomizing %s file.',
        'Atomizing %s files.',
      ),
      'Creating %s document(s).' => array(
        'Creating %s document.',
        'Creating %s documents.',
      ),
      'Deleting %s document(s).' => array(
        'Deleting %s document.',
        'Deleting %s documents.',
      ),
      'Found %s obsolete atom(s) in graph.' => array(
        'Found %s obsolete atom in graph.',
        'Found %s obsolete atoms in graph.',
      ),
      'Found %s new atom(s) in graph.' => array(
        'Found %s new atom in graph.',
        'Found %s new atoms in graph.',
      ),
      'This call takes %s parameter(s), but only %s are documented.' => array(
        array(
          'This call takes %s parameter, but only %s is documented.',
          'This call takes %s parameter, but only %s are documented.',
        ),
        array(
          'This call takes %s parameters, but only %s is documented.',
          'This call takes %s parameters, but only %s are documented.',
        ),
      ),

      '%s Passed Test(s)' => '%s Passed',
      '%s Failed Test(s)' => '%s Failed',
      '%s Skipped Test(s)' => '%s Skipped',
      '%s Broken Test(s)' => '%s Broken',
      '%s Unsound Test(s)' => '%s Unsound',
      '%s Other Test(s)' => '%s Other',

      '%s Bulk Task(s)' => array(
        '%s Task',
        '%s Tasks',
      ),

      '%s automatically subscribed target(s) were not affected: %s.' => array(
        'An automatically subscribed target was not affected: %2$s.',
        'Automatically subscribed targets were not affected: %2$s.',
      ),

      'Declined to resubscribe %s target(s) because they previously '.
      'unsubscribed: %s.' => array(
        'Delined to resubscribe a target because they previously '.
        'unsubscribed: %2$s.',
        'Declined to resubscribe targets because they previously '.
        'unsubscribed: %2$s.',
      ),

      'Added %s subscriber(s): %s.' => array(
        'Added a subscriber: %2$s.',
        'Added subscribers: %2$s.',
      ),

      'Removed %s subscriber(s): %s.' => array(
        'Removed a subscriber: %2$s.',
        'Removed subscribers: %2$s.',
      ),

      'Queued email to be delivered to %s target(s): %s.' => array(
        'Queued email to be delivered to target: %2$s.',
        'Queued email to be delivered to targets: %2$s.',
      ),

      'Queued email to be delivered to %s target(s), ignoring their '.
      'notification preferences: %s.' => array(
        'Queued email to be delivered to target, ignoring notification '.
        'preferences: %2$s.',
        'Queued email to be delivered to targets, ignoring notification '.
        'preferences: %2$s.',
      ),

      'Added %s project(s): %s.' => array(
        'Added a project: %2$s.',
        'Added projects: %2$s.',
      ),

      'Removed %s project(s): %s.' => array(
        'Removed a project: %2$s.',
        'Removed projects: %2$s.',
      ),

      'Added %s reviewer(s): %s.' => array(
        'Added a reviewer: %2$s.',
        'Added reviewers: %2$s.',
      ),

      'Added %s blocking reviewer(s): %s.' => array(
        'Added a blocking reviewer: %2$s.',
        'Added blocking reviewers: %2$s.',
      ),

      'Required %s signature(s): %s.' => array(
        'Required a signature: %2$s.',
        'Required signatures: %2$s.',
      ),

      'Started %s build(s): %s.' => array(
        'Started a build: %2$s.',
        'Started builds: %2$s.',
      ),

      'Added %s auditor(s): %s.' => array(
        'Added an auditor: %2$s.',
        'Added auditors: %2$s.',
      ),

      '%s target(s) do not have permission to see this object: %s.' => array(
        'A target does not have permission to see this object: %2$s.',
        'Targets do not have permission to see this object: %2$s.',
      ),

      'This action has no effect on %s target(s): %s.' => array(
        'This action has no effect on a target: %2$s.',
        'This action has no effect on targets: %2$s.',
      ),

      'Mail sent in the last %s day(s).' => array(
        'Mail sent in the last day.',
        'Mail sent in the last %s days.',
      ),

      '%s Day(s)' => array(
        '%s Day',
        '%s Days',
      ),
      '%s Day(s) Ago' => array(
        '%s Day Ago',
        '%s Days Ago',
      ),

      'Setting retention policy for "%s" to %s day(s).' => array(
        array(
          'Setting retention policy for "%s" to one day.',
          'Setting retention policy for "%s" to %s days.',
        ),
      ),

      'Waiting %s second(s) for lease to activate.' => array(
        'Waiting a second for lease to activate.',
        'Waiting %s seconds for lease to activate.',
      ),

      '%s changed %s automation blueprint(s), added %s: %s; removed %s: %s.' =>
        '%s changed automation blueprints, added: %4$s; removed: %6$s.',

      '%s added %s automation blueprint(s): %s.' => array(
        array(
          '%s added an automation blueprint: %3$s.',
          '%s added automation blueprints: %3$s.',
        ),
      ),

      '%s removed %s automation blueprint(s): %s.' => array(
        array(
          '%s removed an automation blueprint: %3$s.',
          '%s removed automation blueprints: %3$s.',
        ),
      ),

      'WARNING: There are %s unapproved authorization(s)!' => array(
        'WARNING: There is an unapproved authorization!',
        'WARNING: There are unapproved authorizations!',
      ),

      '%s Event(s)' => array(
        '%s Event',
        '%s Events',
      ),

      '%s Unit(s)' => array(
        '%s Unit',
        '%s Units',
      ),

      'Found %s total commit(s); updating...' => array(
        'Found %s total commit; updating...',
        'Found %s total commits; updating...',
      ),

      'Not enough process slots to schedule the other %s '.
      'repository(s) for updates yet.' => array(
        'Not enough process slots to schedule the other '.
        'repository for update yet.',
        'Not enough process slots to schedule the other %s '.
        'repositories for updates yet.',
      ),


      '%s updated %s, added %d: %s.' =>
        '%s updated %s, added: %4$s.',

      '%s updated %s, removed %s: %s.' =>
        '%s updated %s, removed: %4$s.',

      '%s updated %s, added %s: %s; removed %s: %s.' =>
        '%s updated %s, added: %4$s; removed: %6$s.',

      '%s updated %s for %s, added %d: %s.' =>
        '%s updated %s for %s, added: %5$s.',

      '%s updated %s for %s, removed %s: %s.' =>
        '%s updated %s for %s, removed: %5$s.',

      '%s updated %s for %s, added %s: %s; removed %s: %s.' =>
        '%s updated %s for %s, added: %5$s; removed; %7$s.',

      '%s updated JIRA issue(s): added %d %s; removed %d %s.' =>
        '%s updated JIRA issues: added %3$s; removed: %5$s.',

      'Permanently destroyed %s object(s).' => array(
        'Permanently destroyed %s object.',
        'Permanently destroyed %s objects.',
      ),

      '%s added %s watcher(s) for %s: %s.' => array(
        array(
          '%s added a watcher for %3$s: %4$s.',
          '%s added watchers for %3$s: %4$s.',
        ),
      ),

      '%s removed %s watcher(s) for %s: %s.' => array(
        array(
          '%s removed a watcher for %3$s: %4$s.',
          '%s removed watchers for %3$s: %4$s.',
        ),
      ),

      '%s awarded this badge to %s recipient(s): %s.' => array(
        array(
          '%s awarded this badge to recipient: %3$s.',
          '%s awarded this badge to recipients: %3$s.',
        ),
      ),

      '%s revoked this badge from %s recipient(s): %s.' => array(
        array(
          '%s revoked this badge from recipient: %3$s.',
          '%s revoked this badge from recipients: %3$s.',
        ),
      ),

      '%s awarded %s to %s recipient(s): %s.' => array(
        array(
          array(
            '%s awarded %s to recipient: %4$s.',
            '%s awarded %s to recipients: %4$s.',
          ),
        ),
      ),

      '%s revoked %s from %s recipient(s): %s.' => array(
        array(
          array(
            '%s revoked %s from recipient: %4$s.',
            '%s revoked %s from recipients: %4$s.',
          ),
        ),
      ),

      '%s invited %s attendee(s): %s.' =>
        '%s invited: %3$s.',

      '%s uninvited %s attendee(s): %s.' =>
        '%s uninvited: %3$s.',

      '%s invited %s attendee(s): %s; uninvited %s attendee(s): %s.' =>
        '%s invited: %3$s; uninvited: %5$s.',

      '%s invited %s attendee(s) to %s: %s.' =>
        '%s added invites for %3$s: %4$s.',

      '%s uninvited %s attendee(s) to %s: %s.' =>
        '%s removed invites for %3$s: %4$s.',

      '%s updated the invite list for %s, invited %s: %s; uninvited %s: %s.' =>
        '%s updated the invite list for %s, invited: %4$s; uninvited: %6$s.',

      'Restart %s build(s)?' => array(
        'Restart %s build?',
        'Restart %s builds?',
      ),

      '%s is starting in %s minute(s), at %s.' => array(
        array(
          '%s is starting in one minute, at %3$s.',
          '%s is starting in %s minutes, at %s.',
        ),
      ),

      '%s added %s auditor(s): %s.' => array(
        array(
          '%s added an auditor: %3$s.',
          '%s added auditors: %3$s.',
        ),
      ),

      '%s removed %s auditor(s): %s.' => array(
        array(
          '%s removed an auditor: %3$s.',
          '%s removed auditors: %3$s.',
        ),
      ),

      '%s edited %s auditor(s), removed %s: %s; added %s: %s.' =>
        '%s edited auditors, removed: %4$s; added: %6$s.',
      '%s edited %s auditor(s) for %s, removed %s: %s; added %s: %s.' =>
        '%s edited auditors for %3$s, removed: %5$s; added: %7$s.',

      '%s accepted this revision as %s reviewer(s): %s.' =>
        '%s accepted this revision as: %3$s.',

      '%s added %s merchant manager(s): %s.' => array(
        array(
          '%s added a merchant manager: %3$s.',
          '%s added merchant managers: %3$s.',
        ),
      ),

      '%s added %s merchant manager(s) to %s: %s.' => array(
        array(
          '%s added a merchant manager to %3$s: %4$s.',
          '%s added merchant managers to %3$s: %4$s.',
        ),
      ),

      '%s removed %s merchant manager(s): %s.' => array(
        array(
          '%s removed a merchant manager: %3$s.',
          '%s removed merchant managers: %3$s.',
        ),
      ),

      '%s removed %s merchant manager(s) from %s: %s.' => array(
        array(
          '%s removed a merchant manager from %s: %4$s.',
          '%s removed merchant managers from %s: %4$s.',
        ),
      ),
      '%s edited %s merchant manager(s), added %s: %s; removed %s: %s.' =>
        '%s edited merchant managers, added: %4$s; removed: %6$s.',
      '%s edited %s merchant manager(s) for %s, '.
          'added %s: %s; removed %s: %s.' =>
        '%s edited merchant managers for %3$s, added: %5$s; removed: %7$s.',

      '%s added %s account manager(s): %s.' => array(
        array(
          '%s added an account manager: %3$s.',
          '%s added account managers: %3$s.',
        ),
      ),

      '%s added %s account manager(s) to %s: %s.' => array(
        array(
          '%s added an account manager to %3$s: %4$s.',
          '%s added account managers to %3$s: %4$s.',
        ),
      ),

      '%s removed %s account manager(s): %s.' => array(
        array(
          '%s removed an account manager: %3$s.',
          '%s removed account managers: %3$s.',
        ),
      ),

      '%s removed %s account manager(s) from %s: %s.' => array(
        array(
          '%s removed an account manager from %3$s: %4$s.',
          '%s removed account managers from %3$s: %4$s.',
        ),
      ),

      '%s edited %s account manager(s), added %s: %s; removed %s: %s.' =>
       '%s edited account managers, added: %4$s; removed: %6$s;',

      '%s edited %s account manager(s) for %s, added %s: %s; removed %s: %s.' =>
       '%s edited account managers for %3$s, added: %5$s; removed: %7$s;',


      'You are about to apply a bulk edit which will affect '.
      '%s object(s).' => array(
        'You are about to apply a bulk edit to a single object.',
        'You are about to apply a bulk edit which will affect '.
        '%s objects.',
      ),

      'Destroyed %s credential(s) of type "%s".' => array(
        'Destroyed one credential of type "%2$s".',
        'Destroyed %s credentials of type "%s".',
      ),

      '%s notification(s) about objects which no longer exist or which '.
      'you can no longer see were discarded.' => array(
        'One notification about an object which no longer exists or which '.
        'you can no longer see was discarded.',
        '%s notifications about objects which no longer exist or which '.
        'you can no longer see were discarded.',
      ),

      'This draft revision will be sent for review once %s '.
      'build(s) pass: %s.' => array(
        'This draft revision will be sent for review once this build '.
        'passes: %2$s.',
        'This draft revision will be sent for review once these builds '.
        'pass: %2$s.',
      ),

      'This factor recently issued a challenge to a different login '.
      'session. Wait %s second(s) for the code to cycle, then try '.
      'again.' => array(
        'This factor recently issued a challenge to a different login '.
        'session. Wait %s second for the code to cycle, then try '.
        'again.',
        'This factor recently issued a challenge to a different login '.
        'session. Wait %s seconds for the code to cycle, then try '.
        'again.',
      ),

      'This factor recently issued a challenge for a different '.
      'workflow. Wait %s second(s) for the code to cycle, then try '.
      'again.' => array(
        'This factor recently issued a challenge for a different '.
        'workflow. Wait %s second for the code to cycle, then try '.
        'again.',
        'This factor recently issued a challenge for a different '.
        'workflow. Wait %s seconds for the code to cycle, then try '.
        'again.',
      ),


      'This factor recently issued a challenge which has expired. '.
      'A new challenge can not be issued yet. Wait %s second(s) for '.
      'the code to cycle, then try again.' => array(
        'This factor recently issued a challenge which has expired. '.
        'A new challenge can not be issued yet. Wait %s second for '.
        'the code to cycle, then try again.',
        'This factor recently issued a challenge which has expired. '.
        'A new challenge can not be issued yet. Wait %s seconds for '.
        'the code to cycle, then try again.',
      ),

      'You recently provided a response to this factor. Responses '.
      'may not be reused. Wait %s second(s) for the code to cycle, '.
      'then try again.' => array(
        'You recently provided a response to this factor. Responses '.
        'may not be reused. Wait %s second for the code to cycle, '.
        'then try again.',
        'You recently provided a response to this factor. Responses '.
        'may not be reused. Wait %s seconds for the code to cycle, '.
        'then try again.',
      ),

      'View All %d Subscriber(s)' => array(
        'View Subscriber',
        'View All %d Subscribers',
      ),

      'You are currently editing %s inline comment(s) on this '.
      'revision.' => array(
        'You are currently editing an inline comment on this revision.',
        'You are currently editing %s inline comments on this revision.',
      ),

      'These %s inline comment(s) will be saved and published.' => array(
        'This inline comment will be saved and published.',
        'These inline comments will be saved and published.',
      ),

      'Delayed %s task(s).' => array(
        'Delayed %s task.',
        'Delayed %s tasks.',
      ),

      'Freed %s task lease(s).' => array(
        'Freed %s task lease.',
        'Freed %s task leases.',
      ),

      'Cancelled %s task(s).' => array(
        'Cancelled %s task.',
        'Cancelled %s tasks.',
      ),

      'Queued %s task(s) for retry.' => array(
        'Queued %s task for retry.',
        'Queued %s tasks for retry.',
      ),

      'Reprioritized %s task(s).' => array(
        'Reprioritized one task.',
        'Reprioritized %s tasks.',
      ),

      'Executed %s task(s).' => array(
        'Executed %s task.',
        'Executed %s tasks.',
      ),

      '%s modified %s attached file(s): %s.' => array(
        array(
          '%s modified an attached file: %3$s.',
          '%s modified attached files: %3$s.',
        ),
      ),

      '%s attached %s referenced file(s): %s.' => array(
        array(
          '%s attached a referenced file: %3$s.',
          '%s attached referenced files: %3$s.',
        ),
      ),

      '%s removed %s attached file(s): %s.' => array(
        array(
          '%s removed an attached file: %3$s.',
          '%s removed attached files: %3$s.',
        ),
      ),
      '%s updated %s attached file(s), added %s: %s; removed %s: %s.' =>
        '%s updated attached files, added: %4$s; removed: %6$s.',

      '%s updated %s attached file(s), added %s: %s; modified %s: %s.' =>
        '%s updated attached files, added: %4$s; modified: %6$s.',

      '%s updated %s attached file(s), '.
      'added %s: %s; removed %s: %s; modified %s: %s.' =>
        '%s updated attached files, added: %4$s; removed: %6$s; '.
        'modified: %8$s.',

      '%s updated %s attached file(s), removed %s: %s; modified %s: %s.' =>
        '%s updated attached files, removed %4$s; modified: %6$s.',

      '%s added %d JIRA issue(s): %s.' =>
      array(
        array(
            '%s added a JIRA issue: %3$s.',
            '%s added JIRA issues: %3$s.',
        ),
      ),
      '%s removed %d JIRA issue(s): %s.' =>
      array(
        array(
            '%s removed a JIRA issue: %3$s.',
            '%s removed JIRA issues: %3$s.',
        ),
      ),
      '%s added %s blocking reviewer(s) for %s: %s.' =>
      array(
        array(
            '%s added a blocking reviewer for %3$s: %4$s.',
            '%s added blocking reviewers for %3$s: %4$s.',
        ),
      ),
      '%s added %s blocking reviewer(s): %s.' =>
      array(
        array(
            '%s added a blocking reviewer: %3$s.',
            '%s added blocking reviewers: %3$s.',
        ),
      ),
      '%s changed %s blocking reviewer(s), added %s: %s; removed %s: %s.' =>
       '%s changed blocking reviewers, added: %4$s, removed: %6$s',
      '%s changed %s blocking reviewer(s) for %s, '.
         'added %s: %s; removed %s: %s.' =>
       '%s changed blocking reviewers for %3$s, added: %5$s, removed: %7$s',

      '%s removed %s blocking reviewer(s) for %s: %s.' => array(
        array(
            '%s removed a blocking reviewer for %3$s: %4$s.',
            '%s removed blocking reviewers for %3$s: %4$s.',
        ),
      ),
      '%s removed %s blocking reviewer(s): %s.' =>
      array(
        array(
            '%s removed a blocking reviewer: %3$s.',
            '%s removed blocking reviewers: %3$s.',
        ),
      ),
      '%s added %s auditor(s) for %s: %s.' =>
      array(
        array(
            '%s added an auditor for %3$s: %4$s.',
            '%s added auditors for %3$s: %4$s.',
        ),
      ),
      '%s removed %s auditor(s) for %s: %s.' =>
      array(
        array(
            '%s removed an auditor for %3$s: %4$s.',
            '%s removed auditors for %3$s: %4$s.',
        ),
      ),
      '%s attached %s file(s): %s.' =>
      array(
        array(
            '%s attached a file: %3$s.',
            '%s attached files: %3$s.',
        ),
      ),

      'Used on %s and %s other active column(s).' =>
      array(
        array(
            'Used on %s and another active column.',
            'Used on %s and %s other active columns.',
        ),
      ),
      'Used on %s and %s other column(s).' =>
      array(
        array(
            'Used on %s and another column.',
            'Used on %s and %s other columns.',
        ),
      ),
      '%s moved this task on %s board(s): %s.' =>
      array(
        array(
            '%s moved this task on a board: %3$s.',
            '%s moved this task on %s boards: %s.',
        ),
      ),
      '%s moved %s on %s board(s): %s.' =>
      array(
        array(
          array(
              '%s moved %s on a board: %4$s.',
              '%s moved %s on %s boards: %4$s.',
          ),
        ),
      ),
      'Found %s modified file(s) (of %s total).' => array(
          'Found %s modified file (of %s total).',
          'Found %s modified files (of %s total).',
      ),
     'Really delete these %s audit(s)? '.
     'They will be permanently deleted and can not be recovered.' => array(
           'Really delete this audit? '.
           'It will be permanently deleted and can not be recovered.',
           'Really delete these %s audits? '.
           'They will be permanently deleted and can not be recovered.',
      ),
     'You denied this request. Wait %s second(s) to try again.' => array(
          'You denied this request. Wait %s second to try again.',
          'You denied this request. Wait %s seconds to try again.',
      ),
      'Found %s account(s) to refresh.' => array(
          'Found %s account to refresh.',
          'Found %s accounts to refresh.',
      ),
      'Reset %s action(s).' => array(
          'Reset %s action.',
          'Reset %s actions.',
      ),
      'Rebuilding %d resource source(s).' => array(
          'Rebuilding %d resource source.',
          'Rebuilding %d resource sources.',
      ),
      'Detected %s serious issue(s) with the schemata.' => array(
          'Detected a serious issue with the schemata.',
          'Detected %s serious issues with the schemata.',
      ),
      'Detected %s warning(s) with the schemata.' =>
      array(
          'Detected a warning with the schemata.',
          'Detected %s warnings with the schemata.',
      ),
      'This draft revision will not be submitted for review '.
      'because %s build(s) failed: %s.' => array(
          'This draft revision will not be submitted for review '.
          'because a build failed: %2$s.',
          'This draft revision will not be submitted for review '.
          'because %s builds failed: %s.',
      ),
      'Rebuilding %s changeset(s) for diff ID %d.' => array(
          'Rebuilding %s changeset for diff ID %d.',
          'Rebuilding %s changesets for diff ID %d.',
      ),
      'This file has %d collapsed inline comment(s).' => array(
          'This file has one collapsed inline comment.',
          'This file has %d collapsed inline comments.',
      ),
      'This file took too long to load from the repository '.
      '(more than %s second(s)).' => array(
          'This file took too long to load from the repository '.
          '(more than %s second).',
          'This file took too long to load from the repository '.
          '(more than %s seconds).',
      ),
      'Acquired read lock after %s second(s).' => array(
          'Acquired read lock after %s second.',
          'Acquired read lock after %s seconds.',
      ),
      'Failed to acquire read lock after waiting %s second(s). '.
      'You may be able to retry later. (%s)' =>
      array(
          'Failed to acquire read lock after waiting %s second. '.
          'You may be able to retry later. (%s)',
          'Failed to acquire read lock after waiting %s seconds. '.
          'You may be able to retry later. (%s)',
      ),
      'Acquired write lock after %s second(s).' =>
      array(
          'Acquired write lock after %s second.',
          'Acquired write lock after %s seconds.',
      ),
      'Failed to acquire write lock after waiting %s second(s). '.
      'You may be able to retry later. (%s)' =>
      array(
          'Failed to acquire write lock after waiting %s second. '.
          'You may be able to retry later. (%2$s)',
          'Failed to acquire write lock after waiting %s seconds. '.
          'You may be able to retry later. (%s)',
      ),
      'This process will spend %s more second(s) attempting to recover, '.
      'then give up.' => array(
          'This process will spend %s more second attempting to recover, '.
          'then give up.',
          'This process will spend %s more seconds attempting to recover, '.
          'then give up.',
      ),
      'Waiting %s second(s) for resource to activate.' =>
      array(
          'Waiting %s second for resource to activate.',
          'Waiting %s seconds for resource to activate.',
      ),
      'Processed %s file(s) with no errors.' =>
      array(
          'Processed %s file with no errors.',
          'Processed %s files with no errors.',
      ),
      'Failed to fetch remote URI "%s" after '.
      'following %s redirect(s) (%s): %s' =>
      array(
        array(
            'Failed to fetch remote URI "%s" after'.
            ' following a redirect (%3$s): %4$s',
            'Failed to fetch remote URI "%s" after'.
            ' following %s redirects (%s): %s',
        ),
      ),
      'Really abort %s build(s)?' => array(
          'Really abort a build?',
          'Really abort %s builds?',
      ),
      'Really pause %s build(s)?' => array(
          'Really pause a build?',
          'Really pause %s builds?',
      ),
      'Really restart %s build(s)?' => array(
          'Really restart a build?',
          'Really restart %s builds?',
      ),
      'Really resume %s build(s)?' => array(
          'Really resume a build?',
          'Really resume %s builds?',
      ),
      '%s target(s) are invalid or of the wrong type: %s.' => array(
          '%s target is invalid or of the wrong type: %s.',
          '%s targets are invalid or of the wrong type: %s.',
      ),
      '%s target(s) could not be loaded: %s.' =>
      array(
          '%s target could not be loaded: %s.',
          '%s targets could not be loaded: %s.',
      ),
      'Unable to retrieve profile: profiler stack is not empty. The stack '.
      'has %s frame(s); the final frame has type "%s" and key "%s".' =>
      array(
          'Unable to retrieve profile: profiler stack is not empty. The '.
          'stack has %s frame; the final frame has type "%s" and key "%s".',
          'Unable to retrieve profile: profiler stack is not empty. The '.
          'stack has %s frames; the final frame has type "%s" and key "%s".',
      ),
      '%s document(s) are already signed: %s.' =>
      array(
          '%s document is already signed: %s.',
          '%s documents are already signed: %s.',
      ),
      '%s detached %s file(s): %s.' =>
      array(
        array(
            '%s detached a file: %3$s.',
            '%s detached files: %3$s.',
        ),
      ),
      'Respecting "%s" or minimum poll delay: waiting '.
      'for %s second(s) to poll GitHub.' =>
      array(
        array(
            'Respecting "%s" or minimum poll delay: waiting '.
            'for %s second to poll GitHub.',
            'Respecting "%s" or minimum poll delay: waiting '.
            'for %s seconds to poll GitHub.',
        ),
      ),
      'Respecting "%s": waiting for %s second(s) to poll GitHub.' =>
      array(
        array(
            'Respecting "%s": waiting for %s second to poll GitHub.',
            'Respecting "%s": waiting for %s seconds to poll GitHub.',
        ),
      ),
      'You can view this account because you control '.
      '%d merchant(s) it has a relationship with: %s.' =>
      array(
          'You can view this account because you control '.
          'a merchant it has a relationship with: %s.',
          'You can view this account because you control '.
          '%d merchants it has a relationship with: %s.',
      ),
      'Used on %s active column(s).' =>
      array(
          'Used on %s active column.',
          'Used on %s active columns.',
      ),
      'Used on %s column(s).' =>
      array(
          'Used on %s column.',
          'Used on %s columns.',
      ),
      'Repository "%s" is not due for an update for %s second(s).' =>
      array(
        array(
            'Repository "%s" is not due for an update for %s second.',
            'Repository "%s" is not due for an update for %s seconds.',
        ),
      ),
      'Sleeping for %s more second(s)...' =>
      array(
          'Sleeping for %s more second...',
          'Sleeping for %s more seconds...',
      ),
      'Importing %s commit(s) at low priority ("PRIORITY_IMPORT") '.
      'because this repository is still importing.' =>
      array(
          'Importing %s commit at low priority ("PRIORITY_IMPORT") '.
          'because this repository is still importing.',
          'Importing %s commits at low priority ("PRIORITY_IMPORT") '
          .'because this repository is still importing.',
      ),
      'Importing %s commit(s) at normal priority ("PRIORITY_COMMIT").' =>
      array(
          'Importing %s commit at normal priority ("PRIORITY_COMMIT").',
          'Importing %s commits at normal priority ("PRIORITY_COMMIT").',
      ),
      'Found %s surplus local ref(s) to delete.' =>
      array(
          'Found %s surplus local ref to delete.',
          'Found %s surplus local refs to delete.',
      ),
      'Patch generation took longer than configured limit ("%s")'.
      ' of %s second(s).' =>
      array(
        array(
            'Patch generation took longer than configured limit ("%s") '.
            'of %s second.',
            'Patch generation took longer than configured limit ("%s") '.
            'of %s seconds.',
        ),
      ),
      'Indexing %s object(s).' =>
      array(
          'Indexing %s object.',
          'Indexing %s objects.',
      ),
      'Updated search indexes for %s document(s).' =>
      array(
          'Updated search indexes for %s document.',
          'Updated search indexes for %s documents.',
      ),
      'Queued %s document(s) for background indexing.' =>
      array(
          'Queued %s document for background indexing.',
          'Queued %s documents for background indexing.',
      ),
      'Forced search index updates for %s document(s).' =>
      array(
          'Forced search index updates for %s document.',
          'Forced search index updates for %s documents.',
      ),
      'Preparing to hibernate for %s second(s).' =>
      array(
          'Preparing to hibernate for %s second.',
          'Preparing to hibernate for %s seconds.',
      ),
      'Daemon was idle for more than %s second(s), scaling pool down.' =>
      array(
          'Daemon was idle for more than %s second, scaling pool down.',
          'Daemon was idle for more than %s seconds, scaling pool down.',
      ),
      'Waiting %s second(s) to restart process.' =>
      array(
          'Waiting %s second to restart process.',
          'Waiting %s seconds to restart process.',
      ),
      'Process is preparing to hibernate for %s second(s).' =>
      array(
          'Process is preparing to hibernate for %s second.',
          'Process is preparing to hibernate for %s seconds.',
      ),
      'Pool "%s" is exiting, with %s daemon(s) remaining.' =>
      array(
        array(
            'Pool "%s" is exiting, with %s daemon remaining.',
            'Pool "%s" is exiting, with %s daemons remaining.',
        ),
      ),
      'Autoscale pool "%s" scaled down to %s daemon(s).' =>
      array(
        array(
            'Autoscale pool "%s" scaled down to %s daemon.',
            'Autoscale pool "%s" scaled down to %s daemons.',
        ),
      ),
      'Query timed out after %s second(s)!' =>
      array(
          'Query timed out after %s second!',
          'Query timed out after %s seconds!',
      ),
      'Failed to write %d byte(s) to file "%s".' =>
      array(
          'Failed to write %d byte to file "%2$s".',
          'Failed to write %d bytes to file "%s".',
      ),
      'Failed to write %d byte(s) to "%s".' =>
      array(
          'Failed to write %d byte to "%2$s".',
          'Failed to write %d bytes to "%s".',
      ),
      'This lock was most recently acquired by '.
      'a process (%s) %s second(s) ago.' =>
      array(

        array(
            'This lock was most recently acquired by '.
            'a process (%s) %s second ago.',
            'This lock was most recently acquired by '.
            'a process (%s) %s seconds ago.',
        ),
      ),
      'This lock was released %s second(s) ago.' =>
      array(
          'This lock was released %s second ago.',
          'This lock was released %s seconds ago.',
      ),
      'Skipped %s document(s) which have not updated '.
      'since they were last indexed.' =>  array(
        'Skipped %s document which has not updated '.
        'since it was last indexed.',
        'Skipped %s documents which have not updated '.
        'since they were last indexed.',
      ),
      "Found %s adjustment(s) to apply, detailed above.\n\n".
          "You can review adjustments in more detail from the web interface, ".
          "in Config > Database Status. To better understand the adjustment ".
          "workflow, see \"Managing Storage Adjustments\" in the ".
          "documentation.\n\n".
          "MySQL needs to copy table data to make some adjustments, so these ".
          "migrations may take some time." =>
      array(
        "Found %s adjustment to apply, detailed above.\n\n".
        "You can review adjustments in more detail from the web interface, ".
        "in Config > Database Status. To better understand the adjustment ".
        "workflow, see \"Managing Storage Adjustments\" in the ".
        "documentation.\n\n".
        "MySQL needs to copy table data to make some adjustments, so these ".
        "migrations may take some time.",
        "Found %s adjustments to apply, detailed above.\n\n".
        "You can review adjustments in more detail from the web interface, ".
        "in Config > Database Status. To better understand the adjustment ".
        "workflow, see \"Managing Storage Adjustments\" in the ".
        "documentation.\n\n".
        "MySQL needs to copy table data to make some adjustments, so these ".
        "migrations may take some time.",
      ),
      'File alternate text must not be longer than %s character(s).' => array(
         'File alternate text must not be longer than %s character.',
         'File alternate text must not be longer than %s characters.',
      ),
      'File names must not be longer than %s character(s).' => array(
         'File names must not be longer than %s character.',
         'File names must not be longer than %s characters.',
      ),
      'Queue names must not be longer than %s character(s).' => array(
         'Queue names must not be longer than %s character.',
         'Queue names must not be longer than %s characters.',
      ),
      'Source names must not be longer than %s character(s).' => array(
         'Source names must not be longer than %s character.',
         'Source names must not be longer than %s characters.',
      ),
      'Mock image names must not be longer than %s character(s).' => array(
         'Mock image names must not be longer than %s character.',
         'Mock image names must not be longer than %s characters.',
      ),
      'Mock names must not be longer than %s character(s).' => array(
         'Mock names must not be longer than %s character.',
         'Mock names must not be longer than %s characters.',
      ),
      'Project names must not be longer than %s character(s).' => array(
         'Project names must not be longer than %s character.',
         'Project names must not be longer than %s characters.',
      ),
      'Scaling pool "%s" up to %s daemon(s).' => array(
        array(
           'Scaling pool "%s" up to %s daemon.',
           'Scaling pool "%s" up to %s daemons.',
        ),
      ),
      'The current configuration has these %d value(s):' => array(
        'The current configuration has this value:',
        'The current configuration has these values',
      ),
      'To update these %d value(s), '.
      'run these command(s) from the command line:' => array(
        'To update this value, run this command from the command line:',
        'To update these values, run these commands from the command line:',
      ),
      '%s ERROR(S)' => array('%s ERROR', '%s ERRORS'),
      '%s Lines' => array('%s Line', '%s Lines'),
      "# Client already read from service (%s bytes), ".
      "unable to retry.\n" => array(
        "# Client already read from service (%s byte), unable to retry.\n",
        "# Client already read from service (%s bytes), unable to retry.\n",
      ),
      "# Client already wrote to service (%s bytes), ".
      "unable to retry.\n" => array(
        "# Client already wrote to service (%s byte), unable to retry.\n",
        "# Client already wrote to service (%s bytes), unable to retry.\n",
      ),
      'Found %s affected atoms.' => array(
        'Found %s affected atom.',
        'Found %s affected atoms.',
      ),
      'Function "%s" expects %s or more argument(s), '.
      'but only %s argument(s) were provided.' => array(
        array(
          array(
            'Function "%s" expects %s or more arguments, '.
            'but only %s argument was provided',
            'Function "%s" expects %s or more arguments, '.
            'but only %s arguments were provided',
          ),
        ),
      ),
      'Function "%s" expects %s argument(s), '.
      'but %s argument(s) were provided.' => array(
        array(
          array(
            'Function "%s" expects %s argument, but %s argument was provided.',
            'Function "%s" expects %s argument, '.
            'but %s arguments were provided.',
          ),
          array(
            'Function "%s" expects %s arguments, but %s argument was provided.',
            'Function "%s" expects %s arguments, '.
            'but %s arguments were provided.',
          ),
        ),
      ),
      'Function "%s" expects at least %s argument(s), '.
      'but only %s argument(s) were provided.' => array(
        array(
          array(
            'Function "%s" expects at least %s arguments, '.
            'but only %s argument was provided.',
            'Function "%s" expects at least %s arguments, '.
            'but only %s arguments were provided.',
          ),
        ),
      ),
      'Processed %s file(s), encountered %s error(s).' => array(
        array(
          'Processed %s file, encountered %s error.',
          'Processed %s file, encountered %s errors.',
        ),
        array(
          'Processed %s files, encountered %s error.',
          'Processed %s files, encountered %s errors.',
        ),
      ),
      '%s empty logs are hidden.' => array(
        '%s empty log is hidden',
        '%s empty logs are hidden.',
      ),
      '%s changed file(s), attached %s: %s; detached %s: %s.' =>
        '%s changed files, attached: %3$s; detached: %5$s.',

      '%s changed file(s) for %s, attached %d: %s; detached %d: %s' =>
        '%s changed files for %s: attached: %4$s; detached %6$s',

      '%s attached %d file(s) of %s: %s' => array(
        array(
          '%s attached a file of %3$s: %4$s',
          '%s attached files of %3$s: %4$s',
        ),
      ),
      '%s detached %d file(s) of %s: %s' => array(
        array(
          '%s detached a file of %3$s: %4$s',
          '%s detached files of %3$s: %4$s',
        ),
      ),
      'This key has %s remaining API request(s), '.
      'limit resets in %s second(s).' => array(
        array(
          'This key has %s remaining API request, limit resets in %s second.',
          'This key has %s remaining API request, '.
          'limit resets in %s seconds.',
        ),
        array(
          'This key has %s remaining API requests, limit resets in %s second.',
          'This key has %s remaining API requests, limit resets in %s seconds.',
        ),
      ),
      'Set API poll TTL to +%s second(s) (%s second(s) from now).' => array(
        array(
          'Set API poll TTL to +%s second (%s second from now).',
          'Set API poll TTL to +%s second (%s seconds from now).',
        ),
        array(
          'Set API poll TTL to +%s seconds (%s second from now).',
          'Set API poll TTL to +%s seconds (%s seconds from now).',
        ),
      ),
      '%s changed %s ignored attribute(s), added %s: %s; removed %s: %s.' =>
        '%s changed ignored attributes, added: %4$s; removed: %6$s.',

      '%s changed %s ignored attribute(s), added %s: %s.' =>
        '%s changed ignored attributes, added: %4$s.',
      '%s changed %s ignored attribute(s), removed %s: %s.' =>
        '%s changed ignored attributes, removed: %4$s.',
      '%s edited member(s), added %s: %s; removed %s: %s.' =>
        '%s edited members, added: %3$s; removed: %5$s.',
      '%s edited member(s) for %s, added %s: %s; removed %s: %s.' =>
        '%s edited members for %s, added: %4$s; removed: %6$s.',
      'Scheduling repository "%s" with an update window of %s second(s). '.
      'Last update was %s second(s) ago.' =>
      array(
          array(
            array(
              'Scheduling repository "%s" with an update window of '.
              '%s second. Last update was %s second ago.',
              'Scheduling repository "%s" with an update window of '.
              '%s second. Last update was %s seconds ago.',
            ),
            array(
              'Scheduling repository "%s" with an update window of '.
              '%s seconds. Last update was %s second ago.',
              'Scheduling repository "%s" with an update window of '.
              '%s seconds. Last update was %s seconds ago.',
            ),
        ),
      ),
      'Scheduling repository "%s" for an update '.
      '(%s seconds overdue).' => array(
        array(
          'Scheduling repository "%s" for an update (%s second overdue).',
          'Scheduling repository "%s" for an update (%s seconds overdue).',
        ),
      ),
      'Based on activity in repository "%s", considering a wait of '.
      '%s seconds before update.' => array(
        array(
          'Based on activity in repository "%s", considering a wait of '
          .'%s second before update.',
          'Based on activity in repository "%s", considering a wait of '
          .'%s seconds before update.',
        ),
      ),
      'Examined %s commits already in the correct state.' => array(
        'Examined %s commit already in the correct state.',
        'Examined %s commits already in the correct state.',
      ),
      'Found %s feed storie(s).' => array(
        'Found %s feed story.',
        'Found %s feed stories.',
      ),
      'Destroyed %s feed storie(s).' => array(
        'Destroyed %s feed story.',
        'Destroyed %s feed stories.',
      ),
      'Done, compacted %s edge transactions.' => array(
        'Done, compacted %s edge transaction.',
        'Done, compacted %s edge transactions.',
      ),
      '%d line(s)' => array(
        '%d line',
        '%d line(s)',
      ),
      'Adjusted **%s** create statements and **%s** use statements.' => array(
        array(
          'Adjusted **%s** create statement and **%s** use statement.',
          'Adjusted **%s** create statement and **%s** use statements.',
        ),
        array(
          'Adjusted **%s** create statements and **%s** use statement.',
          'Adjusted **%s** create statements and **%s** use statements.',
        ),
      ),
      'Analyzed %d table(s).' => array(
        'Analyzed %d table.',
        'Analyzed %d tables.',
      ),
      'During the last %s second(s) spent waiting for the lock, '.
      'more than %s other process(es) acquired it, '.
      'so this is likely a bottleneck. '.
      'Use "bin/lock log --name %s" to review log activity.' => array(
          'During the last second spent waiting for the lock, '.
          'more than %2$s other processes acquired it, '.
          'so this is likely a bottleneck. '.
          'Use "bin/lock log --name %3$s" to review log activity.',
          'During the last %s seconds spent waiting for the lock, '.
          'more than %s other processes acquired it, '.
          'so this is likely a bottleneck. '.
          'Use "bin/lock log --name %s" to review log activity.',
      ),
      'During the last %s second(s) spent waiting for the lock, '.
      '%s other process(es) acquired it, '.
      'so this is likely a bottleneck. '.
      'Use "bin/lock log --name %s" to review log activity.' => array(
          'During the last second spent waiting for the lock, '.
          '%2$s other processes acquired it, '.
          'so this is likely a bottleneck. '.
          'Use "bin/lock log --name %3$s" to review log activity.',
          'During the last %s seconds spent waiting for the lock, '.
          '%s other processes acquired it, '.
          'so this is likely a bottleneck. '.
          'Use "bin/lock log --name %s" to review log activity.',
      ),
      '%s unread messages.' => array(
        '%s unread message.',
        '%s unread messages.',
      ),
      '%s unread notifications.' => array(
        '%s unread notification.',
        '%s unread notifications.',
      ),
      '%s unresolved issues.' => array(
        '%s unresolved issue.',
        '%s unresolved issues.',
      ),
      'Refreshing token, current token expires in %s seconds.' => array(
        'Refreshing token, current token expires in %s second.',
        'Refreshing token, current token expires in %s seconds.',
      ),
      'Refreshed token, new token expires in %s seconds.' => array(
        'Refreshed token, new token expires in %s second.',
        'Refreshed token, new token expires in %s seconds.',
      ),
      '%s Write [%s bytes]' => array(
        array(
          '%s Write [%s byte]',
          '%s Write [%s bytes]',
        ),
      ),
      '%s Read [%s bytes]' => array(
        array(
          '%s Read [%s byte]',
          '%s Read [%s bytes]',
        ),
      ),
    );
  }
}
