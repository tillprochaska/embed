<?php

namespace Kirby\Plugins\distantnative\oEmbed\Providers;

class Vimeo extends Provider {

  public function code($code) {
    $this->setAutoplay();
    $this->setJsApi();
    return $code;
  }


  // ================================================
  //  Autoplay
  // ================================================

  protected function setAutoplay() {
    if($this->option('lazyvideo') || $this->option('autoplay')) {
      $this->parameter('autoplay=1');
    }
  }


  // ================================================
  //  JS API
  // ================================================

  protected function setJsApi() {
    if($this->option('jsapi')) {
      $this->parameter('api=1');
    }
  }

}
