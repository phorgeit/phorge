/**
 * @requires javelin-behavior
 *           javelin-stratcom
 *           conpherence-thread-manager
 * @provides javelin-behavior-conpherence-pontificate
 */

JX.behavior('conpherence-pontificate', function() {

  var _sendMessage = function(e) {
    e.kill();
    var form = e.getNode('tag:form');
    var threadManager = JX.ConpherenceThreadManager.getInstance();
    threadManager.sendMessage(form, {});
  };

  JX.Stratcom.listen(
    ['submit', 'didSyntheticSubmit'],
    'conpherence-pontificate',
    _sendMessage);

});
