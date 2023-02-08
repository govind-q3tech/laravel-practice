//Header
$(document).on('scroll', function() {
   if ($(document).scrollTop() > 50) {
      $('.header').addClass('header-shrink');
   } else {
      $('.header').removeClass('header-shrink');
   }
});

/*search bar*/
  $(document).ready(function(){
  $(".search-btn-mobile").click(function(){
    $(".search-field").slideToggle("search-field-open"); 
  });
});