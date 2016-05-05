<?php

namespace Kirby\Plugins\distantnative\oEmbed;

use Tpl;

class Html {

  public function __construct($core) {
    $this->core    = $core;
    $this->options = $this->core->options;

    $this->data    = [
      'code'     => $this->core->code(),
      'type'     => $this->core->type(),
      'provider' => $this->core->providerName(),
      'style'    => null,
      'more'     => null
    ];
  }


  // ================================================
  //  Output
  // ================================================

  public function __toString() {

    if($this->core->type() == 'video') {
      // Container ratio
      $ratio               = $this->core->aspectRatio();
      $this->data['style'] = 'padding-top: ' . $ratio . '%';

      if($this->options['lazyvideo']) {
        $this->lazyVideo();
      } else if($this->options['autoplay']) {
        $this->autoplayVideo();
      }
    }

    return $this->snippet('wrapper', $this->data);
  }


  // ================================================
  //  Video lazy loading
  // ================================================

  protected function lazyVideo() {
    // src -> data-src
    $pattern            = '/(iframe.*)(src)(=".*")/U';
    $replace            = '$1data-src$3';
    $this->data['code'] = preg_replace($pattern, $replace, $this->data['code']);

    $this->autoplayVideo();
    $this->thumbVideo();
  }

  protected function autoplayVideo() {
    $pattern            = '/(src|data-src)(=")(.*)(\?)(.*)(")/U';
    $replace            = '$1$2$3$4$5&rel=0&autoplay=1$6';
    $this->data['code'] = preg_replace($pattern, $replace, $this->data['code']);
  }

  protected function thumbVideo() {
    $this->data['more'] = $this->snippet('thumb', [
      'url'   => $this->core->thumb(),
    ]);
  }


  // ================================================
  //  Helpers
  // ================================================

  protected function snippet($name, $data) {
    return tpl::load(dirname(__DIR__) . DS . 'snippets' . DS . $name . '.php', $data);
  }

}
