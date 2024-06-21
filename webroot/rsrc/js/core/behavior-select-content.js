/**
 * @provides javelin-behavior-select-content
 * @requires javelin-behavior
 *           javelin-stratcom
 *           javelin-dom
 * @javelin
 */

JX.behavior('select-content', function() {
  JX.Stratcom.listen(
    'click',
    'select-content',
    function(e) {
      e.kill();

      var node = e.getNode('select-content');
      var data = JX.Stratcom.getData(node);

      if (data.once && data.selected) {
        return;
      }

      var target = JX.$(data.selectID);
      JX.DOM.focus(target);
      target.select();

      if (data.once) {
        JX.Stratcom.addData(node, {selected: true});
      }
    });
});
