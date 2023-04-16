<?php

final class PhorgeWordPressAuthProvider
  extends PhorgeOAuth2AuthProvider {

  public function getProviderName() {
    return pht('WordPress.com');
  }

  protected function getProviderConfigurationHelp() {
    $uri = PhorgeEnv::getProductionURI('/');
    $callback_uri = PhorgeEnv::getURI($this->getLoginURI());

    return pht(
      "To configure WordPress.com OAuth, create a new WordPress.com ".
      "Application here:\n\n".
      "https://developer.wordpress.com/apps/new/.".
      "\n\n".
      "You should use these settings in your application:".
      "\n\n".
      "  - **URL:** Set this to your full domain with protocol. For this ".
      "    server, the correct value is: `%s`\n".
      "  - **Redirect URL**: Set this to: `%s`\n".
      "\n\n".
      "Once you've created an application, copy the **Client ID** and ".
      "**Client Secret** into the fields above.",
      $uri,
      $callback_uri);
  }

  protected function newOAuthAdapter() {
    return new PhutilWordPressAuthAdapter();
  }

  protected function getLoginIcon() {
    return 'WordPressCOM';
  }
}
