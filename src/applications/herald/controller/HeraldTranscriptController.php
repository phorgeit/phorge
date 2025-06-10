<?php

final class HeraldTranscriptController extends HeraldController {

  private $handles;
  private $adapter;

  private function getAdapter() {
    return $this->adapter;
  }

  public function buildApplicationMenu() {
    // Use the menu we build in this controller, not the default menu for
    // Herald.
    return null;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $xscript = id(new HeraldTranscriptQuery())
      ->setViewer($viewer)
      ->withIDs(array($request->getURIData('id')))
      ->executeOne();
    if (!$xscript) {
      return new Aphront404Response();
    }

    $view_key = $this->getViewKey($request);
    if (!$view_key) {
      return new Aphront404Response();
    }

    $navigation = $this->newSideNavView($xscript, $view_key);

    $object = $xscript->getObject();

    require_celerity_resource('herald-test-css');
    $content = array();

    $object_xscript = $xscript->getObjectTranscript();
    if (!$object_xscript) {
      $notice = id(new PHUIInfoView())
        ->setSeverity(PHUIInfoView::SEVERITY_NOTICE)
        ->setTitle(pht('Old Transcript'))
        ->appendChild(phutil_tag(
          'p',
          array(),
          pht('Details of this transcript have been garbage collected.')));
      $content[] = $notice;
    } else {
      $map = HeraldAdapter::getEnabledAdapterMap($viewer);
      $object_type = $object_xscript->getType();
      if (empty($map[$object_type])) {
        // TODO: We should filter these out in the Query, but we have to load
        // the objectTranscript right now, which is potentially enormous. We
        // should denormalize the object type, or move the data into a separate
        // table, and then filter this earlier (and thus raise a better error).
        // For now, just block access so we don't violate policies.
        throw new Exception(
          pht('This transcript has an invalid or inaccessible adapter.'));
      }

      $this->adapter = HeraldAdapter::getAdapterForContentType($object_type);

      $phids = $this->getTranscriptPHIDs($xscript);
      $phids = array_unique($phids);
      $phids = array_filter($phids);

      $handles = $this->loadViewerHandles($phids);
      $this->handles = $handles;

      $warning_panel = $this->buildWarningPanel($xscript);
      $content[] = $warning_panel;

      $content[] = $this->newContentView($xscript, $view_key);
    }

    $crumbs = id($this->buildApplicationCrumbs())
      ->addTextCrumb(
        pht('Transcripts'),
        $this->getApplicationURI('/transcript/'))
      ->addTextCrumb(pht('Transcript %d', $xscript->getID()))
      ->setBorder(true);

    $title = pht('Herald Transcript %s', $xscript->getID());
    $header = $this->newHeaderView($xscript, $title);

    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setFooter($content);

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->setNavigation($navigation)
      ->appendChild($view);
  }

  protected function renderConditionTestValue($condition, $handles) {
    // TODO: This is all a hacky mess and should be driven through FieldValue
    // eventually.

    switch ($condition->getFieldName()) {
      case HeraldAnotherRuleField::FIELDCONST:
        $value = array($condition->getTestValue());
        break;
      default:
        $value = $condition->getTestValue();
        break;
    }

    if (!is_scalar($value) && $value !== null) {
      foreach ($value as $key => $phid) {
        $handle = idx($handles, $phid);
        if ($handle && $handle->isComplete()) {
          $value[$key] = $handle->getName();
        } else {
          // This happens for things like task priorities, statuses, and
          // custom fields.
          $value[$key] = $phid;
        }
      }
      sort($value);
      $value = implode(', ', $value);
    }

    return phutil_tag('span', array('class' => 'condition-test-value'), $value);
  }

  protected function getTranscriptPHIDs($xscript) {
    $phids = array();

    $object_xscript = $xscript->getObjectTranscript();
    if (!$object_xscript) {
      return array();
    }

    $phids[] = $object_xscript->getPHID();

    foreach ($xscript->getApplyTranscripts() as $apply_xscript) {
      // TODO: This is total hacks. Add another amazing layer of abstraction.
      $target = (array)$apply_xscript->getTarget();
      foreach ($target as $phid) {
        if ($phid) {
          $phids[] = $phid;
        }
      }
    }

    foreach ($xscript->getRuleTranscripts() as $rule_xscript) {
      $phids[] = $rule_xscript->getRuleOwner();
    }

    $condition_xscripts = $xscript->getConditionTranscripts();
    if ($condition_xscripts) {
      $condition_xscripts = call_user_func_array(
        'array_merge',
        $condition_xscripts);
    }
    foreach ($condition_xscripts as $condition_xscript) {
      switch ($condition_xscript->getFieldName()) {
        case HeraldAnotherRuleField::FIELDCONST:
          $phids[] = $condition_xscript->getTestValue();
          break;
        default:
          $value = $condition_xscript->getTestValue();
          // TODO: Also total hacks.
          if (is_array($value)) {
            foreach ($value as $phid) {
              if ($phid) {
                // TODO: Probably need to make sure this
                // "looks like" a PHID or decrease the level of hacks here;
                // this used to be an is_numeric() check in Facebook land.
                $phids[] = $phid;
              }
            }
          }
          break;
      }
    }

    return $phids;
  }

