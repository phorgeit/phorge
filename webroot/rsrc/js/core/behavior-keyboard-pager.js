/**
 * @provides javelin-behavior-phorge-keyboard-pager
 * @requires javelin-behavior
 *           javelin-uri
 *           phorge-keyboard-shortcut
 */

JX.behavior('phorge-keyboard-pager', function(config) {

  new JX.KeyboardShortcut('[', 'Prev Page')
    .setHandler(function() {
      if (config.prev) {
        JX.$U(config.prev).go();
      }
    })
    .register();

  new JX.KeyboardShortcut(']', 'Next Page')
    .setHandler(function() {
      if (config.next) {
        JX.$U(config.next).go();
      }
    })
    .register();

});
