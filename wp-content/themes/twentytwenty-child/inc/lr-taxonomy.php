<?php

// Register Custom Taxonomy.
function lr_create_lens_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Lenses', 'Taxonomy General Name', 'twentytwenty' ),
		'singular_name'              => _x( 'Lens', 'Taxonomy Singular Name', 'twentytwenty' ),
		'menu_name'                  => __( 'Lens', 'twentytwenty' ),
		'all_items'                  => __( 'All Items', 'twentytwenty' ),
		'parent_item'                => __( 'Parent Item', 'twentytwenty' ),
		'parent_item_colon'          => __( 'Parent Item:', 'twentytwenty' ),
		'new_item_name'              => __( 'New Item Name', 'twentytwenty' ),
		'add_new_item'               => __( 'Add New Item', 'twentytwenty' ),
		'edit_item'                  => __( 'Edit Item', 'twentytwenty' ),
		'update_item'                => __( 'Update Item', 'twentytwenty' ),
		'view_item'                  => __( 'View Item', 'twentytwenty' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'twentytwenty' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'twentytwenty' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'twentytwenty' ),
		'popular_items'              => __( 'Popular Items', 'twentytwenty' ),
		'search_items'               => __( 'Search Items', 'twentytwenty' ),
		'not_found'                  => __( 'Not Found', 'twentytwenty' ),
		'no_terms'                   => __( 'No items', 'twentytwenty' ),
		'items_list'                 => __( 'Items list', 'twentytwenty' ),
		'items_list_navigation'      => __( 'Items list navigation', 'twentytwenty' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'lr_lens', array( 'product' ), $args );

}
add_action( 'init', 'lr_create_lens_taxonomy', 0 );


/**
 * Adding Meta Field.
 * @return void 
 */
function lr_lens_add_meta( $term ) {
	
	?>
	<div class="form-field">
		<label for="lr_price"><?php _e( 'Price', 'twentytwenty' ); ?></label>
        <input name="lr_price" id="lr_price" type="number" min="0" max="" step="0.0" value="0">
	</div>
	<!-- <div class="form-field">
		<label for="lr_image"><?php _e( 'Image', 'twentytwenty' ); ?></label>
		<input type="text" name="lr_image" id="lr_image" value="">
	</div> -->
<?php
}
add_action( 'lr_lens_add_form_fields', 'lr_lens_add_meta', 10, 2 );


/**
 * Edit Meta Field.
 * @return void 
 */
function lr_lens_edit_meta( $term ) {
	
	// put the term ID into a variable.
	$t_id     = $term->term_id;
	$lr_price = get_term_meta( $t_id, 'lr_price', true );
	// $lr_image = get_term_meta( $t_id, 'lr_image', true );
	?>
	<tr class="form-field">
		<th><label for="lr_price"><?php _e( 'Price', 'twentytwenty' ); ?></label></th>
		<td>	 
			<input type="text" name="lr_price" id="lr_price" value="<?php echo esc_attr( $lr_price ) ? esc_attr( $lr_price ) : ''; ?>">
		</td>
	</tr>
	<!-- <tr class="form-field">
		<th><label for="lr_image"><?php // _e( 'Image', 'twentytwenty' ); ?></label></th>
		<td>	 
			<input type="text" name="lr_image" id="lr_image" value="<?php // echo esc_attr( $lr_image ) ? esc_attr( $lr_image ) : ''; ?>">
		</td>
	</tr> -->
	<?php
}
add_action( 'lr_lens_edit_form_fields', 'lr_lens_edit_meta', 10 );

/**
 * Saving Meta Field.
 */
function lr_lens_save_meta( $term_id ) {

	if ( isset( $_POST['lr_price'] ) ) {
		$lr_price = $_POST['lr_price'];
		if( $lr_price ) {
            update_term_meta( $term_id, 'lr_price', $lr_price );
		}
	}
	// if ( isset( $_POST['lr_image'] ) ) {
	// 	$lr_image = $_POST['lr_image'];
	// 	if( $lr_image ) {
	// 		update_term_meta( $term_id, 'lr_image', $lr_image );
	// 	}
	// }

}  
add_action( 'edited_lr_lens', 'lr_lens_save_meta' );  
add_action( 'create_lr_lens', 'lr_lens_save_meta' );







