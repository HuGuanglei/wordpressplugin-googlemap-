<?php
/**
 * @package cusMapPlugin
 */
/*
Plugin Name: cusMap Plugin
Plugin URI: http://cusMap.com/plugin
Description: First plugin
Version 1.0.0
Author: Guanglei
Author URI:http://cusMap.com
Licence: GPLv2 or lator
Text Domain: cusMap-plugin
 */
 

register_activation_hook( __FILE__, 'create_database_table');

function create_database_table(){
	global $wpdb;
    $table_name = $wpdb->prefix . "markers";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      
      $sql = "CREATE TABLE " . $table_name . " (
		`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`name` VARCHAR( 60 ) NOT NULL ,
		`address` VARCHAR( 80 ) NOT NULL ,
		`lat` FLOAT( 10, 6 ) NOT NULL ,
		`lng` FLOAT( 10, 6 ) NOT NULL,
		`contracttype` varchar(64),
		`city` varchar(64),
		`province` varchar(64),
		`description` text,
		`propertytype` varchar(64),
		`price` int(11),
		`lastupdated` datatime
	);";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta( $sql );	
	}
	
}
 

function add_scripts_1() {
  wp_register_script('map_js', plugins_url( 'js/googlemap.js', __FILE__ ));
  wp_enqueue_script('map_js'); 
}
add_action('wp_enqueue_scripts', 'add_scripts_1');


function add_scripts() {
  wp_register_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDvmMESZzCS2tPNQdc5LKXjlb23Mln_lZE');
  wp_enqueue_script('google-maps'); 
}
add_action('wp_enqueue_scripts', 'add_scripts');





function test_123(){
	
	return "
	<div style='margin-left: -400px;margin-top:50px'>
		<div style='float:left'>
			<div style='height:50px;'><h2>PROPERTIES</h2></div>
			<div>
				<div id='resultTotal'>
				</div>
				<div id='searchCondition' style='background:#00CC99; color:#FFF;width:600px;height:50px' >
					<div style='float:left;width:25%;text-align:center; height:50px;padding-top: 15px;'><p>SHOW ON PAGE</p></div>
					<div style='float:left;width:20%;height:30px;padding-top: 10px;'> <input type='text' style='background:#00CC99; color:#FFF;height:30px' id='showSize' value='6'/>
					</div>
					<div style='float:right;width:30%;height:30px;padding-top: 10px; padding-right: 10px;'> 
					<select id='sortBYSelect' style='background:#00CC99; color:#FFF;height:30px;width:300px' id='showSize' label='SortBY'>
					  <option value='0' selected>Newest First</option>
					  <option value='1'>Oldest First</option>
					</select>
					</div>
					<div style='float:right;width:20%;text-align:center; height:50px;padding-top: 15px;'><p>SORT BY</p></div>
				</div>
				<select id='locationSelect' style='width: 400px;margin-top:20px;margin-bottom:20px; visibility: hidden'></select>
			</div>
			<div id='map' style='width:600px;height:600px'>
			</div>
			<script type=“text/javascipt”>initialize_map()</script>
		</div>
		<div style='float:right;'>
			<div style='height:50px;'><h2>SEARCH</h2></div>
			<div>
				<label for='raddressInput'>Search location:</label>
				<input type='text' style='width:300px' id='addressInput' size='15'/>
				<label for='properityTypeInput' style='margin-top:20px'>PROPERITY TYPE</label>
				<select id='properityType' style='width:300px' label='ProperityType'>
				  <option value='Any' selected>Any</option>
				  <option value='Office'>Office</option>
				  <option value='Single Family'>Single Family</option>
				  <option value='Vacant Land'>Vacant Land</option>
				  <option value='Retail'>Retail</option>
				  <option value='Industrial'>Industrial</option>
				</select>
				<label for='DescriptionInput' style='margin-top:20px'>DESCRIPTION</label>
				<input type='text' style='width:300px' id='descriptionInput'/>
				<label for='priceInput' style='margin-top:20px'>PRICE</label>
				<div style='width:300px'>
				<input type='number' min='0' max='1000000000' style='float:left;width:130px' value='100' id='priceInputStart'/>
				<span style='margin-left: 11px;'> __ </span>
				<input type='number' min='0' max='1000000000' style='float:right;width:130px' value='50000000' id='priceInputEnd'/>
				</div>
				<br>
				<label for='contractTypeInput' style='margin-top:20px'>CONTRACT TYPE</label>
				<select id='contractType' style='width:300px' label='ContractType'>
				  <option value='Any' selected>Any</option>
				  <option value='For sale'>For sale</option>
				  <option value='For rent'>For rent</option>
				  <option value='For lease'>For lease</option>
				</select>
				<label for='radiusSelect' style='margin-top:20px'>Radius</label>
				<select id='radiusSelect'  style='width:300px' label='Radius'>
				  <option value='50' selected>50 kms</option>
				  <option value='30'>30 kms</option>
				  <option value='20'>20 kms</option>
				  <option value='10'>10 kms</option>
				</select>
				<div>
					<input type='button' style='padding: 10px,20px;margin-right: 60px;margin-left: 20px;margin-top: 18px;height: 40px;' id='resetButton' value='Reset'/>
					<input type='button' style='background:#00CC99;height: 40px' id='searchButton' value='Search'/>
				</div>
			</div>
		</div>
	 </div>";
}

add_shortcode('map', 'test_map');

function test_map(){

return test_123();

}



?>
