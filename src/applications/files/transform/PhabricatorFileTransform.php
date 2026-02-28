<?php

abstract class PhabricatorFileTransform extends Phobject {

  abstract public function getTransformName();
  abstract public function getTransformKey();
  abstract public function canApplyTransform(PhabricatorFile $file);
  abstract public function applyTransform(PhabricatorFile $file);

  public function getDefaultTransform(PhabricatorFile $file) {
    return null;
  }

  public function generateTransforms() {
    return array($this);
  }

  /**
   * Get an existing transformed file, or create a new transformed file if no
   * transformed file already exists.
   * If a new file is produced, it is connected to the original file
   * in an explicit way, so, persisting a new 'PhabricatorTransformedFile' row.
   *
   * @param PhabricatorFile $file Original file.
   *                              You must check yourself if the viewer has
   *                              sufficient permissions to see this file.
   * @return PhabricatorFile Transformed file
   */
  public function getOrExecuteTransformExplicit(PhabricatorFile $file) {
    // Use of omnipotent user is okay here because the assume
    // the user can see the input $file, and so, its transforms.
    // See PhabricatorFile::hasAutomaticCapability().
    $xformed_file = id(new PhabricatorFileQuery())
      ->setViewer(PhabricatorUser::getOmnipotentUser())
      ->withTransforms(
        array(
          array(
            'originalPHID' => $file->getPHID(),
            'transform'    => $this->getTransformKey(),
          ),
        ))
      ->executeOne();

    if ($xformed_file) {
      return $xformed_file;
    }

    return $this->executeTransformExplicit($file);
  }

  /**
   * Create a new transformed file.
   * This usually causes the creation of a new 'PhabricatorFile'.
   *
   * @param PhabricatorFile $file Original file
   * @return PhabricatorFile Transformed file
   */
  public function executeTransform(PhabricatorFile $file) {
    if ($this->canApplyTransform($file)) {
      try {
        return $this->applyTransform($file);
      } catch (Exception $ex) {
        // Ignore.
      }
    }

    return $this->getDefaultTransform($file);
  }

  /**
   * Wrapper of executeTransform() that also persists the relationship
   * between the original file and the transform, if it makes sense to do so.
   *
   * @param PhabricatorFile $file Original file
   * @return PhabricatorFile Transformed file
   */
  public function executeTransformExplicit(PhabricatorFile $file) {
    // This can be NULL.
    $xform = $this->executeTransform($file);

    // Connect the original file to its transform, if any.
    // Skip transforms that are derived from a builtin as cautionary measure:
    //  - Builtins may have a lot of transforms. We don't know if the UX scales.
    //    Example page: /file/transforms/1/
    //  - Tracking builtins gives unclear benefits.
    if ($xform && !$file->isBuiltin()) {
      id(new PhabricatorTransformedFile())
        ->setOriginalPHID($file->getPHID())
        ->setTransformedPHID($xform->getPHID())
        ->setTransform($this->getTransformKey())
        ->save();
    }

    return $xform;
  }

  public static function getAllTransforms() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(self::class)
      ->setExpandMethod('generateTransforms')
      ->setUniqueMethod('getTransformKey')
      ->execute();
  }

  public static function getTransformByKey($key) {
    $all = self::getAllTransforms();

    $xform = idx($all, $key);
    if (!$xform) {
      throw new Exception(
        pht(
          'No file transform with key "%s" exists.',
          $key));
    }

    return $xform;
  }

}