// add_action( 'add_meta_boxes', 'lr_add_lens_box' );

// function lr_add_lens_box() {

//     add_meta_box(
//         'lr_lens_type',
//         __( 'Lens Details', 'woocommerce' ),
//         'lr_add_lens_box_class',
//         'product',
//         'normal',
//         'core'
//     );
// }
// function lr_get_lense_types( $is_all = false ) {
//     $data = array();

// 	$parent_terms = get_terms( 'lr_lens', array( 'parent' => 0 ) );

// 	if ( ! $is_all ) {
// 		if ( ! empty( $parent_terms ) ) {
// 			foreach( $parent_terms as $key => $value ) {
// 				error_log( print_r( $value, true ) );
// 				$data['single-vision'] = 'Single Vision';
// 				$data['zero-power']    = 'Zero Power';
// 				$data['progressive']   = 'Progressive';
// 				$data['only-frame']    = 'Only Frame';
// 			}
// 		}
// 	}

//     return $parent_terms;
// }

function lr_add_lens_box_class() {
    ?>
    <div class="bkap_date_timeslot_div">
        <div>
            <h4>Select Lens Type :</h4>
        </div>
        <table id="bkap_date_timeslot_table">
            <tbody>
                <tr>
                    <th style="width:20%" id="weekdaydatetime_heading_weekday">Lens Type</th>
                    <th style="width:23%" id="weekdaydatetime_heading_note">Lens Name</th>
                    <th style="width:10%" id="weekdaydatetime_heading_price">Price</th>
                    <th width="4%" id="bkap_remove_all_timeslots" style="text-align: center;cursor: pointer;">
                        <i class="fa fa-lg fa-trash" title="Delete all timeslots" aria-hidden="true"></i>
                    </th>
                </tr>
                <tr id="bkap_default_date_time_row" style="display: none;">
                    <td width="20%" class="bkap_dateday_td">
                        <select id="bkap_dateday_selector" multiple="multiple">
                            <option value="">Select Type</option>
                            <?php
                            $lense_types = lr_get_lense_types();
                            foreach( $lense_types as $key => $value ) {
                                echo '<option value="' . $key . '">' . $value . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                    <td width="23%" class="bkap_note_time_td"><textarea id="bkap_note_time" rows="1" cols="2" style="width:100%;"></textarea></td>
				    <td width="10%" class="bkap_price_time_td"><input id="bkap_price_time" class="wc_input_price" type="text" name="quantity" style="width:100%;" placeholder="Price"></td>
                    <td width="4%" id="bkap_close" style="text-align: center;cursor:pointer;"><i class="fa fa-trash" aria-hidden="true"></i></td>
                </tr>
                <tr id="bkap_date_time_row_2" class="bkap_added">
                    <td width="20%" class="bkap_dateday_td">
                        <select id="bkap_dateday_selector_2" multiple="" tabindex="-1" class="select2-hidden-accessible" aria-hidden="true">
                            <option value="">Select Type</option>
                            <?php
                            $lense_types = lr_get_lense_types();
                            foreach( $lense_types as $key => $value ) {
                                echo '<option value="' . $key . '">' . $value . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                    <td width="23%" class="bkap_note_time_td"><textarea id="bkap_note_time_2" rows="1" cols="2" style="width:100%;"></textarea></td>
                    <td width="10%" class="bkap_price_time_td"><input id="bkap_price_time_2" class="wc_input_price" type="text" name="quantity" style="width:100%;" placeholder="Price"></td>
                    <td width="4%" id="bkap_close_2" style="text-align: center;cursor:pointer;"><i class="fa fa-trash" aria-hidden="true"></i></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
