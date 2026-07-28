/**
 * @requires javelin-install
 * @provides javelin-workboard-order-template
 *
 * @javelin-installs JX.WorkboardOrderTemplate
 *
 * @javelin
 */

JX.install('WorkboardOrderTemplate', {

  construct: function(order) {
    this._orderKey = order;
  },

  properties: {
    hasHeaders: false,
    canReorder: false
  },

  members: {
    _orderKey: null,

    getOrderKey: function() {
      return this._orderKey;
    }

  }

});
