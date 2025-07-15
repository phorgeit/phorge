/**
 * @provides safe-landing
 */

function isBuildStatusPassed() {
  return Array.from(document.getElementsByClassName('phui-status-list-view'))
    .filter(
      (statusList) =>
        statusList.parentNode &&
        statusList.parentNode.previousSibling &&
        statusList.parentNode.previousSibling.innerText == 'Build Status'
    )
    .every((statusList) =>
      Array.from(statusList.getElementsByClassName('phui-status-item-target')).every((statusListRow) =>
        Array.from(statusListRow.childNodes).some((statusListRowNode) =>
          ['fa-check-circle', 'green'].every((className) => statusListRowNode.classList.contains(className))
        )
      )
    );
}

function getEnabledLandRevisionButtons() {
  return Array.from(document.getElementsByClassName('phabricator-action-view'))
    .filter((actionViewElement) => actionViewElement.innerText == 'Land Revision')
    .filter((landRevisionButton) => !landRevisionButton.classList.contains('phabricator-action-view-disabled'));
}

/* ------ MAIN ------ */
('use strict');
if (isBuildStatusPassed()) {
  getEnabledLandRevisionButtons().forEach((landRevisionButton) => {
    landRevisionButton.style.backgroundColor = '#BFB';
    landRevisionButton.firstChild.lastChild.nodeValue = 'Another happy landing';
  });
} else {
  getEnabledLandRevisionButtons().forEach((landRevisionButton) => {
    landRevisionButton.style.backgroundColor = '#FBB';
    landRevisionButton.firstChild.lastChild.nodeValue = 'CRASH LAND (Tests have NOT passed)';
  });
}
/* ------ END MAIN ------ */
