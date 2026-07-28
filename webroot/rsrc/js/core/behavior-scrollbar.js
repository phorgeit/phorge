/**
 * @requires javelin-behavior
 *           javelin-dom
 *           javelin-scrollbar
 * @provides javelin-behavior-scrollbar
 */

JX.behavior('scrollbar', function(config) {
  var bar = new JX.Scrollbar(JX.$(config.nodeID));
  if (config.isMainContent) {
    bar.setAsScrollFrame();
  }
});
