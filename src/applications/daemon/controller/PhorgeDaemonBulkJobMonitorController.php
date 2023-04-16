<?php

final class PhorgeDaemonBulkJobMonitorController
  extends PhorgeDaemonBulkJobController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $job = id(new PhorgeWorkerBulkJobQuery())
      ->setViewer($viewer)
      ->withIDs(array($request->getURIData('id')))
      ->executeOne();
    if (!$job) {
      return new Aphront404Response();
    }

    // If the user clicks "Continue" on a completed job, take them back to
    // whatever application sent them here.
    if ($request->getStr('done')) {
      if ($request->isFormPost()) {
        $done_uri = $job->getDoneURI();
        return id(new AphrontRedirectResponse())->setURI($done_uri);
      }
    }

    $title = pht('Bulk Job %d', $job->getID());

    if ($job->getStatus() == PhorgeWorkerBulkJob::STATUS_CONFIRM) {
      $can_edit = PhorgePolicyFilter::hasCapability(
        $viewer,
        $job,
        PhorgePolicyCapability::CAN_EDIT);

      if ($can_edit) {
        if ($request->isFormPost()) {
          $type_status = PhorgeWorkerBulkJobTransaction::TYPE_STATUS;

          $xactions = array();
          $xactions[] = id(new PhorgeWorkerBulkJobTransaction())
            ->setTransactionType($type_status)
            ->setNewValue(PhorgeWorkerBulkJob::STATUS_WAITING);

          $editor = id(new PhorgeWorkerBulkJobEditor())
            ->setActor($viewer)
            ->setContentSourceFromRequest($request)
            ->setContinueOnMissingFields(true)
            ->applyTransactions($job, $xactions);

          return id(new AphrontRedirectResponse())
            ->setURI($job->getMonitorURI());
        } else {
          $dialog = $this->newDialog()
            ->setTitle(pht('Confirm Bulk Job'));

          $confirm = $job->getDescriptionForConfirm();
          $confirm = (array)$confirm;
          foreach ($confirm as $paragraph) {
            $dialog->appendParagraph($paragraph);
          }

          $dialog
            ->appendParagraph(
              pht('Start work on this bulk job?'))
            ->addCancelButton($job->getManageURI(), pht('Details'))
            ->addSubmitButton(pht('Start Work'));

          return $dialog;
        }
      } else {
        return $this->newDialog()
          ->setTitle(pht('Waiting For Confirmation'))
          ->appendParagraph(
            pht(
              'This job is waiting for confirmation before work begins.'))
          ->addCancelButton($job->getManageURI(), pht('Details'));
      }
    }


    $dialog = $this->newDialog()
      ->setTitle(pht('%s: %s', $title, $job->getStatusName()))
      ->addCancelButton($job->getManageURI(), pht('Details'));

    switch ($job->getStatus()) {
      case PhorgeWorkerBulkJob::STATUS_WAITING:
        $dialog->appendParagraph(
          pht('This job is waiting for tasks to be queued.'));
        break;
      case PhorgeWorkerBulkJob::STATUS_RUNNING:
        $dialog->appendParagraph(
          pht('This job is running.'));
        break;
      case PhorgeWorkerBulkJob::STATUS_COMPLETE:
        $dialog->appendParagraph(
          pht('This job is complete.'));
        break;
    }

    $counts = $job->loadTaskStatusCounts();
    if ($counts) {
      $dialog->appendParagraph($this->renderProgress($counts));
    }

    switch ($job->getStatus()) {
      case PhorgeWorkerBulkJob::STATUS_COMPLETE:
        $dialog->addHiddenInput('done', true);
        $dialog->addSubmitButton(pht('Continue'));
        break;
      default:
        Javelin::initBehavior('bulk-job-reload');
        break;
    }

    return $dialog;
  }

  private function renderProgress(array $counts) {
    $this->requireResource('bulk-job-css');

    $states = array(
      PhorgeWorkerBulkTask::STATUS_DONE => array(
        'class' => 'bulk-job-progress-slice-green',
      ),
      PhorgeWorkerBulkTask::STATUS_RUNNING => array(
        'class' => 'bulk-job-progress-slice-blue',
      ),
      PhorgeWorkerBulkTask::STATUS_WAITING => array(
        'class' => 'bulk-job-progress-slice-empty',
      ),
      PhorgeWorkerBulkTask::STATUS_FAIL => array(
        'class' => 'bulk-job-progress-slice-red',
      ),
    );

    $total = array_sum($counts);
    $offset = 0;
    $bars = array();
    foreach ($states as $state => $spec) {
      $size = idx($counts, $state, 0);
      if (!$size) {
        continue;
      }

      $classes = array();
      $classes[] = 'bulk-job-progress-slice';
      $classes[] = $spec['class'];

      $width = ($size / $total);
      $bars[] = phutil_tag(
        'div',
        array(
          'class' => implode(' ', $classes),
          'style' =>
            'left: '.sprintf('%.2f%%', 100 * $offset).'; '.
            'width: '.sprintf('%.2f%%', 100 * $width).';',
        ),
        '');

      $offset += $width;
    }

    return phutil_tag(
      'div',
      array(
        'class' => 'bulk-job-progress-bar',
      ),
      $bars);
  }

}