  private function buildWarningPanel(HeraldTranscript $xscript) {
    $request = $this->getRequest();
    $panel = null;
    if ($xscript->getObjectTranscript()) {
      $handles = $this->handles;
      $object_xscript = $xscript->getObjectTranscript();
      $handle = $handles[$object_xscript->getPHID()];
      if ($handle->getType() ==
          PhabricatorRepositoryCommitPHIDType::TYPECONST) {
        $commit = id(new DiffusionCommitQuery())
          ->setViewer($request->getUser())
          ->withPHIDs(array($handle->getPHID()))
          ->executeOne();
        if ($commit) {
          $repository = $commit->getRepository();
          if ($repository->isImporting()) {
            $title = pht(
              'The %s repository is still importing.',
              $repository->getMonogram());
            $body = pht(
              'Herald rules will not trigger until import completes.');
          } else if (!$repository->isTracked()) {
            $title = pht(
              'The %s repository is not tracked.',
              $repository->getMonogram());
            $body = pht(
              'Herald rules will not trigger until tracking is enabled.');
          } else {
            return $panel;
          }
          $panel = id(new PHUIInfoView())
            ->setSeverity(PHUIInfoView::SEVERITY_WARNING)
            ->setTitle($title)
            ->appendChild($body);
        }
      }
    }
    return $panel;
  }

