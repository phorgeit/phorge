<?php

/**
 * Class that can be converted into a string,
 * to mimic the conversion pht() will do
 * but not a number, since the double-conversion loses data.
 */
final class PhorgeStringablePlaceholder extends Phobject {
  public function __toString() { return 'blah blah'; }
}
