// JS for Instgaram Feed
jQuery(document).ready(function() {
  var token = "IGQWRQUHRhazQ4UVIxRnRGMVk5YmdEcmhtOHlyYm1zNGxJcGxrRG01OFNHYm02RWd2bUtmOTItUGZADdktHX0hFNmxlQ1E1OVBtVTQwaDhfMFVrQ0puREVMaVhRZAEdZAREtxcDJMRlZA1ZAXkxdwZDZD";
  if(token != ''){
  $.ajax({
    url: "https://graph.instagram.com/me/media?access_token="+token+"&fields=media_url,media_type,caption,permalink",
    type: "GET",
    success: function (res) {
      let html = "";
      let sliderHtml = "";
      let count = 0;
      $.each(res.data, function (key, value) {
        if (
          value.media_type == "IMAGE" ||
          value.media_type == "CAROUSEL_ALBUM"
        ) {
          html += `<div class="insta__item"><div class="insta_content">
                   <a href="` +value.permalink +`" target="_blank">                                          
                    <img class="insta__image" src="` + value.media_url +`" alt="image"></a></div></div>`;
          sliderHtml += `<div class="insta__item"><div class="insta_content">
                         <a href="` +value.permalink +`" target="_blank">                                            
                         <img class="insta__image" src="` + value.media_url +`" alt="image"></a></div></div>`;
          count++;
        } else if (value.media_type == "VIDEO") {
          html += `<div class="insta__item"><div class="get-social-box">
                    <a href="` +value.permalink +`" target="_blank">                                             
                    <video class="insta__image" controls><source src="` + value.media_url +`" type="video/mp4"></video></a></div></div>`;
          sliderHtml += `<div class="insta__item"><div class="insta_content">
                        <a href="` +value.permalink +`" target="_blank">                                             
                        <video class="insta__image" controls><source src="` + value.media_url +`" type="video/mp4"></video></a></div></div>`;
          count++;
        }
        if (count == 15) return false;
      });

      $(".instagram-wrapper").html(html);
      $(".instagram-wrapper").html(sliderHtml);
      $('.instagram-wrapper').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: true,
        responsive: [
          {
            breakpoint: 767,
            settings: {
              arrows: false,
              dots: true,
              slidesToShow: 2,
            }
          },
          {
            breakpoint: 479,
            settings: {
              arrows: false,
              dots: true,
              slidesToShow: 1,
            }
          }
        ]
      });
    },
  });
}
});


// JS for adding menu more link in navigation
jQuery(document).ready(function() {
  $(window).scroll(function() {
    if ($(this).scrollTop() > 500) {
        $('.to-the-top').fadeIn(500)
    } else {
        $('.to-the-top').fadeOut(500)
    }
  });
  $('.to-the-top').click(function(event) {
      event.preventDefault();
      $('html, body').animate({
          scrollTop: 0
      }, 800)
  });
});


// JS for Product tab
jQuery(document).ready(function() {
  $('.category-list ul').each(function(){
    var active, content, links = $(this).find('a');
    active = links.first().addClass('active');
    content = $(active.attr('href'));
    links.not(':first').each(function () {
      $($(this).attr('href')).hide();
    });
    $(this).find('a').click(function(e){
      active.removeClass('active');
      content.hide();
      active = $(this);
      content = $($(this).attr('href'));
      active.addClass('active');
      content.show();
      return false;
    });
  });
});


// Js for footer & filter toggle
function FooterToggle(){	
  "use strict";	
    jQuery('.footer-widgets .widget-content .wp-block-heading, .filters-container .filter-title').on("click",function () {
        if(jQuery(this).parent().hasClass('toggled-on')){	   
          jQuery(this).parent().removeClass('toggled-on');
          jQuery(this).parent().addClass('toggled-off');
      }else {
          jQuery(this).parent().addClass('toggled-on');
          jQuery(this).parent().removeClass('toggled-off');
      }
      return (false);
  });
}
jQuery(document).ready(function() { "use strict";  FooterToggle()});


// JS for Grid/List toggle 
jQuery(document).ready(function() {
  $(function() {
    $(".gridlist-toggle .view-icon").click(function() {
       // remove classes from all
       $(".gridlist-toggle .view-icon").removeClass("active");
       // add class to the one we clicked
       $(this).addClass("active");
    });
 });
  $('.gridlist-toggle .view-icon.grid-4').click(function(event){
    event.preventDefault();
    $('ul.products').removeClass('columns-3 list');
    $('ul.products').addClass('columns-4');
  });
  $('.gridlist-toggle .view-icon.grid-3').click(function(event){
    event.preventDefault();
    $('ul.products').removeClass('columns-4 list');
    $('ul.products').addClass('columns-3');
  });
  $('.gridlist-toggle .view-icon.list').click(function(event){
    event.preventDefault();
    $('ul.products').removeClass('columns-4 columns-3');
    $('ul.products').addClass('list');
  });
});


