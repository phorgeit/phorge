<?php

final class ProjectCreateConduitAPIMethod extends ProjectConduitAPIMethod {

  public function getAPIMethodName() {
    return 'project.create';
  }

  public function getMethodDescription() {
    return pht('Create a project.');
  }

  public function getMethodStatus() {
    return self::METHOD_STATUS_FROZEN;
  }

  public function getMethodStatusDescription() {
    return pht(
      'This method is frozen and will eventually be deprecated. New code '.
      'should use "project.edit" instead.');
  }

  protected function defineParamTypes() {
    return array(
      'name'       => 'required string',
      'members'    => 'optional list<phid>',
      'icon'       => 'optional string',
      'color'      => 'optional string',
      'tags'       => 'optional list<string>',
    );
  }

  protected function defineReturnType() {
    return 'dict';
  }

  protected function execute(ConduitAPIRequest $request) {
    $user = $request->getUser();

    $this->requireApplicationCapability(
      ProjectCreateProjectsCapability::CAPABILITY,
      $user);

    $project = PhorgeProject::initializeNewProject($user);
    $type_name = PhorgeProjectNameTransaction::TRANSACTIONTYPE;
    $members = $request->getValue('members');
    $xactions = array();

    $xactions[] = id(new PhorgeProjectTransaction())
      ->setTransactionType($type_name)
      ->setNewValue($request->getValue('name'));

    if ($request->getValue('icon')) {
      $xactions[] = id(new PhorgeProjectTransaction())
        ->setTransactionType(
            PhorgeProjectIconTransaction::TRANSACTIONTYPE)
        ->setNewValue($request->getValue('icon'));
    }

    if ($request->getValue('color')) {
      $xactions[] = id(new PhorgeProjectTransaction())
        ->setTransactionType(
          PhorgeProjectColorTransaction::TRANSACTIONTYPE)
        ->setNewValue($request->getValue('color'));
    }

    if ($request->getValue('tags')) {
      $xactions[] = id(new PhorgeProjectTransaction())
        ->setTransactionType(
            PhorgeProjectSlugsTransaction::TRANSACTIONTYPE)
        ->setNewValue($request->getValue('tags'));
    }

    $xactions[] = id(new PhorgeProjectTransaction())
      ->setTransactionType(PhorgeTransactions::TYPE_EDGE)
      ->setMetadataValue(
        'edge:type',
        PhorgeProjectProjectHasMemberEdgeType::EDGECONST)
      ->setNewValue(
        array(
          '+' => array_fuse($members),
        ));

    $editor = id(new PhorgeProjectTransactionEditor())
      ->setActor($user)
      ->setContinueOnNoEffect(true)
      ->setContentSource($request->newContentSource());

    $editor->applyTransactions($project, $xactions);

    return $this->buildProjectInfoDictionary($project);
  }

}
