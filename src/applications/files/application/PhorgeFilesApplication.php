<?php

final class PhorgeFilesApplication extends PhorgeApplication {

  public function getBaseURI() {
    return '/file/';
  }

  public function getName() {
    return pht('Files');
  }

  public function getShortDescription() {
    return pht('Store and Share Files');
  }

  public function getIcon() {
    return 'fa-file';
  }

  public function getTitleGlyph() {
    return "\xE2\x87\xAA";
  }

  public function getFlavorText() {
    return pht('Blob store for Pokemon pictures.');
  }

  public function getApplicationGroup() {
    return self::GROUP_UTILITIES;
  }

  public function canUninstall() {
    return false;
  }

  public function getRemarkupRules() {
    return array(
      new PhorgeEmbedFileRemarkupRule(),
      new PhorgeImageRemarkupRule(),
    );
  }

  public function supportsEmailIntegration() {
    return true;
  }

  public function getAppEmailBlurb() {
    return pht(
      'Send emails with file attachments to these addresses to upload '.
      'files. %s',
      phutil_tag(
        'a',
        array(
          'href' => $this->getInboundEmailSupportLink(),
        ),
        pht('Learn More')));
  }

  protected function getCustomCapabilities() {
    return array(
      FilesDefaultViewCapability::CAPABILITY => array(
        'caption' => pht('Default view policy for newly created files.'),
        'template' => PhorgeFileFilePHIDType::TYPECONST,
        'capability' => PhorgePolicyCapability::CAN_VIEW,
      ),
    );
  }

  public function getRoutes() {
    return array(
      '/F(?P<id>[1-9]\d*)(?:\$(?P<lines>\d+(?:-\d+)?))?'
        => 'PhorgeFileViewController',
      '/file/' => array(
        '(query/(?P<queryKey>[^/]+)/)?' => 'PhorgeFileListController',
        'view/(?P<id>[1-9]\d*)/'.
          '(?:(?P<engineKey>[^/]+)/)?'.
          '(?:\$(?P<lines>\d+(?:-\d+)?))?'
          => 'PhorgeFileViewController',
        'info/(?P<phid>[^/]+)/' => 'PhorgeFileViewController',
        'upload/' => 'PhorgeFileUploadController',
        'dropupload/' => 'PhorgeFileDropUploadController',
        'compose/' => 'PhorgeFileComposeController',
        'thread/(?P<phid>[^/]+)/' => 'PhorgeFileLightboxController',
        'delete/(?P<id>[1-9]\d*)/' => 'PhorgeFileDeleteController',
        $this->getEditRoutePattern('edit/')
          => 'PhorgeFileEditController',
        'imageproxy/' => 'PhorgeFileImageProxyController',
        'transforms/(?P<id>[1-9]\d*)/' =>
          'PhorgeFileTransformListController',
        'uploaddialog/(?P<single>single/)?'
          => 'PhorgeFileUploadDialogController',
        'iconset/(?P<key>[^/]+)/' => array(
          'select/' => 'PhorgeFileIconSetSelectController',
        ),
        'document/(?P<engineKey>[^/]+)/(?P<phid>[^/]+)/'
          => 'PhorgeFileDocumentController',
        'ui/' => array(
          'detach/(?P<objectPHID>[^/]+)/(?P<filePHID>[^/]+)/'
            => 'PhorgeFileDetachController',
          'curtain/' => array(
            'list/(?P<phid>[^/]+)/'
              => 'PhorgeFileUICurtainListController',
            'attach/(?P<objectPHID>[^/]+)/(?P<filePHID>[^/]+)/'
              => 'PhorgeFileUICurtainAttachController',
          ),
        ),
      ) + $this->getResourceSubroutes(),
    );
  }

  public function getResourceRoutes() {
    return array(
      '/file/' => $this->getResourceSubroutes(),
    );
  }

  private function getResourceSubroutes() {
    return array(
      '(?P<kind>data|download)/'.
        '(?:@(?P<instance>[^/]+)/)?'.
        '(?P<key>[^/]+)/'.
        '(?P<phid>[^/]+)/'.
        '(?:(?P<token>[^/]+)/)?'.
        '.*'
        => 'PhorgeFileDataController',
      'xform/'.
        '(?:@(?P<instance>[^/]+)/)?'.
        '(?P<transform>[^/]+)/'.
        '(?P<phid>[^/]+)/'.
        '(?P<key>[^/]+)/'
        => 'PhorgeFileTransformController',
    );
  }

  public function getMailCommandObjects() {
    return array(
      'file' => array(
        'name' => pht('Email Commands: Files'),
        'header' => pht('Interacting with Files'),
        'object' => new PhorgeFile(),
        'summary' => pht(
          'This page documents the commands you can use to interact with '.
          'files.'),
      ),
    );
  }

  public function getQuicksandURIPatternBlacklist() {
    return array(
      '/file/(data|download)/.*',
    );
  }

}