jQuery(document).ready(function() {
  // JS for adding label in peroducts per page option in shop page
  $( "body .products-per-page" ).prepend('<span class="show-product">Show</span>');

  // wrap price & review in single product page
  $('.single-product .summary p.price, .single-product .woocommerce-product-rating, .single-product .write-review').wrapAll('<div class="product-review-section"></div>');

  // Js for language carat icon
  $('.js-wcml-dropdown-click-toggle').on('click', function(){
    $(this).toggleClass('open');
  });
});


// JS to slide woocommercde product gallery 
jQuery(document).ready(function() {
  setTimeout(function() {
    $('.woocommerce-product-gallery__wrapper').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      infinite:false,
      arrows: false,
      fade: true,
      asNavFor: '.flex-control-thumbs'
    });
    $('.flex-control-thumbs').slick({
      slidesToShow: 5,
      slidesToScroll: 1,
      infinite:false,
      asNavFor: '.woocommerce-product-gallery__wrapper',
      focusOnSelect: true
    });

    $('.quantity').on('click', '.plus', function(e) {
      e.preventDefault();
      var $input = $(this).prev('input.qty');
      var val = parseInt($input.val());
      $input.val(val + 1).change();
    });

    $('.quantity').on('click', '.minus', function(e) {
        e.preventDefault();
        var $input = $(this).next('input.qty');
        var val = parseInt($input.val());
        if (val > 1) {
            $input.val(val - 1).change();
        }
    });

  }, 1000);
});

// Review Popup
jQuery(document).ready(function() {
  $( "#review_form_wrapper" ).insertAfter( "#openPopup" );
  $( "#openPopup" ).on( "click", function() {
      $("#review_form_wrapper").css("display", "block")
  });
  $("#closePopup").click(function() {
    $("#review_form_wrapper").css("display", "none")
  });

  // lens toggle tab
  $(".entry-summary button.add-power").on( "click", function() {
    $(".lens-type-tab-wrapper").css("display", "block");
    $(".lens-tab .overlay").addClass("active");
  });
  $(".closelenstab").click(function() {
    $(".lens-type-tab-wrapper").css("display", "none");
    $(".lens-tab .overlay").removeClass("active");
  });
  $("ul.select-lens-type li.frame").on( "click", function() {
    // alert($(this).data('lens_type'));
    var lens_type = $(this).data('lens_type');
    $(".sub-lenses").css("display", "block");
    $(".select-lens-type").css("display", "none");
    $(".lens-tab ul.sub-lenses .prescription-form.progresiive").css("display", "none");
    $(".lens-tab ul.select-lens-type .subtotal-of-product .button").css("display", "none");
    get_lenses_by_type( lens_type );
  });
  $(".lens-tab-header span svg").on( "click", function() {
    $(".select-lens-type").css("display", "block");
    $(".sub-lenses").css("display", "none");
  });
  $("ul.select-lens-type li.frame.progressive").on( "click", function() {
    $(".lens-tab ul.sub-lenses .prescription-form.progresiive").css("display", "block");
  });
  $("ul.select-lens-type li.frame.only-frame").on( "click", function() {
    $(".lens-tab ul.select-lens-type .subtotal-of-product .button").css("display", "block");
  });

  function get_lenses_by_type( lens_type ) {
    $form        = $('form.cart'),
    product_id   = parseInt( $('form.cart').find('input[name=product_id]').val() );
    variation_id = parseInt( $('form.cart').find('input[name=variation_id]').val() );

    var data = {
      product_id : product_id,
      variation_id : variation_id,
      lens_type : lens_type,
      action: 'lr_get_lenses_by_type'
    }

    $.ajax({
      dataType: 'json',
      url: ajax_obj.ajax_url,
      type: 'POST',
      data: data,
      success: function (response) {
        console.log( response );
        if ( response.success ) {
          $('.sub-lenses').find('.lr_sub_lenses').html( response.data.html );
        }
      },
      error: function ( err ) {
        console.log( err );
      }
      }); 
  }

  $( 'body' ).on( 'click', '#lr_sub_lenses li', function() {
    // $(this).block({
    //     message: null
    // });
    $(this).toggleClass('selected');
    // $(this).off('click');
    var lens_type     = $(this).data('lens_type');
    var lens_id       = $(this).data('lens_id');
    var lens_price    = $(this).data('lens_price');
    var product_price = $(this).data('product_price');

    $('.sub-lenses').find('#lr_lens_type').val( lens_type );
    $('.sub-lenses').find('#lr_lens_id').val( lens_id );
    $('.sub-lenses').find('#lr_lens_price').val( lens_price );
    $('.sub-lenses').find('#lr_product_price').val( product_price );

    // var originalPrice = parseFloat($('.lens-tab .subtotal-of-product .woocommerce-Price-amount.amount').text().replace(/[^\d.-]/g, ''));
    // var newPrice = originalPrice + lens_price;

    var newPrice = $(this).hasClass("selected") ? ( product_price + lens_price ) : product_price;
    
    // Display the new price
    $('.lens-tab .subtotal-of-product .woocommerce-Price-amount.amount').html('<span class="amount">' + '$' + newPrice.toFixed(2) + '</span>');

  });

});




