/**
 * @requires javelin-behavior
 *           javelin-dom
 *           javelin-util
 *           phuix-button-view
 *           phuix-icon-view
 * @provides javelin-behavior-phuix-example
 */

JX.behavior('phuix-example', function(config) {
  var node;
  var spec;
  var defaults;

  switch (config.type) {
    case 'button':
      var button = new JX.PHUIXButtonView();
      defaults = {
        text: null,
        icon: null,
        type: null,
        color: null
      };

      spec = JX.copy(defaults, config.spec);

      if (spec.text !== null) {
        button.setText(spec.text);
      }

      if (spec.icon !== null) {
        button.setIcon(spec.icon);
      }

      if (spec.type !== null) {
        button.setButtonType(spec.type);
      }

      if (spec.color !== null) {
        button.setColor(spec.color);
      }

      node = button.getNode();
      break;
    case 'icon':
      var icon = new JX.PHUIXIconView();
      defaults = {
        icon: null,
        color: null
      };

      spec = JX.copy(defaults, config.spec);

      if (spec.icon !== null) {
        icon.setIcon(spec.icon);
      }

      if (spec.color !== null) {
        icon.setColor(spec.color);
      }

      node = icon.getNode();
      break;
  }

  JX.DOM.setContent(JX.$(config.id), node);
});
