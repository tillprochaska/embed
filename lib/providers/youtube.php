<?php

namespace Kirby\Plugins\distantnative\oEmbed\Providers;

class YouTube extends Provider {

  protected function init() {
    $this->getTimecode();
  }

  public function code($code) {
    $code = $this->setTimecode($code);
    return $code;
  }


  // ================================================
  //  Timecode
  // ================================================

  protected function getTimecode() {
    $this->timecode = preg_match('/t=(.+?)&?/U', $this->url, $t) ? $t[1] : false;
  }

  protected function setTimecode($code) {
    if($this->timecode !== false) {
      $pattern = '/(src|data-src)(=")(.*)(\?)(.*)(")/U';
      $replace = '$1$2$3$4$5&start=' . $this->calculateTimecode() . '$6';
      $code    = preg_replace($pattern, $replace, $code);
      return $code;
    }
  }

  protected function calculateTimecode() {
    $time = 0;
    if(preg_match('/([0-9]+)h/i', $this->timecode, $h)) $time += $h[0] * 60 * 60;
    if(preg_match('/([0-9]+)m/i', $this->timecode, $m)) $time += $m[0] * 60;
    if(preg_match('/([0-9]+)s/i', $this->timecode, $s)) $time += $s[0];
    return $time;
  }

}
