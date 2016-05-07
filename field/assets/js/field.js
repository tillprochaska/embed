(function($) {
  $.fn.oembedfield = function() {
    return this.each(function() {

      var $this = $(this);

      if($this.data('oembedfield')) {
        return;
      } else {
        $this.data('oembedfield', true);
      }

      var $icon = $this.next('.field-icon');

      $icon.css({
        'cursor': 'pointer',
        'pointer-events': 'auto'
      });


      var container = $this.parent().next();
      var $preview  = container.find('.field-oembed-preview__bucket');

      var timer;
      $this.bind('input', function() {
        window.clearTimeout(timer);
        timer = window.setTimeout(function(){
          oembedPreviewLoad($this, $preview);
        }, 1000);
      });

      $this.on('blur', function() {
        window.clearTimeout(timer);
        oembedPreviewLoad($this, $preview);
      });

      oembedPreviewLoad($this, $preview);

      $icon.on('click', function() {
        var url = $.trim($this.val());
        if(url !== '' && $this.is(':valid')) {
          window.open(url);
        } else {
          $this.focus();
        }
      });

    });
  };
})(jQuery);


function oembedPreviewLoad($this, $preview) {
  var url = $.trim($this.val());

  if(url === '') {
    $preview.css('opacity', '0').html('');

  } else if($this.is(':valid')) {
    $preview.css('opacity', '0');

    $.ajax({
      url:     $this.data('ajax'),
      type:    'POST',
      data:    { url: url },
      success: function(data) {
        $preview.html(data[0]).css('opacity', '1').find('.kirby-plugin-oembed__thumb').click(oembedLoad);
      },
    });
  }
}
