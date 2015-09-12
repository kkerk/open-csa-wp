/* **********************
 * ** --- PRODUCTS --- **
 * ********************** 
 */

function slow_hideshow_addNewProductForm() {
	var $j = jQuery.noConflict();
	if (document.getElementById("csa_wp_addProduct_ack").style.display != "none")
		document.getElementById("csa_wp_addProduct_ack").style.display = "none";
	
	if (document.getElementById("csa_wp_addProduct_div").style.display == "none")
		document.getElementById("csa_wp_addProduct_formHeader_text").innerHTML = "<font size='4'> New Product Form (hide) </font>";
	else document.getElementById("csa_wp_addProduct_formHeader_text").innerHTML = "<font size='4'> New Product Form (show) </font>";

	$j("#csa_wp_addProduct_div").slideToggle("slow");
}

function slow_hideshow_showProductsList() {
	var $j = jQuery.noConflict();

	if (document.getElementById("csa_wp_showProductsList_div").style.display == "none")
		document.getElementById("csa_wp_showProductsList_header_text").innerHTML = "<font size='4'> Products List (hide) </font>";
	else document.getElementById("csa_wp_showProductsList_header_text").innerHTML = "<font size='4'> Products List (show) </font>";

	$j("#csa_wp_showProductsList_div").slideToggle("slow");

}

// Send an "Insert New Product Request" to server, wait for response, and show result.
function CsaWpPluginSendRequestAddProductToServer()  {

	var $j = jQuery.noConflict();
	var type_field = document.getElementById("type_field").value;
	var variety_field = document.getElementById("variety_field").value;
	var category_field = document.getElementById("category_field").value;
	var price_field = document.getElementById("price_field").value;
	price_field = price_field.replace(',' , '.'); //replace comma entries with dot
	var selection = document.getElementById("unit_field");
	var unit_field = selection.options[selection.selectedIndex].text;
	var producer_field = document.getElementById("producer_field").value;
	selection = document.getElementById("available_field");
	var details_field = document.getElementById("details_field").value;
	var available_field = selection.options[selection.selectedIndex].value;
	
	var data = {
		'action': 'csa_wp_plugin_add_new_product',
		'type': type_field,
		'variety': variety_field,
		'category': category_field,
		'price': price_field,
		'unit': unit_field,
		'producer': producer_field,
		'details': details_field,
		'available': available_field
	  }
	
	$j.post(ajaxurl, data ,
		function(data,status){
			$j("#csa_wp_addProduct_div").hide("slow");
			
			document.getElementById("type_field").value = "";
			document.getElementById("variety_field").value = "";
			document.getElementById("category_field").value = "";
			document.getElementById("price_field").value = "";
			document.getElementById("unit_field").options[0].selected = true;
			document.getElementById("producer_field").value = "";
			document.getElementById("details_field").value = "";
			document.getElementById("available_field").options[0].selected = true;		

			document.getElementById("csa_wp_addProduct_ack").style.display = "block";
			document.getElementById("csa_wp_addProduct_formHeader_text").innerHTML = "<font size='4'>New Product Form (show)</font>";
		});
}

function CsaWpPluginToggleProductVisibility(productID, pluginsDir) {
	var image = document.getElementById("eye"+productID);
	var row = document.getElementById(productID);
	var availability;
	
	//toggle row color and image source
	if (row.style.color=='gray') {
		row.style.color='black';
		image.src = pluginsDir + "/csa-wp-plugin/icons/visible.png";
		availability = "true";
	} else {
		row.style.color='gray';
		image.src = pluginsDir + "/csa-wp-plugin/icons/nonVisible.png";
		availability = "false";
	}

	//update database
	var data = {
		"action" : "csa_wp_plugin_update_product_availability",
		"productID" : productID,
		"availability" : availability
	};
	
	var $j = jQuery.noConflict();		
	$j.post(ajaxurl, data, 
		function(response) { 
			//console.log ("Server returned:["+response+"]");
		}
	);
}


/* ********************
 * ** --- ORDERS --- **
 * ******************** 
 */
 
function slow_hideshow_submitOrderForm() {
	var $j = jQuery.noConflict();

	if (document.getElementById("csa_wp_submitOrder_div").style.display == "none")
		document.getElementById("csa_wp_submitOrder_formHeader_text").innerHTML = "<font size='4'> Submit New Order Form (hide) </font>";
	else document.getElementById("csa_wp_submitOrder_formHeader_text").innerHTML = "<font size='4'> Submit New Order Form (show) </font>";

	$j("#csa_wp_submitOrder_div").slideToggle("slow");

}

function calcNewOrderCost() {
	var $j = jQuery.noConflict();
	var serializedFormData = $j('#csa_wp_plugin_sumbitOrder_form').serializeArray();
	var totalCost = 0;
	var price, quantity;

	$j.each(serializedFormData, function(i, field){
		if (field.name == "csa_wp_plugin_order_productPrice") price = field.value;
		if (field.name == "csa_wp_plugin_order_productQuantity") totalCost += field.value * price;
	});

	document.getElementById('totalCalc').innerHTML = 'Σύνολο: <span style=font-weight:bold;color:green>' + totalCost.toFixed(2) + ' €</span>';
}

function CsaWpPluginSendRequestSumbitOrderToServer(user_login) {

	console.log ("Initializing submit");

	var $j = jQuery.noConflict();
	var serializedFormData = $j('#csa_wp_plugin_sumbitOrder_form').serializeArray();
		
	console.log("Length of data:" + serializedFormData.length);
	
    $j.each(serializedFormData, function(i, field){
        console.log(field.name + ":" + field.value + " ");
    });
	
	console.log ("data to sent: [" + serializedFormData+ "]");
	
	var data = {
		'action': 'csa_wp_plugin_add_new_order',
		'user_login': user_login,
		'data': serializedFormData
	  }
	
	console.log ("submitting to server");
	
	$j.post(ajaxurl, data ,
		function(response){
			console.log("Server returned: [" + response + "]");
		});


}

/* **************************
 * ** --- OLD - UNUSED --- **
 * **************************
 */
/*

function hideshow (which){
	if (!document.getElementById) return;
	if (which.style.display=="block") which.style.display="none";
	else which.style.display="block";
}

// Prevent a form page to autorefresh after submission  -->
var $j = jQuery.noConflict();
$j(document).ready(function() {
  $j("#form_id").on('submit', function(e) {
	e.preventDefault(); // prevent default form submit
  });
});
*/
