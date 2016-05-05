<?php

namespace Kirby\Plugins\distantnative\oEmbed\Providers;

class Vimeo extends Provider {

  public function code($code) {
    $this->setAutoplay();
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

}