  private function buildActionTranscriptPanel(HeraldTranscript $xscript) {
    $viewer = $this->getViewer();
    $action_xscript = mgroup($xscript->getApplyTranscripts(), 'getRuleID');

    $adapter = $this->getAdapter();

    $field_names = $adapter->getFieldNameMap();
    $condition_names = $adapter->getConditionNameMap();

    $handles = $this->handles;

    $action_map = $xscript->getApplyTranscripts();
    $action_map = mgroup($action_map, 'getRuleID');

    $rule_list = id(new PHUIObjectItemListView())
      ->setNoDataString(pht('No Herald rules applied to this object.'))
      ->setFlush(true);

    $rule_xscripts = $xscript->getRuleTranscripts();
    $rule_xscripts = msort($rule_xscripts, 'getRuleID');
    foreach ($rule_xscripts as $rule_xscript) {
      $rule_id = $rule_xscript->getRuleID();

      $rule_monogram = pht('H%d', $rule_id);
      $rule_uri = '/'.$rule_monogram;

      $rule_item = id(new PHUIObjectItemView())
        ->setObjectName($rule_monogram)
        ->setHeader($rule_xscript->getRuleName())
        ->setHref($rule_uri);

      $rule_result = $rule_xscript->getRuleResult();

      if (!$rule_result->getShouldApplyActions()) {
        $rule_item->setDisabled(true);
      }

      $rule_list->addItem($rule_item);

      // Build the field/condition transcript.

      $cond_xscripts = $xscript->getConditionTranscriptsForRule($rule_id);

      $cond_list = id(new PHUIStatusListView());
      $cond_list->addItem(
        id(new PHUIStatusItemView())
          ->setTarget(phutil_tag('strong', array(), pht('Conditions'))));

      foreach ($cond_xscripts as $cond_xscript) {
        $result = $cond_xscript->getResult();

        $icon = $result->getIconIcon();
        $color = $result->getIconColor();
        $name = $result->getName();

        $result_details = $result->newDetailsView($viewer);
        if ($result_details !== null) {
          $result_details = phutil_tag(
            'div',
            array(
              'class' => 'herald-condition-note',
            ),
            $result_details);
        }

        // TODO: This is not really translatable and should be driven through
        // HeraldField.
        $explanation = pht(
          '%s %s %s',
          idx($field_names, $cond_xscript->getFieldName(), pht('Unknown')),
          idx($condition_names, $cond_xscript->getCondition(), pht('Unknown')),
          $this->renderConditionTestValue($cond_xscript, $handles));

        $cond_item = id(new PHUIStatusItemView())
          ->setIcon($icon, $color)
          ->setTarget($name)
          ->setNote(array($explanation, $result_details));

        $cond_list->addItem($cond_item);
      }

      $rule_result = $rule_xscript->getRuleResult();

      $last_icon = $rule_result->getIconIcon();
      $last_color = $rule_result->getIconColor();
      $last_result = $rule_result->getName();
      $last_note = $rule_result->getDescription();

      $last_details = $rule_result->newDetailsView($viewer);
      if ($last_details !== null) {
        $last_details = phutil_tag(
          'div',
          array(
            'class' => 'herald-condition-note',
          ),
          $last_details);
      }

      $cond_last = id(new PHUIStatusItemView())
        ->setIcon($last_icon, $last_color)
        ->setTarget(phutil_tag('strong', array(), $last_result))
        ->setNote(array($last_note, $last_details));
      $cond_list->addItem($cond_last);

      $cond_box = id(new PHUIBoxView())
        ->appendChild($cond_list)
        ->addMargin(PHUI::MARGIN_LARGE_LEFT);

      $rule_item->appendChild($cond_box);

      // Not all rules will have any action transcripts, but we show them
      // in general because they may have relevant information even when
      // rules did not take actions. In particular, state-based actions may
      // forbid rules from matching.

      $cond_box->addMargin(PHUI::MARGIN_MEDIUM_BOTTOM);

      $action_xscripts = idx($action_map, $rule_id, array());
      foreach ($action_xscripts as $action_xscript) {
        $action_key = $action_xscript->getAction();
        $action = $adapter->getActionImplementation($action_key);

        if ($action) {
          $name = $action->getHeraldActionName();
          $action->setViewer($this->getViewer());
        } else {
          $name = pht('Unknown Action ("%s")', $action_key);
        }

        $name = pht('Action: %s', $name);

        $action_list = id(new PHUIStatusListView());
        $action_list->addItem(
          id(new PHUIStatusItemView())
            ->setTarget(phutil_tag('strong', array(), $name)));

        $action_box = id(new PHUIBoxView())
          ->appendChild($action_list)
          ->addMargin(PHUI::MARGIN_LARGE_LEFT);

        $rule_item->appendChild($action_box);

        $log = $action_xscript->getAppliedReason();

        // Handle older transcripts which used a static string to record
        // action results.

        if ($xscript->getDryRun()) {
          $action_list->addItem(
            id(new PHUIStatusItemView())
              ->setIcon('fa-ban', 'grey')
              ->setTarget(pht('Dry Run'))
              ->setNote(
                pht(
                  'This was a dry run, so no actions were taken.')));
          continue;
        } else if (!is_array($log)) {
          $action_list->addItem(
            id(new PHUIStatusItemView())
              ->setIcon('fa-clock-o', 'grey')
              ->setTarget(pht('Old Transcript'))
              ->setNote(
                pht(
                  'This is an old transcript which uses an obsolete log '.
                  'format. Detailed action information is not available.')));
          continue;
        }

        foreach ($log as $entry) {
          $type = idx($entry, 'type');
          $data = idx($entry, 'data');

          if ($action) {
            $icon = $action->renderActionEffectIcon($type, $data);
            $color = $action->renderActionEffectColor($type, $data);
            $name = $action->renderActionEffectName($type, $data);
            $note = $action->renderEffectDescription($type, $data);
          } else {
            $icon = 'fa-question-circle';
            $color = 'indigo';
            $name = pht('Unknown Effect ("%s")', $type);
            $note = null;
          }

          $action_item = id(new PHUIStatusItemView())
            ->setIcon($icon, $color)
            ->setTarget($name)
            ->setNote($note);

          $action_list->addItem($action_item);
        }
      }
    }

    $box = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Rule Transcript'))
      ->appendChild($rule_list);

    $content = array();

    if ($xscript->getDryRun()) {
      $notice = new PHUIInfoView();
      $notice->setSeverity(PHUIInfoView::SEVERITY_NOTICE);
      $notice->setTitle(pht('Dry Run'));
      $notice->appendChild(
        pht(
          'This was a dry run to test Herald rules, '.
          'no actions were executed.'));
      $content[] = $notice;
    }

    $content[] = $box;

    return $content;
  }

