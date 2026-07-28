<?php

final class RemarkupReferenceMentionModule
extends PhorgeRemarkupReferenceModule {

  public function getModuleKey() {
    return 'mention';
  }

  public function getTitle() {
    return pht('Mentioning and Embedding objects');
  }

  public function getModuleOrder() {
    return 2000;
  }

  public function getContent() {
    return <<<EOT

= Linking to Objects

You can link ("mention") other Phorge objects, such as Differential revisions,
Diffusion commits and Maniphest tasks, by mentioning the name of the object:

  D123          # Link to Differential revision D123
  rX123         # Link to SVN commit 123 from the "X" repository
  rXaf3192cd5   # Link to Git commit "af3192cd5..." from the "X" repository.
                # You must specify at least 7 characters of the hash.
  T123          # Link to Maniphest task T123

You can also link directly to a comment in Maniphest and Differential (these
can be found on the date stamp of any transaction/comment):

  T123#412       # Link to comment id #412 of task T123

Most objects that have short names (a letter followed by a number) can be linked
in this way. Internally, we call the letter "Monogram".

See the Phorge configuration setting `remarkup.ignored-object-names` to
modify this behavior.

In most cases, mentioning (linking) an object in this way creates a "Mention"
entry under "Related Objects" in both the object being mentioned and the object
on which the comment is made.


= Embedding Objects

You can also generate full-name references to some objects by using braces:

  {D123}        # Link to Differential revision D123 with the full name
  {T123}        # Link to Maniphest task T123 with the full name

These references will also show when an object changes state (for instance, a
task or revision is closed). Some types of objects support richer embedding,
providing a more engaging experience, such as:

  {{W5}}        # Dashboard panel
  {{P31}}       # a Paste
  {{C121}}      # a ticking countdown

If the object's PHID is known, it can be used directly in this syntax:

  {PHID-TASK-fhif5y3dfdop2edjxmml}

== Linking to Project Tags

Projects can be linked to with the use of a hashtag `#`. This works by default
using the name of the Project (lowercase, underscored). Additionally you
can set multiple additional hashtags by editing the Project details.

  #qa, #quality_assurance

= Mentioning Users =

You can mention another user by writing:

  @username

When you submit your comment, this will subscribe (add as a CC) them to the
object if they aren't already subscribed.


== Embedding Mocks (Pholio)

You can embed a Pholio mock by using braces to refer to it:

  {M123}

By default the first four images from the mock set are displayed. This behavior
can be overridden with the **image** option. With the **image** option you can
provide one or more image IDs to display.

You can set the image (or images) to display like this:

  {M123, image=12345}
  {M123, image=12345 & 6789}

== Embedding Pastes

You can embed a Paste using braces:

  {P123}

You can adjust the embed height with the `lines` option:

  {P123, lines=15}

You can highlight specific lines with the `highlight` option:

  {P123, highlight=15}
  {P123, highlight="23-25, 31"}

== Embedding Images

You can embed an image or other file by using braces to refer to it:

  {F123}

In most interfaces, you can drag-and-drop an image from your computer into the
text area, or just paste it, to upload and reference it.

See [[ /remarkup/images/ | Images section ]] for more features.

EOT;
  }

}
