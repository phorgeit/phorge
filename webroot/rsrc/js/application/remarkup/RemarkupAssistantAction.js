/**
 * @requires javelin-install
 *           javelin-util
 *           javelin-behavior-phorge-remarkup-assist-manager
 *
 * @provides javelin-behavior-phorge-remarkup-assist-action
 *
 * @javelin-installs JX.RemarkupAssistantAction
 */

JX.install('RemarkupAssistantAction', {

  construct: function(code, action) {
    this.setActionCode(code);
    this.setAction(action);
  },

  properties: {
    actionCode: null,
    action: JX.bag,
    triggersWorkflow: false,
  },

  members : {
    register : function() {
      JX.RemarkupAssistantManager.getInstance().registerAction(this);
      return this;
    },
  }

});
