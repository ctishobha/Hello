<?php

add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
        
function woocommerce_ajax_add_to_cart() {
    // error_log( print_r( $_POST, true ) );

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);

    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

        do_action('woocommerce_ajax_added_to_cart', $product_id);

        if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }

        WC_AJAX :: get_refreshed_fragments();
    } else {

        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

        echo wp_send_json($data);
    }

    wp_die();
}



add_action('wp_ajax_lr_get_lenses_by_type', 'lr_get_lenses_by_type');
add_action('wp_ajax_nopriv_lr_get_lenses_by_type', 'lr_get_lenses_by_type');
        
function lr_get_lenses_by_type() {
    // error_log( print_r( $_POST, true ) );

    $product_id   = absint( $_POST['product_id'] );
    $variation_id = absint( $_POST['variation_id'] );
    $lens_type    = absint( $_POST['lens_type'] );

    if ( $variation_id > 0 ) {
        $product = wc_get_product( $variation_id );
        $price   = $product->get_price();
    } else {
        $product = wc_get_product( $product_id );
        $price   = $product->get_price();
    }

    $data        = array();
    $sub_html    = '';
    $lr_taxonomy = 'lr_lens';
    $c_terms     = wp_get_object_terms($product_id, $lr_taxonomy, array( 'parent' => $lens_type, 'orderby' => 'term_id', 'hide_empty' => false ) );
    if ( ! empty( $c_terms ) ) {
        foreach( $c_terms as $child ) {
            $lr_price = get_term_meta( $child->term_id, 'lr_price', true );
            $sub_html .= '<li class="" data-product_price="' . $price . '" data-lens_type="' . $child->parent . '" data-lens_id="' . $child->term_id . '" data-lens_price="' . $lr_price . '">
                <div class="content-wrapper">
                    <div class="lens-title">
                        <h5>' . $child->name . '</h5>
                        <p>' . $child->description . ' ' . wc_price( $lr_price ) . '</p>
                    </div>
                </div>
            </li>';
        }
    }

    $data['html'] = $sub_html;
    wp_send_json_success( $data );

    wp_die();
}


function sh_get_lense_types() {
    $data = array();

    $data['single-vision'] = 'Single Vision';
    $data['zero-power']    = 'Zero Power';
    $data['progressive']   = 'Progressive';
    $data['only-frame']    = 'Only Frame';

    return $data;
}

function sh_get_lenses() {
    $data = array();

    $data['anti-blue-glare'] = 'Anti Blue - Glare';
    $data['frame-lens']      = 'Frame + Lens 80+';
    $data['buy-only-frame']  = 'Buy Only Frame';

    return $data;
}


add_filter( 'woocommerce_add_cart_item', 'lr_add_cart_item', 25, 1 );
function lr_add_cart_item( $cart_item ) {

    error_log( print_r( 'pppppppppppppppppppp', true ) );
    error_log( print_r( $cart_item, true ) );
    if ( isset( $cart_item['tinvwl_formdata'] ) ) {

        if ( isset( $cart_item['variation_id'] ) && absint( $cart_item['variation_id'] ) > 0 ) {
            $product = wc_get_product( $cart_item['variation_id'] );
        } else {
            $product = wc_get_product( $cart_item['product_id'] );
        }

        if ( isset( $cart_item['tinvwl_formdata']['lr_lens_price'] ) ) {
            $product_price = ( $product ) ? $product->get_price() : 0;
            $lens_price    = $cart_item['tinvwl_formdata']['lr_lens_price'];
            $net_cost      = (float) $product_price + (float) $lens_price;
            $cart_item['data']->set_price( $net_cost );
        }
    }

    return $cart_item;
}

add_filter( 'woocommerce_get_cart_item_from_session', 'lr_get_cart_item_from_session', 25, 2 );

function lr_get_cart_item_from_session( $cart_item, $values ) {

    // error_log( print_r( 'ssssssssssssssss', true ) );
    if ( isset( $values['tinvwl_formdata'] ) ) {
        $cart_item = lr_add_cart_item( $cart_item );
    }
    return $cart_item;
}

/**
 * This function adds the lens details of the product when add to
 * cart button is clicked.
 * @hook woocommerce_add_cart_item_data
 */
