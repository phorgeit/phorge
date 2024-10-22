#!/usr/bin/env php
<?php

// NOTE: This script is very oldschool and takes the environment as an argument.
// Some day, we could take a shot at cleaning this up.
if ($argc > 1) {
  foreach (array_slice($argv, 1) as $arg) {
    if (!preg_match('/^-/', $arg)) {
      $_SERVER['PHABRICATOR_ENV'] = $arg;
      break;
    }
  }
}

$root = dirname(dirname(dirname(__FILE__)));
require_once $root.'/scripts/__init_script__.php';
require_once $root.'/externals/mimemailparser/Contracts/CharsetManager.php';
require_once $root.'/externals/mimemailparser/Contracts/Middleware.php';
require_once $root.'/externals/mimemailparser/Parser.php';
require_once $root.'/externals/mimemailparser/Charset.php';
require_once $root.'/externals/mimemailparser/Attachment.php';
require_once $root.'/externals/mimemailparser/Exception.php';
require_once $root.'/externals/mimemailparser/Middleware.php';
require_once $root.'/externals/mimemailparser/MiddlewareStack.php';
require_once $root.'/externals/mimemailparser/MimePart.php';

$args = new PhutilArgumentParser($argv);
$args->parseStandardArguments();
$args->parse(
  array(
    array(
      'name' => 'process-duplicates',
      'help' => pht(
        "Process this message, even if it's a duplicate of another message. ".
        "This is mostly useful when debugging issues with mail routing."),
    ),
    array(
      'name' => 'env',
      'wildcard' => true,
    ),
  ));

if (!extension_loaded('mailparse')) {
  throw new Exception(
    pht(
      'PhpMimeMailParser for handling incoming mail requires the PHP '.
      'mailparse extension to be installed.'));
}

$parser = new \PhpMimeMailParser\Parser();
$parser->setText(file_get_contents('php://stdin'));

$content = array();
foreach (array('text', 'html') as $part) {
  $part_body = $parser->getMessageBody($part);
  $content[$part] = $part_body;
}

$headers = $parser->getHeaders();
if (array_key_exists('subject', $headers)) {
  $headers['subject'] = phutil_decode_mime_header($headers['subject']);
}
$headers['from'] = phutil_decode_mime_header($headers['from']);

if ($args->getArg('process-duplicates')) {
  $headers['message-id'] = Filesystem::readRandomCharacters(64);
}

$received = new PhabricatorMetaMTAReceivedMail();
$received->setHeaders($headers);
$received->setBodies($content);

$attachments = array();
foreach ($parser->getAttachments() as $attachment) {
  if (preg_match('@text/(plain|html)@', $attachment->getContentType()) &&
      $attachment->getContentDisposition() == 'inline') {
    // If this is an "inline" attachment with some sort of text content-type,
    // do not treat it as a file for attachment. MimeMailParser already picked
    // it up in the getMessageBody() call above. We still want to treat 'inline'
    // attachments with other content types (e.g., images) as attachments.
    continue;
  }

  $file = PhabricatorFile::newFromFileData(
    $attachment->getContent(),
    array(
      'name' => $attachment->getFilename(),
      'viewPolicy' => PhabricatorPolicies::POLICY_NOONE,
    ));
  $attachments[] = $file->getPHID();
}

try {
  $received->setAttachments($attachments);
  $received->save();
  $received->processReceivedMail();
} catch (Exception $e) {
  $received
    ->setMessage(pht('EXCEPTION: %s', $e->getMessage()))
    ->save();

  throw $e;
}
