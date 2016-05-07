(function($) {
  $.fn.oembedfield = function() {

    var setupIcon = function($this) {
      var $icon = $this.next('.field-icon');

      $icon.css({
        'cursor': 'pointer',
        'pointer-events': 'auto'
      });

      $icon.on('click', function() {
        var url = $.trim($this.val());
        if(url !== '' && $this.is(':valid')) {
          window.open(url);
        } else {
          $this.focus();
        }
      });
    };

    var updateEmbed = function($this, $bucket, $label, $info) {
      var url = $.trim($this.val());

      if(!$this.data('oembedurl') || url !== $this.data('oembedurl')) {
        if($bucket)       updatePreview($this, $bucket, $label, url);
        if($info.wrapper) updateInfo($this, $info, url);
        $this.data('oembedurl', url);
      }
    };

    var updatePreview = function($this, $bucket, $label, url) {
      if(url === '') {
        $bucket.css('opacity', '0').html('');
        $label.css('opacity', '1');

      } else if($this.is(':valid')) {
        $bucket.css('opacity', '0');
        $label.css('opacity', '1');

        $.ajax({
          url:     $this.data('ajax') + 'preview',
          type:    'POST',
          data:    { url: url },
          success: function(data) {
            $label.css('opacity', '0');
            $bucket.html(data[0]);
            $bucket.css('opacity', '1');
            $bucket.find('.kirby-plugin-oembed__thumb').click(pluginOembedLoadLazyVideo);
          },
        });
      }
    };


    var updateInfo = function($this, $info, url) {
      if(url === '') {
        $info.wrapper.slideUp();

      } else if($this.is(':valid')) {

        $.ajax({
          url:     $this.data('ajax') + 'info',
          type:    'POST',
          data:    { url: url },
          success: function(data) {
            if(data[0]!=='false') {

              if(data.title) {
                $info.title.html(data.title).show();
              } else {
                $info.title.hide();
              }

              if(data.authorName) {
                $info.author.show().find('a').attr('href', data.authorUrl ).html(data.authorName);
              } else {
                $info.author.hide();
              }

              if(data.providerName) {
                $info.provider.show().find('a').attr('href', data.providerUrl ).html(data.providerName);
              } else {
                $info.provider.hide();
              }

              if(data.type) {
                $info.type.show().html(data.type);
              } else {
                $info.type.hide();
              }

              if($info.wrapper.prop('style').display === '') {
                $info.wrapper.show();
              } else {
                $info.wrapper.slideDown();
              }

            } else {
              $info.wrapper.slideUp();
            }
          },
        });
      }
    };

    return this.each(function() {

      var $this = $(this);

      if($this.data('oembedfield')) { return; }
      else {
        $this.data('oembedfield', true);
      }

      setupIcon($this);

      var inputTimer;
      var $preview = $this.parent().nextAll('.field-oembed-preview');
      var $bucket  = $preview.find('.field-oembed-preview__bucket');
      var $label   = $preview.find('.field-oembed-preview__label');
      var $info    = $this.parent().nextAll('.field-oembed-info');
      $info    = {
        wrapper:  $info,
        title:    $info.find('.field-oembed-info__title'),
        author:   $info.find('.field-oembed-info__author'),
        provider: $info.find('.field-oembed-info__provider'),
        type:     $info.find('.field-oembed-info__type')
      };

      $this.bind('input', function() {
        window.clearTimeout(inputTimer);
        inputTimer = window.setTimeout(function(){
          updateEmbed($this, $bucket, $label, $info);
        }, 1000);
      });

      $this.on('blur', function() {
        window.clearTimeout(inputTimer);
        updateEmbed($this, $bucket, $label, $info);
      });

      updateEmbed($this, $bucket, $label, $info);

      $this.focus(function(){
        if(!$this.parents('.field').hasClass('field-with-error')) {
          $preview.css('border-color','#8dae28');
          $info.wrapper.css('border-color','#8dae28');
        } else {
          $preview.css('border-color','#000');
          $info.wrapper.css('border-color','#000');
        }
      }).blur(function(){
        $preview.css('border-color','');
        $info.wrapper.css('border-color','');

      });
    });
  };
})(jQuery);