function lr_add_cart_item_data( $cart_item_meta, $product_id ) {
    global $wpdb;
    $lense_types = sh_get_lense_types();
    $lenses      = sh_get_lenses();

    error_log( 'cccccccccccccccc' );
    error_log( print_r( $_POST, true ) );

    if ( isset( $_POST['lr_lens_price'] ) ) {
        $cart_item_meta['lr_lens_price'] = $lense_types[$_POST['lr_lens_price']];
    }
    if ( isset( $_POST['lr_product_price'] ) ) {
        $cart_item_meta['lr_product_price'] = $lense_types[$_POST['lr_product_price']];
    }

    if ( isset( $_POST['lr_lens_type'] ) ) {
        $cart_item_meta['lr_lens_type'] = $lense_types[$_POST['lr_lens_type']];
    }
    if ( isset( $_POST['lr_lens_id'] ) ) {
        $cart_item_meta['lr_lens_id'] = $lenses[$_POST['lr_lens_id']];
    }

    if ( isset( $_POST['lrl_sph'] ) && '' !== $_POST['lrl_sph'] ) {
        $cart_item_meta['lrl_sph'] = $_POST['lrl_sph'];
    }
    if ( isset( $_POST['lrl_cyl'] ) && '' !== $_POST['lrl_cyl'] ) {
        $cart_item_meta['lrl_cyl'] = $_POST['lrl_cyl'];
    }
    if ( isset( $_POST['lrl_axis'] ) && '' !== $_POST['lrl_axis'] ) {
        $cart_item_meta['lrl_axis'] = $_POST['lrl_axis'];
    }

    if ( isset( $_POST['lrr_sph'] ) && '' !== $_POST['lrr_sph'] ) {
        $cart_item_meta['lrr_sph'] = $_POST['lrr_sph'];
    }
    if ( isset( $_POST['lrr_cyl'] ) && '' !== $_POST['lrr_cyl'] ) {
        $cart_item_meta['lrr_cyl'] = $_POST['lrr_cyl'];
    }
    if ( isset( $_POST['lrr_axis'] ) && '' !== $_POST['lrr_axis'] ) {
        $cart_item_meta['lrr_axis'] = $_POST['lrr_axis'];
    }
    
    if ( isset( $_POST['lr_accept'] ) && '' !== $_POST['lr_accept'] ) {
        $cart_item_meta['lr_accept'] = $_POST['lr_accept'];
    }

    // error_log( print_r( $cart_item_meta, true ) );
    return $cart_item_meta;
}
add_filter( 'woocommerce_add_cart_item_data', 'lr_add_cart_item_data', 25, 2 );


/**
 * This function display the lens details of the product on cart & checkout page
 * @hook woocommerce_get_item_data
 */
function lr_get_item_data( $other_data, $cart_item ) {

    if ( isset( $cart_item['tinvwl_formdata'] ) && $cart_item['tinvwl_formdata']['lr_lens_id'] && '' != $cart_item['tinvwl_formdata']['lr_lens_id'] ) {
        $term = get_term_by('term_id', $cart_item['tinvwl_formdata']['lr_lens_id'], 'lr_lens');
        if ( ! empty( $term ) ) {
            $other_data[] = array(
                'key'   => __( 'Lens ', 'woocommerce' ),
                'value' => wc_clean( $term->name ),
            );
        }
    }
    return $other_data;
}
add_filter( 'woocommerce_get_item_data', 'lr_get_item_data', 25, 2 );


/**
 * Add WooCommerce Product Addon Prices in Order Item Meta
 *
 * @param WC_Order_Item $item WooCommerce Order Item
 * @param string        $cart_item_key Cart Item Key
 * @param array         $values Cart Item Meta Array
 *
 * @hook woocommerce_checkout_create_order_line_item
 */
function lr_add_order_item_meta( $item, $cart_item_key, $values ) {
    // error_log( print_r( $item, true ) );
    // error_log( print_r( $values, true ) );

    // if ( isset( $_POST['lr_lens_type'] ) ) {
    //     $cart_item_meta['lr_lens_type'] = $lense_types[$_POST['lr_lens_type']];
    // }
    // if ( isset( $_POST['lr_lens_id'] ) ) {
    //     $cart_item_meta['lr_lens_id'] = $lenses[$_POST['lr_lens_id']];
    // }

    
    if ( isset( $values['tinvwl_formdata'] ) ) {
        if ( $values['tinvwl_formdata']['lr_lens_id'] && '' != $values['tinvwl_formdata']['lr_lens_id'] ) {
            $term = get_term_by('term_id', $values['tinvwl_formdata']['lr_lens_id'], 'lr_lens');
            if ( ! empty( $term ) ) {
                $lens_lable = __( 'Lens', 'woocommerce-booking' );
                $item->add_meta_data( $lens_lable, sanitize_text_field( $term->name, true ) );
            }
        }
        if ( $values['tinvwl_formdata']['lr_lens_type'] && '' != $values['tinvwl_formdata']['lr_lens_type'] ) {
            $t_term = get_term_by('term_id', $values['tinvwl_formdata']['lr_lens_type'], 'lr_lens');
            if ( ! empty( $t_term ) ) {
                $lens_lable = __( 'Lens Type', 'woocommerce-booking' );
                $item->add_meta_data( $lens_lable, sanitize_text_field( $t_term->name, true ) );
            }
        }
        if ( isset( $values['tinvwl_formdata']['lrl_sph'] ) || isset( $values['tinvwl_formdata']['lrl_cyl'] ) || isset( $values['tinvwl_formdata']['lrl_axis'] ) ) {
            $l_eye   = __( 'Left Eye(SPH, CYL, AXI)', 'woocommerce-booking' );
            $l_value = $values['tinvwl_formdata']['lrl_sph'] . ', ' . $values['tinvwl_formdata']['lrl_cyl'] . ', ' . $values['tinvwl_formdata']['lrl_axis'];
            $item->add_meta_data( $l_eye, $l_value );
        }
        if ( isset( $values['tinvwl_formdata']['lrr_sph'] ) || isset( $values['tinvwl_formdata']['lrr_cyl'] ) || isset( $values['tinvwl_formdata']['lrr_axis'] ) ) {
            $l_eye   = __( 'Right Eye(SPH, CYL, AXI)', 'woocommerce-booking' );
            $l_value = $values['tinvwl_formdata']['lrr_sph'] . ', ' . $values['tinvwl_formdata']['lrr_cyl'] . ', ' . $values['tinvwl_formdata']['lrr_axis'];
            $item->add_meta_data( $l_eye, $l_value );
        }

        $new_meta = $values['tinvwl_formdata'];
        if ( ! empty( $new_meta ) ) {
            unset( $new_meta['action'] );
            foreach( $new_meta as $key => $val ) {
                $item->add_meta_data( '_' . $key, $val, true );
            }
        }
    }

}
add_action( 'woocommerce_checkout_create_order_line_item', 'lr_add_order_item_meta', 10, 3 );

