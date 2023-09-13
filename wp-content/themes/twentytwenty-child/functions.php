<?php


require 'inc/custom-functions.php';
require 'inc/lr-taxonomy.php';


// enqueue style
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
   function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
   wp_enqueue_script( 'jquery-script', get_stylesheet_directory_uri() . '/jquery.min.js');
   wp_enqueue_script( 'slick-js', get_stylesheet_directory_uri() . '/slick.min.js');
   wp_enqueue_style( 'slick-css', get_stylesheet_directory_uri().'/slick.css' );
//    wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/custom.js', array('jquery'), null, true);

    wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/custom.js', array('jquery'), null, true );
    wp_localize_script( 'custom-script', 'ajax_obj',
        array( 
            'ajax_url' => admin_url( 'admin-ajax.php' ),
        )
    );
}


// Add widget areas
function register_widget_areas() {
   register_sidebar( array(
      'name'          => 'Footer #3',
      'id'            => 'sidebar-3',
      'description'   => 'Widgets in this area will be displayed in the second column in the footer',
      'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
      'after_widget'  => '</div></div>',
      'before_title'  => '<h2>',
      'after_title'   => '</h2>',
   ));
   register_sidebar( array(
      'name'          => 'Footer #4',
      'id'            => 'sidebar-4',
      'description'   => 'Widgets in this area will be displayed in the second column in the footer',
      'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
      'after_widget'  => '</div></div>',
      'before_title'  => '<h2>',
      'after_title'   => '</h2>',
   ));
   register_sidebar( array(
      'name'          => 'Footer Payment',
      'id'            => 'sidebar-5',
      'description'   => 'Widgets in this area will be displayed in the second column in the footer',
      'before_widget' => '<div class="widget-content footer-payment">',
      'after_widget'  => '</div>',
      'before_title'  => ' ',
      'after_title'   => ' ',
   ));
   register_sidebar( array(
    'name'          => 'Shop Sidebar',
    'id'            => 'sidebar-6',
    'description'   => 'Widgets in this area will be displayed in the shop page',
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => ' ',
    'after_title'   => ' ',
 ));

}
add_action( 'widgets_init', 'register_widget_areas' );  

// product category tab
function category_products_shortcode($atts) {
   $atts = shortcode_atts(array(
       'category_ids' => '', 
   ), $atts);

   $category_ids = explode(',', $atts['category_ids']);

   ob_start();

   echo '<div class="category-list">';
   echo '<ul>';
   foreach ($category_ids as $category_id) {
       $category_id = intval(trim($category_id));
       $category = get_term($category_id, 'product_cat');
       
       if (!empty($category)) {
           echo '<li><a href="#category-' . esc_attr($category_id) . '">' . esc_html($category->name) . '</a></li>';
       }
   }
   echo '</ul>';
   echo '</div>';
   
   foreach ($category_ids as $category_id) {
       $category_id = intval(trim($category_id));
       $category = get_term($category_id, 'product_cat');

       if (!empty($category)) {
           echo '<div id="category-' . esc_attr($category_id) . '" class="category-products">';
           //echo '<h2>' . esc_html($category->name) . '</h2>';

           $products_args = array(
               'post_type' => 'product',
               'posts_per_page' => -1,
               'tax_query' => array(
                   array(
                       'taxonomy' => 'product_cat',
                       'field' => 'term_id',
                       'terms' => $category_id,
                   ),
               ),
           );

           $products_query = new WP_Query($products_args);

           if ($products_query->have_posts()) {
               echo '<ul class="product-list products">';

               while ($products_query->have_posts()) {
                   $products_query->the_post();
                   global $product;
                  // $percentage = round( ( ( $product->regular_price - $product->sale_price ) / $product->regular_price ) * 100 );
                   $wishlist = do_shortcode("[ti_wishlists_addtowishlist loop=yes]");

                   echo '<li class="product">';
                   echo $wishlist;
                   echo '<a href="' . get_permalink() . '">';
                  // echo '<span class="onsale">- '.$percentage.'%</span>';
                   echo '<div class="product-thumbnail">' . get_the_post_thumbnail($product->get_id(), 'single-post-thumbnail') . '</div>';
                   echo '<div class="product-content-section">';
                   echo '<h2 class="woocommerce-loop-product__title">' . get_the_title() . '</h2>';
                   echo '<span class="price">' . $product->get_price_html() . '</span>';
                   echo '</div>';
                   echo '</a>';
                   echo '</li>';
               }

               echo '</ul>';
           } else {
               echo '<p>No products found in the category: ' . esc_html($category->name) . '</p>';
           }

           wp_reset_postdata();
           echo '</div>';
       }
   }

   return ob_get_clean();
}
add_shortcode('category_products', 'category_products_shortcode');


