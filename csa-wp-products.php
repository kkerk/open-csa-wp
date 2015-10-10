<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function csa_wp_plugin_show_new_product_form($product_id, $display, $page_url) { 
	
	wp_enqueue_script( 'csa-wp-plugin-enqueue-csa-scripts' );
	wp_enqueue_script( 'csa-wp-plugin-products-scripts' );
	
	global $days_of_week,$wpdb;
	$product_info;
	if ($product_id != null) {
		$product_info = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CSA_WP_PLUGIN_TABLE_PRODUCTS." WHERE id=%d", $product_id));
	}
?>

	<br/>
	<div id="csa-wp-plugin-addProduct_formHeader">
		<span 
			id="csa-wp-plugin-addProduct_formHeader_text" 
			<?php 
				if ($product_id == null) {
					echo 'style="cursor:pointer"';
					echo 'onclick="csa_wp_plugin_toggle_form(\'addProduct\',\'Add New Product\', \' form\')"';
				}
			?>>
			<font size='4'>
			<?php 
			if ($product_id == null) {
				if ($display == false) echo 'Add New Product (show form)';
				else echo 'Add New Product (hide form)';
			} else {
				echo 'Edit Product #'.$product_id;
			}
			?>

			</font>
		</span>
	</div>
	<div id="csa-wp-plugin-addProduct_div" 
		<?php 
			if ($display == false) {
				echo 'style="display:none"';
			}
		?>	
	>
		<form method="POST" id='csa-wp-plugin-showNewProduct_form'>
			<table class="form-table">
				<tr valign="top">
					<td>
					<input 
						type='text' 
						<?php 
							if ($product_id != null && $product_info[0]->name != "" && $product_info[0]->name != null) {
								echo "value='".$product_info[0]->name."'"; 
							}
						?>
						placeholder='Product Name *' 
						name="csa-wp-plugin-product_name_input" 
						required></td></tr>
				<tr valign="top"><td>
					<select 
						name="csa-wp-plugin-product_category_input" 
						id="csa-wp-plugin-newProductForm_category_input_id"
						<?php 
							if ($product_id == null) {
								echo "style='color:#999'";
							}		
						?>
						onfocus = '
							getElementById("csa-wp-plugin-newProductForm_category_input_span_id").style.display = "none";
						'
						onchange = '
							this.style.color="black"
							if (this.options[this.selectedIndex].text.split(" ")[0] != "Category") {
								this.options[this.selectedIndex].text = "Category is " + this.options[this.selectedIndex].text;
							}
						'
					>
					<option 
						value="" 
						selected='selected' 
						disabled='disabled'
						id = "csa-wp-plugin-newProductForm_category_input_disabled_id"
					>Category *</option>
 					<?php echo csa_wp_plugin_select_options_from_db(
									array("name"), 
									"id", 
									CSA_WP_PLUGIN_TABLE_PRODUCT_CATEGORIES, 
									($product_id != null)?$product_info[0]->category:null,
									"Category is "
								); ?>
                  	</select>
					<span id="csa-wp-plugin-newProductForm_category_input_span_id"></span>
				</td></tr>
					
				<tr valign="top"><td>
					<select 
						name="csa-wp-plugin-product_producer_input"
						id="csa-wp-plugin-newProductForm_producer_input_id"
						onfocus = '
							getElementById("csa-wp-plugin-newProductForm_producer_input_span_id").style.display = "none";
						'					
						onchange = '
							this.style.color="black"
							if (this.options[this.selectedIndex].text.split(" ")[0] != "Producer") {
								this.options[this.selectedIndex].text = "Producer is " + this.options[this.selectedIndex].text;
							}
						'
						<?php 
							if ($product_id == null) { 
								echo "style='color:#999'";
							}
						?>
					>
						<option 
							value="" 
							<?php 
								if ($product_id == null) 
									echo "selected='selected'";
							?>
							disabled='disabled'
							id = "csa-wp-plugin-newProductForm_producer_input_disabled_id"
						>Producer *</option>
						<?php echo csa_wp_plugin_select_users_of_type("producer", ($product_id!=null)?$product_info[0]->producer:null, "Producer is "); ?>
					</select>
					<span id="csa-wp-plugin-newProductForm_producer_input_span_id"></span>
				</td></tr>

				<tr valign="top">
					<td>
					<input 
						type='text' 
						onfocus = ' 
							getElementById ("csa-wp-plugin-showNewProduct_button_id").disabled=true;
							if (this.value != "") {
								this.value = (this.value.split(" ").slice(2)).join(" ");
							}
						'
						onblur = '
							getElementById ("csa-wp-plugin-showNewProduct_button_id").disabled=false;
							if (this.value != "") {
								this.value = "Variety is "+ this.value;
							}
						'
						<?php 
							if ($product_id != null && $product_info[0]->variety != "" && $product_info[0]->variety != null) {
								echo "value='Variety is ".$product_info[0]->variety."'"; 
							}
						?>
						placeholder='Variety *' 
						required 
						name="csa-wp-plugin-product_variety_input">
					</td>
				</tr>
				<tr valign="top">
					<td>
					<input 
						min='0' step='0.1'
						<?php 
							if ($product_id != null && $product_info[0]->current_price_in_euro != "" && $product_info[0]->current_price_in_euro != null) {
								echo "type='text'";
								echo "style='width:8em; text-align:right'";
								echo 'value = "it costs '. $product_info[0]->current_price_in_euro. '"';
							} else {
								echo "type='number'";
								echo "style='width:8em'";
							}
						?>
						placeholder='Price *' 
						onfocus = '
							getElementById ("csa-wp-plugin-showNewProduct_button_id").disabled=true;
							this.value = this.value.split(" ")[2];
							this.type = "number";
						'
						onblur = '
							getElementById ("csa-wp-plugin-showNewProduct_button_id").disabled=false;
							this.type = "text";
							if (this.value == "") {
								this.style.textAlign="left";
							} else {
								this.value = "It costs " + this.value;
								this.style.textAlign="right";
							}
						'
						name="csa-wp-plugin-product_price_input" required> € &nbsp;
					<select 
						name="csa-wp-plugin-product_unit_input" 
						id="csa-wp-plugin-newProductForm_unit_input_id"
						<?php 
							if ($product_id == null) {
								echo "style='color:#999'";
							}
						?>
						onfocus = '
							getElementById("csa-wp-plugin-newProductForm_unit_input_span_id").style.display = "none";
						'
						onchange = '
							this.style.color="black";
							if (this.options[this.selectedIndex].text.split(" ")[0] != "per") {
								this.options[this.selectedIndex].text = "per " + this.options[this.selectedIndex].text;
							}
					'>
						<option 
							value="" 
							<?php 
								if ($product_id == null) {
									echo "selected='selected'"; 
								}
							?>
							disabled='disabled'
							id = "csa-wp-plugin-newProductForm_unit_input_disabled_id"
						>per... *</option>
						<?php echo csa_wp_plugin_select_measurement_unit($product_id, $product_info); ?>
					</select> 
					<span id="csa-wp-plugin-newProductForm_unit_input_span_id"></span>
				</td></tr>
				<tr valign="top">
					<td>
						<textarea placeholder='Description' rows="3" cols="30" name="csa-wp-plugin-product_descritpion_input"
						><?php 
							if ($product_id != null && $product_info[0]->description != "" && $product_info[0]->description != null) {
								echo $product_info[0]->description; 
							}
						?></textarea></td></tr>


				<tr valign="top"><td>
					<select 
					name="csa-wp-plugin-product_availability_input" 
					id="csa-wp-plugin-newProductForm_availability_input_id"
					<?php 
						if ($product_id == null) {
							echo "style='color:#999'";
						} else if ($product_info[0]->is_available == 1) {
							echo "style='color:green'";
						} else {
							echo "style='color:brown'";
						}
					?>
					onfocus = '
							getElementById("csa-wp-plugin-newProductForm_availability_input_span_id").style.display = "none";
						'
					onchange='
						if (this.options[this.selectedIndex].value == "yes") {
							this.style.color = "green";
							this.options[this.selectedIndex].text = "Currently, it is available"
						} else {
							this.style.color = "brown";
							this.options[this.selectedIndex].text = "Currently, it not is available"
						}
						'
				>
					<option 
						value="" 
						<?php 
							if ($product_id == null) {
								echo "selected='selected'"; 
							}
						?>
						disabled='disabled'
						id = "csa-wp-plugin-newProductForm_availability_input_disabled_id"
					>Available? *</option>
					<?php 
						if ($product_id != null) {
							echo '
								<option value="yes" style="color:green". '. ($product_info[0]->is_available == 1?"selected='selected'> Currently, it is available":">yes") .' </option>
								<option value="no" style="color:brown"'. ($product_info[0]->is_available == 0?"selected='selected'> Currently, it is not available":">no") .' </option>
							';
						} else {
						?>
							<option value="yes" style="color:green">yes</option>
							<option value="no" style="color:brown">no</option>
						<?php
						}
					?>					
					</select>
					<span id="csa-wp-plugin-newProductForm_availability_input_span_id"></span>
				</td></tr>
			</table> 
		<input 
			type="submit" 
			name="Add Product"  
			class="button button-primary"
			id="csa-wp-plugin-showNewProduct_button_id"
			<?php 
				if ($product_id == null) {
					echo "value='Add Product'";
					echo "onclick='csa_wp_plugin_new_product_fields_validation(this, null, \"$page_url\")'";
				} else { 
					echo "value='Update Product'";
					echo "onclick='csa_wp_plugin_new_product_fields_validation(this, $product_id, \"$page_url\")'";
				}
				
			?>
		/>
		<input 
			type="button"
			class="button button-secondary"
			<?php 
			if ($product_id == null) {
				echo "
				value='Reset Info'
				onclick='csa_wp_plugin_reset_product_form();'";
			}
			else {
				echo "
				value='Cancel'
				onclick='window.location.replace(\"$page_url\")'
				'";
			}
			?>
		/>
		
		</form>
		<br/><br/>
	</div>
	
