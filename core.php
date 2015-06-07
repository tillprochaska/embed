<?php

spl_autoload_register('KirbyOEmbed::autoload');

use Essence\Essence;
use Multiplayer\Multiplayer;

class KirbyOEmbed {

  public $url      = '';
  public $thumb    = null;
  public $autoplay = false;
  public $doCache  = false;

  protected $embedObject  = null;
  protected $Essence      = null;
  protected $Multiplacer  = null;
  protected $Cache        = null;

  public function __construct($url) {
    $this->url      = $url;
    $this->doCache  = c::get('oembed.caching', false);

    $this->Essence     = new Essence();
    $this->Multiplayer = new Multiplayer();

    if ($this->doCache)
      $this->Cache = $this->cache('file', kirby()->roots()->cache().'/oembed');
  }

  public function get($parameters) {
    if ($this->embedObject = $this->embedObject()) {
      $output = $this->template();
      $output = $this->replaceParameters($output, $this->embedObject->providerName, $parameters);
      return $output;
    } else {
      return $this->url;
    }
  }

  public function getThumbnail() {
    if ($this->thumb) {
      return $this->thumb;
    }
    else {
      if ($this->embedObject = $this->embedObject()) {
        return $this->cachedThumbnail($this->embedObject->thumbnailUrl);
      }
    }
  }

  public function setThumbnail($thumb) {
    $this->thumb = $thumb;
  }

  protected function template() {
    // Create oembed-video wrapper
    $output = new Brick('div');
    $output->addClass('oembed');

    if ($this->embedObject->type === 'video') :
      // Maintain aspect ratio of videos
      $output->addClass('oembed-video');
      $wrapperRatio = ($this->embedObject->height / $this->embedObject->width) * 100;
      $output->attr('style','padding-top:'.$wrapperRatio.'%');

      if (c::get('oembed.lazyvideo', false)) $output->addClass('oembed-lazyvideo');

      // Create thumb image
      $thumb = new Brick('div');
      $thumb->addClass('thumb');
      $thumb->attr('style','background-image: url('.$this->getThumbnail().')');

      // Create play button overlay
      $htmlPlay = new Brick('div');
      $htmlPlay->addClass('play');
      $htmlPlay->append('<img src="'.url('assets/oembed/oembed-play.png').'" alt="Play">');

      // Add elements to wrapper
      $output->append($htmlPlay);
      $output->append($thumb);

      // Create embed HTML
      if (isset($parameters['color'])) :
        $htmlEmbed = $this->Multiplayer->html($this->embedObject->url, [
          'autoPlay'       => $this->autoplay or c::get('oembed.lazyvideo', false),
          'showInfos'      => false,
          'showBranding'   => false,
          'showRelated'    => false,
          'highlightColor' => $parameters['color']
        ]);
      else :
        $htmlEmbed = $this->Multiplayer->html($this->embedObject->url, [
          'autoPlay'     => $this->autoplay or c::get('oembed.lazyvideo', false),
          'showInfos'    => false,
          'showBranding' => false,
          'showRelated'  => false
        ]);
      endif;

      if (c::get('oembed.lazyvideo', false)):
        $htmlEmbed = str_replace(' src="', ' data-src="', $htmlEmbed);
      endif;

    else:
      $htmlEmbed = $this->embedObject->html;
    endif;

    // Better HTML validation
    $htmlEmbed = str_ireplace(array('frameborder="0"', 'webkitallowfullscreen', 'mozallowfullscreen'), '', $htmlEmbed);
    $htmlEmbed = str_replace('&','&amp;', str_replace('&amp;', '&', $htmlEmbed));

    // Add embed HTML to wrapper
    $output->append($htmlEmbed);

    return $output;
  }

  protected function replaceParameters($html, $embedType, $customParameters = array()) {
    switch ($embedType) {
      case 'SoundCloud':
        if (isset($customParameters['size']) &&
            $customParameters['size'] == 'compact')
            $html = str_replace('height="400"', 'height="140"', $html);
        if (isset($customParameters['size']) &&
            $customParameters['size'] == 'smaller')
            $html = str_replace('height="400"', 'height="300"', $html);
        if (isset($customParameters['visual']) &&
            $customParameters['visual'] == 'false')
            $html = str_replace('visual=true', 'visual=false', $html);
        if (isset($customParameters['artwork']) &&
            $customParameters['artwork'] == 'false')
            $html = str_replace('show_artwork=true', 'show_artwork=false', $html);
        return $html;
        break;
      default:
        return $html;
    }
  }

  protected function cachedThumbnail($thumbUrl) {
    // Get images from cache if possible (and ombed.caching is true)
    if ($this->doCache) {
      $dir = kirby()->roots()->thumbs().'/oembed';

      if (!file_exists($dir)) mkdir($dir);

      $thumbKey  = md5($thumbUrl).'.'.pathinfo($thumbUrl, PATHINFO_EXTENSION);
      $thumbPath = $dir.'/'.$thumbKey;

      // Cache image if cache doesn't exist or expired
      if (!file_exists($thumbPath)) {
        $thumbFile = file_get_contents($thumbUrl);
        file_put_contents($thumbPath, $thumbFile);
      } elseif (filemtime($thumbPath) >= (time() - c::get('oembed.cacheexpires', 3600*24))) {
        unlink($thumbPath);
        $thumbFile = file_get_contents($thumbUrl);
        file_put_contents($thumbPath, $thumbFile);
      }

      // Get URL to cached image
      $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

      return $root.'thumbs/oembed/' . $thumbKey;

    } else {
      return $thumbUrl;
    }
  }

  protected function embedObject() {
    // try to get from Cache first.
    if ($this->doCache)
      $oEmbed = $this->Cache->get(md5($this->url));

    if(!isset($oEmbed) or $oEmbed == null) :
        $oEmbed = $this->Essence->extract($this->url, [
            'thumbnailFormat' => 'maxres'
        ]);

        // Write to Cache to save API Calls next time
        if (c::get('oembed.caching', false))
          $this->Cache->set(md5($this->url), $oEmbed, c::get('oembed.cacheexpires', 60*24));
    endif;

    return $oEmbed;
  }

  protected function cache($driver, $dir) {
    if (!file_exists($dir)) mkdir($dir);
    return cache::setup($driver, array('root' => $dir));
  }

  public static function autoload($class) {
    $path = dirname( __FILE__ ) . DS . 'lib' . DS . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) require $path;
  }

}
