/*!jslint
  this
*/
jQuery(function ($) {
	"use strict";
	$(document).on("click", ".nav-toggle", function () {
		$(this).toggleClass("open");
		$("html").toggleClass("nav-open");
	});
  function rtl_slick(){
  if ($('body').hasClass("rtl")) {
     return true;
  } else {
     return false;
  }}
	$('.banner-cl').slick({
		dots: false,
		infinite: true,
		speed: 300,
		slidesToShow: 1,
		adaptiveHeight: true,
		rtl: rtl_slick(),
		arrows: false,
		autoplay: true,
		autoplaySpeed: 5000
	});

	if ($(".gutentoc").length) {
		if ($(window).width() >= 992) {
			var StickBar2 = new StickySidebar(".gutentoc-toc-wrap", {
				topSpacing: 20,
				bottomSpacing: 20,
				containerSelector: ".entry-content",
				//innerWrapperSelector: ".social-share-in"
			});
		}
	}
  if ($(".floating-sidebar").length) {
    if ($(window).width() >= 992) {
      var StickBar2 = new StickySidebar(".floating-sidebar", {
        topSpacing: 20,
        bottomSpacing: 20,
        containerSelector: ".accommodation-items-main",
        //innerWrapperSelector: ".social-share-in"
      });
    }
  }
  if ($(window).width() <= 991) {
    $(document).on('click', '.floating-sidebar-widget h3', function(e){
      $(this).next().slideToggle();
    });
  }
	$(document).on("click",'.grf-button', function () {

		var $button = $(this);
		var oldValue = parseInt($button.parent().find(".ginput_quantity").val()) || 0;
		if ($button.text() == "+") {
			var newVal = parseInt(oldValue) + 1;
		} else {
			// Don't allow decrementing below zero
			if (oldValue > 0) {
				var newVal = parseInt(oldValue) - 1;
			} else {
				newVal = 0;
			}
		}

		$button.parent().find(".ginput_quantity").val(newVal).trigger('change');

	});
	function add_form_support() {
		if ($(".ginput_quantity").length) {
			$(".ginput_quantity").wrap('<span class="ginput_quantity_wrap"></span>');
			$(".ginput_quantity").after('<button type="button" class="grf-dec grf-button">-</button>');
			$(".ginput_quantity").before('<button type="button" class="grf-inc grf-button">+</button>');


			$(".ginput_product_price").each(function () {
				var Label = $(this).parents('.gfield_price').find('label').text();
				$(this).wrap('<span class="ginput_product_label">' + Label + '</span>');
			});

		}
	}
	add_form_support();
	$(document).on('gform_post_render', function (event, form_id, current_page) {

		add_form_support();
	});

	if ($(".faq-items").length) {
		$('.faq-item-content').hide();
		$('.faq-item-content:first').show();
		$('.faq-item-title').click(function () {
			$('.faq-item').removeClass('on');
			$('.faq-item-content').slideUp('normal');
			if ($(this).next().is(':hidden') == true) {
				$(this).parent().addClass('on');
				$(this).next().slideDown('normal');
			}
		});
	}
  $('.chabadautosubmit').on('change', function(){
    $('#hotelfilter').submit();
  });
  // custom scroll spy nav
  var lastId,
    topMenu = $(".floating-sidebar-widget-nav"),
    topMenuHeight = topMenu.outerHeight()+15,
    // All list items
    menuItems = topMenu.find("a"),
    // Anchors corresponding to menu items
    scrollItems = menuItems.map(function(){
      var item = $($(this).attr("href"));
      if (item.length) { return item; }
    });

    menuItems.click(function(e){
      var href = $(this).attr("href"),
          offsetTop = href === "#" ? 0 : $(href).offset().top - 50;
      $('html, body').stop().animate({ 
          scrollTop: offsetTop
      }, 300);
      e.preventDefault();
    });

    $(window).scroll(function(){
       var fromTop = $(this).scrollTop() + 100;
       var cur = scrollItems.map(function(){
         if ($(this).offset().top < fromTop)
           return this;
       });
       cur = cur[cur.length-1];
       var id = cur && cur.length ? cur[0].id : "";
       
       if (lastId !== id) {
           lastId = id;
           menuItems
             .parent().removeClass("active")
             .end().filter("[href='#"+id+"']").parent().addClass("active");
       }                   
    });
});