  private function buildObjectTranscriptPanel(HeraldTranscript $xscript) {
    $viewer = $this->getViewer();
    $adapter = $this->getAdapter();

    $field_names = $adapter->getFieldNameMap();

    $object_xscript = $xscript->getObjectTranscript();

    $rows = array();
    if ($object_xscript) {
      $phid = $object_xscript->getPHID();
      $handles = $this->handles;

      $rows[] = array(
        pht('Object Name'),
        $object_xscript->getName(),
      );

      $rows[] = array(
        pht('Object Type'),
        $object_xscript->getType(),
      );

      $rows[] = array(
        pht('Object PHID'),
        $phid,
      );

      $rows[] = array(
        pht('Object Link'),
        $handles[$phid]->renderLink(),
      );
    }

    foreach ($xscript->getMetadataMap() as $key => $value) {
      $rows[] = array(
        $key,
        $value,
      );
    }

    if ($object_xscript) {
      foreach ($object_xscript->getFields() as $field_type => $value) {
        if (isset($field_names[$field_type])) {
          $field_name = pht('Field: %s', $field_names[$field_type]);
        } else {
          $field_name = pht('Unknown Field ("%s")', $field_type);
        }

        $field_value = $adapter->renderFieldTranscriptValue(
          $viewer,
          $field_type,
          $value);

        $rows[] = array(
          $field_name,
          $field_value,
        );
      }
    }

    $property_list = new PHUIPropertyListView();
    $property_list->setStacked(true);
    foreach ($rows as $row) {
      $property_list->addProperty($row[0], $row[1]);
    }

    $box = new PHUIObjectBoxView();
    $box->setHeaderText(pht('Object Transcript'));
    $box->appendChild($property_list);

    return $box;
  }

  private function buildTransactionsTranscriptPanel(HeraldTranscript $xscript) {
    $viewer = $this->getViewer();

    $xaction_phids = $this->getTranscriptTransactionPHIDs($xscript);

    if ($xaction_phids) {
      $object = $xscript->getObject();
      $query = PhabricatorApplicationTransactionQuery::newQueryForObject(
        $object);
      $xactions = $query
        ->setViewer($viewer)
        ->withPHIDs($xaction_phids)
        ->execute();
      $xactions = mpull($xactions, null, 'getPHID');
    } else {
      $xactions = array();
    }

    $rows = array();
    foreach ($xaction_phids as $xaction_phid) {
      $xaction = idx($xactions, $xaction_phid);

      $xaction_identifier = $xaction_phid;
      $xaction_date = null;
      $xaction_display = null;
      if ($xaction) {
        $xaction_identifier = $xaction->getID();
        $xaction_date = vixon_datetime(
          $xaction->getDateCreated(),
          $viewer);

        // Since we don't usually render transactions outside of the context
        // of objects, some of them might depend on missing object data. Out of
        // an abundance of caution, catch any rendering issues.
        try {
          $xaction_display = $xaction->getTitle();
        } catch (Exception $ex) {
          $xaction_display = $ex->getMessage();
        }
      }

      $rows[] = array(
        $xaction_identifier,
        $xaction_display,
        $xaction_date,
      );
    }

    $table_view = id(new AphrontTableView($rows))
      ->setHeaders(
        array(
          pht('ID'),
          pht('Transaction'),
          pht('Date'),
        ))
      ->setColumnClasses(
        array(
          null,
          'wide',
          null,
        ));

    $box_view = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Transactions'))
      ->setTable($table_view);

    return $box_view;
  }


