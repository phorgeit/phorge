<?php

final class PhutilURIHelperTestCase extends PhabricatorTestCase {

 public function testPhutilURIHelper() {

    // Every row is a test. Every column is:
    // - 0: name of the test
    // - 1: test input value
    // - 2: is the URI pointing to Phorge itself?
    // - 3: is the URI an anchor? (no domain, no protocol)
    // - 4: is the URI starting with a slash? (no domain, no protocol)
    $tests = array(
      array('internal anchor', '#asd', true, true, false),
      array('internal relative dir', '/foo/', true, false, true),
      array('internal relative dir also', 'foo/', true, false, false),
      array('internal root dir', '/', true, false, true),
      array('internal root dir', './', true, false, false),
      array('internal root dir', '../', true, false, false),
      array('internal root dir', '/#asd', true, false, true),
      array('external', 'https://gnu.org/', false, false, false),
      array('external anchor', 'https://gnu.org/#asd', false, false, false),
    );

    // Add additional self-tests if base URI is available.
    $base = PhabricatorEnv::getEnvConfigIfExists('phabricator.base-uri');
    if ($base) {
      $domain = id(new PhutilURI($base))->getDomain();
      $tests[] = array('base uri', $base, true, false, false);
      $tests[] = array('base uri anchor', "{$base}#asd", true, false, false);
    }

    foreach ($tests as $test) {
      $name = $test[0];
      $uri = $test[1];
      $is_self = $test[2];
      $is_anchor = $test[3];
      $is_slash = $test[4];

      // Test input variants for the constructor of PhutilURIHelper.
      $uri_variants = array(
        $uri,
        new PhutilURI($uri),
      );
      foreach ($uri_variants as $variant_uri) {

        $test_name = pht("test %s value '%s' (from '%s' type %s)",
          $name, $variant_uri, $uri, phutil_describe_type($variant_uri));

        $uri = new PhutilURIHelper($variant_uri);

        $this->assertEqual($is_self, $uri->isSelf(),
          pht('%s - points to myself', $test_name));

        $this->assertEqual($is_anchor, $uri->isAnchor(),
          pht('%s - is just an anchor', $test_name));

        $this->assertEqual($is_slash, $uri->isStartingWithSlash(),
          pht('%s - is starting with slash', $test_name));
      }
    }
  }
}
