<?php

abstract class PhorgeSite extends AphrontSite {

  public function shouldRequireHTTPS() {
    // If this is an intracluster request, it's okay for it to use HTTP even
    // if the site otherwise requires HTTPS. It is common to terminate SSL at
    // a load balancer and use plain HTTP from then on, and administrators are
    // usually not concerned about attackers observing traffic within a
    // datacenter.
    if (PhorgeEnv::isClusterRemoteAddress()) {
      return false;
    }

    return PhorgeEnv::getEnvConfig('security.require-https');
  }

}
