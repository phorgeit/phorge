<?php

final class PhorgeMailAmazonSNSAdapter
  extends PhorgeMailAdapter {

  const ADAPTERTYPE = 'sns';

  public function getSupportedMessageTypes() {
    return array(
      PhorgeMailSMSMessage::MESSAGETYPE,
    );
  }

  protected function validateOptions(array $options) {
    PhutilTypeSpec::checkMap(
      $options,
      array(
        'access-key' => 'string',
        'secret-key' => 'string',
        'endpoint' => 'string',
        'region' => 'string',
      ));
  }

  public function newDefaultOptions() {
    return array(
      'access-key' => null,
      'secret-key' => null,
      'endpoint' => null,
      'region' => null,
    );
  }

  public function sendMessage(PhorgeMailExternalMessage $message) {
    $access_key = $this->getOption('access-key');

    $secret_key = $this->getOption('secret-key');
    $secret_key = new PhutilOpaqueEnvelope($secret_key);

    $endpoint = $this->getOption('endpoint');
    $region = $this->getOption('region');

    $to_number = $message->getToNumber();
    $text_body = $message->getTextBody();

    $params = array(
      'Version' => '2010-03-31',
      'Action' => 'Publish',
      'PhoneNumber' => $to_number->toE164(),
      'Message' => $text_body,
    );

    return id(new PhorgeAmazonSNSFuture())
      ->setParameters($params)
      ->setEndpoint($endpoint)
      ->setAccessKey($access_key)
      ->setSecretKey($secret_key)
      ->setRegion($region)
      ->setTimeout(60)
      ->resolve();
  }

}
