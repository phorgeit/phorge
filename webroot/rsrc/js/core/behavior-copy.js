/**
 * @provides javelin-behavior-phabricator-clipboard-copy
 * @requires javelin-behavior
 *           javelin-dom
 *           javelin-stratcom
 *           phabricator-notification
 * @javelin
 */

JX.behavior('phabricator-clipboard-copy', function() {

  var fallback_working = document.queryCommandSupported &&
    document.queryCommandSupported('copy');

  if (!navigator.clipboard && !fallback_working) {
    return;
  }

  JX.DOM.alterClass(document.body, 'supports-clipboard', true);

  var copy_fallback = function(text) {
    var attr = {
      value: text || '',
      className: 'clipboard-buffer'
    };

    var node = JX.$N('textarea', attr);
    document.body.appendChild(node);

    node.select();
    document.execCommand('copy');

    JX.DOM.remove(node);
  };

  var show_success_message = function(message) {
    if (!message) {
      return;
    }
    new JX.Notification()
      .setContent(message)
      .alterClassName('jx-notification-done', true)
      .setDuration(8000)
      .show();
  };

  var show_error_message = function(message) {
    if (!message) {
      return;
    }
    new JX.Notification()
      .setContent(message)
      .alterClassName('jx-notification-error', true)
      .setDuration(8000)
      .show();
  };

  JX.Stratcom.listen('click', 'clipboard-copy', function(e) {
    var data = e.getNodeData('clipboard-copy');
    var text = data.text || '';

    var copy = async function( // jshint ignore:line
      text,
      successMessage,
      errorMessage
    ) {
      try {
        if (navigator.clipboard) {
          await navigator.clipboard.writeText(text);
        } else {
          copy_fallback(text);
        }
        show_success_message(successMessage);
      } catch (ex) {
        show_error_message(errorMessage);
      }
    };

    e.kill();
    copy(text, data.successMessage, data.errorMessage);
  });

});
