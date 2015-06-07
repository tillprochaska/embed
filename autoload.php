<?php

class OembedAutoload {
  public static function autoload($class) {
    $path = dirname( __FILE__ ) . DS . 'lib' . DS . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) require $path;
  }
}