<?php

}

function csa_wp_plugin_select_measurement_unit($product_id, $product_info) {
?>
	<option 
		value='kilogram'
		<?php
			if ($product_id != null && $product_info[0]->measurement_unit == "kilogram" ) {
				echo "selected='selected' >per kilogram"; 
			} else {
				echo ">kilogram";
			}
		?>
	</option>
	<option 
		value='piece'
		<?php 
			if ($product_id != null && $product_info[0]->measurement_unit == "piece" ) {
				echo "selected='selected' >per piece"; 
			} else {
				echo ">piece";
			}
		?>
	</option>
	<option 
		value='bunch'
		<?php 
			if ($product_id != null && $product_info[0]->measurement_unit == "bunch" ) {
				echo "selected='selected' >per bunch"; 
			} else {
				echo ">bunch";
			}
		?>
	</option>
	<option 
		value='litre'
		<?php 
			if ($product_id != null && $product_info[0]->measurement_unit == "litre" ) {
				echo "selected='selected' >per litre"; 
			} else {
				echo ">litre";
			}
		?>
	</option>
<?php
}


add_action( 'wp_ajax_csa-wp-plugin-product_add_or_update_request', 'csa_wp_plugin_add_or_update_product' );

function csa_wp_plugin_add_or_update_product() {

	if( isset($_POST['data']) && isset($_POST['product_id'])) {

		$data_received = json_decode(stripslashes($_POST['data']),true);
		
		$variety_message = "Variety is ";
		$variety = substr($data_received[3]['value'], strlen($variety_message)); 
		$price_message = "it costs ";
		$price = substr($data_received[4]['value'], strlen($price_message)); 
		
		$data_vals = array(
					'name' 						=> $data_received[0]['value'],
					'category' 					=> $data_received[1]['value'],
					'producer' 					=> $data_received[2]['value'],
					'variety'					=> $variety,
					'current_price_in_euro'		=> $price,
					'measurement_unit'	 		=> $data_received[5]['value'],
					'description'				=> $data_received[6]['value'],
					'is_available' 				=> $data_received[7]['value'] == "yes"?1:0
				);
		$data_types = array ("%s", "%d", "%d", "%s", "%f", "%s", "%s", "%d");
		
		global $wpdb;
	
		$product_id = intval(csa_wp_plugin_clean_input($_POST['product_id']));
	
		if ($product_id != null) {
			$product_id = intval($product_id);
			
			//update product (query)
			if(	$wpdb->update(
				CSA_WP_PLUGIN_TABLE_PRODUCTS, 
				$data_vals, 
				array('id' => $product_id), 
				$data_types
			) === FALSE) {
				echo 'error, sql request failed.';
			} else {
				echo 'Success, product is updated.';
			}
		
		}
		else { 
			//insert product (query)
			if(	$wpdb->insert(
				CSA_WP_PLUGIN_TABLE_PRODUCTS, 
				$data_vals, 
				$data_types
			) === FALSE) {
				echo 'error, sql request failed.';
			} else {
				echo 'Success, product is added.';
			}
		}
	}
	else echo 'error,Bad request.';
	
	wp_die(); 	// this is required to terminate immediately and return a proper response

}

