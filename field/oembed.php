<?php

class OembedField extends UrlField {

  public $preview    = true;
  public $info       = true;
  public $cheatsheet = true;
  public $height     = 'none';

  public static $assets = [
    'css' => [
      'oembed.css',
      'field.css'
    ],
    'js' => [
      'oembed.js',
      'field.js'
    ]
  ];

  public function __construct() {
    parent::__construct();

    $this->type        = 'oembed';
    $this->icon        = 'object-group';

    $this->translations();
  }

  public function input() {
    $input = parent::input();
    $input->data('field', 'oembedfield');
    $input->data('ajax', url('api/plugin/oembed/'));
    return $input;
  }

  public function template() {
    $template = parent::template();

    if($this->preview) {
      $template->append(tpl::load(__DIR__ . DS . 'templates' . DS . 'preview.php', [
        'height' => $this->height,
      ]));
    }

    if($this->info) {
      $template->append(tpl::load(__DIR__ . DS . 'templates' . DS . 'info.php'));
    }

    return $template;
  }

  public function label() {
    $label = parent::label();

    if($this->cheatsheet) {
      $label->append(tpl::load(__DIR__ . DS . 'templates' . DS . 'cheatsheet.php'));
    }

    return $label;
  }

  protected function translations() {
    $root = dirname(__DIR__) . DS . 'translations' . DS;

    // Default (English)
    require($root . 'en.php');

    // Current panel language
    if(file_exists($root . panel()->language()->code() . '.php')) {
      require($root . panel()->language()->code() . '.php');
    }
  }

}
