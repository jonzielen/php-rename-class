$(function() {
  $('#show-more').on('click', function(e) {
    e.preventDefault();
    $('.hidden-pre').slideToggle(500);
  });
});