function csa_wp_plugin_show_products($display, $page_url) {
	wp_enqueue_script('csa-wp-plugin-enqueue-csa-scripts');
	wp_enqueue_script('csa-wp-plugin-products-scripts');
	wp_enqueue_script('jquery.datatables');
	wp_enqueue_script('jquery.jeditable'); 
	wp_enqueue_script('jquery.blockui'); 	
?>
		
	<br />
	<div id="csa-wp-plugin-showProductsList_header">
		<span 
			style="cursor:pointer" 
			id="csa-wp-plugin-showProductsList_formHeader_text" 
			onclick="csa_wp_plugin_toggle_form('showProductsList','Product List', '')">
			<font size='4'>
			<?php 
				if ($display == false) {
					echo 'Product List (show)';
				} else {
					echo 'Product List (hide)';
				}
			?>
			</font>
		</span>
	</div>
	<div id="csa-wp-plugin-showProductsList_div" 
		<?php 
			if ($display == false) {
				echo 'style="display:none"';
			}
		?>	
	>
		
		<span class='csa-wp-plugin-tip_products' title='
			If you want to update one among the name, variety, and description fields, click on it, write the new value, and then press ENTER.
			| To change the availilability of a product, you can either click on its field or click the "eye" icon.
			| If you want to edit some of the other product details, click on the "pen" icon.
			| If you want to delete some product, click on the "x" icon.
			'>
		<p style="color:green;font-style:italic; font-size:13px">
			by pointing here you can read additional information...</p></span>


		<table 
			class='table-bordered' 
			id="csa-wp-plugin-showProductsList_table" 
			style='border-spacing:1em'
			csa-wp-plugin-plugins_dir='<?php echo plugins_url(); ?>' 
		> 
		<thead class='tableHeader'>
			<tr>
				<th>Name</th>
				<th>Category</th>
				<th>Variety</th>
				<th>Price(€)</th>
				<th>Per...</th>
				<th>Producer</th>
				<th>Description</th>
				<th>Available?</th>
				<th/>
				<th/>
				<th/>
			</tr>
		</thead> 
		<tbody> <?php
			global $wpdb;
			$plugins_dir = plugins_url();
			
			$product_categories_map = $wpdb->get_results("SELECT id,name FROM ".CSA_WP_PLUGIN_TABLE_PRODUCT_CATEGORIES, OBJECT_K);
			$producers_map = csa_wp_plugin_producers_map_array();


			$products = $wpdb->get_results("SELECT * FROM ". CSA_WP_PLUGIN_TABLE_PRODUCTS);
			foreach($products as $row) 
			{
				$product_id = $row->id;				
				$category = $product_categories_map[$row->category]->name;
				$producer_id = $wpdb->get_var($wpdb->prepare("SELECT producer FROM ". CSA_WP_PLUGIN_TABLE_PRODUCTS ." WHERE id=%d", $product_id));
				$producer = $producers_map[$producer_id];
				
				echo "
					<tr 
						valign='top' 
						id='csa-wp-plugin-showProductsProductID_$product_id'  
						class='csa-wp-plugin-showProducts-product'
						style='text-align:center;color:". (($row->is_available == '1')?"black":"gray") ."'
					>
					<td class='editable'>$row->name </td>
					<td>$category </td>
					<td class='editable'>$row->variety</td>
					<td>$row->current_price_in_euro</td>
					<td>$row->measurement_unit</td>
					<td	>$producer</td>
					<td class='editable'>$row->description</td>
					<td 
						class='editable_boolean'
						id = 'csa-wp-plugin-showProductsAvailabilityID_$product_id'
					>".(($row->is_available == 1)?"yes":"no")."</td>
					<td style='text-align:center'><img 
							style='cursor:pointer' 
							src='".plugins_url()."/csa-wp-plugin/icons/".(($row->is_available == 1)?"visible":"nonVisible").".png' 
							height='24' width='24' 
							id = 'csa-wp-plugin-showProductsAvailabilityIconID_$product_id'
							title='mark it as ".(($row->is_available == 1)?"unavailable":"available")."'
							onclick='csa_wp_plugin_request_toggle_product_visibility(this,\"$plugins_dir\")'></td>
					<td style='text-align:center'> 
						<img 
							width='24' height='24'  
							class='delete no-underline' 
							src='$plugins_dir/csa-wp-plugin/icons/edit.png' 
							style='cursor:pointer;padding-left:10px;' 
							onclick='csa_wp_plugin_edit_product(this, \"$page_url\")' 
							title='click to edit this product'/></td>
					<td style='text-align:center'> <img 
						style='cursor:pointer' 
						src='".plugins_url()."/csa-wp-plugin/icons/delete.png' 
						height='24' width='24'
						onmouseover='csa_wp_plugin_hover_icon(this, \"delete\", \"$plugins_dir\")' 
						onmouseout='csa_wp_plugin_unhover_icon(this, \"delete\", \"$plugins_dir\")' 						
						onclick='csa_wp_plugin_request_delete_product(this)' 
						title='delete product'></td>
					</tr>

				";
						
			}
			?>
		</tbody> </table>
	</div>	
<?php
}