  private function buildProfilerTranscriptPanel(HeraldTranscript $xscript) {
    $viewer = $this->getViewer();

    $object_xscript = $xscript->getObjectTranscript();

    $profile = $object_xscript->getProfile();

    // If this is an older transcript without profiler information, don't
    // show anything.
    if ($profile === null) {
      return null;
    }

    $profile = isort($profile, 'elapsed');
    $profile = array_reverse($profile);

    $phids = array();
    foreach ($profile as $frame) {
      if ($frame['type'] === 'rule') {
        $phids[] = $frame['key'];
      }
    }
    $handles = $viewer->loadHandles($phids);

    $field_map = HeraldField::getAllFields();

    $rows = array();
    foreach ($profile as $frame) {
      $cost = $frame['elapsed'];
      $cost = 1000000 * $cost;
      $cost = pht('%s%ss', new PhutilNumber($cost), mb_chr(956, 'UTF-8'));

      $type = $frame['type'];
      switch ($type) {
        case 'rule':
          $type_display = pht('Rule');
          break;
        case 'field':
          $type_display = pht('Field');
          break;
        default:
          $type_display = $type;
          break;
      }

      $key = $frame['key'];
      switch ($type) {
        case 'field':
          $field_object = idx($field_map, $key);
          if ($field_object) {
            $key_display = $field_object->getHeraldFieldName();
          } else {
            $key_display = $key;
          }
          break;
        case 'rule':
          $key_display = $handles[$key]->renderLink();
          break;
        default:
          $key_display = $key;
          break;
      }

      $rows[] = array(
        $type_display,
        $key_display,
        $cost,
        pht('%s', new PhutilNumber($frame['count'])),
      );
    }

    $table_view = id(new AphrontTableView($rows))
      ->setHeaders(
        array(
          pht('Type'),
          pht('What'),
          pht('Cost'),
          pht('Count'),
        ))
      ->setColumnClasses(
        array(
          null,
          'wide',
          'right',
          'right',
        ));

    $box_view = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Profile'))
      ->setTable($table_view);

    return $box_view;
  }

  private function getViewKey(AphrontRequest $request) {
    $view_key = $request->getURIData('view');

    if ($view_key === null) {
      return 'rules';
    }

    switch ($view_key) {
      case 'fields':
      case 'xactions':
      case 'profile':
        return $view_key;
      default:
        return null;
    }
  }

  private function newSideNavView(
    HeraldTranscript $xscript,
    $view_key) {

    $base_uri = urisprintf(
      'transcript/%d/',
      $xscript->getID());

    $base_uri = $this->getApplicationURI($base_uri);
    $base_uri = new PhutilURI($base_uri);

    $nav = id(new AphrontSideNavFilterView())
      ->setBaseURI($base_uri);

    $nav->newLink('rules')
      ->setHref($base_uri)
      ->setName(pht('Rules'))
      ->setIcon('fa-list-ul');

    $nav->newLink('fields')
      ->setName(pht('Field Values'))
      ->setIcon('fa-file-text-o');

    $has_xactions = $xscript->getObjectTranscript()
      && $this->getTranscriptTransactionPHIDs($xscript);

    $nav->newLink('xactions')
      ->setName(pht('Transactions'))
      ->setIcon('fa-forward')
      ->setDisabled(!$has_xactions);

    $nav->newLink('profile')
      ->setName(pht('Profiler'))
      ->setIcon('fa-tachometer');

    $nav->selectFilter($view_key);

    return $nav;
  }

  private function newContentView(
    HeraldTranscript $xscript,
    $view_key) {

    switch ($view_key) {
      case 'rules':
        $content = $this->buildActionTranscriptPanel($xscript);
        break;
      case 'fields':
        $content = $this->buildObjectTranscriptPanel($xscript);
        break;
      case 'xactions':
        $content = $this->buildTransactionsTranscriptPanel($xscript);
        break;
      case 'profile':
        $content = $this->buildProfilerTranscriptPanel($xscript);
        break;
      default:
        throw new Exception(pht('Unknown view key "%s".', $view_key));
    }

    return $content;
  }

  private function getTranscriptTransactionPHIDs(HeraldTranscript $xscript) {

    $object_xscript = $xscript->getObjectTranscript();
    $xaction_phids = $object_xscript->getAppliedTransactionPHIDs();

    // If the value is "null", this is an older transcript or this adapter
    // does not use transactions.
    //
    // (If the value is "array()", this is a modern transcript which uses
    // transactions, there just weren't any applied.)
    if ($xaction_phids === null) {
      return array();
    }

    $object = $xscript->getObject();

    // If this object doesn't implement the right interface, we won't be
    // able to load the transactions.
    if (!($object instanceof PhabricatorApplicationTransactionInterface)) {
      return array();
    }

    return $xaction_phids;
  }

  private function newHeaderView(HeraldTranscript $xscript, $title) {
    $header = id(new PHUIHeaderView())
      ->setHeader($title)
      ->setHeaderIcon('fa-list-ul');

    if ($xscript->getDryRun()) {
      $dry_run_tag = id(new PHUITagView())
        ->setType(PHUITagView::TYPE_SHADE)
        ->setColor(PHUITagView::COLOR_VIOLET)
        ->setName(pht('Dry Run'))
        ->setIcon('fa-exclamation-triangle');

      $header->addTag($dry_run_tag);
    }

    return $header;
  }

}
