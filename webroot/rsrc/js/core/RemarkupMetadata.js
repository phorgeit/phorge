/**
 * @requires javelin-install
 *           javelin-dom
 *           javelin-json
 * @provides phabricator-remarkup-metadata
 * @javelin
 */

JX.install('RemarkupMetadata', {

  construct: function(metadataValue, metadataID) {
    if (JX.RemarkupMetadata._metadataValue == null) {
      JX.RemarkupMetadata._metadataValue = {};
    }
    if (!JX.RemarkupMetadata._metadataValue.hasOwnProperty(metadataID)) {
      JX.RemarkupMetadata._metadataValue[metadataID] = metadataValue;
    }
    this._metadataNode = JX.$(metadataID);
    this._metadataID = metadataID;
  },

  statics: {
    _metadataValue: null
  },

  members: {
    _metadataNode: null,
    _metadataID: null,

    _writeMetadata: function() {
      this._metadataNode.value = JX.JSON.stringify(
        JX.RemarkupMetadata._metadataValue[this._metadataID]);
    },

    getMetadata: function(key, default_value) {
      if (JX.RemarkupMetadata._metadataValue[this._metadataID]
        .hasOwnProperty(key)) {
        return JX.RemarkupMetadata._metadataValue[this._metadataID][key];
      }
      return default_value;
    },

    setMetadata: function(key, value) {
      JX.RemarkupMetadata._metadataValue[this._metadataID][key] = value;
      this._writeMetadata();
    }
  }

});