// jQuery(document).ready(function($) {
//     $('#eyewear-prescription-form').on('submit', function(e) {
//         e.preventDefault();

//         // Serialize the form data into an object
//         var formData = {};
//         $(this).serializeArray().forEach(function(item) {
//             formData[item.name] = item.value;
//         });

//         // Display the form data
//         displayFormData(formData);
//     });

//     function displayFormData(formData) {
//         // Assuming you have an empty <div> with the ID "form-data-container"
//         var container = $('#form-data-container');
        
//         // Create HTML to display the form data
//         var html = '';
//         for (var field in formData) {
//             html += '<p>' + field + ': ' + formData[field] + '</p>';
//         }

//         // Insert the HTML into the container
//         container.html(html);
//     }
// });

// SHOBHA.

jQuery(document).ready(function() {
  // lens toggle tab
  $(".entry-summary button.add-power").on( "click", function() {
    $(".lens-type-tab-wrapper").css("display", "block");
    $(".lens-tab .overlay").addClass("active");
  });


  $(document).on('click', '.single_add_to_cart_button', function (e) {
    e.preventDefault();

    var $thisbutton = $(this),
        $form = $('form.cart'),
        id = $thisbutton.val(),
        product_qty = $form.find('input[name=quantity]').val() || 1,
        product_id = $form.find('input[name=product_id]').val() || id,
        variation_id = $form.find('input[name=variation_id]').val() || 0;

    var data = {
        action: 'woocommerce_ajax_add_to_cart',
        product_id: product_id,
        quantity: product_qty,
        variation_id: variation_id,
    };
    
    var lr_lens_type     = $(".lens-tab").find("#lr_lens_type").val();
    var lr_lens_id       = $(".lens-tab").find("#lr_lens_id").val();
    var lr_lens_price    = $(".lens-tab").find("#lr_lens_price").val();
    var lr_product_price = $(".lens-tab").find("#lr_product_price").val();



    var lrl_sph  = $(".lens-tab").find("#lrl_sph").val();
    var lrl_cyl  = $(".lens-tab").find("#lrl_cyl").val();
    var lrl_axis = $(".lens-tab").find("#lrl_axis").val();
    var lrr_sph  = $(".lens-tab").find("#lrr_sph").val();
    var lrr_cyl  = $(".lens-tab").find("#lrr_cyl").val();
    var lrr_axis = $(".lens-tab").find("#lrr_axis").val();
    var lr_accept = $(".lens-tab").find("#lr_accept").val();

    data[ 'lr_lens_type' ]     = lr_lens_type;
    data[ 'lr_lens_id' ]       = lr_lens_id;
    data[ 'lr_lens_price' ]    = lr_lens_price;
    data[ 'lr_product_price' ] = lr_product_price;

    data[ 'lrl_sph' ]  = lrl_sph;
    data[ 'lrl_cyl' ]  = lrl_cyl;
    data[ 'lrl_axis' ] = lrl_axis;
    data[ 'lrr_sph' ]  = lrr_sph;
    data[ 'lrr_cyl' ]  = lrr_cyl;
    data[ 'lrr_axis' ] = lrr_axis;
    data[ 'lr_accept' ] = lr_accept;

    console.log(data);

    $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

    if ( lr_lens_type != '' && lr_lens_id != '' ) {
      $.ajax({
          type: 'post',
          url: wc_add_to_cart_params.ajax_url,
          data: data,
          beforeSend: function (response) {
              $thisbutton.removeClass('added').addClass('loading');
          },
          complete: function (response) {
              $thisbutton.addClass('added').removeClass('loading');
          },
          success: function (response) {
              if (response.error && response.product_url) {
                  window.location = response.product_url;
                  return;
              } else {
                  $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                  window.location = wc_add_to_cart_params.cart_url;
              }
          },
      });
    }

    return false;
  });

  // jQuery( document ).on( 'click', '.single_add_to_cart_button ', function() {
  //   var data = array(
  //     'action'  = 'lr_get_lense_data',
  //     'lr_page' = 1213,
  //   );
  //   if ( jQuery( '#bkap_vendor_id' ).length > 0 ) {
  //       data['bkap_vendor_id'] = jQuery( '#bkap_vendor_id').val();
  //   }
  //   jQuery.ajax({
  //       url: ajax_obj.ajax_url,
  //       data: {
  //           action        : 'lr_get_lense_data',
  //           lr_page       : 1213,
  //       },
  //       type: 'POST',
  //       success: function( response ) {
  //         if (response.error & response.product_url) {
  //           window.location = response.product_url;
  //           return;
  //         } else {
  //           $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $(this)]);
  //         }
  //       }
  //   });
  // });

});
















