/**
 * @requires javelin-install
 *           javelin-dom
 * @provides phui-hovercard
 *
 * @javelin-installs JX.Hovercard
 *
 * @javelin
 */

JX.install('Hovercard', {

  properties: {
    hovercardKey: null,
    objectPHID: null,
    contextPHID: null,
    isLoading: false,
    isLoaded: false,
    content: null
  },

  members: {
    newContentNode: function() {
      return JX.$H(this.getContent());
    }
  }

});
