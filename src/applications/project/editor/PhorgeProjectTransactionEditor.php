<?php

final class PhorgeProjectTransactionEditor
  extends PhorgeApplicationTransactionEditor {

  private $isMilestone;

  private function setIsMilestone($is_milestone) {
    $this->isMilestone = $is_milestone;
    return $this;
  }

  public function getIsMilestone() {
    return $this->isMilestone;
  }

  public function getEditorApplicationClass() {
    return 'PhorgeProjectApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Projects');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this project.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_EDGE;
    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;
    $types[] = PhorgeTransactions::TYPE_JOIN_POLICY;

    return $types;
  }

  protected function validateAllTransactions(
    PhorgeLiskDAO $object,
    array $xactions) {

    $errors = array();

    // Prevent creating projects which are both subprojects and milestones,
    // since this does not make sense, won't work, and will break everything.
    $parent_xaction = null;
    foreach ($xactions as $xaction) {
      switch ($xaction->getTransactionType()) {
        case PhorgeProjectParentTransaction::TRANSACTIONTYPE:
        case PhorgeProjectMilestoneTransaction::TRANSACTIONTYPE:
          if ($xaction->getNewValue() === null) {
            continue 2;
          }

          if (!$parent_xaction) {
            $parent_xaction = $xaction;
            continue 2;
          }

          $errors[] = new PhorgeApplicationTransactionValidationError(
            $xaction->getTransactionType(),
            pht('Invalid'),
            pht(
              'When creating a project, specify a maximum of one parent '.
              'project or milestone project. A project can not be both a '.
              'subproject and a milestone.'),
            $xaction);
          break 2;
      }
    }

    $is_milestone = $this->getIsMilestone();

    $is_parent = $object->getHasSubprojects();

    foreach ($xactions as $xaction) {
      switch ($xaction->getTransactionType()) {
        case PhorgeTransactions::TYPE_EDGE:
          $type = $xaction->getMetadataValue('edge:type');
          if ($type != PhorgeProjectProjectHasMemberEdgeType::EDGECONST) {
            break;
          }

          if ($is_parent) {
            $errors[] = new PhorgeApplicationTransactionValidationError(
              $xaction->getTransactionType(),
              pht('Invalid'),
              pht(
                'You can not change members of a project with subprojects '.
                'directly. Members of any subproject are automatically '.
                'members of the parent project.'),
              $xaction);
          }

          if ($is_milestone) {
            $errors[] = new PhorgeApplicationTransactionValidationError(
              $xaction->getTransactionType(),
              pht('Invalid'),
              pht(
                'You can not change members of a milestone. Members of the '.
                'parent project are automatically members of the milestone.'),
              $xaction);
          }
          break;
      }
    }

    return $errors;
  }

  protected function willPublish(PhorgeLiskDAO $object, array $xactions) {
    // NOTE: We're using the omnipotent user here because the original actor
    // may no longer have permission to view the object.
    return id(new PhorgeProjectQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withPHIDs(array($object->getPHID()))
      ->needAncestorMembers(true)
      ->executeOne();
  }

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function getMailSubjectPrefix() {
    return pht('[Project]');
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    return array(
      $this->getActingAsPHID(),
    );
  }

  protected function getMailCc(PhorgeLiskDAO $object) {
    return array();
  }

  public function getMailTagsMap() {
    return array(
      PhorgeProjectTransaction::MAILTAG_METADATA =>
        pht('Project name, hashtags, icon, image, or color changes.'),
      PhorgeProjectTransaction::MAILTAG_MEMBERS =>
        pht('Project membership changes.'),
      PhorgeProjectTransaction::MAILTAG_WATCHERS =>
        pht('Project watcher list changes.'),
      PhorgeProjectTransaction::MAILTAG_OTHER =>
        pht('Other project activity not listed above occurs.'),
    );
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new ProjectReplyHandler())
      ->setMailReceiver($object);
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $name = $object->getName();

    return id(new PhorgeMetaMTAMail())
      ->setSubject("{$name}");
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);

    $uri = '/project/profile/'.$object->getID().'/';
    $body->addLinkSection(
      pht('PROJECT DETAIL'),
      PhorgeEnv::getProductionURI($uri));

    return $body;
  }

  protected function shouldPublishFeedStory(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function supportsSearch() {
    return true;
  }

  protected function applyFinalEffects(
    PhorgeLiskDAO $object,
    array $xactions) {

    $materialize = false;
    $new_parent = null;
    foreach ($xactions as $xaction) {
      switch ($xaction->getTransactionType()) {
        case PhorgeTransactions::TYPE_EDGE:
          switch ($xaction->getMetadataValue('edge:type')) {
            case PhorgeProjectProjectHasMemberEdgeType::EDGECONST:
              $materialize = true;
              break;
          }
          break;
        case PhorgeProjectParentTransaction::TRANSACTIONTYPE:
        case PhorgeProjectMilestoneTransaction::TRANSACTIONTYPE:
          $materialize = true;
          $new_parent = $object->getParentProject();
          break;
      }
    }

    if ($new_parent) {
      // If we just created the first subproject of this parent, we want to
      // copy all of the real members to the subproject.
      if (!$new_parent->getHasSubprojects()) {
        $member_type = PhorgeProjectProjectHasMemberEdgeType::EDGECONST;

        $project_members = PhorgeEdgeQuery::loadDestinationPHIDs(
          $new_parent->getPHID(),
          $member_type);

        if ($project_members) {
          $editor = id(new PhorgeEdgeEditor());
          foreach ($project_members as $phid) {
            $editor->addEdge($object->getPHID(), $member_type, $phid);
          }
          $editor->save();
        }
      }
    }

    // TODO: We should dump an informational transaction onto the parent
    // project to show that we created the sub-thing.

    if ($materialize) {
      id(new PhorgeProjectsMembershipIndexEngineExtension())
        ->rematerialize($object);
    }

    if ($new_parent) {
      id(new PhorgeProjectsMembershipIndexEngineExtension())
        ->rematerialize($new_parent);
    }

    // See PHI1046. Milestones are always in the Space of their parent project.
    // Synchronize the database values to match the application values.
    $conn = $object->establishConnection('w');
    queryfx(
      $conn,
      'UPDATE %R SET spacePHID = %ns
        WHERE parentProjectPHID = %s AND milestoneNumber IS NOT NULL',
      $object,
      $object->getSpacePHID(),
      $object->getPHID());

    return parent::applyFinalEffects($object, $xactions);
  }

  public function addSlug(PhorgeProject $project, $slug, $force) {
    $slug = PhorgeSlug::normalizeProjectSlug($slug);
    $table = new PhorgeProjectSlug();
    $project_phid = $project->getPHID();

    if ($force) {
      // If we have the `$force` flag set, we only want to ignore an existing
      // slug if it's for the same project. We'll error on collisions with
      // other projects.
      $current = $table->loadOneWhere(
        'slug = %s AND projectPHID = %s',
        $slug,
        $project_phid);
    } else {
      // Without the `$force` flag, we'll just return without doing anything
      // if any other project already has the slug.
      $current = $table->loadOneWhere(
        'slug = %s',
        $slug);
    }

    if ($current) {
      return;
    }

    return id(new PhorgeProjectSlug())
      ->setSlug($slug)
      ->setProjectPHID($project_phid)
      ->save();
  }

  public function removeSlugs(PhorgeProject $project, array $slugs) {
    if (!$slugs) {
      return;
    }

    // We're going to try to delete both the literal and normalized versions
    // of all slugs. This allows us to destroy old slugs that are no longer
    // valid.
    foreach ($this->normalizeSlugs($slugs) as $slug) {
      $slugs[] = $slug;
    }

    $objects = id(new PhorgeProjectSlug())->loadAllWhere(
      'projectPHID = %s AND slug IN (%Ls)',
      $project->getPHID(),
      $slugs);

    foreach ($objects as $object) {
      $object->delete();
    }
  }

  public function normalizeSlugs(array $slugs) {
    foreach ($slugs as $key => $slug) {
      $slugs[$key] = PhorgeSlug::normalizeProjectSlug($slug);
    }

    $slugs = array_unique($slugs);
    $slugs = array_values($slugs);

    return $slugs;
  }

  protected function adjustObjectForPolicyChecks(
    PhorgeLiskDAO $object,
    array $xactions) {

    $copy = parent::adjustObjectForPolicyChecks($object, $xactions);

    $type_edge = PhorgeTransactions::TYPE_EDGE;
    $edgetype_member = PhorgeProjectProjectHasMemberEdgeType::EDGECONST;

    // See T13462. If we're creating a milestone, set a dummy milestone
    // number so the project behaves like a milestone and uses milestone
    // policy rules. Otherwise, we'll end up checking the default policies
    // (which are not relevant to milestones) instead of the parent project
    // policies (which are the correct policies).
    if ($this->getIsMilestone() && !$copy->isMilestone()) {
      $copy->setMilestoneNumber(1);
    }

    $hint = null;
    if ($this->getIsMilestone()) {
      // See T13462. If we're creating a milestone, predict that the members
      // of the newly created milestone will be the same as the members of the
      // parent project, since this is the governing rule.

      $parent = $copy->getParentProject();

      $parent = id(new PhorgeProjectQuery())
        ->setViewer($this->getActor())
        ->withPHIDs(array($parent->getPHID()))
        ->needMembers(true)
        ->executeOne();
      $members = $parent->getMemberPHIDs();

      $hint = array_fuse($members);
    } else {
      $member_xaction = null;
      foreach ($xactions as $xaction) {
        if ($xaction->getTransactionType() !== $type_edge) {
          continue;
        }

        $edgetype = $xaction->getMetadataValue('edge:type');
        if ($edgetype !== $edgetype_member) {
          continue;
        }

        $member_xaction = $xaction;
      }

      if ($member_xaction) {
        $object_phid = $object->getPHID();

        if ($object_phid) {
          $project = id(new PhorgeProjectQuery())
            ->setViewer($this->getActor())
            ->withPHIDs(array($object_phid))
            ->needMembers(true)
            ->executeOne();
          $members = $project->getMemberPHIDs();
        } else {
          $members = array();
        }

        $clone_xaction = clone $member_xaction;
        $hint = $this->getPHIDTransactionNewValue($clone_xaction, $members);
        $hint = array_fuse($hint);
      }
    }

    if ($hint !== null) {
      $rule = new PhorgeProjectMembersPolicyRule();
      PhorgePolicyRule::passTransactionHintToRule(
        $copy,
        $rule,
        $hint);
    }

    return $copy;
  }

  protected function expandTransactions(
    PhorgeLiskDAO $object,
    array $xactions) {

    $actor = $this->getActor();
    $actor_phid = $actor->getPHID();

    $results = parent::expandTransactions($object, $xactions);

    $is_milestone = $object->isMilestone();
    foreach ($xactions as $xaction) {
      switch ($xaction->getTransactionType()) {
        case PhorgeProjectMilestoneTransaction::TRANSACTIONTYPE:
          if ($xaction->getNewValue() !== null) {
            $is_milestone = true;
          }
          break;
      }
    }

    $this->setIsMilestone($is_milestone);

    return $results;
  }

  protected function shouldApplyHeraldRules(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function buildHeraldAdapter(
    PhorgeLiskDAO $object,
    array $xactions) {

    // Herald rules may run on behalf of other users and need to execute
    // membership checks against ancestors.
    $project = id(new PhorgeProjectQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withPHIDs(array($object->getPHID()))
      ->needAncestorMembers(true)
      ->executeOne();

    return id(new PhorgeProjectHeraldAdapter())
      ->setProject($project);
  }

}
