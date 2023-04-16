<?php

final class PhorgeMailReceiverTestCase extends PhorgeTestCase {

  public function testAddressSimilarity() {
    $env = PhorgeEnv::beginScopedEnv();
    $env->overrideEnvConfig('metamta.single-reply-handler-prefix', 'prefix');

    $base = 'alincoln@example.com';

    $same = array(
      'alincoln@example.com',
      '"Abrahamn Lincoln" <alincoln@example.com>',
      'ALincoln@example.com',
      'prefix+alincoln@example.com',
    );

    foreach ($same as $address) {
      $this->assertTrue(
        PhorgeMailUtil::matchAddresses(
          new PhutilEmailAddress($base),
          new PhutilEmailAddress($address)),
        pht('Address %s', $address));
    }

    $diff = array(
      'a.lincoln@example.com',
      'aluncoln@example.com',
      'prefixalincoln@example.com',
      'badprefix+alincoln@example.com',
      'bad+prefix+alincoln@example.com',
      'prefix+alincoln+sufffix@example.com',
    );

    foreach ($diff as $address) {
      $this->assertFalse(
        PhorgeMailUtil::matchAddresses(
          new PhutilEmailAddress($base),
          new PhutilEmailAddress($address)),
        pht('Address: %s', $address));
    }
  }

  public function testReservedAddresses() {
    $default_address = id(new PhorgeMailEmailEngine())
      ->newDefaultEmailAddress();

    $void_address = id(new PhorgeMailEmailEngine())
      ->newVoidEmailAddress();

    $map = array(
      'alincoln@example.com' => false,
      'sysadmin@example.com' => true,
      'hostmaster@example.com' => true,
      '"Walter Ebmaster" <webmaster@example.com>' => true,
      (string)$default_address => true,
      (string)$void_address => true,
    );

    foreach ($map as $raw_address => $expect) {
      $address = new PhutilEmailAddress($raw_address);

      $this->assertEqual(
        $expect,
        PhorgeMailUtil::isReservedAddress($address),
        pht('Reserved: %s', $raw_address));
    }
  }

}
