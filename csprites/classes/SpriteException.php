<?php
class SpriteException extends Exception{
  public function __construct($message, $code = 0) {
    SpriteConfig::debug($message);
    parent::__construct($message, $code);
  }

  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }
}
?>