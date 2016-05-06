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
    // call preparation method for type
    $this->prepareType();

    // update embed ifram url
    $this->updateData('code', function($code) {
      return $this->core->url->update($code);
    });

    return $this->snippet('wrapper', $this->data);
  }

  public static function error($url) {
    return tpl::load(dirname(__DIR__) . DS . 'snippets' . DS . 'error.php', ['url' => $url]);
  }

  // ================================================
  //  Types
  // ================================================

  protected function prepareType() {
    $prepareType = 'prepare' . ucfirst($this->core->type());
    if(method_exists($this, $prepareType)) {
      $this->{$prepareType}();
    }
  }


  // ================================================
  //  Videos
  // ================================================

  protected function prepareVideo() {
    // Container ratio
    $this->data['style'] = 'padding-top:'.$this->core->aspectRatio().'%';

    // Lazy video
    if($this->options['lazyvideo']) {
      $this->lazyVideo();
    }
  }

  protected function lazyVideo() {
    // src -> data-src
    $this->updateData('code', function($code) {
      $pattern = '/(iframe.*)(src)(=".*")/U';
      $replace = '$1data-src$3';
      return preg_replace($pattern, $replace, $code);
    });

    // thumb
    $this->data['more'] = $this->snippet('thumb', [
      'url'   => $this->core->thumb(),
    ]);
  }


  // ================================================
  //  Helpers
  // ================================================

  protected function updateData($data, $value) {
    if(is_callable($value)) {
      $this->data[$data] = $value($this->data[$data]);
    } else {
      $this->data[$data] = $value;
    }
  }

  protected function snippet($name, $data) {
    return tpl::load(dirname(__DIR__) . DS . 'snippets' . DS . $name . '.php', $data);
  }

}
