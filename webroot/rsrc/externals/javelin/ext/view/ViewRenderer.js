/**
 * @requires javelin-install
 *           javelin-util
 * @provides javelin-view-renderer
 *
 * @javelin-installs JX.ViewRenderer
 */

JX.install('ViewRenderer', {
  members: {
    visit: function(view, children) {
      return view.render(children);
    }
  },
  statics: {
    render: function(view) {
      var renderer = new JX.ViewRenderer();
      return view.accept(JX.bind(renderer, renderer.visit));
    }
  }
});
