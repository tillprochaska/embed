$(function() {
  $('.oembed-video .thumb, .oembed-video .play').click(function() {
    wrapper = $(this).parent();
    embed = wrapper.find('iframe, object');
    embed.attr('src', embed.attr('data-src'));
    embed.css({'display' : 'block'});
    wrapper.find('.play, .thumb').remove();
  });
});
