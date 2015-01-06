Kirby oEmbed v0.2
============

This plugin extends [Kirby 2 CMS](http://getkirby.com) with some basic [oEmbed](http://oembed.com) functionalities. 
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
echo js('assets/oembed/oembed.min.js');
```

# Update
1. Replace the `site/plugins/oembed` and  `assets/oembed` directories with recent version

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
c::set('oembed.color', 'aad450');
c::set('oembed.caching', false);
c::set('oembed.cacheexpires', 3600);
```
- **oembed.lazyvideo**:  
Only after clicking on the videos thumbnail, the actual embed (iframe, object) is loaded (default: false)
- **oembed.color**:  
Color used to theme some media's (e.g. Vimeo) video elements (default: 'aad450')
- **oembed.caching**:  
Enable/disable caching of oEmbed HTML and video thumbnails (default: false)
- **oembed.cacheexpires**:  
Duration after the cached thumbnails expire in seconds (default: 3600)

# Examples
### Blog: Featured Video
Use Kirby oEmbed to embed featured videos to your blog posts. The URL to the video (e.g. on YouTube or Vimeo) is stored in a field calles ´video´ in this example.
```php
// site/snippets/article.php
<article>
  <aside class="entry-meta">
    ...
  </aside>

  <div class="entry-main">
    <?php if ($post->video()!='') : ?>
      <figure class="entry-cover">
        <?php echo $post->video()->oembed(); ?>
      </figure>
    <?php endif : ?>

    <div class="entry-content">
      <?php echo $post->text()->kirbytext(); ?>
    </div>
  </div>
  
</article>
```
