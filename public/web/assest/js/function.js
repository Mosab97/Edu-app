$(function () {
  "use strict";

 	/*------------------------------------
		menu mobile
	--------------------------------------*/
  $(".header-mobile__toolbar").on("click", function () {
    $(".menu--mobile").addClass("menu-mobile-active");
    $(".mobile-menu-overlay").addClass("mobile-menu-overlay-active");
  });

  $(".mobile-menu-overlay").on("click", function () {
    $(".menu--mobile").removeClass("menu-mobile-active");
    $(".mobile-menu-overlay").removeClass("mobile-menu-overlay-active");
  });

  $(".main-header .btn-close-header-mobile").on("click", function () {
    $(".menu--mobile").removeClass("menu-mobile-active");
    $(".mobile-menu-overlay").removeClass("mobile-menu-overlay-active");
  });
  if($(window).width() < 992){
    $('.menu--mobile .main-menu .menu_item .menu_link ').click(function(e){
      $(this).closest('.menu_item').siblings().find('.menu__submenu').slideUp()
      $(this).closest('.menu_item').siblings().find('.fa-chevron-down').removeClass('fa-chevron-up').addClass('fa-chevron-down')
      $(this).closest('.menu_item').find('.menu__submenu').slideToggle()
      $(this).closest('.menu_item').find('.fa-chevron-down').toggleClass('fa-chevron-up')
    })
  }
  

  
	/*------------------------------------
		loader page
	--------------------------------------*/
  $(window).on("load", function () {
    $(".loader-page").fadeOut(500);
    var wow = new WOW({
      boxClass: 'wow',
      animateClass: 'animated',
      offset: 0,
      mobile: false,
      live: true
    });
    new WOW().init();
  });


$(function(){
$('.filterHead .fa-chevron-down').click(function(){
$('.filterCont').slideToggle();
$('.filterHead .fa-chevron-down').toggleClass('Rotate')
}) ; 
});
 
});
 