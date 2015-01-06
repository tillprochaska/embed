<?php
/**
 * Kirby oEmbed plugin for Kirby 2
 *
 * @author: Nico Hoffmann - distantnative.com
 * @version: 0.1
 *
 */

require('Embera/Autoload.php');


/**
 * Converts a media URL into an embed (oEmbed)
 * @param string      The URL that will be converted
 * @param true/false  Will the object be placed inline with text
 * @return string     The HTML with the embed (iframe, object)
 */
function oembed_convert($url, $_inline = false, $_page = false, $_fieldname = false) {

  return $_page->content()->get($_fieldname);
  /*
  try {

    page('mypage')->update(array(
      'title'        => 'A new title',
      'text'         => 'Some text',
      'anotherfield' => 'Some more data'
    ));

    echo 'The page has been updated';

  } catch(Exception $e) {

    echo $e->getMessage();

  }
  */


  $embera = new \Embera\Embera();
  $embera = new \Embera\Formatter($embera);
  $url_info = $embera->getUrlInfo($url);

  // For video embeds
  if ($url_info[$url]['type'] == 'video') :

    // Create oembed-video wrapper
    $output = new Brick('div');
    $output->addClass('oembed-video');
    if (c::get('oembed.lazyvideo', false))
      $output->addClass('oembed-lazyvideo');

    // Create embed element
    $embera->setTemplate('{html}');
    $embed = $embera->transform($url);

    if (c::get('oembed.lazyvideo', false)) :
      // Add Custom Parameters to embed URL (e.g. autoload)
      // YouTube
      if ($url_info[$url]['provider_name'] == 'YouTube')
        $embed = str_replace('?feature=oembed', '?feature=oembed'.'&amp;'.'autoplay=1'.'&amp;'.'rel=0'.'&amp;'.'showinfo=0', $embed);
      // Vimeo
      elseif ($url_info[$url]['provider_name'] == 'Vimeo')
        $embed = str_replace('" width="', '?'.'autoplay=1'.'&amp;'.'color='.c::get('oembed.color','aad450').'&amp;'.'byline=0'.'&amp;'.'title=0'.'" width="', $embed);

      $embed = str_replace(' src="', ' data-src="', $embed);

      // Create thumbnail placeholder
      // Get thumbnail with higher resolution for YouTube
      $youtube_maxres_thumb = youtube_id_from_url($url);
      if ($youtube_maxres_thumb) :
        $thumb_url = "http://i1.ytimg.com/vi/".$youtube_maxres_thumb."/maxresdefault.jpg";
      else :
        $embera->setTemplate('{thumbnail_url}');
        $thumb_url = $embera->transform($url);
      endif;

      // Get images from cache if possible (and ombed.caching is true)
      if (c::get('oembed.caching', false)) :

        // Create cache directory if it doesn't exist yet
        $_cahce_dir = kirby()->roots()->index() . '/thumbs/oembed';
        dir::make($_cahce_dir);

        $thumb_cache_key   = 'thumb-' . md5($thumb_url) . '.' . pathinfo($thumb_url, PATHINFO_EXTENSION);;
        $thumb_cache_path  = $_cahce_dir . '/' . $thumb_cache_key;

        // Try to fetch data from cache
        $thumb_cache_exists = (filemtime($thumb_cache_path) < time() - c::get('oembed.cacheexpires', 3600)) ? false : file_exists($thumb_cache_path);

        // Cache image if cache doesn't exist or expired
        if (!$thumb_cache_exists) {
          $file_to_cache = file_get_contents($thumb_url);
          file_put_contents($thumb_cache_path, $file_to_cache);
        }

        // Get URL to cached image
        $thumb_url = 'thumbs/oembed/' . $thumb_cache_key;
      endif;

      $thumb = '<img src="'.$thumb_url.'" class="thumb">';


      // Create play button overlay
      $play = new Brick('div');
      $play->addClass('play');
      $play->append('<img src="'.url('assets/oembed/oembed-play.png').'">');

      // Create oembed-video wrapper
      $output = new Brick('div');
      if (!$_inline)
        $output->addClass('oembed-video');
      if (c::get('oembed.lazyvideo', false))
        $output->addClass('oembed-lazyvideo');

      // Add elements to wrapper
      $output->append($play);
      $output->append($thumb);
      $output->append($embed);

    else:
      $output = $embed;
    endif;

  // For non-video embeds
  else :
    $embera->setTemplate('{html}');
    $output = $embera->transform($url);
  endif;

  // return $output;
}


/**
 * Adding an oEmbed field method: e.g. $page->video()->oembed()
 */
field::$methods['oembed'] = function($field) {
  return oembed_convert($field->value, false, $field->page, $field->key);
};


/**
 * Extending Kirbytext with an oEmbed tag: e.g.
 * (oembed: https://www.youtube.com/watch?v=wZZ7oFKsKzY)
 */
kirbytext::$tags['oembed'] = array(
  'html' => function($tag) {
    return oembed_convert($tag->attr('oembed'), true);
  }
);


/**
 * Extracts the YouTube ID from an URL
 * @param string    The url from where the ID should be extracted.
 * @return string   The ID extracted from the URL - if not possible false
 */
function youtube_id_from_url($url) {
  $pattern =
    '%^# Match any youtube URL
    (?:https?://)?  # Optional scheme. Either http or https
    (?:www\.)?      # Optional www subdomain
    (?:             # Group host alternatives
      youtu\.be/    # Either youtu.be,
    | youtube\.com  # or youtube.com
      (?:           # Group path alternatives
        /embed/     # Either /embed/
      | /v/         # or /v/
      | .*v=        # or /watch\?v=
      )             # End path alternatives.
    )               # End host alternatives.
    ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
    ($|&).*         # if additional parameters are also in query string after video id.
    $%x'
    ;
    $result = preg_match($pattern, trim($url), $matches);
    if (false !== $result)
      return $matches[1];
    return false;
}
