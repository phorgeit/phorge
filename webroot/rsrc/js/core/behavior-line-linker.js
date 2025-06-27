/**
 * @provides javelin-behavior-phabricator-line-linker
 * @requires javelin-behavior
 *           javelin-stratcom
 *           javelin-dom
 *           javelin-history
 *           javelin-external-editor-link-engine
 */

JX.behavior('phabricator-line-linker', function() {
  var origin = null;
  var target = null;
  var root = null;
  var highlighted = null;

  var editor_link = null;
  try {
    editor_link = JX.$('editor_link');
  } catch (ex) {
    // Ignore.
  }

  function getRowNumber(th) {
    // If the "<th />" tag contains an "<a />" with "data-n" that we're using
    // to prevent copy/paste of line numbers, use that.
    if (th.firstChild) {
      var line = th.firstChild.getAttribute('data-n');
      if (line) {
        return line;
      }
    }

    return null;
  }

  JX.Stratcom.listen(
    ['click', 'mousedown'],
    ['phabricator-source', 'tag:th', 'tag:a'],
    function(e) {
      if (!e.isNormalMouseEvent()) {
        return;
      }

      // Make sure the link we clicked is actually a line number in a source
      // table, not some kind of link in some element embedded inside the
      // table. The row's immediate ancestor table needs to be the table with
      // the "phabricator-source" sigil.

      var cell = e.getNode('tag:th');
      var table = e.getNode('phabricator-source');
      if (JX.DOM.findAbove(cell, 'table') !== table) {
        return;
      }

      var number = getRowNumber(cell);
      if (!number) {
        return;
      }

      e.kill();

      // If this is a click event, kill it. We handle mousedown and mouseup
      // instead.
      if (e.getType() === 'click') {
        return;
      }

      origin = cell;
      target = origin;

      root = table;
    });

  var highlight = function(e) {
    if (!origin) {
      return;
    }

    if (e.getNode('phabricator-source') !== root) {
      return;
    }
    target = e.getNode('tag:th');

    var min;
    var max;

    // NOTE: We're using position to figure out which order these rows are in,
    // not row numbers. We do this because Harbormaster build logs may have
    // multiple rows with the same row number.

    if (JX.$V(origin).y <= JX.$V(target).y) {
      min = origin;
      max = target;
    } else {
      min = target;
      max = origin;
    }

    // If we haven't changed highlighting, we don't have a list of highlighted
    // nodes yet. Assume every row is highlighted.
    var ii;
    if (highlighted === null) {
      highlighted = [];
      var rows = JX.DOM.scry(root, 'tr');
      for (ii = 0; ii < rows.length; ii++) {
        highlighted.push(rows[ii]);
      }
    }

    // Unhighlight any existing highlighted rows.
    for (ii = 0; ii < highlighted.length; ii++) {
      JX.DOM.alterClass(highlighted[ii], 'phabricator-source-highlight', false);
    }
    highlighted = [];

    // Highlight the newly selected rows.
    min = JX.DOM.findAbove(min, 'tr');
    max = JX.DOM.findAbove(max, 'tr');

    var cursor = min;
    while (true) {
      JX.DOM.alterClass(cursor, 'phabricator-source-highlight', true);
      highlighted.push(cursor);

      if (cursor === max) {
        break;
      }

      cursor = cursor.nextSibling;
    }
  };

  /**
   * Get a valid line number (int) from a string, or scream violently.
   *
   * @param {String} vRaw String containing a number, like '1'.
   * @return {Integer} Integer like 1.
   * @throws Do not accept zero. Do not accept negative numbers.
   */
  var _parseLineNumber = function(vRaw) {
    var v = parseInt(vRaw);
    if (isNaN(v) || v <= 0) {
      throw 'Input fragment parts must be positive integer. Got: ' + vRaw;
    }
    return v;
  };

  /**
   * Parse the highlighted lines from web fragment, or scream violently.
   *
   * @param {String} Input string like 'L123' or 'L123-124'.
   * @return {Array} Array with always 2 elements: min and max line.
   * From the fragment '#L123'     you get the array [123, 123].
   * From the fragment '#L123-124' you get the array [123, 124].
   * From the fragment '#L123-123' you get the array [123, 123].
   * @throws Do not accept trash like '#Labc', '#L123-456-789', '#L123-abc'.
   */
  var parseMinMaxSelectedLineFromFragment = function(input) {
    // The web fragment must be 'L123' or 'L123-124' or similar.
    if (!input || input.charAt(0) !== 'L') {
      throw 'Input fragment is not a line fragment.';
    }

    // Strip the 'L' and parse the '-' interval (if any).
    var linesStr = input.substring(1);
    var lines = linesStr.split('-', 2);
    var hasOne = lines.length === 1;
    var hasTwo = lines.length === 2;
    if (!hasOne && !hasTwo) {
      throw 'Input fragment must be valid, like L123 or L123-456.';
    }

    // Require valid integers.
    var a = _parseLineNumber(lines[0]);
    var b = hasTwo ? _parseLineNumber(lines[1]) : a;

    // Sort interval. Avoid dumb JavaScript sort() that returns strings.
    if (a < b) {
      return [a, b];
    }
    return [b, a];
  };

  JX.Stratcom.listen('mouseover', 'phabricator-source', highlight);

  JX.Stratcom.listen(
    'mouseup',
    null,
    function(e) {
      if (!origin) {
        return;
      }

      highlight(e);
      e.kill();

      var o = getRowNumber(origin);
      var t = getRowNumber(target);
      var uri = JX.Stratcom.getData(root).uri;
      var path;

      if (!uri) {
        uri = JX.$U(window.location);
        path = uri.getPath();

        // Cleanup legacy URIs using '$123' to highlight that line.
        path = path.replace(/\$[\d-]+$/, '');
        uri.setPath(path);
        uri = uri.toString();
      }

      origin = null;
      target = null;
      root = null;

      uri = JX.$U(uri);
      path = uri.getPath();

      // Check if we should highlight a single line or an interval.
      // Refresh the web fragment.
      var lineInterval = (o == t) ? o : Math.min(o, t) + '-' + Math.max(o, t);
      var lineIdentifier = 'L' + lineInterval;
      uri.setFragment(lineIdentifier);

      uri = uri.setPath(path).toString();

      JX.History.replace(uri);

      if (editor_link) {
        var data = JX.Stratcom.getData(editor_link);

        var variables = {
          l: parseInt(Math.min(o, t), 10),
        };

        var template = data.template;

        var editor_uri = new JX.ExternalEditorLinkEngine()
          .setTemplate(template)
          .setVariables(variables)
          .newURI();

        editor_link.href = editor_uri;
      }
    });


  // Try to jump to the highlighted lines at startup.
  if (window.location.hash.length) {
    // Parse the web fragment '#L123' or '#L123-124' and highlight that.
    var currentFragment = JX.$U(window.location).getFragment();
    try {
      var lines = parseMinMaxSelectedLineFromFragment(currentFragment);
      var minLine = lines[0];
      var maxLine = lines[1];

      // Scroll to the very first line.
      var lineNode = JX.$('L' + minLine);
      var tr = JX.DOM.findAbove(lineNode, 'tr');
      JX.DOM.scrollToPosition(0, JX.$V(tr).y - 60);

      // Highlight every line in the interval.
      // Note that this crashes successfully on the first non-existing element,
      // so you cannot really use '#L1-9999999999 to cause JS overheat.
      for (var i = minLine; i <= maxLine; i++) {
        lineNode = JX.$('L' + i);
        tr = JX.DOM.findAbove(lineNode, 'tr');
        JX.DOM.alterClass(tr, 'phabricator-source-highlight', true);
      }
    } catch (ex) {
      // If the '#L' fragment parser crashed, just move on.
      // If we didn't hit an element on the page, just move on.
    }
  } else {
    // in the URI.
    // This is from legacy '$123' URIs.
    try {
      var anchor = JX.$('phabricator-line-linker-anchor');
      JX.DOM.scrollToPosition(0, JX.$V(anchor).y - 60);
    } catch (ex) {
      // If we didn't hit an element on the page, just move on.
    }
  }

  if (editor_link) {
    // TODO: This should be pht()'d, but this behavior is weird enough to
    // make that a bit tricky.

    new JX.KeyboardShortcut('\\', 'Open File in External Editor')
        .setGroup('diff-nav')
        .setHandler(function() {
          JX.$U(editor_link.href).go();
        })
        .register();
  }

});
