(function ($) {
    "use-strict";
  
  
  
  var owl_1 = $(".owl_1");
  owl_1.owlCarousel({
    rtl: true,
    loop: true,
    responsiveClass: true,
    autoplay: true,
    autoplayTimeout: 3500,
    autoplayHoverPause: true,
    margin:20,
    nav:true,
    animateIn: 'fadeIn',
    animateOut: 'fadeOut',
    nav: false,
    dots:false,
   
    
  
    responsive: {
      0: {
        items: 1,
      
      },
      600: {
        items: 1,
   
      },
      1000: {
        items: 1,
   
      },
      1200: {
        items:1,
     
      },
    },
  });
  var owl_2 = $(".owl_2");
  owl_2.owlCarousel({
    rtl: true,
    center:true,
    loop: true,
    responsiveClass: true,
    autoplay: false,
    autoplayTimeout: 3500,
    autoplayHoverPause: true,
    nav: true,
    navText: [
      "<i class='fas fa-arrow-left'></i>",
      "<i class='fas fa-arrow-right'></i>",
    ],
  
    margin:10,
    stagePadding:0,
  
    responsive: {
      0: {
        items: 1,
        nav: false,
        stagePadding:0,
      },
      500: {
        items: 1,
        nav: false,
        stagePadding:0,
      },
      900: {
        items: 3,
        nav: false,
        stagePadding:0,
      },
      1000: {
        items: 4,
        nav: true,
        stagePadding:0,
      },
      1200: {
        items: 5,
        nav: true,
        stagePadding:0,
      },
    },
  });
  
  
  // Go to the next item
  $('.custom-nav-next').click(function() {
    owl_1.trigger('next.owl.carousel');
      
  })
  // Go to the previous item
  $('.custom-nav-prev').click(function() {
      // With optional speed parameter
      // Parameters has to be in square bracket '[]'
      owl_1.trigger('prev.owl.carousel', [300]);
  })
  
  
  })(jQuery);


  var owl_4 = $(".owl_4");
owl_4.owlCarousel({
  rtl: true,
  loop: true,
  responsiveClass: true,
  autoplay: false,
  autoplayTimeout: 3500,
  autoplayHoverPause: true,
  margin:20,
  dots:true,
  responsive: {
    0: {
      items: 1,
    
    },
    600: {
      items: 1,
 
    },
    1000: {
      items: 1,
 
    },
    1200: {
      items:1,
   
    },
  },
});


var owl_5 = $(".owl_5");
owl_5.owlCarousel({
 rtl:true,
  loop: true,
  responsiveClass: true,
  autoplay: true,
  autoplayTimeout: 3500,
  autoplayHoverPause: true,
  nav: true,
  navText: [
    "<i class='fas fa-arrow-left'></i>",
    "<i class='fas fa-arrow-right'></i>",
  ],

  margin:10,
  stagePadding:0,

  responsive: {
    0: {
      items: 1,
      nav: false,
      stagePadding:0,
    },
    500: {
      items: 1,
      nav: false,
      stagePadding:0,
    },
    900: {
      items: 3,
      nav: false,
      stagePadding:0,
    },
    1000: {
      items: 4,
      nav: true,
      stagePadding:0,
    },
    1200: {
      items: 4,
      nav: true,
      stagePadding:0,
    },
  },
});