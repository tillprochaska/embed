<?php

namespace Kirby\Plugins\distantnative\oEmbed;

use F;

class Thumb {

  protected $dir;

  public function __construct($url) {
    $this->dir  = kirby()->roots()->thumbs() . DS . 'oembed';
    $this->file = md5($url) . '.' . pathinfo($url, PATHINFO_EXTENSION);
    $this->root = $this->dir . DS . $this->file;

    $this->expired();
    $this->cache($url);
  }

  protected function expired() {
    if(f::modified($this->root) + 86400 < time()) {
      f::remove($this->root);
    }
  }

  protected function cache($url) {
    if(!f::exists($this->root)) {
      if(!file_exists($this->dir)) mkdir($this->dir);
      file_put_contents($this->root, file_get_contents($url));
    }
  }

  public function __toString() {
    return url('thumbs/oembed/' . $this->file);
  }


}
