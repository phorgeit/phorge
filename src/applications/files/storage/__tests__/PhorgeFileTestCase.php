<?php

final class PhorgeFileTestCase extends PhorgeTestCase {

  protected function getPhorgeTestCaseConfiguration() {
    return array(
      self::PHORGE_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  public function testFileDirectScramble() {
    // Changes to a file's view policy should scramble the file secret.

    $engine = new PhorgeTestStorageEngine();
    $data = Filesystem::readRandomCharacters(64);

    $author = $this->generateNewTestUser();

    $params = array(
      'name' => 'test.dat',
      'viewPolicy' => PhorgePolicies::POLICY_USER,
      'authorPHID' => $author->getPHID(),
      'storageEngines' => array(
        $engine,
      ),
    );

    $file = PhorgeFile::newFromFileData($data, $params);

    $secret1 = $file->getSecretKey();

    // First, change the name: this should not scramble the secret.
    $xactions = array();
    $xactions[] = id(new PhorgeFileTransaction())
      ->setTransactionType(PhorgeFileNameTransaction::TRANSACTIONTYPE)
      ->setNewValue('test.dat2');

    $engine = id(new PhorgeFileEditor())
      ->setActor($author)
      ->setContentSource($this->newContentSource())
      ->applyTransactions($file, $xactions);

    $file = $file->reload();

    $secret2 = $file->getSecretKey();

    $this->assertEqual(
      $secret1,
      $secret2,
      pht('No secret scramble on non-policy edit.'));

    // Now, change the view policy. This should scramble the secret.
    $xactions = array();
    $xactions[] = id(new PhorgeFileTransaction())
      ->setTransactionType(PhorgeTransactions::TYPE_VIEW_POLICY)
      ->setNewValue($author->getPHID());

    $engine = id(new PhorgeFileEditor())
      ->setActor($author)
      ->setContentSource($this->newContentSource())
      ->applyTransactions($file, $xactions);

    $file = $file->reload();
    $secret3 = $file->getSecretKey();

    $this->assertTrue(
      ($secret1 !== $secret3),
      pht('Changing file view policy should scramble secret.'));
  }

  public function testFileIndirectScramble() {
    // When a file is attached to an object like a task and the task view
    // policy changes, the file secret should be scrambled. This invalidates
    // old URIs if tasks get locked down.

    $engine = new PhorgeTestStorageEngine();
    $data = Filesystem::readRandomCharacters(64);

    $author = $this->generateNewTestUser();

    $params = array(
      'name' => 'test.dat',
      'viewPolicy' => $author->getPHID(),
      'authorPHID' => $author->getPHID(),
      'storageEngines' => array(
        $engine,
      ),
    );

    $file = PhorgeFile::newFromFileData($data, $params);
    $secret1 = $file->getSecretKey();

    $task = ManiphestTask::initializeNewTask($author);

    $xactions = array();
    $xactions[] = id(new ManiphestTransaction())
      ->setTransactionType(ManiphestTaskTitleTransaction::TRANSACTIONTYPE)
      ->setNewValue(pht('File Scramble Test Task'));

    $xactions[] = id(new ManiphestTransaction())
      ->setTransactionType(
        ManiphestTaskDescriptionTransaction::TRANSACTIONTYPE)
      ->setNewValue('{'.$file->getMonogram().'}')
      ->setMetadataValue(
        'remarkup.control',
        array(
          'attachedFilePHIDs' => array(
            $file->getPHID(),
          ),
        ));

    id(new ManiphestTransactionEditor())
      ->setActor($author)
      ->setContentSource($this->newContentSource())
      ->applyTransactions($task, $xactions);

    $file = $file->reload();
    $secret2 = $file->getSecretKey();

    $this->assertEqual(
      $secret1,
      $secret2,
      pht(
        'File policy should not scramble when attached to '.
        'newly created object.'));

    $xactions = array();
    $xactions[] = id(new ManiphestTransaction())
      ->setTransactionType(PhorgeTransactions::TYPE_VIEW_POLICY)
      ->setNewValue($author->getPHID());

    id(new ManiphestTransactionEditor())
      ->setActor($author)
      ->setContentSource($this->newContentSource())
      ->applyTransactions($task, $xactions);

    $file = $file->reload();
    $secret3 = $file->getSecretKey();

    $this->assertTrue(
      ($secret1 !== $secret3),
      pht('Changing attached object view policy should scramble secret.'));
  }


  public function testFileVisibility() {
    $engine = new PhorgeTestStorageEngine();
    $data = Filesystem::readRandomCharacters(64);

    $author = $this->generateNewTestUser();
    $viewer = $this->generateNewTestUser();
    $users = array($author, $viewer);

    $params = array(
      'name' => 'test.dat',
      'viewPolicy' => PhorgePolicies::POLICY_NOONE,
      'authorPHID' => $author->getPHID(),
      'storageEngines' => array(
        $engine,
      ),
    );

    $file = PhorgeFile::newFromFileData($data, $params);
    $filter = new PhorgePolicyFilter();

    // Test bare file policies.
    $this->assertEqual(
      array(
        true,
        false,
      ),
      $this->canViewFile($users, $file),
      pht('File Visibility'));

    // Create an object and test object policies.

    $object = ManiphestTask::initializeNewTask($author)
      ->setTitle(pht('File Visibility Test Task'))
      ->setViewPolicy(PhorgePolicies::getMostOpenPolicy())
      ->save();

    $this->assertTrue(
      $filter->hasCapability(
        $author,
        $object,
        PhorgePolicyCapability::CAN_VIEW),
      pht('Object Visible to Author'));

    $this->assertTrue(
      $filter->hasCapability(
        $viewer,
        $object,
        PhorgePolicyCapability::CAN_VIEW),
      pht('Object Visible to Others'));

    // Reference the file in a comment. This should not affect the file
    // policy.

    $file_ref = '{F'.$file->getID().'}';

    $xactions = array();
    $xactions[] = id(new ManiphestTransaction())
      ->setTransactionType(PhorgeTransactions::TYPE_COMMENT)
      ->attachComment(
        id(new ManiphestTransactionComment())
          ->setContent($file_ref));

    id(new ManiphestTransactionEditor())
      ->setActor($author)
      ->setContentSource($this->newContentSource())
      ->applyTransactions($object, $xactions);

    // Test the referenced file's visibility.
    $this->assertEqual(
      array(
        true,
        false,
      ),
      $this->canViewFile($users, $file),
      pht('Referenced File Visibility'));

    // Attach the file to the object and test that the association opens a
    // policy exception for the non-author viewer.

    $xactions = array();
    $xactions[] = id(new ManiphestTransaction())
      ->setTransactionType(PhorgeTransactions::TYPE_COMMENT)
      ->setMetadataValue(
        'remarkup.control',
        array(
          'attachedFilePHIDs' => array(
            $file->getPHID(),
          ),
        ))
      ->attachComment(
        id(new ManiphestTransactionComment())
          ->setContent($file_ref));

    id(new ManiphestTransactionEditor())
      ->setActor($author)
      ->setContentSource($this->newContentSource())
      ->applyTransactions($object, $xactions);

    // Test the attached file's visibility.
    $this->assertEqual(
      array(
        true,
        true,
      ),
      $this->canViewFile($users, $file),
      pht('Attached File Visibility'));

    // Create a "thumbnail" of the original file.
    $params = array(
      'name' => 'test.thumb.dat',
      'viewPolicy' => PhorgePolicies::POLICY_NOONE,
      'storageEngines' => array(
        $engine,
      ),
    );

    $xform = PhorgeFile::newFromFileData($data, $params);

    id(new PhorgeTransformedFile())
      ->setOriginalPHID($file->getPHID())
      ->setTransform('test-thumb')
      ->setTransformedPHID($xform->getPHID())
      ->save();

    // Test the thumbnail's visibility.
    $this->assertEqual(
      array(
        true,
        true,
      ),
      $this->canViewFile($users, $xform),
      pht('Attached Thumbnail Visibility'));
  }

  private function canViewFile(array $users, PhorgeFile $file) {
    $results = array();
    foreach ($users as $user) {
      $results[] = (bool)id(new PhorgeFileQuery())
        ->setViewer($user)
        ->withPHIDs(array($file->getPHID()))
        ->execute();
    }
    return $results;
  }

  public function testFileStorageReadWrite() {
    $engine = new PhorgeTestStorageEngine();

    $data = Filesystem::readRandomCharacters(64);

    $params = array(
      'name' => 'test.dat',
      'storageEngines' => array(
        $engine,
      ),
    );

    $file = PhorgeFile::newFromFileData($data, $params);

    // Test that the storage engine worked, and was the target of the write. We
    // don't actually care what the data is (future changes may compress or
    // encrypt it), just that it exists in the test storage engine.
    $engine->readFile($file->getStorageHandle());

    // Now test that we get the same data back out.
    $this->assertEqual($data, $file->loadFileData());
  }

  public function testFileStorageUploadDifferentFiles() {
    $engine = new PhorgeTestStorageEngine();

    $data = Filesystem::readRandomCharacters(64);
    $other_data = Filesystem::readRandomCharacters(64);

    $params = array(
      'name' => 'test.dat',
      'storageEngines' => array(
        $engine,
      ),
    );

    $first_file = PhorgeFile::newFromFileData($data, $params);

    $second_file = PhorgeFile::newFromFileData($other_data, $params);

    // Test that the second file uses  different storage handle from
    // the first file.
    $first_handle = $first_file->getStorageHandle();
    $second_handle = $second_file->getStorageHandle();

    $this->assertTrue($first_handle != $second_handle);
  }

  public function testFileStorageUploadSameFile() {
    $engine = new PhorgeTestStorageEngine();

    $data = Filesystem::readRandomCharacters(64);

    $hash = PhorgeFile::hashFileContent($data);
    if ($hash === null) {
      $this->assertSkipped(pht('File content hashing is not available.'));
    }

    $params = array(
      'name' => 'test.dat',
      'storageEngines' => array(
        $engine,
      ),
    );

    $first_file = PhorgeFile::newFromFileData($data, $params);

    $second_file = PhorgeFile::newFromFileData($data, $params);

    // Test that the second file uses the same storage handle as
    // the first file.
    $handle = $first_file->getStorageHandle();
    $second_handle = $second_file->getStorageHandle();

    $this->assertEqual($handle, $second_handle);
  }

  public function testFileStorageDelete() {
    $engine = new PhorgeTestStorageEngine();

    $data = Filesystem::readRandomCharacters(64);

    $params = array(
      'name' => 'test.dat',
      'storageEngines' => array(
        $engine,
      ),
    );

    $file = PhorgeFile::newFromFileData($data, $params);
    $handle = $file->getStorageHandle();
    $file->delete();

    $caught = null;
    try {
      $engine->readFile($handle);
    } catch (Exception $ex) {
      $caught = $ex;
    }

    $this->assertTrue($caught instanceof Exception);
  }

  public function testFileStorageDeleteSharedHandle() {
    $engine = new PhorgeTestStorageEngine();

    $data = Filesystem::readRandomCharacters(64);

    $params = array(
      'name' => 'test.dat',
      'storageEngines' => array(
        $engine,
      ),
    );

    $first_file = PhorgeFile::newFromFileData($data, $params);
    $second_file = PhorgeFile::newFromFileData($data, $params);
    $first_file->delete();

    $this->assertEqual($data, $second_file->loadFileData());
  }

  public function testReadWriteTtlFiles() {
    $engine = new PhorgeTestStorageEngine();

    $data = Filesystem::readRandomCharacters(64);

    $ttl = (PhorgeTime::getNow() + phutil_units('24 hours in seconds'));

    $params = array(
      'name' => 'test.dat',
      'ttl.absolute' => $ttl,
      'storageEngines' => array(
        $engine,
      ),
    );

    $file = PhorgeFile::newFromFileData($data, $params);
    $this->assertEqual($ttl, $file->getTTL());
  }

  public function testFileTransformDelete() {
    // We want to test that a file deletes all its inbound transformation
    // records and outbound transformed derivatives when it is deleted.

    // First, we create a chain of transforms, A -> B -> C.

    $engine = new PhorgeTestStorageEngine();

    $params = array(
      'name' => 'test.txt',
      'storageEngines' => array(
        $engine,
      ),
    );

    $a = PhorgeFile::newFromFileData('a', $params);
    $b = PhorgeFile::newFromFileData('b', $params);
    $c = PhorgeFile::newFromFileData('c', $params);

    id(new PhorgeTransformedFile())
      ->setOriginalPHID($a->getPHID())
      ->setTransform('test:a->b')
      ->setTransformedPHID($b->getPHID())
      ->save();

    id(new PhorgeTransformedFile())
      ->setOriginalPHID($b->getPHID())
      ->setTransform('test:b->c')
      ->setTransformedPHID($c->getPHID())
      ->save();

    // Now, verify that A -> B and B -> C exist.

    $xform_a = id(new PhorgeFileQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withTransforms(
        array(
          array(
            'originalPHID' => $a->getPHID(),
            'transform'    => true,
          ),
        ))
      ->execute();

    $this->assertEqual(1, count($xform_a));
    $this->assertEqual($b->getPHID(), head($xform_a)->getPHID());

    $xform_b = id(new PhorgeFileQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withTransforms(
        array(
          array(
            'originalPHID' => $b->getPHID(),
            'transform'    => true,
          ),
        ))
      ->execute();

    $this->assertEqual(1, count($xform_b));
    $this->assertEqual($c->getPHID(), head($xform_b)->getPHID());

    // Delete "B".

    $b->delete();

    // Now, verify that the A -> B and B -> C links are gone.

    $xform_a = id(new PhorgeFileQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withTransforms(
        array(
          array(
            'originalPHID' => $a->getPHID(),
            'transform'    => true,
          ),
        ))
      ->execute();

    $this->assertEqual(0, count($xform_a));

    $xform_b = id(new PhorgeFileQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withTransforms(
        array(
          array(
            'originalPHID' => $b->getPHID(),
            'transform'    => true,
          ),
        ))
      ->execute();

    $this->assertEqual(0, count($xform_b));

    // Also verify that C has been deleted.

    $alternate_c = id(new PhorgeFileQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withPHIDs(array($c->getPHID()))
      ->execute();

    $this->assertEqual(array(), $alternate_c);
  }

}