// service shortcode
function service_shortcode() {
    echo'<div class="service-section">
    <div class="elementor-container">
        <div class="elementor-column elementor-widget-icon-box">
          <div class="elementor-icon-box-wrapper">
              <div class="elementor-icon-box-icon">
                <span class="elementor-icon elementor-animation-">'.file_get_contents( get_stylesheet_directory_uri() . '/image/shipping.svg' ).'</span>
              </div>
              <div class="elementor-icon-box-content">
              <h5 class="elementor-icon-box-title"><span>Free Shipping</span></h5>
              <p class="elementor-icon-box-description">Capped at $50 per order </p>
              </div>
            </div>
        </div>
      <div class="elementor-column elementor-widget-icon-box">
        <div class="elementor-icon-box-wrapper">
            <div class="elementor-icon-box-icon">
            <span class="elementor-icon elementor-animation-">'.file_get_contents( get_stylesheet_directory_uri() . '/image/return.svg' ).'</span>
            </div>
            <div class="elementor-icon-box-content">
            <h5 class="elementor-icon-box-title"><span>Return of Goods</span></h5>
            <p class="elementor-icon-box-description">Back Guarantee in 15 days </p>
            </div>
          </div>
      </div>
      <div class="elementor-column elementor-widget-icon-box">
        <div class="elementor-icon-box-wrapper">
            <div class="elementor-icon-box-icon">
            <span class="elementor-icon elementor-animation-">'.file_get_contents( get_stylesheet_directory_uri() . '/image/payment.svg' ).'</span>
            </div>
            <div class="elementor-icon-box-content">
            <h5 class="elementor-icon-box-title"><span>Payment</span></h5>
            <p class="elementor-icon-box-description">100% Secure Payment </p>
            </div>
          </div>
      </div>
      <div class="elementor-column elementor-widget-icon-box">
        <div class="elementor-icon-box-wrapper">
            <div class="elementor-icon-box-icon">
            <span class="elementor-icon elementor-animation-">'.file_get_contents( get_stylesheet_directory_uri() . '/image/support.svg' ).'</span>
            </div>
            <div class="elementor-icon-box-content">
            <h5 class="elementor-icon-box-title"><span>Support</span></h5>
            <p class="elementor-icon-box-description">24/7 Customer Support</p>
            </div>
        </div>
      </div>
  </div>
</div>';
}
add_action( 'woocommerce_after_single_product_summary', 'service_shortcode', 9 );
add_action( 'woocommerce_after_cart', 'service_shortcode' );

// Sale Percentage Badge on products
add_filter( 'woocommerce_sale_flash', 'add_percentage_to_sale_badge', 20, 3 );
function add_percentage_to_sale_badge( $html, $post, $product ) {

  if( $product->is_type('variable')){
      $percentages = array();

      // Get all variation prices
      $prices = $product->get_variation_prices();

      // Loop through variation prices
      foreach( $prices['price'] as $key => $price ){
          // Only on sale variations
          if( $prices['regular_price'][$key] !== $price ){
              // Calculate and set in the array the percentage for each variation on sale
              $percentages[] = round( 100 - ( floatval($prices['sale_price'][$key]) / floatval($prices['regular_price'][$key]) * 100 ) );
          }
      }
      // We keep the highest value
      $percentage = max($percentages) . '%';

  } elseif( $product->is_type('grouped') ){
      $percentages = array();

      // Get all variation prices
      $children_ids = $product->get_children();

      // Loop through variation prices
      foreach( $children_ids as $child_id ){
          $child_product = wc_get_product($child_id);

          $regular_price = (float) $child_product->get_regular_price();
          $sale_price    = (float) $child_product->get_sale_price();

          if ( $sale_price != 0 || ! empty($sale_price) ) {
              // Calculate and set in the array the percentage for each child on sale
              $percentages[] = round(100 - ($sale_price / $regular_price * 100));
          }
      }
      // We keep the highest value
      $percentage = max($percentages) . '%';

  } else {
      $regular_price = (float) $product->get_regular_price();
      $sale_price    = (float) $product->get_sale_price();

      if ( $sale_price != 0 || ! empty($sale_price) ) {
          $percentage    = round(100 - ($sale_price / $regular_price * 100)) . '%';
      } else {
          return $html;
      }
  }
  return '<span class="onsale">' . esc_html__( '-', 'woocommerce' ) . ' ' . $percentage . '</span>';
}


