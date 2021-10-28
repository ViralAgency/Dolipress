<?php
/**
* Plugin Name: DoliPress
* Version: 0.1
* Description: Synchronize gravity form with dolibarr third parties.
* Author: Luca Scandroglio
* Author URI: https://www.lucascandroglio.it
* License: GPL v3
* Licence URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

/*
DoliPress is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.
 
DoliPress is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Dolipress. If not, see https://www.gnu.org/licenses/gpl-3.0.html.
*/
	
require_once( 'settings.php' );
require_once( 'functions.php' );

dolipress_init();

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['eraseErrors'])){
	eraseLog();
}

//Silence is golden

?>