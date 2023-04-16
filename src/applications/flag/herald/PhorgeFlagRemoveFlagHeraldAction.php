<?php

final class PhorgeFlagRemoveFlagHeraldAction
  extends PhorgeFlagHeraldAction {

  const ACTIONCONST = 'unflag';

  const DO_UNFLAG = 'do.unflag';
  const DO_IGNORE_UNFLAG = 'do.ignore-unflag';

  public function getHeraldActionName() {
    return pht('Remove flag');
  }

  public function applyEffect($object, HeraldEffect $effect) {
    $phid = $this->getAdapter()->getPHID();
    $rule = $effect->getRule();
    $author = $rule->getAuthor();

    $flag = PhorgeFlagQuery::loadUserFlag($author, $phid);
    if (!$flag) {
      $this->logEffect(self::DO_IGNORE_UNFLAG, null);
      return;
    }

    if ($flag->getColor() !== $effect->getTarget()) {
      $this->logEffect(self::DO_IGNORE_UNFLAG, $flag->getColor());
      return;
    }

    $flag->delete();

    $this->logEffect(self::DO_UNFLAG, $flag->getColor());
  }

  public function getHeraldActionValueType() {
    return id(new HeraldSelectFieldValue())
      ->setKey('flag.color')
      ->setOptions(PhorgeFlagColor::getColorNameMap())
      ->setDefault(PhorgeFlagColor::COLOR_BLUE);
  }

  protected function getActionEffectMap() {
    return array(
      self::DO_IGNORE_UNFLAG => array(
        'icon' => 'fa-times',
        'color' => 'grey',
        'name' => pht('Did Not Remove Flag'),
      ),
      self::DO_UNFLAG => array(
        'icon' => 'fa-flag',
        'name' => pht('Removed Flag'),
      ),
    );
  }

  public function renderActionDescription($value) {
    $color = PhorgeFlagColor::getColorName($value);
    return pht('Remove %s flag.', $color);
  }

  protected function renderActionEffectDescription($type, $data) {
    switch ($type) {
      case self::DO_IGNORE_UNFLAG:
        if (!$data) {
          return pht('Not marked with any flag.');
        } else {
          return pht(
            'Marked with flag of the wrong color ("%s").',
            PhorgeFlagColor::getColorName($data));
        }
      case self::DO_UNFLAG:
        return pht(
          'Removed "%s" flag.',
          PhorgeFlagColor::getColorName($data));
    }
  }

}
