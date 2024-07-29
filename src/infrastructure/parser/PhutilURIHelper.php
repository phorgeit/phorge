<?php

/**
 * A simple wrapper for PhutilURI, to be aware of the
 * relative/absolute context, and other minor things.
 */
final class PhutilURIHelper extends Phobject {

  /**
   * String version of your original URI.
   * @var string
   */
  private $uriStr;

  /**
   * Structured version of your URI.
   * @var PhutilURI
   */
  private $phutilUri;

  /**
   * @param string|PhutilURI
   */
  public function __construct($uri) {

    // Keep the original string for basic checks.
    $this->uriStr = phutil_string_cast($uri);

    // A PhutilURI may be useful. If available, import that as-is.
    // Note that the constructor PhutilURI(string) is a bit expensive.
    if ($uri instanceof PhutilURI) {
      $this->phutilUri = $uri;
    }
  }

  /**
   * Check if the URI points to Phorge itself.
   * @return bool
   */
  public function isSelf() {
    // The backend prefers a PhutilURI object, if available.
    $uri = $this->phutilUri ? $this->phutilUri : $this->uriStr;
    return PhabricatorEnv::isSelfURI($uri);
  }

  /**
   * Check whenever an URI is just a simple fragment without path and protocol.
   * @return bool
   */
  public function isAnchor() {
    return $this->isStartingWithChar('#');
  }

  /**
   * Check whenever an URI starts with a slash (no protocol, etc.)
   * @return bool
   */
  public function isStartingWithSlash() {
    return $this->isStartingWithChar('/');
  }

  /**
   * A sane default.
   */
  public function __toString() {
    return $this->uriStr;
  }

  /**
   * Check whenever the URI starts with the provided character.
   * @param string $char String that MUST have length of 1.
   * @return boolean
   */
  private function isStartingWithChar($char) {
    return strncmp($this->uriStr, $char, 1) === 0;
  }

}
