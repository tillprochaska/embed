<?php

namespace Kirby\Plugins\distantnative\oEmbed;

use Tpl;

class Html {

  public function __construct($core) {
    $this->core    = $core;
    $this->options = $this->core->options;

    $this->data    = [
      'code'     => $this->core->code(),
      'class'    => $this->core->options['class'], 
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

      // Lazy video
      if($this->options['lazyvideo']) {
        $this->lazyVideo();
      }
    }

    $this->parameters();
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

    $this->thumbVideo();
  }

  protected function thumbVideo() {
    $this->data['more'] = $this->snippet('thumb', [
      'url'   => $this->core->thumb(),
    ]);
  }


  // ================================================
  //  Helpers
  // ================================================

    }
  }

  protected function snippet($name, $data) {
    return tpl::load(dirname(__DIR__) . DS . 'snippets' . DS . $name . '.php', $data);
  }

}
