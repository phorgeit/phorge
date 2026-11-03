<?php

/**
 * @deprecated This is related to DifferentialTransaction::TYPE_ACTION.
 */
final class DifferentialAction extends Phobject {

  const ACTION_CLOSE          = 'commit';
  const ACTION_ABANDON        = 'abandon';
  const ACTION_CREATE         = 'create';

}
