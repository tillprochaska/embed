<?php

namespace Kirby\Plugins\distantnative\oEmbed\Providers;

class YouTube extends Provider {

  protected function init() {
    $this->getTimecode();
  }

  public function code($code) {
    $this->setAutoplay();
    $this->setTimecode();
    $this->setJsApi();
    return $code;
  }


  // ================================================
  //  Autoplay
  // ================================================

  protected function setAutoplay() {
    if($this->option('lazyvideo') || $this->option('autoplay')) {
      $this->parameter(['rel=0', 'autoplay=1']);
    }
  }


  // ================================================
  //  Timecode
  // ================================================

  protected function getTimecode() {
    $this->timecode = preg_match('/t=(.+?)&?/U', $this->url, $t) ? $t[1] : false;
  }

  protected function setTimecode() {
    if($this->timecode !== false) {
      $this->parameter('start=' . $this->calculateTimecode());
    }
  }

  protected function calculateTimecode() {
    $time  = $this->disectTimecode('h') * 60 * 60;
    $time += $this->disectTimecode('m') * 60 ;
    $time += $this->disectTimecode('s');
    return $time;
  }

  protected function disectTimecode($identifier) {
    return preg_match('/([0-9]+)' . $identifier . '/i', $this->timecode, $match) ? $match[0] : 0;
  }


  // ================================================
  //  JS API
  // ================================================

  protected function setJsApi() {
    if($this->option('jsapi')) {
      $this->parameter('enablejsapi=1');
    }
  }

}
