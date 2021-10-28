<?php
/*
Plugin Name: DoliPress
Version: 0.1
Description: Synchronize gravity form with dolibarr third parties.
Author: Luca Scandroglio
Author URI: https://www.lucascandroglio.it
*/

//Plugin Init

function dolipress_init(){

add_action( 'admin_menu', 'createMenu' );
add_action( 'admin_init', 'registerSettings' );
add_action( 'admin_init', 'getTelegramMessages' );
add_action( 'admin_enqueue_scripts', 'register_styles' );
$form_id = get_form_id();
add_action( "gform_after_submission_$form_id",'callAPI', 10, 4 );
}

//Function to generate the menu's item

function register_styles(){
	wp_register_style('bootstrap_admin_theme_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css');
	wp_register_style('bootstrap_admin_theme', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js');
	wp_register_style('fontawesome_js_css', 'https://use.fontawesome.com/releases/v5.15.4/css/all.css');
	wp_register_style('doligravity_style_css', '/wp-content/plugins/DoliPress/css/style.css');
	
	wp_enqueue_style('bootstrap_admin_theme_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css');
	wp_enqueue_style('fontawesome_js', 'https://kit.fontawesome.com/adab434d3e.js');
	wp_enqueue_style('fontawesome_js_css', 'https://use.fontawesome.com/releases/v5.15.4/css/all.css');
	wp_enqueue_style('doligravity_style_css', '/wp-content/plugins/DoliPress/css/style.css');
	wp_enqueue_script('bootstrap_admin_theme', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js');
	wp_enqueue_script('core', '/wp-content/plugins/DoliPress/js/core.js');
	wp_enqueue_script('js', '/wp-content/plugins/DoliPress/js/javascript.js');
}

//Create WP Menu Item

function createMenu() {
	$icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIzLjEuMSwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxpdmVsbG9fMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiCgkgdmlld0JveD0iMCAwIDIwMCAyMDAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDIwMCAyMDA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCS5zdDB7ZmlsbDojOUJBRUI0O30KCS5zdDF7ZmlsbDojNjY2NjY2O30KCS5zdDJ7ZmlsbDojOTBBN0FDO30KPC9zdHlsZT4KPGc+Cgk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMTQyLjEsMTM2Ljg1Yy0wLjI1LDAuNDItMC40MSwwLjMtMC41My0wLjA5QzE0MS43NSwxMzYuNzYsMTQxLjkzLDEzNi43OSwxNDIuMSwxMzYuODUiLz4KCTxwYXRoIGNsYXNzPSJzdDEiIGQ9Ik0xMjkuMTksMzYuMjljMC0wLjAzLDAuMDEtMC4wNiwwLjAyLTAuMDljMS42Ny01Ljc1LDIuMTQtNi4xLDguMTMtNi4xMWM1LjQ3LTAuMDEsMTAuOTMtMC4wMiwxNi40LDAuMDEKCQljMC45OSwwLjAxLDIuMzgtMC40MSwyLjI1LDEuNDVjLTAuMTEsMS41Mi0wLjUyLDIuNTYtMi40MywyLjUyYy0zLjczLTAuMDktNy40NywwLjAxLTExLjIsMC4wNGMtMi0wLjAyLTQuMDEtMC4wOS02LjAxLTAuMDYKCQljLTEuMiwwLjAyLTIuMjcsMC40OC0yLjE5LDEuODhjMC4wOCwxLjQ4LDIuNjQsMS4wNiwzLjQsMS4wN2M0LjU1LDAuMDYsNy44NC0wLjIsMTIuNC0wLjEzYzEuMDEsMC4wMSwyLjY0LTAuNjQsMi41LDEuNDQKCQljLTAuMSwxLjQ0LTAuNDMsMi41LTIuMzgsMi41NGMtNC42NSwwLjEtOS4yOSwwLjI5LTEzLjkyLTAuMDdjLTIuMDYtMC4xNi0zLjIsMC4yMy0zLjM2LDIuNjNjLTAuMSwxLjYxLDAuMDksMy44OS0yLjgyLDMuNzEKCQljLTEuNTgtMC4xLTIuMzUtMC4zNS0xLjk4LTIuMjNDMTI4LjU0LDQyLjA1LDEyOC44MSwzOS4xNiwxMjkuMTksMzYuMjkiLz4KCTxwYXRoIGNsYXNzPSJzdDIiIGQ9Ik0xNDUuMzksMTI5LjgydjAuODdjLTAuMTgtMC4xLTAuNDgtMC4xNy0wLjUyLTAuMzFDMTQ0Ljc3LDEzMC4wMSwxNDUuMDIsMTI5LjgzLDE0NS4zOSwxMjkuODIiLz4KCTxwb2x5bGluZSBjbGFzcz0ic3QwIiBwb2ludHM9IjEwMS41NSw1OSAxMDEuNTUsNTkgMTAyLjU1LDU5IAkiLz4KCTxwYXRoIGNsYXNzPSJzdDEiIGQ9Ik0xNzUuNjgsNzQuNzRjMC4wNS02Ljg3LTEuOTUtMTIuOTgtNi4xMS0xOC4yN2MtMi4xNy0yLjc1LTMuNTUtNS4wMS0yLjk0LTguOTYKCQljMC44NC01LjQzLDAuMjctMTEuMDksMC4xOS0xNi42NWMtMC4wNS00LjA1LTEuODYtNy4xNS01LjQxLTkuMTljLTQuNjUtMi42Ny05LjMyLTUuMzQtMTMuOTctOC4wMwoJCWMtMy43Mi0yLjE1LTcuNDEtMi4xNC0xMS4xMywwLjAyYy00LjA5LDIuMzgtNy41Myw1LjIyLTExLjc2LDcuMzRjLTIsMS00LjAzLDMuMy02LjExLDMuMDJjLTIuMTctMC4zLTQuMTEtMi4xOS02LjIxLTMuMjkKCQljLTkuMzctNC45Ni0xOC45OS01LjI4LTI4LjIzLTAuMTVjLTE2Ljc5LDkuMzMtMzMuNDQsMTguOS00OS45MywyOC43NWMtOC45OSw1LjM3LTEzLjYxLDEzLjc5LTEzLjgyLDI0LjMKCQljLTAuMiw5LjM5LTAuMDQsMTguNzgtMC4wNCwyOC4xOGMwLDkuMzktMC4xNiwxOC43OCwwLjAzLDI4LjE3YzAuMjIsMTAuOCw0LjksMTkuNCwxNC4yMywyNC45MgoJCWMxNi4xMyw5LjU0LDMyLjQyLDE4LjgzLDQ4Ljc1LDI4LjA1YzkuNyw1LjQ3LDE5LjcsNS40NywyOS40LDBjMTYuMzMtOS4yMSwzMi42MS0xOC41MSw0OC43NS0yOC4wNQoJCWM5LjMyLTUuNTEsMTQuMTEtMTQuMTEsMTQuMjItMjQuOTJDMTc1Ljc3LDExMS41NywxNzUuNTYsOTMuMTUsMTc1LjY4LDc0Ljc0eiBNMTQyLjMyLDEwOC43NGMtMS4xLDE0LjIxLTYuOCwyNi4wOC0xOC41NSwzNC42MQoJCWMtNy4zNiw1LjM1LTE1LjcyLDcuNDQtMjQuNjQsNy40NGMtMC43LDAtMS40LTAuMDEtMi4xLTAuMDRjLTUuNDUtMC4yLTUuNDUtMC4xNC01LjQ2LTUuNDljLTAuMDEtNi4zMiwwLjA4LTEyLjY0LTAuMDctMTguOTUKCQljLTAuMDUtMi4xLDAuMzQtMi45NywyLjQ2LTIuOTdjMC4xMywwLDAuMjcsMCwwLjQxLDAuMDFjMC45MiwwLjA0LDEuODMsMC4wNywyLjc0LDAuMDdjMi42MiwwLDUuMjMtMC4yNCw3Ljg2LTEuMTQKCQljNy44Mi0yLjY5LDExLjQtOC4yNiwxMi4wNy0xNi4wOGMwLjI1LTIuOTEsMC4yNS01Ljg2LTAuNTMtOC43M2MtMi4xLTcuNzktNy40MS0xMS45OC0xNi40Ny0xMi43MWMtMS43Ni0wLjE0LTMuNTMtMC4xNy01LjMtMC4xNwoJCWMtMC45OSwwLTEuOTgsMC4wMS0yLjk2LDAuMDFjLTAuOTUsMC0xLjkxLTAuMDEtMi44Ni0wLjA0Yy0wLjA0LDAtMC4wOCwwLTAuMTIsMGMtMS43OCwwLTEuMjMsMS4zMS0xLjIzLDIuMjIKCQljLTAuMDQsMTYuNzItMC4wNSwzMy40NC0wLjA3LDUwLjE1YzAsMy44NS0wLjA3LDcuNzEsMC4wMywxMS41NmMwLjAzLDEuNDItMC40MywxLjgxLTEuNzcsMS44MWgtMC4wOAoJCWMtNC4xNi0wLjA0LTguMzItMC4wOC0xMi40Ny0wLjA4Yy0yLjgsMC01LjYsMC4wMS04LjQsMC4wNWgtMC4wOGMtMS42MiwwLTEuOTUtMC41OS0xLjk0LTIuMTFjMC4wNS0xNC43MiwwLjAzLTI5LjQzLDAuMDMtNDQuMTUKCQloLTAuMDdjMC0xNC41NiwwLjAzLTI5LjEyLTAuMDQtNDMuNjhjLTAuMDEtMS42OSwwLjI5LTIuMzUsMi4xNC0yLjM1aDAuMDljMTEuNDQsMC4xMywyMi44OSwwLjAxLDM0LjMzLDAuMTgKCQljNi42MiwwLjEsMTMuMDQsMS40NywxOS4xMSw0LjJjNi45MSwzLjExLDEyLjIyLDguMDEsMTYuNTQsMTQuMjRDMTQxLjcsODYuNCwxNDMuMjEsOTcuMjksMTQyLjMyLDEwOC43NHogTTE2NC4xNSwzOS4yNAoJCWMwLDIuNjQtMC4wNCw1LjI4LDAuMDEsNy45MmMwLjA2LDMuMjQtMS40LDUuNTctNC4xMyw3LjE2Yy00LjU2LDIuNjYtOS4xMiw1LjMtMTMuNyw3LjkyYy0yLjgsMS41OS01LjY0LDEuODUtOC41NCwwLjE2CgkJYy00Ljc5LTIuOC05LjY1LTUuNDktMTQuNC04LjM1Yy0yLjQtMS40NS0zLjY4LTMuNjUtMy42NS02LjU5YzAuMDYtNS42NCwwLjA1LTExLjI5LDAuMDEtMTYuOTNjLTAuMDItMi44MywxLjIxLTQuOTUsMy41NS02LjMzCgkJYzUuMDItMi45NiwxMC4wOC01LjgzLDE1LjE4LTguNjRjMi4xOS0xLjIxLDQuNjEtMS4yNiw2LjgxLTAuMDVjNS4xNywyLjg2LDEwLjMyLDUuNzcsMTUuMzcsOC44NGMyLjI4LDEuMzksMy41MiwzLjYyLDMuNSw2LjQyCgkJQzE2NC4xMywzMy41OSwxNjQuMTUsMzYuNDEsMTY0LjE1LDM5LjI0eiIvPgo8L2c+Cjwvc3ZnPgo=';
	add_menu_page( 'DoliPress', 'DoliPress', 'manage_options', 'doligravity-settings', 'settingsPage', $icon  );
	}

//Function to generate the settings field

function registerSettings(){

	register_setting( 'doligravity-settings', 'dgform');
    register_setting( 'doligravity-settings', 'dpapikey');
	register_setting( 'doligravity-settings', 'dgurl');
	register_setting( 'doligravity-settings', 'usehttps');
	
	$form = get_form();
	$dbfields = array("Company","name","address","zip","state_id","state_code","state","phone","fax","email","social_networks","url");
	$gffields = $form['fields'];
	$fieldscount =count($gffields);	
	
	for ($y=0; $y<($fieldscount); $y++) {
		$gf_value = get_option('gffield'.$y);
		$db_value = get_option('dbfield'.$y);
		$gf_fields = array ($gffields, $y, 'gf_field');
		register_setting('doligravity-settings',"dbfield$y");
		register_setting('doligravity-settings',"gffield$y");	
		}
}

//Function to generate the settings field

function settingsfields(){	 	
	
	add_option('dpapikey','');
	add_option('dgurl','');
	add_option('dgform','');
	add_option('usehttps','');
	add_settings_section('doligravity_settings','','dg_section_text','doligravity-settings' );
	add_settings_field( 'dpapikey', 'API Key', 'dg_api_key', 'doligravity-settings', 'doligravity_settings' );
	add_settings_field( 'usehttps','Use Localhost/HTTPS','use_https','doligravity-settings','doligravity_settings');
	add_settings_field( 'dgurl', 'URL', 'dg_url', 'doligravity-settings', 'doligravity_settings' );
	add_settings_field( 'dgform', 'Select Form','gf_form', 'doligravity-settings', 'doligravity_settings');
	settings_fields( 'doligravity-settings' );
	do_settings_sections ( 'doligravity-settings' );
	exception_handler();
}

//Manage Exception for empty URL field

function exception_handler(){

	$formvalue = get_option('dgform');
	if ($formvalue ==''){
		echo '<br><div class="alert alert-warning" role="alert">Select a form to proceed.</div>';
	}
	else {
	basic_settings();	
	}
}

//Callback functions to printout saved fields

function dg_api_key(){
	
	echo '<div class="col-12" id="pswfield">
	<input type="password" class="form-control" id="dpapikey" name="dpapikey" aria-describedby="basic-addon3" value="'. esc_attr(get_option( 'dpapikey')) . '"></div>';
	echo "<p>Insert your Dolibarr API Key. It must be 32 carachters [A-z,0-9].Tipically, you can find it here: https://www.youdomain.com/htdocs/user/card.php?id=1&action=edit</p>";
			}

//Echo https:// or http://localhost/

function dg_url(){
	$usehttps = get_option('usehttps');
	if ($usehttps == 1){
		echo '<div class="input-group mb-3" id="dburl">
		<span class="input-group-text" id="urldescription">https://</span>
		<input type="text" class="form-control" id="dgurl" name="dgurl" aria-describedby="basic-addon3" value="' . esc_attr( get_option( 'dgurl', 'Insert Dolibarr URL here' )). '"></div>';
		}
	else {
		echo '<div class="input-group mb-3" id="dburl">
		<span class="input-group-text" id="urldescription">http://localhost/</span>
		<input type="text" class="form-control" id="dgurl" name="dgurl" aria-describedby="basic-addon3" value="' . esc_attr( get_option( 'dgurl', 'Insert Dolibarr URL here' )). '"></div>';			

	}
	echo "<p>In a default configuration it can be 'www.yourdomain.com/htdocs/api/index.php/thirdparties'. Use HTTPS to improve security and GDPR compliance.</p>";
}

//Find Gravity's Forms 

function gf_form(){
	$forms = GFAPI::get_forms();
	$formscount = count($forms);
	$formvalue = get_option('dgform');

	echo '<select class="form-select form-select-md" name="dgform" id="dgform">';

	if ($formvalue != null) {
		echo '<option selected value="'.$formvalue.'">'.$forms[$formvalue]['title']. '</option>' ;
		unset($forms[$formvalue], $forms );
	}

	else {
		echo '<option selected value="">Select a Form</option>';	
	}
	foreach ($forms as $key => $value) {
			echo '<option value="'.$key.'">'.$forms[$key]['title'].'</option>' ;
		}
	echo '</select>';

	if ($formvalue == '') {
		echo '<br><div class="alert alert-warning" role="alert">Select a form to proceed.</div>';
	}
	}

//Echo settings description

function dg_section_text(){
	echo '<p class="p-3">Here you can set all the options for using the API</p>';
		}

//Echo switch button to select HTTPS mode

function use_https(){
	$usehttps = get_option('usehttps');

	if ($usehttps == 1) {
	echo '<div class="form-check form-switch">
	<input class="form-check-input" type="checkbox" name="usehttps" id="usehttps" value="1" checked>
	</div>';
	}
	else {
	echo '<div class="form-check form-switch">
	<input class="form-check-input" type="checkbox" name="usehttps" id="usehttps" value="1">
	</div>';	
	}
}

//Function to get the form ID

function get_form(){
	$forms = GFAPI::get_forms();
	$form = GFAPI::get_form($forms[get_option('dgform')]['id']);
	
	return $form;
}

function get_form_id(){
	$forms = GFAPI::get_forms();
	$form_id = $forms[get_option('dgform')]['id'];
	
	return $form_id;
}

//Get fields number of Gravity's form

function count_Fields(){

	$form = get_form();
	$gffields = $form['fields'];
	$fieldscount = count($gffields);
	
	return $fieldscount;
}

//Function to generate the forms field options

function basic_settings(){

	$form = get_form();
	$dbfields = array("name","name_alias","address","zip","state_id","state_code","state","phone","fax","email","social_networks","url");
	$gffields = $form['fields'];
	if ($gffields != 'null') {
		$fieldscount = count_Fields();
		echo '<h3 class="p-3">Select Pairs</h3><br><table><tbody><tr><th>Gravity Form Field</th><th>Dolibarr Field</th></tr>';
		
			for ($y=0; $y<$fieldscount; $y++) {
				$gf_value = get_option('gffield'.$y);
				$db_value = get_option('dbfield'.$y);
				$gf_fields = array ($gffields, $y, 'gf_field');
				add_option("gffield$y",'','','yes');
				add_option("dbfield$y",'','','yes');
				echo '<tr><td><select class="form-select form-select-lg" name='."gffield$y".' id='."gffield$y>";
				add_settings_field( "gffield$y", "Select pair $y", gf_field($gffields, $gf_value),  'doligravity-settings', 'doligravity_settings');
				echo '</select></td><td><select class="form-select form-select-lg" name='."dbfield$y".' id='."dbfield$y>";
				add_settings_field( "dbfield$y",'', db_field($dbfields, $db_value), 'doligravity-settings', 'doligravity_settings');
				echo '</select></td></tr>';	
			}
		echo '</table></tbody><br>';
	}
}

//Functions to generate the gravity's form options for <select> field row

function gf_field($gffields, $gf_value){

	foreach ($gffields as $key => $gfvalue){
		if ($gffields[$key]['id']==$gf_value){
			echo '<option selected value="' . $gfvalue['id']  . '">' . $gfvalue['label'] . '</option>';
			unset($gffields[$key], $$gffields );
		}
	}

	foreach ( $gffields as $gfvalue){
		echo '<option value="' . $gfvalue['id']  . '">' . $gfvalue['label'] . '</option>';
	}
}

//Functions to generate the Dolibarr form options for <select> field row

function db_field($dbfields, $db_value) {

	foreach ( $dbfields as $key => $dbvalue){
		if ($dbvalue==$db_value){
			echo '<option selected value="' . $dbvalue  . '">' . $dbvalue . '</option>';
			unset($dbfields[$key], $$dbfields );
		}
	}
	foreach ( $dbfields as $dbvalue){
		echo '<option value="' . $dbvalue  . '">' . $dbvalue . '</option>';
	}
}


//Function to call Dolibarr API

function callAPI( $entry, $form, $fieldscount ){
	
	$apikey= get_option('dpapikey');
	$urladdress=  get_option('dgurl');
	$usehttps = get_option('usehttps');
	$values = array('1');
	$labels = array('entity');
	
	if ($usehttps == 1){
		$urlroot = 'https://';
	}
	else {
		$urlroot = 'http://localhost/';
	}
	
	$url = $urlroot . $urladdress;
	
	for ($y=0; $y<count_Fields(); $y++){
		$labels[]= get_option("dbfield$y");
		$field = get_option("gffield$y");
		$values[]= rgar($entry, $field, get_option("gffield$y"));
	};
			
	$dati = array_combine($labels, $values);
	$nome = rgar( $entry, $dati['name'].'.3').' '.rgar( $entry, $dati['name'].'.6');
	$dati['name'] = $nome;
	
	
	if (array_search('Address', $dati) !== 'false'){
      $address['address'] = rgar( $entry, $dati['address'].'.1');
      $address['town'] = rgar( $entry, $dati['address'].'.3');
      $address['state'] = rgar( $entry, $dati['address'].'.4');
      $address['zip'] = rgar( $entry, $dati['address'].'.5');
      $address['country'] = rgar( $entry, $dati['address'].'.6');
      $dati = array_merge($dati, $address);		
	}
	
	/*echo '<pre>'; print_r($dati);echo '</pre>';*/

	$data = json_encode($dati);
	
    $httpheader = ['DOLAPIKEY: '.$apikey];
	$curl = curl_init();
    $method =  "POST";
    $httpheader[] = "Content-Type:application/json";
	
	curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
    curl_setopt( $curl, CURLOPT_URL, $url);
    curl_setopt( $curl, CURLOPT_HTTPHEADER, $httpheader );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST,  2);
	
	
 	$response = curl_exec( $curl );
    curl_close( $curl );
	
	if ($response !== 'true' ){
      if ($response === 'null'){
		$response = '{"error":{"code":404,"message":"Connection Problem. Verify Address."}';
	  }
      
      $errors = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/wp-content/plugins/DoliPress/logfile.log");

      $log = date("Y-m-d H:i:s").' '. "$response \n";
		
      if ($errors =='No errors yet.') {
        file_put_contents('wp-content/plugins/DoliPress/logfile.log', $log .'<br>');
	  }
      else {
		file_put_contents('wp-content/plugins/DoliPress/logfile.log', $log .'<br>', FILE_APPEND);
		}
	}
}

//Retrieve and save last Telegram message

function getTelegramMessages(){
	register_setting('doligravity-settings','telegramid');

	$url = 'https://www.viral-agency.com/get_telegram_id.php';	
	$curl = curl_init();

	curl_setopt( $curl,CURLOPT_HEADER, false);
	curl_setopt( $curl, CURLOPT_URL, $url );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST,  2);

	$lastmessage = curl_exec( $curl );

	curl_close( $curl );
	
	if ($lastmessage != 'null'){
		add_option('telegramid', $lastmessage,'','yes');
		update_option('telegramid', $lastmessage,'','yes');
	}
}

//Print last Telegram Message otherwise default

function retrieveMessage(){
	
	$msgid =  get_option('telegramid', '9' );
	if ($msgid == 'null'){
		$msgid = 9;
	}
	echo '<script async src="https://telegram.org/js/telegram-widget.js?15" data-telegram-post="dolipress/'.$msgid.'" data-width="100%"></script>';
}

//Erase file .log

function eraseLog(){
	$erase = 'No Errors yet.';
	file_put_contents('../wp-content/plugins/DoliPress/logfile.log', $erase .'<br>');

}
?>
