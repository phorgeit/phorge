<?php

/**
 * Abstract "Before Destruction Engine",
 * to fire a hook before something is permamently destroyed.
 *
 * This class is to be considered unstable and may receive variations
 * over time. If you want to use this engine or extend its features, please
 * share your use-case here, even if the task is closed:
 * https://we.phorge.it/T16079
 */
abstract class PhabricatorBeforeDestructionEngineExtension extends Phobject {

  /**
   * Get the extension internal key.
   *
   * @return string
   */
  final public function getExtensionKey(): string {
    return $this->getPhobjectClassConstant('EXTENSIONKEY');
  }

  /**
   * Get the extension human name.
   *
   * @return string
   */
  abstract public function getExtensionName(): string;

  /**
   * Check if this extension supports a "Before Destruction" hook
   * on the specified object.
   *
   * The object is guaranteed to have a PHID and still exist but
   * will be destroyed later.
   * This method should not contain write operations.
   * This method exposes a PhabricatorDestructionEngine since it can give
   * useful info, but here you should not use it to destroy objects.
   * When this method returns true, the method beforeDestroyObject()
   * will be fired.
   *
   * @param PhabricatorDestructionEngine $destruction_engine
   *                                     Available destruction engine
   * @param object                       $object
   *                                     Object that will be destroyed
   * @return bool                        If true, beforeDestroyObject()
   *                                     will be fired.
   */
  public function canBeforeDestroyObject(
    PhabricatorDestructionEngine $destruction_engine,
    $object): bool {
    return true;
  }

  /**
   * Call your "Before Destruction" hook on the specified object.
   * The object is guaranteed to have a PHID and still exist but
   * will be destroyed later.
   * This method is not called if canBeforeDestroyObject() returns false.
   *
   * @param PhabricatorDestructionEngine $destruction_engine
   *                                     Available destruction engine
   * @param object                       $object
   *                                     Object that will be destroyed
   */
  abstract public function beforeDestroyObject(
    PhabricatorDestructionEngine $destruction_engine,
    $object): void;

  /**
   * Get all "Before Destruction Engine" extensions.
   *
   * @return list<PhabricatorDestructionEngineExtension>
   */
  final public static function getAllExtensions(): array {
    $map = new PhutilClassMapQuery();
    return $map
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getExtensionKey')
      ->execute();
  }

}
