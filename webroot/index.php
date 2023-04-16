<?php

phorge_startup();

$fatal_exception = null;
try {
  PhorgeStartup::beginStartupPhase('libraries');
  PhorgeStartup::loadCoreLibraries();

  PhorgeStartup::beginStartupPhase('purge');
  PhorgeCaches::destroyRequestCache();

  PhorgeStartup::beginStartupPhase('sink');
  $sink = new AphrontPHPHTTPSink();

  // PHP introduced a "Throwable" interface in PHP 7 and began making more
  // runtime errors throw as "Throwable" errors. This is generally good, but
  // makes top-level exception handling that is compatible with both PHP 5
  // and PHP 7 a bit tricky.

  // In PHP 5, "Throwable" does not exist, so "catch (Throwable $ex)" catches
  // nothing.

  // In PHP 7, various runtime conditions raise an Error which is a Throwable
  // but NOT an Exception, so "catch (Exception $ex)" will not catch them.

  // To cover both cases, we "catch (Exception $ex)" to catch everything in
  // PHP 5, and most things in PHP 7. Then, we "catch (Throwable $ex)" to catch
  // everything else in PHP 7. For the most part, we only need to do this at
  // the top level.

  $main_exception = null;
  try {
    PhorgeStartup::beginStartupPhase('run');
    AphrontApplicationConfiguration::runHTTPRequest($sink);
  } catch (Exception $ex) {
    $main_exception = $ex;
  } catch (Throwable $ex) {
    $main_exception = $ex;
  }

  if ($main_exception) {
    $response_exception = null;
    try {
      $response = new AphrontUnhandledExceptionResponse();
      $response->setException($main_exception);
      $response->setShowStackTraces($sink->getShowStackTraces());

      PhorgeStartup::endOutputCapture();
      $sink->writeResponse($response);
    } catch (Exception $ex) {
      $response_exception = $ex;
    } catch (Throwable $ex) {
      $response_exception = $ex;
    }

    // If we hit a rendering exception, ignore it and throw the original
    // exception. It is generally more interesting and more likely to be
    // the root cause.

    if ($response_exception) {
      throw $main_exception;
    }
  }
} catch (Exception $ex) {
  $fatal_exception = $ex;
} catch (Throwable $ex) {
  $fatal_exception = $ex;
}

if ($fatal_exception) {
  PhorgeStartup::didEncounterFatalException(
    'Core Exception',
    $fatal_exception,
    false);
}

function phorge_startup() {
  // Load the PhorgeStartup class itself.
  $t_startup = microtime(true);
  $root = dirname(dirname(__FILE__));
  require_once $root.'/support/startup/PhorgeStartup.php';

  // Load client limit classes so the preamble can configure limits.
  require_once $root.'/support/startup/PhorgeClientLimit.php';
  require_once $root.'/support/startup/PhorgeClientRateLimit.php';
  require_once $root.'/support/startup/PhorgeClientConnectionLimit.php';
  require_once $root.'/support/startup/preamble-utils.php';

  // If the preamble script exists, load it.
  $t_preamble = microtime(true);
  $preamble_path = $root.'/support/preamble.php';
  if (file_exists($preamble_path)) {
    require_once $preamble_path;
  }

  $t_hook = microtime(true);
  PhorgeStartup::didStartup($t_startup);

  PhorgeStartup::recordStartupPhase('startup.init', $t_startup);
  PhorgeStartup::recordStartupPhase('preamble', $t_preamble);
  PhorgeStartup::recordStartupPhase('hook', $t_hook);
}
