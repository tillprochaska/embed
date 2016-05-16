<?php

namespace Kirby\Plugins\distantnative\oEmbed\Providers;

class Spotify extends Provider {

  protected function init() {
    $this->getSizes();
    $this->getTheme();
    $this->getView();
  }

  public function code($code) {
    $this->setTheme();
    $this->setView();
    $code = $this->setSizes($code);
    return $code;
  }


  // ================================================
  //  Parameters for Panel Field Cheatsheet
  // ================================================

  public function providerParameters() {
    return [
      ['view', 'Set the view style (list/coverart)'],
      ['theme', 'Set the theme (white/black)'],
      ['width', 'Set the width of the embed (e.g. 600)'],
      ['height', 'Set the height of the embed (e.g. 80)'],
    ];
  }


  // ================================================
  //  Theme
  // ================================================

  protected function getTheme() {
    $this->theme = preg_match('/theme=([a-zA-Z]*)/', $this->url, $t) ? $t[1] : false;
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
    $this->view = preg_match('/view=([a-zA-Z]*)/', $this->url, $t) ? $t[1] : false;
  }

  protected function setView() {
    if($this->view !== false) {
      $this->parameter('view=' . $this->view);
    }
  }


  // ================================================
  //  Sizes
  // ================================================

  protected function getSizes() {
    $this->width = preg_match('/width=([0-9]*)/', $this->url, $t) ? $t[1] : false;
    $this->height = preg_match('/height=([0-9]*)/', $this->url, $t) ? $t[1] : false;
  }

  protected function setSizes($code) {
    if($this->width !== false) {
      $code = str_ireplace('<iframe', '<iframe width="' . $this->width . '"', $code);
    }
    if($this->height !== false) {
      $code = str_ireplace('<iframe', '<iframe height="' . $this->height . '"', $code);
    }
    return $code;
  }
}
