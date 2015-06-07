<?php

class OembedThumb {

  public $thumb   = null;
  public $dir     = null;
  public $caching = false;

  public function __construct($caching) {
    $this->dir     = kirby()->roots()->thumbs();
    if (!file_exists($this->dir)) mkdir($this->dir);

    $this->caching = $caching;
  }

  public function set($thumb) {
    $this->thumb = $thumb;
  }

  public function get($Media) {
    if($this->thumb) return $this->thumb;
    else             return $this->cache($Media->get('thumbnail_url'));
  }

  protected function cache($url) {
    if($this->caching) {

      $highRes = $this->path($url);
      $this->clearCache($highRes);

      $lowRes  = $this->path(self::lowRes($url));
      $this->clearCache($lowRes);

      if($this->cached($highRes)) {
        echo 1;
        $key = $this->key($url);

      } elseif($this->cached($lowRes)) {
        echo 2;
        $key = $this->key(self::lowRes($url));

      } else {
        echo 3;
        $file = $this->loadRemote($url);
        $url  = $file['type'] == 'high' ? $url : self::lowRes($url);
        $path = $file['type'] == 'high' ? $highRes : $lowRes;
        file_put_contents($path, $file['file']);
        $key = $this->key($url);
      }

      $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . DS;

      return $root . str_replace(kirby()->roots()->index() . DS, '', $this->dir) . DS . $key;

    } else {
      return $url;
    }
  }

  protected function cached($path) {
    return file_exists($path);
  }

  protected function key($url) {
    return 'oembed-' . md5($url) . '.' . pathinfo($url, PATHINFO_EXTENSION);
  }

  protected function path($url) {
    $path = $this->dir . DS . $this->key($url);
    return $path;
  }

  protected function loadRemote($url) {
    $ype = 'high';
    $file = @file_get_contents($url);
    if($file === false) {
      $file = file_get_contents(self::lowRes($url));
      $type = 'low';
    }
    return array('type' => $type, 'file' => $file);
  }

  protected function clearCache($path) {
    $expires = time() - c::get('oembed.cacheexpires', 3600*24);
    if (file_exists($path) and filemtime($path) >= $expires) {
      unlink($path);
    }
  }

  public static function lowRes($thumbnail) {
    $thumbnail = str_replace('maxresdefault', 'hqdefault', $thumbnail);
    return $thumbnail;
  }

  public static function highRes($Media) {
    $thumbnail = $Media->get('thumbnail_url');
    $thumbnail = str_replace('hqdefault', 'maxresdefault', $thumbnail);
    $Media->set('thumbnail_url', $thumbnail);
    return $Media;
  }
}
