Kirby oEmbed v0.5
============

This plugin extends [Kirby 2 CMS](http://getkirby.com) with some basic [oEmbed](http://oembed.com) functionalities.  
It uses [Essence](https://github.com/felixgirault/essence) and [Multiplayer](https://github.com/felixgirault/multiplayer/) as PHP wrappers for oEmbed.

Using this plugin enables Kirby 2 CMS to display embeds of several media sites (e.g. YouTube, Vimeo, Soundcloud) by only providing the URL to the medium. The plugin also includes some [options](#options) to reduce the site loading time by using lazy videos (thumbnail preview and embed is only loaded after click) as well as extensive caching.

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

Instead of including additional CSS and JS links inside your header, you can also include the contents of `assets/oembed/oembed.css` or `assets/oembed/oembed.scss` as well as `assets/oembed/oembed.js` in your existing CSS/SCSS and JS files.

**If caching [option](#options) is active:** 
6. You might need to set CHMODs for `site/cache/oembed` and `thumbs/oembed`

# Update
1. Replace the `site/plugins/oembed` and  `assets/oembed` directories with recent version
2. Delete `site/cache/oembed` and `thumbs/oembed`

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
There are a few options you can set globally for Kirby oEmbed in `site/config/config.php`:
```php
// site/config/config.php
c::set('oembed.lazyvideo', true);
c::set('oembed.caching', false);
c::set('oembed.cacheexpires', 3600*24);
```
- **oembed.lazyvideo**:  
Only after clicking on the videos thumbnail, the actual embed (iframe, object) is loaded (default: false)
- **oembed.caching**:  
Enable/disable caching of oEmbed HTML and video thumbnails (default: false)
- **oembed.cacheexpires**:  
Duration after the cached thumbnails expire in seconds (default: 3600)

**Optional parameters:**
There are a few optional parameters for some media sites. For the Kirbytext tag you can use them in the following way:
 
```
(oembed: https://www.youtube.com/watch?v=wZZ7oFKsKzY color: FF00FF)
```

And for the field method `->oembed()`:
```php
<?php echo $page->featured_video()->oembed(array('color' => 'FF00FF')); ?>
```

The following parameters are available so far:
- **YouTube**
-- color
- **Vimeo**
-- color
- **SoundCloud**
-- visual
-- artwork

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