add_action( 'wp_ajax_csa-wp-plugin-update_product', 'csa_wp_plugin_update_product' );

function csa_wp_plugin_update_product() {
	if(isset($_POST['value']) && isset($_POST['column']) && isset($_POST['product_id'])) {
		//$old_value = csa_wp_plugin_clean_input($_POST['old_val']);
		$new_value = csa_wp_plugin_clean_input($_POST['value']);
		$column_num = intval(csa_wp_plugin_clean_input($_POST['column']))+1; //not valid for getting the right column, when html table structure differs from the relative db table
		$product_id = intval(csa_wp_plugin_clean_input($_POST['product_id']));
		if ($column_num == 8) {
			$new_value = ($new_value == "yes"?1:0);
		}
		
		if(!empty($column_num) && !empty($product_id)) {
			// Updating the information 
			global $wpdb;
			//get csa_product's column names and assign them to an array
			$columns = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".CSA_WP_PLUGIN_TABLE_PRODUCTS."' ORDER BY ORDINAL_POSITION", ARRAY_N);
			//update the database, using the relative column name
			$column_name = $columns[$column_num][0];

			if(	$wpdb->update(
				CSA_WP_PLUGIN_TABLE_PRODUCTS,
				array($column_name => $new_value), 
				array('id' => $product_id )
			) === FALSE) {
				echo 'error, sql request failed.';											
			} else {
				echo 'success,'.$new_value;
			}
		} else {
			echo 'error,Empty values.';
		}
	} else {
		echo 'error,Bad request.';
	}
	
	wp_die(); 	// this is required to terminate immediately and return a proper response

}

