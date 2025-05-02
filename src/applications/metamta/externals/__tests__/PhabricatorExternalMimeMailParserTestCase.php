<?php

/**
 * @phutil-external-symbol class \PhpMimeMailParser\Parser
 */
final class PhabricatorExternalMimeMailParserTestCase
  extends PhabricatorTestCase {

  /**
   * Be sure to have mimemailparser classes.
   */
  private function initMimemailparser() {
    // Not having this extension is probably a frequent error locally.
    if (!function_exists('mailparse_msg_create')) {
      $this->assertSkipped(pht('PHP mailparse extension is not installed'));
    }

    // Root of Phorge installation
    $root = dirname(dirname(dirname(dirname(dirname(__DIR__)))));

    // This is safe to be called multiple times.
    require_once $root.'/externals/mimemailparser/__init.php';
  }

  public function testMailParse() {
    $this->initMimemailparser();

    $tests = array(
      // Test case 0.
      // Check that no silly "ISO" things are in the headers,
      // even with esoteric accents.
      __DIR__.'/data/test_accents',
    );

    foreach ($tests as $test) {
      $test_file = $test.'.mbox';
      $test_file_basename = basename($test_file);
      $expected_headers_file = $test.'.headers.txt';

      // Unpack the test.
      $mail_content = Filesystem::readFile($test_file);
      $expected_headers_raw = Filesystem::readFile($expected_headers_file);
      $expected_headers = $this->readAssociativeConf($expected_headers_raw);

      // Parse the email.
      $parser = new \PhpMimeMailParser\Parser();
      $parser->setText($mail_content);

      // Check email fields headers from the corresponding txt file.
      $headers = $parser->getHeaders();
      foreach ($expected_headers as $k => $v) {
        $this->assertEqual($v, $headers[$k], pht(
          "Read the header '%s' from the test email %s",
          $k,
          $test_file_basename));
      }

      // If you are creative enough, you can do some tests on the body.
//      $content = array();
//      foreach (array('text', 'html') as $part) {
//        $part_body = $parser->getMessageBody($part);
//        $content[$part] = $part_body;
//      }
    }
  }

  /**
   * Get an associative array from "key:value" lines.
   * @return array
   */
  private function readAssociativeConf(string $conf_raw) {
    $conf = [];
    $lines = explode("\n", $conf_raw);
    foreach ($lines as $line) {
      $line = trim($line);
      if ($line !== '') {
        list($k, $v) = explode(':', $line, 2);
        $conf[$k] = $v;
      }
    }
    return $conf;
  }

}
