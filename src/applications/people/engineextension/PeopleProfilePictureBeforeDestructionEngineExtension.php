<?php

/**
 * Before a profile picture is destroyed, restore the builtin picture.
 * https://we.phorge.it/T16074
 */
final class PeopleProfilePictureBeforeDestructionEngineExtension
  extends PhabricatorBeforeDestructionEngineExtension {

  const EXTENSIONKEY = 'people-profiles';

  public function getExtensionName(): string {
    return pht('People Profile Pictures');
  }

  public function canBeforeDestroyObject(
    PhabricatorDestructionEngine $destruction_engine,
    $object): bool {
    return ($object instanceof PhabricatorFile)
      && $object->getIsProfileImage();
  }

  public function beforeDestroyObject(
    PhabricatorDestructionEngine $destruction_engine,
    $object): void {
    // File that will be destroyed soon.
    // The file PHID is always non-empty at this point.
    $file_phid = $object->getPHID();

    // Note that a file that is used as profile images have
    // the authorPHID = null, so it's not so obvious which
    // is the affected user.
    // https://we.phorge.it/T15407

    // Note that we could find the affected users by running this
    // very inefficient query that would lead to a full table scan:
    //   SELECT * FROM user WHERE profileImagePHID = $file_phid
    // In the future it might make sense to add an index on 'profileImagePHID'
    // if more frontend features will read that info, so we can also avoid the
    // following lines of code.
    // https://we.phorge.it/T16080

    // We look at the file attachments to find the affected user efficiently.
    // Note that file attachments are only available before destroying the file,
    // and... fortunately we are inside a "Before Destruction" engine.
    // This query is efficient thanks to the database index on 'filePHID' and
    // the low cardinality of this result set.
    $viewer = $destruction_engine->getViewer();
    $file_attachments_query = new PhabricatorFileAttachmentQuery();
    $file_attachments =
      $file_attachments_query
        ->setViewer($viewer)
        ->withFilePHIDs(array($file_phid))
        ->withObjectPHIDType(PhabricatorPeopleUserPHIDType::TYPECONST)
        ->withAttachmentModes(array(PhabricatorFileAttachment::MODE_ATTACH))
        ->execute();
    $attached_objects = mpull($file_attachments, 'getObject');

    // Be 100% sure to only operate on users,
    // and that these are really using this picture.
    $affected_users = array();
    foreach ($attached_objects as $attached_object) {
      if (($attached_object instanceof PhabricatorUser) &&
        ($attached_object->getProfileImagePHID() == $file_phid)) {
        $affected_users[] = $attached_object;
      }
    }

    $user_table = new PhabricatorUser();

    if (!$affected_users) {
      // The above fast speculation has found no users.
      // It can happen when somebody manually used the "Detach File" button
      // from the file (why people can generally do that? uhm).
      // Only in this desperate case, we run this inefficient query.
      $affected_users = $user_table
        ->loadAllWhere(
        'profileImagePHID = %s',
        $file_phid);
    }

    // Avoid opening an empty transaction.
    if (!$affected_users) {
      return;
    }

    // Set the builtin profile image to each affected user.
    // Premising that it's supposed to be just one user.
    // Maybe in the future multiple users may use the same
    // profile picture, so let's covers more corner cases,
    // because we can.
    $user_table->openTransaction();
    foreach ($affected_users as $affected_user) {
      $affected_user->setProfileImagePHID(null);
      $affected_user->save();
    }
    $user_table->saveTransaction();
  }

}
