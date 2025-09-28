<?php

final class CelerityDarkModePostprocessor
  extends CelerityPostprocessor {

  public function getPostprocessorKey() {
    return 'darkmode';
  }

  public function getPostprocessorName() {
    return pht('Dark Mode (Experimental)');
  }

  public function buildVariables() {
    return array(

      // Fonts
      'basefont' => "13px 'Segoe UI', 'Segoe UI Emoji', ".
        "'Segoe UI Symbol', 'Lato', 'Helvetica Neue', ".
        "Helvetica, Arial, sans-serif",

      'fontfamily' => "'Segoe UI', 'Segoe UI Emoji', ".
        "'Segoe UI Symbol', 'Lato', 'Helvetica Neue', ".
        "Helvetica, Arial, sans-serif",

      // Drop Shadow
      'dropshadow' => '0 2px 12px rgba(0, 0, 0, .20)',

      // Anchors
      'anchor' => '#3498db',

      // Base Colors
      'red'           => '#c0392b',
      'lightred'      => '#7f261c',
      'orange'        => '#e67e22',
      'lightorange'   => '#f7e2d4',
      'yellow'        => '#f1c40f',
      'lightyellow'   => '#a4850a',
      'green'         => '#139543',
      'lightgreen'    => '#0e7032',
      'blue'          => '#2980b9',
      'lightblue'     => '#1d5981',
      'sky'           => '#3498db',
      'lightsky'      => '#175782',
      'fire'          => '#e62f17',
      'indigo'        => '#6e5cb6',
      'lightindigo'   => '#372574',
      'pink'          => '#da49be',
      'lightpink'     => '#81186d',
      'violet'        => '#8e44ad',
      'lightviolet'   => '#622f78',
      'charcoal'      => '#4b4d51',
      'backdrop'      => '#c4cde0',
      'hoverwhite'    => 'rgba(255,255,255,.6)',
      'hovergrey'     => '#c5cbcf',
      'hoverblue'     => '#2a425f',
      'hoverborder'   => '#dfe1e9',
      'hoverselectedgrey' => '#bbc4ca',
      'hoverselectedblue' => '#e6e9ee',
      'borderinset' => 'inset 0 0 0 1px rgba(55,55,55,.15)',
      'timeline'    => '#4e6078',
      'timeline.icon.background' => '#416086',
      'bluepropertybackground' => '#2d435f',

      // Alphas
      'alphawhite'          => '255,255,255',
      'alphagrey'           => '255,255,255',
      'alphablue'           => '255,255,255',
      'alphablack'          => '0,0,0',

      // Base Greys
      'lightgreyborder'     => 'rgba(255,255,255,.3)',
      'greyborder'          => 'rgba(255,255,255,.6)',
      'darkgreyborder'      => 'rgba(255,255,255,.9)',
      'lightgreytext'       => 'rgba(255,255,255,.3)',
      'greytext'            => 'rgba(255,255,255,.6)',
      'darkgreytext'        => 'rgba(255,255,255,.9)',
      'lightgreybackground' => '#2a425f',
      'greybackground'      => '#304a6d',
      'darkgreybackground'  => '#8C98B8',

      // Base Blues
      'thinblueborder'      => '#2c405a',
      'lightblueborder'     => '#3e5675',
      'blueborder'          => '#8C98B8',
      'darkblueborder'      => '#626E82',
      'lightbluebackground' => 'rgba(255,255,255,.05)',
      'bluebackground'      => 'rgba(255,255,255,.1)',
      'lightbluetext'       => 'rgba(255,255,255,.3)',
      'bluetext'            => 'rgba(255,255,255,.6)',
      'darkbluetext'        => 'rgba(255,255,255,.8)',
      'blacktext'           => 'rgba(255,255,255,.9)',

      // Base Greens
      'lightgreenborder'      => '#105610',
      'greenborder'           => '#446f54',
      'greentext'             => '#e0eedd',
      'lightgreenbackground'  => '#132211',

      // Base Red
      'lightredborder'        => '#561010',
      'redborder'             => '#6b1414',
      'redtext'               => '#f2d9d9',
      'lightredbackground'    => '#260d0d',

      // Base Yellow
      'lightyellowborder'     => '#565610',
      'yellowborder'          => '#707042',
      'yellowtext'            => '#ededde',
      'lightyellowbackground' => '#31311b',

      // Base Violet
      'lightvioletborder'     => '#331056',
      'violetborder'          => '#6c4270',
      'violettext'            => '#e8deed',
      'lightvioletbackground' => '#2a1a32',

      // Shades are a more muted set of our base colors
      // better suited to blending into other UIs.

      // Shade Red
      'sh-lightredborder'     => '#7b1e1e',
      'sh-redborder'          => '#8d3f3f',
      'sh-redicon'            => '#ff9999',
      'sh-redtext'            => '#ffcccc',
      'sh-redbackground'      => '#563636',

      // Shade Orange
      'sh-lightorangeborder'  => '#7b4d1e',
      'sh-orangeborder'       => '#8d663f',
      'sh-orangeicon'         => '#ffcc99',
      'sh-orangetext'         => '#ffe6cc',
      'sh-orangebackground'   => '#554535',

      // Shade Yellow
      'sh-lightyellowborder'  => '#7b7b1e',
      'sh-yellowborder'       => '#8d8d3f',
      'sh-yellowicon'         => '#ffff99',
      'sh-yellowtext'         => '#ffffcc',
      'sh-yellowbackground'   => '#555535',

      // Shade Green
      'sh-lightgreenborder'   => '#357b1e',
      'sh-greenborder'        => '#538d3f',
      'sh-greenicon'          => '#99ff99',
      'sh-greentext'          => '#d9ffcc',
      'sh-greenbackground'    => '#355535',

      // Shade Blue
      'sh-lightblueborder'    => '#1e4d7b',
      'sh-blueborder'         => '#3f668d',
      'sh-blueicon'           => '#99ccff',
      'sh-bluetext'           => '#cce6ff',
      'sh-bluebackground'     => '#353d55',

      // Shade Sky (mostly re-uses Blue colors above)
      'sh-skybackground'      => '#354d55',

      // Shade Indigo
      'sh-lightindigoborder'  => '#1e1e7b',
      'sh-indigoborder'       => '#3f3f8d',
      'sh-indigoicon'         => '#9999ff',
      'sh-indigotext'         => '#ccccff',
      'sh-indigobackground'   => '#3d3555',

      // Shade Violet
      'sh-lightvioletborder'  => '#4d1e7b',
      'sh-violetborder'       => '#663f8d',
      'sh-violeticon'         => '#cc99ff',
      'sh-violettext'         => '#e6ccff',
      'sh-violetbackground'   => '#4d3555',

      // Shade Pink
      'sh-lightpinkborder'  => '#7b1e7b',
      'sh-pinkborder'       => '#8d3f8d',
      'sh-pinkicon'         => '#ff99ff',
      'sh-pinktext'         => '#ffccff',
      'sh-pinkbackground'   => '#553555',

      // Shade Grey
      'sh-lightgreyborder'    => '#737373',
      'sh-greyborder'         => '#b9bbc6',
      'sh-greyicon'           => '#4d4d4d',
      'sh-greytext'           => '#262626',
      'sh-greybackground'     => '#979db4',

      // Shade Disabled
      'sh-lightdisabledborder'  => '#1a1a1a',
      'sh-disabledborder'       => '#333333',
      'sh-disabledicon'         => '#595959',
      'sh-disabledtext'         => '#737373',
      'sh-disabledbackground'   => '#223144',

      // Diffs
      'diff.background' => '#121b27',
      'new-background' => 'rgba(151, 234, 151, .55)',
      'new-bright' => 'rgba(151, 234, 151, .75)',
      'old-background' => 'rgba(251, 175, 175, .55)',
      'old-bright' => 'rgba(251, 175, 175, .8)',
      'move-background' => '#faca00',
      'copy-background' => '#f1c40f',

      'diffsize.small.background' => '#324d67',
      'diffsize.large.background' => '#4b3826',
      'diffsize.small.icon' => '#cadce7',
      'diffsize.large.icon' => '#f2d7c0',

      'diff.update-history-new' => '#226622',
      'diff.update-history-new-now' => '#155815',
      'diff.update-history-old' => '#a65353',
      'diff.update-history-old-now' => '#903e3e',

      // Usually light yellow
      'gentle.highlight' => '#105356',
      'gentle.highlight.border' => '#0c3e40',

      'paste.content' => '#222222',
      'paste.border' => '#000000',
      'paste.highlight' => '#121212',

      // Background color for "most" themes.
      'page.background' => '#223246',
      'page.sidenav' => '#1c293b',
      'page.content' => '#26374c',

      'menu.profile.text' => 'rgba(255,255,255,.8)',
      'menu.profile.text.selected' => 'rgba(255,255,255,1)',
      'menu.profile.icon.disabled' => 'rgba(255,255,255,.4)',

      'navigation-menu-selection-background' => 'rgba(255, 255, 255,.2)',
      'navigation-menu-hover-background' => 'rgba(255,255,255,.1)',
      'workboard-column-background' => 'rgba(60,90,120,.55)',
      'form-inset-background' => '#1c293b',

      // Buttons
      'blue.button.color' => '#2980b9',
      'blue.button.gradient' => 'linear-gradient(to bottom, #3498db, #2980b9)',
      'green.button.color' => '#139543',
      'green.button.gradient' => 'linear-gradient(to bottom, #23BB5B, #139543)',
      'grey.button.color' => '#223246',
      'grey.button.gradient' => 'linear-gradient(to bottom, #223246, #223246)',
      'grey.button.hover' => 'linear-gradient(to bottom, #1c293b, #1c293b)',

      // Codeblock syntax highlighting
      'syntax.highlighted-line' => '#fa8',
      'syntax.comment' => '#6d6',
      'syntax.comment-multiline' => '#6d6',
      'syntax.comment-single' => '#6d6',
      'syntax.comment-special' => '#6d6',
      'syntax.string-doc' => '#fff',
      'syntax.string-heredoc' => '#fff',
      'syntax.string' => '#f88',
      'syntax.string-backtick' => '#f88',
      'syntax.literal-string-char' => '#f88',
      'syntax.string-double' => '#f88',
      'syntax.string-single' => '#f88',
      'syntax.string-other' => '#f88',
      'syntax.string-regex' => '#f88',
      'syntax.name-variable' => '#8ff',
      'syntax.variable-instance' => '#8ff',
      'syntax.variable-global' => '#8ff',
      'syntax.name-attribute' => '#4cf',
      'syntax.keyword-constant' => '#0cf',
      'syntax.name-operator' => '#0cf',
      'syntax.keyword' => '#e8e',
      'syntax.keyword-declaration' => '#e8e',
      'syntax.keyword-namespace' => '#e8e',
      'syntax.keyword-type' => '#e8e',
      'syntax.comment-preproc' => '#08f',
      'syntax.keyword-preproc' => '#08f',
      'syntax.keyword-reserved' => '#08f',
      'syntax.name-builtin' => '#08f',
      'syntax.builtin-pseudo' => '#08f',
      'syntax.name-class' => '#4ff',
      'syntax.name-tag' => '#dc0',
      'syntax.name-variable-class' => '#4ff',
      'syntax.name-function' => '#8af',
      'syntax.name-exception' => '#ed8',
      'syntax.operator' => '#aaa',
      'syntax.punctuation' => '#aaa',
      'syntax.literal-string-symbol' => '#aaa',
      'syntax.literal-number' => '#fa4',
      'syntax.literal-number-float' => '#fa4',
      'syntax.literal-number-hex' => '#fa4',
      'syntax.literal-number-integer' => '#fa4',
      'syntax.literal-number-octal' => '#fa4',
      'syntax.literal-number-integer-long' => '#fa4',
      'syntax.generic-deleted' => '#f55',
      'syntax.generic-red' => '#f52',
      'syntax.generic-heading' => '#fff',
      'syntax.generic-inserted' => '#4f4',
      'syntax.generic-output' => '#ccc',
      'syntax.generic-prompt' => '#fff',
      'syntax.generic-underline' => '#f4f',
      'syntax.generic-traceback' => '#07f',
      'syntax.name-decorator' => '#c7f',
      'syntax.name-identifier' => '#92969d',
      'syntax.name-entity' => '#f44',
      'syntax.name-label' => '#aa0',
      'syntax.name-namespace' => '#48f',
      'syntax.operator-word' => '#c7f',
      'syntax.text-whitespace' => '#bbb',
      'syntax.literal-string-escape' => '#d84',
      'syntax.literal-string-interpol' => '#b6b',
    );
  }

}
