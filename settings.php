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

	include( 'functions.php' );
		
	function settingsPage() {
	?>
			<div class="row p-3">
				<div class="col-10">
					<h1 class="text-center">DoliPress Control Panel</h1>
				</div>
			</div>
			<ul class="nav nav-tabs">
			  	<li class="nav-item">
					<a class="nav-link active" aria-current="Gravity Form" href="#">Gravity Form</a>
			  	</li>
				<li class="nav-item">
					<a class="nav-link disabled" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Coming Soon--></a>
				</li>
			  	<li class="nav-item">
					<a class="nav-link disabled" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Woocommerce</a>
				</li>
				<li class="nav-item">
					<a class="nav-link disabled" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Tickets</a>
				</li>
				<li class="nav-item">
					<a class="nav-link disabled" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Users</a>
				</li>

			</ul>
			<div class="row g-0">
					<div class="col-6 m-1 p-3 border bg-light">
						<div class="col-12 d-flex justify-content-center p-3">
						<img src="../wp-content/plugins/DoliPress/img/Dolipress-Logo.png" class="img-fluid dplogo" alt="dolipress logo dobibarr wordpress">
						</div>
						<h3 class="p-3">What is that</h3>
						<p class="px-3">Dolipress is a Wordpress plugin that connects the Wordpress platform to the CRM/ERP Open Souce Dolibarr to automate data acquisition between these two platforms. In this first version we have created an interface system between <b></b>Gravity Forms plugin</b> (the wordpress plugin dedicated to the creation of forms), but we want  to create a complete multi-component integration, with the development of interfaces dedicated to other plugins. Stay connected, because if you like working with these software, the future will be very interesting.</p>
					</div>
					
						
					<div class="col-5 m-1 p-3 border bg-light">
						<h3 class="p-3">Latest News</h3>
							<div class="col-11 justify-content-center">
								<?php
								retrieveMessage(); 
								?>
							</div>
							<div class="text-center pt-2">
								<a class="btn btn-primary " href="https://t.me/dolipress" role="button"><i class="fab fa-telegram pe-2"></i>Sign Up</a>
							</div>
					</div>
			</div>
			<div class="row g-0">
				<div class="col-6 m-1 p-3 border bg-light">
					<h3 class="p-3">Settings</h3>
					<form action="options.php" method="post">
						<?php	
						settingsfields();
						?>
				</div>
				<div class="col-5 justify-content-center">
					<div class="col-12 m-1 mb-2 py-3 border bg-light justify-content-center">
						<h3 class="p-3">How it works?</h3>
						<p class="px-3">To start using dolipress immediately, enter the required settings taking care to match correctly the Gravity Form fields with those of Dolibarr. Once this is done, the data entered through the Gravity Form will be sent directly to Dolibarr.</p>
					</div>
					<div class="col-12 m-1 my-2 p-3 border bg-light bg-gradient justify-content-center">
						<h3 class="text-center">Premium is coming</h3>
							<div class="dragon text-center p-2">
								<i class="fas fa-dragon py-3 fa-4x"></i>
							</div>
						<p class="py-3">We have developed this plugin but we want to go further: new features, total integration, great user support, the work to be done is still a lot and the time available is short. For this we have decided to transform this plugin into an integrated solution developing a premium version of this plugin, in order to make possibile the impossible.</p>
					</div>		
					<div class="col-12 m-1 my-2 p-3 border bg-light justify-content-center">
						<h3 class="p-3">Error Monitor</h3>
						<div class="col-12 bg-dark  overflow-auto error_monitor">
							<p class="p-3 text-success" id="errors">
								<?php
								$errors = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/wp-content/plugins/DoliPress/logfile.log");
								echo $errors;
								?>
							</p>
						</div>
						<div class="col-12 text-center p-3 m-3">
							<form  method="post">
								<input id="submit" class="btn btn-primary btn-md btn-danger" type="submit" name="eraseErrors" value="Erase Errors" />
							</form>	
						</div>
					</div>
				</div>
			<div class="row g-0 p-3">
				<div class="col-4 m-1 p-3">
					<input name="submit" id="submit" class="btn btn-primary btn-md" type="submit" value="Save" /></form>
				</div>
			</div>
			<div class="row g-0 p-3">
				<div class="col-4 p-3 m-1 border bg-light">
					<h3 class="text-center">Support</h3>
						<div class="stars text-center p-2">
							<i class="fab fa-github fa-2x"></i>
						</div>
					<p>Il you need support about this plugin, please read the support on the Official Wordpress Plugin's page</p>
						<div class="text-center">
						<a class="btn btn-primary btn-md" href="https://github.com/ViralAgency/Dolipress/issues" role="button"><i class="fab fa-github pe-2"></i>Open Ticket on Github</a>
						</div>	
				</div>
				<div class="col-4 p-3 m-1 border bg-light">
					<h3 class="text-center">Donate</h3>
						<div id="donate" class="">
							<div id="icons" class="text-center p-2 justify-content-between">
							<i class="fab fa-2x fa-bitcoin bitcoin"></i><i class="fab fa-2x fa-cc-paypal"></i>
						</div>
							<img id="qr" src="../wp-content/plugins/DoliPress/img/QR-bitcoin.png" class="img-fluid p-3 hide qr">
							<p id="text" class="text-start">The development of this plugin took a long time (and still takes). To contribute to its maintenance, please donate.</p>
						</div>
						<div class="text-center">
						<a class="btn btn-primary btn-md" id="paypal" href="https://www.paypal.com/donate?hosted_button_id=GZ6Q8M2JXXNQY" role="button"><i class="fab fa-paypal pe-2"></i>Paypal</a>
						<a class="btn btn-primary btn-md" id="bitcoin" href="#qr" role="button"><i class="fab fa-bitcoin pe-2"></i>Bitcoin</a>
						</div>
				</div>
				<div class="col-3 p-3 m-1 border bg-light">
					<h3 class="text-center">Rate this plugin</h3>
						<div class="stars text-center p-2">
							<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
						</div>
					<p class="mt-1">We have offered you the opportunity to save a lot of time and effort. Please leave us a good review.</p>
						<div class="text-center">
						<a class="btn btn-primary" href="#" role="button">Learn more</a>
						</div>
				</div>
			</div>
	<?php	
	}
?>