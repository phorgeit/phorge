<?php

/**
 * Consolidates Phabricator application cookies, including registration
 * and session management.
 *
 * @task clientid   Client ID Cookie
 * @task next       Next URI Cookie
 */
final class PhabricatorCookies extends Phobject {

  /**
   * Stores the login username for password authentication. This is just a
   * display value for convenience, used to prefill the login form. It is not
   * authoritative.
   */
  const COOKIE_USERNAME       = 'phusr';


  /**
   * Stores the user's current session ID. This is authoritative and establishes
   * the user's identity.
   */
  const COOKIE_SESSION        = 'phsid';


  /**
   * Stores a secret used during new account registration to prevent an attacker
   * from tricking a victim into registering an account which is linked to
   * credentials the attacker controls.
   */
  const COOKIE_REGISTRATION   = 'phreg';


  /**
   * Stores a secret used during OAuth2 handshakes to prevent various attacks
   * where an attacker hands a victim a URI corresponding to the middle of an
   * OAuth2 workflow and we might otherwise do something sketchy. Particularly,
   * this corresponds to the OAuth2 "code".
   */
  const COOKIE_CLIENTID       = 'phcid';


  /**
   * Stores the URI to redirect the user to after login. This allows users to
   * visit a path like `/feed/`, be prompted to login, and then be redirected
   * back to `/feed/` after the workflow completes.
   */
  const COOKIE_NEXTURI        = 'next_uri';


  /**
   * Stores a hint that the user should be moved directly into high security
   * after upgrading a partial login session. This is used during password
   * recovery to avoid a double-prompt.
   */
  const COOKIE_HISEC          = 'jump_to_hisec';


  /**
   * Stores an invite code.
   */
  const COOKIE_INVITE = 'invite';


  /**
   * Stores a workflow completion across a redirect-after-POST following a
   * form submission. This can be used to show "Changes Saved" messages.
   */
  const COOKIE_SUBMIT = 'phfrm';


/* -(  Client ID Cookie  )--------------------------------------------------- */


  /**
   * Set the client ID cookie. This is a random cookie used like a CSRF value
   * during authentication workflows.
   *
   * @param AphrontRequest $request Request to modify.
   * @return void
   * @task clientid
   */
  public static function setClientIDCookie(AphrontRequest $request) {

    // NOTE: See T3471 for some discussion. Some browsers and browser extensions
    // can make duplicate requests, so we overwrite this cookie only if it is
    // not present in the request. The cookie lifetime is limited by making it
    // temporary and clearing it when users log out.

    $value = $request->getCookie(self::COOKIE_CLIENTID);
    if (!phutil_nonempty_string($value)) {
      $request->setTemporaryCookie(
        self::COOKIE_CLIENTID,
        Filesystem::readRandomCharacters(16));
    }
  }


/* -(  Next URI Cookie  )---------------------------------------------------- */


  /**
   * Set the Next URI cookie. We only write the cookie if it wasn't recently
   * written, to avoid writing over a real URI with a bunch of "humans.txt"
   * stuff. See T3793 for discussion.
   *
   * @param   AphrontRequest    $request Request to write to.
   * @param   string            $next_uri URI to write.
   * @param   bool              $force (optional) Write this cookie even if we
   *                            have a fresh cookie already.
   * @return  void
   *
   * @task next
   */
  public static function setNextURICookie(
    AphrontRequest $request,
    $next_uri,
    $force = false) {

    if (!$force) {
      $cookie_value = $request->getCookie(self::COOKIE_NEXTURI);
      list($set_at, $current_uri) = self::parseNextURICookie($cookie_value);

      // If the cookie was set within the last 2 minutes, don't overwrite it.
      // Primarily, this prevents browser requests for resources which do not
      // exist (like "humans.txt" and various icons) from overwriting a normal
      // URI like "/feed/".
      if ($set_at > (time() - 120)) {
        return;
      }
    }

    $new_value = time().','.$next_uri;
    $request->setTemporaryCookie(self::COOKIE_NEXTURI, $new_value);
  }


  /**
   * Read the URI out of the Next URI cookie.
   *
   * @param   AphrontRequest  $request Request to examine.
   * @return  string|null     Next URI cookie's URI value.
   *
   * @task next
   */
  public static function getNextURICookie(AphrontRequest $request) {
    $cookie_value = $request->getCookie(self::COOKIE_NEXTURI);
    list($set_at, $next_uri) = self::parseNextURICookie($cookie_value);

    return $next_uri;
  }


  /**
   * Parse a Next URI cookie into its components.
   *
   * @param   string                 $cookie Raw cookie value.
   * @return  list<int,string>|null  List of timestamp and URI, or null if the
   *   cookie is empty or null.
   *
   * @task next
   */
  private static function parseNextURICookie($cookie) {
    // Old cookies look like: /uri
    // New cookies look like: timestamp,/uri

    if (!phutil_nonempty_string($cookie)) {
      return null;
    }

    if (strpos($cookie, ',') !== false) {
      list($timestamp, $uri) = explode(',', $cookie, 2);
      return array((int)$timestamp, $uri);
    }

    return array(0, $cookie);
  }

}
