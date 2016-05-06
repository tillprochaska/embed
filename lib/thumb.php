<?php

namespace Kirby\Plugins\distantnative\oEmbed;

use C;
use F;

class Thumb {

  protected $dir;

  public function __construct($url) {
    $this->url  = $url;
    $this->dir  = kirby()->roots()->cache() . DS . 'oembed' . DS . 'thumbs';
    
    $this->file = md5($this->url) . '.' . pathinfo($this->url, PATHINFO_EXTENSION);
    $this->root = $this->dir . DS . $this->file;

    $this->expired();
    $this->cache();
  }

  protected function expired() {
    if(f::modified($this->root) + (c::get('plugin.oembed.caching.duration', 24) * 60 * 60) < time()) {
      f::remove($this->root);
    }
  }

  protected function cache() {
    if(!f::exists($this->root)) {
      if(!file_exists($this->dir)) mkdir($this->dir);
      file_put_contents($this->root, file_get_contents($this->url));
    }
  }

  public function __toString() {
    return c::set('plugin.oembed.caching', true) ? url('thumbs/oembed/' . $this->file) : $this->url;
  }


}