// change position of breadcrumb
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
add_action( 'woocommerce_archive_description', 'woocommerce_breadcrumb', 15 );


// Display category image on category archive
add_action( 'woocommerce_archive_description', 'woocommerce_category_image', 25 );
function woocommerce_category_image() {
    if ( is_product_category() ){
	    global $wp_query;
	    $cat = $wp_query->get_queried_object();
	    $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
	    $image = wp_get_attachment_url( $thumbnail_id );
	    if ( $image ) {
		    echo '<img src="' . $image . '" alt="' . $cat->name . '" />';
		}
	}
}   

// change position of cart button in product loop
//remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
//add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 10 );


// wrap product content section
add_action ( "woocommerce_before_shop_loop_item_title", "after_li_started", 11 );
function after_li_started () {
    echo '<div class="product-content-section">';
}
add_action ( "woocommerce_after_shop_loop_item", "before_li_started", 10 );
function before_li_started () {
    echo '</div>';
}


// grid/list stucture add in shop page

add_action('woocommerce_before_shop_loop','before_shop_loop');
function before_shop_loop(){
    echo '<div class="category-toolbar"><nav class="gridlist-toggle">
         <a href="#" class="view-icon grid-4 active"></a>
         <a href="#" class="view-icon grid-3"></a>
         <a href="#" class="view-icon list"></a></nav>';
}

// display short description
function webroom_add_short_description_in_product_categories() {
	global $product;
	if ( ! $product->get_short_description() ) return;
	?>
	<div class="product-short-description">
		<?php echo apply_filters( 'woocommerce_short_description', $product->get_short_description() ) ?>
	</div>
	<?php
}
add_action('woocommerce_after_shop_loop_item_title', 'webroom_add_short_description_in_product_categories', 10);


//display color options on products
add_action( 'woocommerce_after_shop_loop_item_title', 'display_color_attribute', 5 );
function display_color_attribute() {
    global $product;
    // Get the color attribute terms
    $color_terms = wc_get_product_terms( $product->get_id(), 'pa_color', array( 'fields' => 'names' ) );

    if ( ! empty( $color_terms ) ) {
        echo '<div class="color-swatches">';
        foreach ( $color_terms as $color ) {
            // Output a color swatch
            echo '<span class="color-swatch" data-color="' . esc_attr( $color ) . '" style="background-color:'. esc_attr( $color ) .'"></span>';
        }
        echo '</div>';
    }   
}


// review section in single product page
function custom_content_after_price() {
    global $product;
    // Display the rating stars
    if ( $product->get_rating_count() < 0 ) {
        echo '<div class="product-rating">';
        echo wc_get_rating_html( $product->get_average_rating() );
        echo '</div>';
    }
    // Display the write a review link
    echo '<div id="openPopup" class="write-review">';
    echo '<span class="write-review-link"><span class="edit-review-icon"></span>' . __( 'Write a review', 'woocommerce' ) . '</span>';
    echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'custom_content_after_price', 10 );


// change position of price in single product page
function move_price_after_title() {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 6 );
}
add_action( 'woocommerce_single_product_summary', 'move_price_after_title', 1 );


// change title of related prodcuts in single product page
add_filter(  'gettext',  'change_related_products_title', 10, 3 );
function change_related_products_title( $translated, $text, $domain  ) {
    if( $text === 'Related products' && $domain === 'woocommerce' ){
        $translated = esc_html__( 'You Might Also Like', $domain );
    }
    return $translated;
}

