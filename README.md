Kirby oEmbed v0.1
============

This plugin extends [Kirby CMS v2](http://getkirby.com) with some basic [oEmbed](http://oembed.com) functionalities. 
Uses [Embera](https://github.com/mpratt/Embera) as a PHP wrapper for oEmbed.

# Installation
1. Download [Kirby oEmbed](https://github.com/distantnative/kirby-oembed/zipball/master/)
2. Copy the `site/plugins/oembed` directory to `site/plugins/`
3. Copy the `assets/oembed` directory to `assets/`
4. Add CSS link to your header:
```php
// site/snippets/header.php
echo css('assets/oembed/oembed.css');
```

**If lazy video [option](#options) is active:**
5. Add the following JS links to your header:
```php
// site/snippets/header.php
echo js('//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'); // if jQuery isn't included already
echo js('assets/oembed/oembed.js');
```

# Update
1. Copy and replace the `site/plugins/oembed` and  `assets/oembed` directories

# Usage
There are two way to use Kirby oEmbed:

**Inside (Kirbytext) fields:**

Use `(oembed: URL)` inside your Kirbytext. The URL has to point to a supported media (e.g. YouTube, Vimeo, Soundcloud).
```
Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
(oembed: https://www.youtube.com/watch?v=wZZ7oFKsKzY)
```

**In [templates](http://getkirby.com/docs/templates):**

Use the field method `->oembed()` on fields that contain the link to the supported media (e.g. YouTube, Vimeo, Soundcloud).
```php
<?php echo $page->featured_video()->oembed(); ?>
```

# Options <a id="options"></a>
There are a few options you can set for Kirby oEmbed in `site/config/config.php`:
```php
// site/config/config.php
c::set('oembed.lazyvideo', true);
c::set('oembed.color', 3f739f);
```