add_action( 'wp_ajax_csa-wp-plugin-update_product_availability', 'csa_wp_plugin_update_product_availability' );

function csa_wp_plugin_update_product_availability() {
	if(isset($_POST['product_id']) && isset($_POST['availability'])) {
		$product_id = intval($_POST['product_id']);
		$availability = $_POST['availability'];

		global $wpdb;		
		if(	$wpdb->update(
			CSA_WP_PLUGIN_TABLE_PRODUCTS,
			array("is_available" => $availability), 
			array('id' => $product_id)
		) === FALSE) {
			echo 'error, sql request failed';												
		} else {
			echo 'success, Availability has been updated.';
		}
	} else {
		echo 'error,Invalid request made.';
	}
	
	wp_die(); 	// this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_csa-wp-plugin-delete_product', 'csa_wp_plugin_delete_product' );

function csa_wp_plugin_delete_product() {
	if(isset($_POST['product_id'])) {
		$product_id = intval(csa_wp_plugin_clean_input($_POST['product_id']));
		if(!empty($product_id)) {
			// Updating the information 
			global $wpdb;

			$product_is_used = $wpdb->get_var($wpdb->prepare("
									SELECT COUNT(product_id)
									FROM ".CSA_WP_PLUGIN_TABLE_PRODUCT_ORDERS." 
									WHERE product_id=%d", $product_id));
			if ($product_is_used > 0) {
				echo 'skipped, used in orders';
			} else {			
				if(	$wpdb->delete(
					CSA_WP_PLUGIN_TABLE_PRODUCTS,
					array('id' => $product_id ),
					array ('%d')
				) === FALSE) {
					echo 'error, sql request failed.';												
				} else {
					echo 'success';
				}
			}
		} else {
			echo 'error,Empty values.';
		}
	} else {
		echo 'error,Bad request.';
	}
	
	wp_die(); 	// this is required to terminate immediately and return a proper response

}

function  csa_wp_plugin_delivery_products_exist (){
	global $wpdb;
	if ($wpdb->get_var("SELECT COUNT(id) FROM " .CSA_WP_PLUGIN_TABLE_PRODUCTS. " WHERE is_available = 1") == 0) {
		echo "
			<h3 style='color:brown'>sorry... no available products found... be patient, soon they will have grown enough... !</h3> 
		";
		return false;
	} else {
		return true;	
	}
}