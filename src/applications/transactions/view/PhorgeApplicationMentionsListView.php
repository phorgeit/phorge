<?php

final class PhorgeApplicationMentionsListView extends Phobject {

    private $edgeQuery;
    private $viewer;
    private $hasMentions = false;

    public function setEdgeQuery(
            PhabricatorEdgeQuery $edge_query): self {
        $this->edgeQuery = $edge_query;
        return $this;
    }

    public function setViewer(
            PhabricatorUser $viewer): self {
        $this->viewer = $viewer;
        return $this;
    }

    public function getMentionsView(): ?PHUIPropertyListView {

        $in_type = PhabricatorObjectMentionedByObjectEdgeType::EDGECONST;
        $out_type = PhabricatorObjectMentionsObjectEdgeType::EDGECONST;

        $in_phids = $this->edgeQuery->getDestinationPHIDs(
          array(),
          array($in_type));
        $out_phids = $this->edgeQuery->getDestinationPHIDs(
          array(),
          array($out_type));

        // Filter out any mentioned users from the list. These are not generally
        // very interesting to show in a relationship summary since they usually
        // end up as subscribers anyway.

        $user_type = PhabricatorPeopleUserPHIDType::TYPECONST;

        foreach ($out_phids as $key => $out_phid) {
            if (phid_get_type($out_phid) == $user_type) {
                unset($out_phids[$key]);
            }
        }

        if (!$in_phids && !$out_phids) {
            return null;
        }

        $in_handles = $this->viewer->loadHandles($in_phids);
        $out_handles = $this->viewer->loadHandles($out_phids);

        $in_handles = $this->getCompleteHandles($in_handles);
        $out_handles = $this->getCompleteHandles($out_handles);

        if (!count($in_handles) && !count($out_handles)) {
            return null;
        }

        $view = new PHUIPropertyListView();

        if (count($in_handles)) {
            $view->addProperty(
                pht('Mentioned In'), $in_handles->renderList());
        }

        if (count($out_handles)) {
            $view->addProperty(
                pht('Mentioned Here'), $out_handles->renderList());
        }

        $this->hasMentions = true;

        return $view;

    }

    public function hasMentions(): bool {
        return $this->hasMentions;
    }


    private function getCompleteHandles(
        PhabricatorHandleList $handles): PhabricatorHandleList {
        $phids = array();

        foreach ($handles as $phid => $handle) {
            if (!$handle->isComplete()) {
                continue;
            }
            $phids[] = $phid;
        }

        return $handles->newSublist($phids);
    }


}
