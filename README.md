Kirby oEmbed
============

This plugin extends [Kirby CMS](http://getkirby.com) with some basic [oEmbed](http://oembed.com) functionalities. 
Uses [Embera](https://github.com/mpratt/Embera) as a PHP wrapper for oEmbed.

## Installation
1. Download [Kirby oEmbed](https://github.com/distantnative/kirby-oembed/zipball/master/)
2. Copy the `oembed` folder to `site/plugins/`
3. Add to your CSS for fluid + lazy videos:
```
.oembed-video {
  width: 100%;
  position: relative;
  padding: 0;
  padding-top: 56.25%;
  background-color: #dedede;
}

.oembed-video iframe,
.oembed-video object {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.oembed-video .thumb {
  cursor: pointer;
}

.oembed-video iframe,
.oembed-video object {
  display: none;
}
```
4. Add to your JS for lazy videos (jQuery required):
```
$(function() {
  $('.oembed-video .thumb').click(function() {
    wrapper = $(this).parent();
    embed = wrapper.find('iframe, object');
    embed.attr('src', embed.attr('data-src'));
    embed.css({'display' : 'block'});
    wrapper.find('.thumb').remove();
  });
});
```

## Usage
There are two way to use Kirby oEmbed:

**Inside (Kirbytext) fields:**

Use `(oembed: URL)` inside your Kirbytext. The URL has to point to a supported media (e.g. YouTube, Vimeo, Soundcloud).
```
Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
(oembed: https://www.youtube.com/watch?v=wZZ7oFKsKzY)
```

**In templates:**

Use the field method `->oembed()` on fields that contain the link to the supported media (e.g. YouTube, Vimeo, Soundcloud).
```
<?php echo $page->featured_video()->oembed(); ?>
```
