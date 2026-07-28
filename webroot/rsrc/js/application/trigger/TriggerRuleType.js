/**
 * @requires trigger-rule-control
 *           javelin-install
 * @provides trigger-rule-type
 *
 * @javelin-installs JX.TriggerRuleType
 *
 * @javelin
 */

JX.install('TriggerRuleType', {

  construct: function() {
  },

  properties: {
    type: null,
    name: null,
    isSelectable: true,
    defaultValue: null,
    control: null
  },

  statics: {
    newFromDictionary: function(map) {
      var control = JX.TriggerRuleControl.newFromDictionary(map.control);

      return new JX.TriggerRuleType()
        .setType(map.type)
        .setName(map.name)
        .setIsSelectable(map.selectable)
        .setDefaultValue(map.defaultValue)
        .setControl(control);
    },
  },

  members: {
  }

});