// change title of description tab title  in single product page
add_filter( 'woocommerce_product_tabs', 'woo_customize_tabs', 100, 1 );
function woo_customize_tabs( $tabs ) {

    unset($tabs['additional_information']);
    $reviews_count = get_comments_number();
    $tabs['description']['title'] = __( 'Product Description' ); // Rename the description tab
    return $tabs;
}


// Quantity Box 
function custom_quantity_input() {
    echo '<div class="quantity-box">
            <span class="product-label">Quantity</span>
            <div class="quantity">
            <input type="button" class="minus" value="-">
            <input type="number" step="1" min="1" name="quantity" value="1" title="Qty" class="input-text qty text" size="4">
            <input type="button" class="plus" value="+">
          </div></div>';
}
add_action('woocommerce_before_add_to_cart_button', 'custom_quantity_input');

// Stock
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_stock', 10);
function custom_product_stock_label() {
    global $product;

    if ($product->managing_stock()) {
        $availability = $product->get_availability();

        echo '<div class="product-meta">';
        if ($availability['availability'] === 'in-stock') {
            echo '<span class="product-label">Availability</span> <span class="product-meta-data in-stock">In Stock</span>';
        } elseif ($availability['availability'] === 'out-of-stock') {
            echo '<span class="product-label">Availability</span> <span class="product-meta-data out-of-stock">Out of Stock</span>';
        } else {
            echo '<span class="product-label">Availability</span><span class="product-meta-data in-stock">' . $availability['availability'] .'</span>';
        }
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'custom_product_stock_label', 25);


// Type
function display_product_category() {
    global $product;
    // Get the product categories
    $categories = get_the_terms($product->get_id(), 'product_cat');
    if ($categories && !is_wp_error($categories)) {
        $category_names = array();
        foreach ($categories as $category) {
            $category_names[] = $category->name;
        }
        echo '<div class="product-meta">';
        echo '<span class="product-label">Type</span>';
        echo '<span class="product-meta-data">' . implode(', ', $category_names) . '</span>';
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'display_product_category', 20);

// Change "Your cart is currently empty" text
function custom_empty_cart_message() {
    return "You don't have any goods in your cart.";
}
add_filter( 'wc_empty_cart_message', 'custom_empty_cart_message' );


// Add BuyNow Button in single product page
add_action( 'woocommerce_before_add_to_cart_button', 'add_custom_addtocart_and_checkout', 30 );
function add_custom_addtocart_and_checkout() {
    global $product;

    $addtocart_url = wc_get_checkout_url().'?add-to-cart='. $product->get_id();
    $button_class  = 'single_add_to_cart_button buy_now button alt custom-checkout-btn';
    $button_text   = __("Buy It Now", "woocommerce");

    if( $product->is_type( 'simple' )) :
    ?>
    <script>
    jQuery(function($) {
        var url    = '<?php echo $addtocart_url; ?>',
            qty    = 'input.qty',
            button = 'a.custom-checkout-btn';

        // On input/change quantity event
        $(qty).on('input change', function() {
            $(button).attr('href', url + '&quantity=' + $(this).val() );
        });
    });
    </script>
    <?php

    elseif( $product->is_type( 'variable' ) ) : 

    $addtocart_url = wc_get_checkout_url().'?add-to-cart=';
    ?>
    <script>
    jQuery(function($) {
        var url    = '<?php echo $addtocart_url; ?>',
            vid    = 'input[name="variation_id"]',
            pid    = 'input[name="product_id"]',
            qty    = 'input.qty',
            button = 'a.custom-checkout-btn';

        // Once DOM is loaded
        setTimeout( function(){
            if( $(vid).val() != '' ){
                $(button).attr('href', url + $(vid).val() + '&quantity=' + $(qty).val() );
            }
        }, 300 );

        // On input/change quantity event
        $(qty).on('input change', function() {
            if( $(vid).val() != '' ){
                $(button).attr('href', url + $(vid).val() + '&quantity=' + $(this).val() );
            }
        });

        // On select attribute field change event
        $('.variations_form').on('change blur', 'table.variations select', function() {
            if( $(vid).val() != '' ){
                $(button).attr('href', url + $(vid).val() + '&quantity=' + $(qty).val() );
            }
        });
    });
    </script>
    <?php
    endif;
    echo '<a href="'.$addtocart_url.'" class="'.$button_class.'">'.$button_text.'</a>
    <button type="button" class="single_add_to_cart_button button add-power">Add Power</button>';
}

// HTML content in single product page
function add_custom_content_after_product_meta() {
    echo '<div class="custom-product-content">
            <span>'.file_get_contents( get_stylesheet_directory_uri() . '/image/truck-fast.svg' ).'Estimated deliver 5-7 days</span>
            <span class="social-share">
                <span class="social-share-title">Share With Us:</span>
                <ul class="social-icons">
                    <li><a href="#" target="_blank">'.file_get_contents( get_stylesheet_directory_uri() . '/image/Facebook.svg' ).'</a></li>
                    <li><a href="#" target="_blank">'.file_get_contents( get_stylesheet_directory_uri() . '/image/Instagram.svg' ).'</a></li>
                    <li><a href="#" target="_blank">'.file_get_contents( get_stylesheet_directory_uri() . '/image/Linkedin.svg' ).'</a></li>
                    <li><a href="#" target="_blank">'.file_get_contents( get_stylesheet_directory_uri() . '/image/Twitter.svg' ).'</a></li>
                </ul>
            </span>
            <span class="securepayment">'.file_get_contents( get_stylesheet_directory_uri() . '/image/secure-payment.svg' ).'</span>
          </div>';
}
add_action('woocommerce_single_product_summary', 'add_custom_content_after_product_meta', 30);


// Account dasboard Content
add_action( 'woocommerce_account_dashboard', 'custom_account_dashboard_content' );
function custom_account_dashboard_content(){
    echo '<div class="account-dasboard-links">
            <ul>
            <li><a href='.get_site_url().'/my-account/orders/">'.file_get_contents( get_stylesheet_directory_uri() . '/image/ac-order.svg' ).'  
                <h3>Orders</h3>
            </a></li>
            <li><a href='.get_site_url().'/my-account/downloads/">'.file_get_contents( get_stylesheet_directory_uri() . '/image/ac-download.svg' ).'<h3>Downloads</h3></a></li>
            <li><a href='.get_site_url().'/my-account/edit-address/">'.file_get_contents( get_stylesheet_directory_uri() . '/image/ac-address.svg' ).'<h3>Address</h3></a></li>
            <li><a href='.get_site_url().'/my-account/edit-account/">'.file_get_contents( get_stylesheet_directory_uri() . '/image/ac-account.svg' ).'<h3>Account Details</h3></a></li>
            </ul>
          </div>';
}


/* custom variable product cart button in lens selection tab */
function add_custom_cart_button() {
    global $product;
    $addtocart_url = wc_get_cart_url().'?add-to-cart='.$product->get_id();
    $button_class  = 'single_add_to_cart_button buy_now button alt custom-checkout-btn';
    $button_text   = __("I agree", "woocommerce");

    if( $product->is_type( 'simple' )) :
    ?>
    <script>
    jQuery(function($) {
        var url    = '<?php echo $addtocart_url; ?>',
            qty    = 'input.qty',
            button = 'a.custom-checkout-btn';

        // On input/change quantity event
        $(qty).on('input change', function() {
            $(button).attr('href', url + '&quantity=' + $(this).val() );
        });
    });
    </script>
    <?php

    elseif( $product->is_type( 'variable' ) ) : 

    $addtocart_url = wc_get_cart_url().'?add-to-cart=';
    ?>
    <script>
    jQuery(function($) {
        var url    = '<?php echo $addtocart_url; ?>',
            vid    = 'input[name="variation_id"]',
            pid    = 'input[name="product_id"]',
            qty    = 'input.qty',
            button = 'a.custom-checkout-btn';

        // Once DOM is loaded
        setTimeout( function(){
            if( $(vid).val() != '' ){
                $(button).attr('href', url + $(vid).val() + '&quantity=' + $(qty).val() );
            }
        }, 300 );

        // On input/change quantity event
        $(qty).on('input change', function() {
            if( $(vid).val() != '' ){
                $(button).attr('href', url + $(vid).val() + '&quantity=' + $(this).val() );
            }
        });

        // On select attribute field change event
        $('.variations_form').on('change blur', 'table.variations select', function() {
            if( $(vid).val() != '' ){
                $(button).attr('href', url + $(vid).val() + '&quantity=' + $(qty).val() );
            }
        });
    });
    </script>
    <?php
    endif;
    echo '<a href="'.$addtocart_url.'" class="'.$button_class.'">'.$button_text.'</a>';
}
add_shortcode('custom_cart_button', 'add_custom_cart_button');





// Lens Tab
function select_lens_type() {
    // error_log( print_r( $product, true ) );
    global $product;
    $variation_id = 0;
    // $product      = wc_get_product( $product_id );
    $product_id = $product->get_id();
    if ( $product->is_type( 'variation' ) ) {
        $product_id   = $product->get_parent_id();
        $variation_id = $product->get_id();
        $product      = wc_get_product( $variation_id );
    }

    $lr_taxonomy = 'lr_lens';
    $terms       = wp_get_object_terms($product_id, $lr_taxonomy, array( 'parent' => '0', 'orderby' => 'term_id', 'hide_empty' => '0' ) );
	// if ( $terms ) {
    //     foreach( $terms as $term ) {
    //         $c_terms = wp_get_object_terms($product_id, $lr_taxonomy, array( 'parent' => $term->term_id, 'orderby' => 'term_id', 'hide_empty' => false ) );
    //         if ( ! empty( $c_terms ) ) {
    //             foreach( $c_terms as $child ) {
    //             }
    //         }
    //     }
    // }
    

    $addtocart_url = wc_get_cart_url().'?add-to-cart='.$product->get_id();
    echo '<div class="lens-tab"><div class="overlay"></div><div class="lens-type-tab-wrapper">
            <div class="lens-tab-header">
                <span>'.file_get_contents( get_stylesheet_directory_uri() . '/image/black-arrow.svg' ).'Select Lens Type</span>
                <button class="closelenstab">'.file_get_contents( get_stylesheet_directory_uri() . '/image/Cross.svg' ).'</button>
            </div>';
            echo '<ul class="select-lens-type">';
            if ( $terms ) {
                foreach( $terms as $term ) {
                    // error_log( print_r( $terms, true ) );
                    $image_id  = get_option( 'z_taxonomy_image_id' . $term->term_id );
                    $image_url = get_option( 'z_taxonomy_image' . $term->term_id );

                    $class = 'frame';
                    if ( 56 === $term->term_id) {
                        $class .= ' progressive';
                    } elseif ( 57 === $term->term_id ) {
                        $class = 'only-frame';
                    }

                    echo '<li class="' . $class . '" data-lens_type="' . $term->term_id . '">
                        <img src="' . wp_get_attachment_url( $image_id ) . '" id="lens_image" class="lens_image" width="48" height="48" />
                        <div class="content-wrapper">
                            <div class="lens-title">
                                <h5>' . $term->name . '</h5>
                                <p>' . $term->description . '</p>
                            </div>
                            <spna class="angle">'.file_get_contents( get_stylesheet_directory_uri() . '/image/angle.svg' ).'</span>
                        </div>
                    </li>';
                }
                echo '<div class="lens-description">
                <p>'.file_get_contents( get_stylesheet_directory_uri() . '/image/calling.svg' ).'Not sure what to select?<span>Call +1 123(456)7890</span></p>
                </div>
                <div class="subtotal-of-product">
                <h3>Subtotal (Frame):</h3>';
                if ($product) {
                  $product_price = $product->get_price();
                  echo wc_price($product_price);
                }
                do_shortcode("[custom_cart_button]");
                // $popuplog = do_shortcode("[xoo_el_action display='button' text='I Agree' redirect_to='http://localhost/lowrance/cart' change_to_text='I Agree' change_to='$addtocart_url']");
                // echo $popuplog;
                echo '</div>';
            }
            echo '</ul>';

                echo '<ul class="sub-lenses">';
                
                echo '<input type="hidden" id="lr_lens_type" name="lr_lens_type" value=""/>';
                echo '<input type="hidden" id="lr_lens_id" name="lr_lens_id" value=""/>';
                echo '<input type="hidden" id="lr_lens_price" name="lr_lens_price" value=""/>';
                echo '<input type="hidden" id="lr_product_price" name="lr_product_price" value=""/>';

                echo '<ul class="lr_sub_lenses" id="lr_sub_lenses" style="padding: 0; margin-inline: 0;">';
                echo '</ul>';

                // <form action="#" method="post" id="eyewear-prescription-form">
                echo '<div class="prescription-form">
                    <h4>My Eye Power Prescription</h4>
                    <table>
                    <thead>
                      <th></th>
                      <th>Sphere(Sph)</th>
                      <th>Cylinder(Cyl)</th>
                      <th>Axis(Axi)</th>
                    </thead>
                    <tr>
                      <td>OS (LEFT EYE)*</td>
                      <td><input name="lrl_sph" id="lrl_sph" type="number" min="-10" max="10" step="0.25" value="0" name="sph-left"></td>
                      <td><input name="lrl_cyl" id="lrl_cyl" type="number" min="-6" max="6" step="0.25" value="0" name="cyl-left"></td>
                      <td><input name="lrl_axis" id="lrl_axis" type="number" min="0" max="180" step="1" value="0" name="axis-left"></td>
                    </tr>
                    <tr>
                      <td>OD (RIGHT EYE)*</td>
                        <td><input name="lrr_sph" id="lrr_sph" type="number" min="-10" max="10" step="0.25" value="0" name="sph-right"></td>
                        <td><input name="lrr_cyl" id="lrr_cyl" type="number" min="-6" max="6" step="0.25" value="0" name="cyl-right"></td>
                        <td><input name="lrr_axis" id="lrr_axis" type="number" min="0" max="180" step="1" value="0" name="axis-right"></td>
                    </tr>
                  </table>
                </div>
                <div class="prescription-form progresiive">
                    <label><input type="checkbox"> Do you have a Bifocal Power?</label>
                    <table>
                    <thead>
                      <th></th>
                      <th>ADDITIONAL POWER</th>
                      <th>PUPILLARY DISTANCE</th>
                    </thead>
                    <tr>
                      <td>OD (RIGHT EYE)*</td>
                      <td><input type="number" min="-10" max="10" step="0.25" value="0" name="ap-left"></td>
                      <td><input type="number" min="-6" max="6" step="0.25" value="0" name="ap-left"></td>
                    </tr>
                    <tr>
                      <td>OS (LEFT EYE)*</td>
                      <td><input type="number" min="-10" max="10" step="0.25" value="0" name="pd-right"></td>
                      <td><input type="number" min="-6" max="6" step="0.25" value="0" name="pd-right"></td>
                    </tr>
                  </table>
                </div>
                <div class="lens-description">
                <ul>
                    <li>Power can be submitted within 10 days of order placement </li>
                    <li>No additional charges for high/complex power</li>
                </ul>
                <label><input name="lr_accept" id="lr_accept" type="checkbox" class="submit-prescription">  I have read and understood the terms of power submission.</label>
                </div>';
                // </form>
                echo '<div id="form-data-container"></div>
                <div class="subtotal-of-product">
                <h3>Subtotal (Frame + Lens):</h3>';
                if ($product) {
                  $product_price = $product->get_price();
                  echo wc_price($product_price);
                }
                do_shortcode("[custom_cart_button]");
                // $popuplog = do_shortcode("[xoo_el_action display='button' text='I Agree' redirect_to='http://localhost/lowrance/cart' change_to_text='I Agree' change_to='$addtocart_url']");
                // echo $popuplog;
                echo '</div></ul>';

          echo '</div></div>';
        
}
// add_action('woocommerce_before_add_to_cart_button', 'select_lens_type', 9999);
add_action('woocommerce_single_product_summary', 'select_lens_type', 9999);

// add_filter( 'woocommerce_add_to_cart_validation', 'bkap_get_validate_add_cart_item' );






































