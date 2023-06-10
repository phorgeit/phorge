/**
 * @provides javelin-behavior-aphront-drag-and-drop-textarea
 * @requires javelin-behavior
 *           javelin-dom
 *           phabricator-drag-and-drop-file-upload
 *           phabricator-textareautils
 *           phabricator-remarkup-metadata
 */

JX.behavior('aphront-drag-and-drop-textarea', function(config) {

  var target = JX.$(config.target);

  if (JX.PhabricatorDragAndDropFileUpload.isSupported()) {
    var drop = new JX.PhabricatorDragAndDropFileUpload(target)
      .setURI(config.uri)
      .setChunkThreshold(config.chunkThreshold);

    drop.listen('didBeginDrag', function() {
      JX.DOM.alterClass(target, config.activatedClass, true);
    });

    drop.listen('didEndDrag', function() {
      JX.DOM.alterClass(target, config.activatedClass, false);
    });

    drop.listen('didUpload', function(file) {
      JX.TextAreaUtils.insertFileReference(target, file);

      if(config.remarkupMetadataID) {
        // Try to auto-attach files by default
        // https://we.phorge.it/T15106
        var metadata = new JX.RemarkupMetadata(config.remarkupMetadataValue,
          config.remarkupMetadataID);
        var phids = metadata.getMetadata('attachedFilePHIDs', []);
        phids.push(file.getPHID());
        metadata.setMetadata('attachedFilePHIDs', phids);
      }
    });

    drop.start();
  }

});
