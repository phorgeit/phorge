/**
 * @requires javelin-install
 * @provides javelin-behavior-phorge-remarkup-assist-manager
 *
 * @javelin-installs JX.RemarkupAssistantManager
 */

JX.install('RemarkupAssistantManager', {

  construct: function() {
    this._actions = [];
  },

  initialize: function() {
    JX.RemarkupAssistantManager._instance = new JX.RemarkupAssistantManager();
  },

  statics : {
    _instance : null,

    getInstance : function() {
      console.assert(JX.RemarkupAssistantManager._instance);
      return JX.RemarkupAssistantManager._instance;
    }
  },

  members : {
    _actions: null,

    /** You should be using RemarkupAssistantAction.register(). */
    registerAction: function(a) {
      this._actions[a.getActionCode()] = a;
    },

    getAction: function(code) {
      if (code in this._actions) {
        return this._actions[code];
      }

      return null;
    },

  },
});
