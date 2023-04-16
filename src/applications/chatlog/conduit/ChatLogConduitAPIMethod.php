<?php

abstract class ChatLogConduitAPIMethod extends ConduitAPIMethod {

  final public function getApplication() {
    return PhorgeApplication::getByClass('PhorgeChatLogApplication');
  }

}
