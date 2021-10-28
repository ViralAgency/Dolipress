<?php

/*
Plugin Name: Dolipress
Version: 0.1
Description: Synchronize gravity form with dolibarr third parties.
Author: Luca Scandroglio
Author URI: https://www.lucascandroglio.it
*/
	
require_once( 'settings.php' );
require_once( 'functions.php' );

dolipress_init();

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['eraseErrors'])){
	eraseLog();
}

//Silence is golden

?>