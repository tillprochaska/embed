<?php
/**
 * Kirby oEmbed plugin for Kirby 2
 *
 * @author: Nico Hoffmann - distantnative.com
 * @version: 0.1
 *
 */

require_once('lib/bootstrap.php');
require_once('lib/Multiplayer.php');

if (c::get('oembed.caching', false))
  require_once("lib/phpfastcache/phpfastcache.php");



/**
 * Adding an oEmbed field method: e.g. $page->video()->oembed()
 */
field::$methods['oembed'] = function($field, $customParameters = array()) {
  return oembed_convert($field->value, $customParameters);
};


/**
 * Extending Kirbytext with an oEmbed tag: e.g.
 * (oembed: https://www.youtube.com/watch?v=wZZ7oFKsKzY)
 */
kirbytext::$tags['oembed'] = array(
  'attr' => array(
      'artwork'
  ),
  'html' => function($tag) {
    $customParameters = array(
      "artwork" => $tag->attr('artwork', 'true')
    );
    return oembed_convert($tag->attr('oembed'), $customParameters);
  }
);




/**
 * Converts a media URL into an embed (oEmbed)
 * @param string      The URL that will be converted
 */
function oembed_convert($text, $customParameters = array()) {
  $Essence = Essence\Essence::instance();
  $Multiplayer = new Multiplayer\Multiplayer( );


  if (c::get('oembed.caching', false)) :
    $cacheDir = kirby()->roots()->cache()."/oembed";
    if (!file_exists($cacheDir))
      mkdir($cacheDir);
    $Cache = phpFastCache("auto",
                    array("path" => kirby()->roots()->cache()."/oembed"));

    // try to get from Cache first.
    $oEmbed = $Cache->get($text.'-json');
  endif;

  if($oEmbed == null) :
      $oEmbed = $Essence->embed($text, [
          'thumbnailFormat' => 'maxres'
      ]);

      // Write to Cache Save API Calls next time
      if (c::get('oembed.caching', false))
        $Cache->set($text.'-json', $oEmbed, c::get('oembed.cacheexpires', 3600*24));
  endif;

  if ($oEmbed) :
      // Create oembed-video wrapper
      $htmlOutput = new Brick('div');

      if ($oEmbed->type === 'video') :
        $htmlOutput->addClass('oembed-video');
        $WrapperRatio = ($oEmbed->height / $oEmbed->width) * 100;
        $htmlOutput->attr('style','padding-top:'.$WrapperRatio.'%');

        if (c::get('oembed.lazyvideo', false))
          $htmlOutput->addClass('oembed-lazyvideo');

        // Create thumb image
        $htmlThumb = '<img src="'.cachedThumbnail($oEmbed->thumbnailUrl).'" class="thumb">';

        // Create play button overlay
        $htmlPlay = new Brick('div');
        $htmlPlay->addClass('play');
        $htmlPlay->append('<img src="'.url('assets/oembed/oembed-play.png').'">');

        // Add elements to wrapper
        $htmlOutput->append($htmlPlay);
        $htmlOutput->append($htmlThumb);

        // Create embed HTML
        $htmlEmbed = $Multiplayer->html($oEmbed->url, [
          'autoPlay' => true,
          'showInfos' => false,
          'showBranding' => false,
          'showRelated' => false,
          'highlightColor' => 'BADA55'
        ]);
        $htmlEmbed = str_replace(' src="', ' data-src="', $htmlEmbed);

      else:
        $htmlEmbed = $oEmbed->html;
      endif;

      // Add embed HTML to wrapper
      $htmlOutput->append($htmlEmbed);

      return replaceParameters($htmlOutput, $oEmbed->providerName, $customParameters);
  else :
    return $text;
  endif;
}


/**
 * Adds/replaces optional parameters
 * @param string      embed type / media sites
 */
function replaceParameters($html, $embedType, $customParameters = array()) {
  switch ($embedType) {
    case 'SoundCloud':
      if ($customParameters['artwork'] == 'false') :
        $html = str_replace('visual=true', 'visual=false', $html);
        $html = str_replace('show_artwork=true', 'show_artwork=false', $html);
      endif;
      return $html;
      break;
    default:
      return $html;
  }
}


/**
 * Returns URL to cached thumb if it exists
 * @param string      Thumbnail URL
 */
function cachedThumbnail($ThumbnailURL) {
  // Get images from cache if possible (and ombed.caching is true)
  if (c::get('oembed.caching', false)) :
    if (!file_exists('thumbs/oembed'))
      mkdir('thumbs/oembed');
    $thumbKey = 'thumb-'.md5($ThumbnailURL).'.'.pathinfo($ThumbnailURL, PATHINFO_EXTENSION);
    $thumbPath = kirby()->roots()->thumbs().'/oembed/'.$thumbKey;

    // Cache image if cache doesn't exist or expired
    if (!file_exists($thumbPath)) :
      $thumbFile = file_get_contents($ThumbnailURL);
      file_put_contents($thumbPath, $thumbFile);
    elseif (filemtime($thumbPath) >= (time() - c::get('oembed.cacheexpires', 3600*24))) :
      unlink($thumbPath);
      $thumbFile = file_get_contents($ThumbnailURL);
      file_put_contents($thumbPath, $thumbFile);
    endif;

    // Get URL to cached image
    $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

    return $root.'thumbs/oembed/' . $thumbKey;

  else :
    return $ThumbnailURL;
  endif;
}
