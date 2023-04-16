/**
 * @provides javelin-behavior-phorge-autofocus
 * @requires javelin-behavior javelin-dom
 */

JX.behavior('phorge-autofocus', function(config) {
  try { JX.$(config.id).focus(); } catch (x) { }
});
