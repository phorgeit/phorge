<?php

/**
 * Amazon S3 file storage engine. This engine scales well but is relatively
 * high-latency since data has to be pulled off S3.
 *
 * @task internal Internals
 */
final class PhorgeS3FileStorageEngine
  extends PhorgeFileStorageEngine {


/* -(  Engine Metadata  )---------------------------------------------------- */


  /**
   * This engine identifies as `amazon-s3`.
   */
  public function getEngineIdentifier() {
    return 'amazon-s3';
  }

  public function getEnginePriority() {
    return 100;
  }

  public function canWriteFiles() {
    $bucket = PhorgeEnv::getEnvConfig('storage.s3.bucket');
    $access_key = PhorgeEnv::getEnvConfig('amazon-s3.access-key');
    $secret_key = PhorgeEnv::getEnvConfig('amazon-s3.secret-key');
    $endpoint = PhorgeEnv::getEnvConfig('amazon-s3.endpoint');
    $region = PhorgeEnv::getEnvConfig('amazon-s3.region');

    return phutil_nonempty_string($bucket) &&
      phutil_nonempty_string($access_key) &&
      phutil_nonempty_string($secret_key) &&
      phutil_nonempty_string($endpoint) &&
      phutil_nonempty_string($region);
  }


/* -(  Managing File Data  )------------------------------------------------- */


  /**
   * Writes file data into Amazon S3.
   */
  public function writeFile($data, array $params) {
    $s3 = $this->newS3API();

    // Generate a random name for this file. We add some directories to it
    // (e.g. 'abcdef123456' becomes 'ab/cd/ef123456') to make large numbers of
    // files more browsable with web/debugging tools like the S3 administration
    // tool.
    $seed = Filesystem::readRandomCharacters(20);
    $parts = array();
    $parts[] = 'phorge';

    $instance_name = PhorgeEnv::getEnvConfig('cluster.instance');
    if (phutil_nonempty_string($instance_name)) {
      $parts[] = $instance_name;
    }

    $parts[] = substr($seed, 0, 2);
    $parts[] = substr($seed, 2, 2);
    $parts[] = substr($seed, 4);

    $name = implode('/', $parts);

    AphrontWriteGuard::willWrite();
    $profiler = PhutilServiceProfiler::getInstance();
    $call_id = $profiler->beginServiceCall(
      array(
        'type' => 's3',
        'method' => 'putObject',
      ));

    $s3
      ->setParametersForPutObject($name, $data)
      ->resolve();

    $profiler->endServiceCall($call_id, array());

    return $name;
  }


  /**
   * Load a stored blob from Amazon S3.
   */
  public function readFile($handle) {
    $s3 = $this->newS3API();

    $profiler = PhutilServiceProfiler::getInstance();
    $call_id = $profiler->beginServiceCall(
      array(
        'type' => 's3',
        'method' => 'getObject',
      ));

    $result = $s3
      ->setParametersForGetObject($handle)
      ->resolve();

    $profiler->endServiceCall($call_id, array());

    return $result;
  }


  /**
   * Delete a blob from Amazon S3.
   */
  public function deleteFile($handle) {
    $s3 = $this->newS3API();

    AphrontWriteGuard::willWrite();
    $profiler = PhutilServiceProfiler::getInstance();
    $call_id = $profiler->beginServiceCall(
      array(
        'type' => 's3',
        'method' => 'deleteObject',
      ));

    $s3
      ->setParametersForDeleteObject($handle)
      ->resolve();

    $profiler->endServiceCall($call_id, array());
  }


/* -(  Internals  )---------------------------------------------------------- */


  /**
   * Retrieve the S3 bucket name.
   *
   * @task internal
   */
  private function getBucketName() {
    $bucket = PhorgeEnv::getEnvConfig('storage.s3.bucket');
    if (!$bucket) {
      throw new PhorgeFileStorageConfigurationException(
        pht(
          "No '%s' specified!",
          'storage.s3.bucket'));
    }
    return $bucket;
  }

  /**
   * Create a new S3 API object.
   *
   * @task internal
   */
  private function newS3API() {
    $access_key = PhorgeEnv::getEnvConfig('amazon-s3.access-key');
    $secret_key = PhorgeEnv::getEnvConfig('amazon-s3.secret-key');
    $region = PhorgeEnv::getEnvConfig('amazon-s3.region');
    $endpoint = PhorgeEnv::getEnvConfig('amazon-s3.endpoint');

    return id(new PhutilAWSS3Future())
      ->setAccessKey($access_key)
      ->setSecretKey(new PhutilOpaqueEnvelope($secret_key))
      ->setRegion($region)
      ->setEndpoint($endpoint)
      ->setBucket($this->getBucketName());
  }

}
