/**
 * @requires javelin-behavior
 *           javelin-workflow
 *           javelin-behavior-phorge-remarkup-assist-action
 *
 * @provides javelin-behavior-macro-remarkup-button-meme
 */
JX.behavior(
  'macro-remarkup-button-meme',
  function(config) {

    var actionCode = config['action_code'];

    var action = function(controller) {
      new JX.Workflow('/macro/meme/create/')
        .setHandler(function(response) {
          var prefix = (controller.selectedRange.start === 0 ? '' : '\n\n');
          controller.insert(prefix + response.text + '\n\n');
        })
        .start();
    };

    var action = new JX.RemarkupAssistantAction(actionCode, action);
    action.setTriggersWorkflow(true);
    action.register();
});
