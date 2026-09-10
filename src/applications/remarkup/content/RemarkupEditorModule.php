<?php

final class RemarkupEditorModule extends PhorgeRemarkupReferenceModule {

  public function getModuleKey() {
    return 'editor';
  }

  public function getTitle() {
    return pht('The Remarkup Editor');
  }

  public function getModuleOrder() {
    return 2500;
  }

  public function getContent() {
    return <<<EOT
= The Remarkup Editor

In places where the user may enter fancy text - such as comments and
descriptions - we provide the Remarkup Editor, which is like a fancy text box
with extra buttons.


== Fullscreen Mode {icon arrows-alt}
Remarkup editors provide a fullscreen composition mode. This can make it easier
to edit large blocks of text, or improve focus by removing distractions. You can
exit **Fullscreen** mode by clicking the button again or by pressing escape.

== Extending the Editor

Extensions can add more buttons to the Editor by providing code and an icon for
the new action.

To provide an action, extend @{class:PhorgeRemarkupControlExtension} and build
one or more instances of @{class:PhorgeRemarkupControlAction}.

Each Action instance will need an Icon, and either an HREF (url) or an
action-code. If provided an HREF, the target will be opened in a new tab. If
provided an action-code, a Javascript action of the same code will be invoked.

The Javascript action is an instance of `JX.RemarkupAssistantAction` that is
registered with the  action-code. `JX.RemarkupAssistantAction` has two
properties:
- `triggersWorkflow` - set to `true` if your action loads a dialog. Due to
  z-index issues, the editor might need to minimize itself to show the dialog.
- `action` - the actual code of your action. provide it with a function that
  takes a single argument, `controller`. `controller` has the following keys:
  - `selectedText` - the text currently selected.
  - `selectedRange` - object with `start` and `end` keys indexes of the selected
    text.
  - `replace_selection(pre, body, post)`: Will replace the currently selected
    text with `pre + body + post`, and select (highlight) only the `body` part.
  - `insert(text)`: will insert `text` before the selected text (without
    changing the selection).
  - `prepend_char_to_lines(prefix, text)`: breaks `text` into lines, adding the
    `prefix` to each one, and return the resulting string. Does not change the
    text area.
  - `area` - the underlying HTMLTextAreaElement, for direct manipulation (not
    recommended). You may use `JX.TextAreaUtils` to inspect it directly.

For example, the "highlight" action can be implemented like this:

```lang=javascript
  function(controller) {
    var text = controller.selectedText || 'IMPORTANT';
    controller.replace_selection('!!', text, '!!');
  }
```

Example implementations:
- The Meme generator @{class:PhorgeMacroMemeRemarkupControlExtension} and its JS
  behavior `behavior-macro-remarkup-button-meme.js`.

EOT;
  }

}
