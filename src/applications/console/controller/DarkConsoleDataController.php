<?php

final class DarkConsoleDataController extends PhorgeController {

  public function shouldRequireLogin() {
    return !PhorgeEnv::getEnvConfig('darkconsole.always-on');
  }

  public function shouldRequireEnabledUser() {
    return !PhorgeEnv::getEnvConfig('darkconsole.always-on');
  }

  public function shouldAllowPartialSessions() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $key = $request->getURIData('key');

    $cache = new PhorgeKeyValueDatabaseCache();
    $cache = new PhutilKeyValueCacheProfiler($cache);
    $cache->setProfiler(PhutilServiceProfiler::getInstance());

    $result = $cache->getKey('darkconsole:'.$key);
    if (!$result) {
      return new Aphront400Response();
    }

    try {
      $result = phutil_json_decode($result);
    } catch (PhutilJSONParserException $ex) {
      return new Aphront400Response();
    }

    if ($result['vers'] != DarkConsoleCore::STORAGE_VERSION) {
      return new Aphront400Response();
    }

    if ($result['user'] != $viewer->getPHID()) {
      return new Aphront400Response();
    }

    $output = array();
    $output['tabs'] = $result['tabs'];
    $output['panel'] = array();

    foreach ($result['data'] as $class => $data) {
      try {
        $obj = newv($class, array());
        $obj->setData($data);
        $obj->setRequest($request);

        $panel = $obj->renderPanel();

        // Because cookie names can now be prefixed, wipe out any cookie value
        // with the session cookie name anywhere in its name.
        $pattern = '('.preg_quote(PhorgeCookies::COOKIE_SESSION).')';
        foreach ($_COOKIE as $cookie_name => $cookie_value) {
          if (preg_match($pattern, $cookie_name)) {
            $panel = PhutilSafeHTML::applyFunction(
              'str_replace',
              $cookie_value,
              '(session-key)',
              $panel);
          }
        }

        $output['panel'][$class] = $panel;
      } catch (Exception $ex) {
        $output['panel'][$class] = 'error';
      }
    }

    return id(new AphrontAjaxResponse())->setContent($output);
  }

}
