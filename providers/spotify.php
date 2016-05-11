<?php

namespace Kirby\Plugins\distantnative\oEmbed\Providers;

class Spotify extends Provider {

  protected function init() {
    $this->getTheme();
    $this->getView();
  }

  public function code($code) {
    $this->setTheme();
    $this->setView();
    return $code;
  }

  // ================================================
  //  Theme
  // ================================================

  protected function getTheme() {
    $this->theme = preg_match('/theme=(.+?)&?/U', $this->url, $t) ? $t[1] : false;
  }

  protected function setTheme() {
    if($this->theme !== false) {
      $this->parameter('theme=' . $this->theme);
    }
  }

  // ================================================
  //  View
  // ================================================

  protected function getView() {
    $this->view = preg_match('/view=(.+?)&?/U', $this->url, $t) ? $t[1] : false;
  }

  protected function setView() {
    if($this->view !== false) {
      $this->parameter('view=' . $this->view);
    }
  }

}
