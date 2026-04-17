<?php

final class RemarkupReferenceImageModule
extends PhorgeRemarkupReferenceModule {

  public function getModuleKey() {
    return 'images';
  }

  public function getTitle() {
    return pht('Images');
  }

  public function getModuleOrder() {
    return 1200;
  }

  public function getContent() {
    return <<<EOT

= Embedding Images

You can embed an image or other file by using braces to refer to it:

  {F123}

In most interfaces, you can drag-and-drop an image from your computer into the
text area to upload and reference it.

Some browsers (e.g. Chrome) support uploading an image data just by pasting them
from clipboard into the text area.

This is a special case of the general [[ /remarkup/mention/ | "Embed object"
syntax ]].

You can set file display options like this:

  {F123, layout=left, float, size=full, alt="a duckling"}

Valid options for all files are:

  - **layout** left (default), center, right, inline, link (render a link
    instead of a thumbnail for images)
  - **name** with `layout=link` or for non-images, use this name for the link
    text
  - **alt** Provide alternate text for assistive technologies.

Image files support these options:

  - **float** If layout is set to left or right, the image will be floated so
    text wraps around it.
  - **size** thumb (default), full
  - **width** Scale image to a specific width.
  - **height** Scale image to a specific height.

Audio and video files support these options:

  - **media**: Specify the media type as `audio` or `video`. This allows you
    to disambiguate how file format which may contain either audio or video
    should be rendered.
  - **loop**: Loop this media.
  - **autoplay**: Automatically begin playing this media.


EOT;
  }

}