add_filter( 'woocommerce_hidden_order_itemmeta', 'hide_my_item_meta' );
function hide_my_item_meta( $hidden_meta ) {

    $hidden_meta[] = '_lr_accept';
    $hidden_meta[] = '_lr_lens_id';
    $hidden_meta[] = '_lr_lens_price';
    $hidden_meta[] = '_lr_lens_type';
    $hidden_meta[] = '_lr_product_price';
    $hidden_meta[] = '_lrl_axis';
    $hidden_meta[] = '_lrl_cyl';
    $hidden_meta[] = '_lrl_sph';
    $hidden_meta[] = '_lrr_axis';
    $hidden_meta[] = '_lrr_cyl';
    $hidden_meta[] = '_lrr_sph';

    return $hidden_meta;
  }

  function kia_woocommerce_order_item_name( $name, $item ){

    error_log( print_r( $name, true ) );
    error_log( print_r( $item, true ) );
    // $product_id = $item['product_id'];
    // $tax = 'product_cat';

    // $terms = wp_get_post_terms( $product_id, $tax, array( 'fields' => 'names' ) );

    // if( $terms && ! is_wp_error( $terms )) {
    //     $taxonomy = get_taxonomy($tax);
    //     $name .= '<label>' . $taxonomy->label . ': </label>' . implode( ', ', $terms );
    // }

    return $name . ' hhhhhhhhhhhhhh ';
}
// add_filter( 'woocommerce_order_item_name', 'kia_woocommerce_order_item_name', 10, 2 );


// add_filter( 'woocommerce_order_item_get_formatted_meta_data', 'unset_specific_order_item_meta_data', 9999, 2);
function unset_specific_order_item_meta_data($formatted_meta, $item){
    error_log( print_r( 'ooooooooooooooooo', true ) );
    error_log( print_r( $formatted_meta, true ) );
    // error_log( print_r( $item, true ) );
    // Only on emails notifications
    if( is_admin() || is_wc_endpoint_url() )
        return $formatted_meta;

    foreach( $formatted_meta as $key => $meta ){
        if( in_array( $meta->key, array('pa_size', 'pa_color') ) ) {
            unset($formatted_meta[$key]);
        }
    }
    error_log( print_r( 'fffffffffffffffffff', true ) );
    error_log( print_r( $formatted_meta, true ) );
    return $formatted_meta;
}

// add_filter('woocommerce_order_item_display_meta_key', 'filter_wc_order_item_display_meta_key', 20, 3 );
function filter_wc_order_item_display_meta_key( $display_key, $meta, $item ) {

    error_log( print_r( $display_key, true ) );
    error_log( print_r( $meta, true ) );
    error_log( print_r( $item, true ) );
    // Change displayed label for specific order item meta key
    // if( is_admin() && $item->get_type() === 'line_item' && $meta->key === '_articleid' ) {
    //     $display_key = __("Article ID", "woocommerce" );
    // }
    return $display_key;
}

// add_filter( 'woocommerce_order_item_display_meta_value', 'change_order_item_meta_value', 99, 3 );
function change_order_item_meta_value( $value, $meta, $item ) {
    error_log( print_r( $value, true ) );
    error_log( print_r( $meta, true ) );

    // Change displayed value for specific order item meta key
    if( is_admin() && $item->get_type() === 'line_item' && $meta->key === '_supplier' ) {
        $value = __('<a class="button" target="_blank" href="'.$value.'">Order</a>', 'woocommerce' );
    }
    return $value;
}
