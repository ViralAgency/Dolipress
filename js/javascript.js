/**
* Plugin Name: DoliPress
* Version: 1.0
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
//Apply style if HTTPS is active

var Usehttps = {
	init: function(){
		var button = document.getElementById("usehttps");
			Core.addEventListener(button, "click", Usehttps.switchMode);
		var urldescription = document.getElementById("urldescription");
		var textdescription = urldescription.firstChild;
		var iconlock = document.createElement("i");
			Core.addClass(iconlock,'fab fa-expeditedssl pe-2');
		if 	( textdescription.textContent === 'https://'){
			Core.addClass(urldescription,'bg-success');
			Core.addClass(urldescription,'text-white');
			Core.addClass(urldescription,'valid');
			Core.addClass(iconlock,'fab fa-expeditedssl');
			urldescription.insertBefore(iconlock, textdescription);		
		}
	},
	switchMode: function(iconlock){
		var urldescription = document.getElementById("urldescription");
		var textdescription = urldescription.firstChild;
		var iconlock = document.createElement("i");
			Core.addClass(iconlock,'fab fa-expeditedssl pe-2');
		if 	(textdescription.textContent == 'http://localhost/'){	
			var description = document.createTextNode('https://');
			urldescription.removeChild(urldescription.firstChild);
			urldescription.appendChild(iconlock);
			urldescription.appendChild(description);
			Core.addClass(urldescription,'bg-success text-white valid');
			}
		else {
			description = document.createTextNode('http://localhost/');
			urldescription.removeChild(urldescription.firstChild);
			urldescription.removeChild(urldescription.firstChild);
			urldescription.appendChild(description);
			Core.removeClass(urldescription,'bg-success text-white valid');

		}
	}
};

Core.start(Usehttps);

//Validate API key

var FieldValidation = {
	init: function(){
		var dpapikey = document.getElementById('dpapikey');
		var pswinput = dpapikey.getAttribute('value');
		var regexp = /\w{32}/;
		if (regexp.test(pswinput) === true){
			Core.addClass(dpapikey,'valid');
		}
		else {
			Core.addClass(dpapikey,'invalid');
		}
	}
};

Core.start(FieldValidation);

//Show QR code for BTC donation

var MakeDonation = {
	init:function(){
		var bitcoin = document.getElementById('bitcoin');
			Core.addEventListener(bitcoin, "click", MakeDonation.showBtcQr);
	},
		showBtcQr: function(event){
		var donate = document.getElementById('donate');
		var icons = document.getElementById('icons');
		var text = document.getElementById('text');
		var img = document.getElementById('qr');		
		if (Core.hasClass(img,'hide')){
			Core.addClass(icons, 'hide');
			Core.addClass(text, 'hide');
			Core.addClass(donate, 'text-center');
			Core.removeClass(img, 'hide');
			Core.preventDefault(event);
			
		}
		else {
			Core.addClass(img, 'hide');
			Core.removeClass(icons, 'hide');
			Core.removeClass(text, 'hide');
			Core.preventDefault(event);

		}
	}
};

Core.start(MakeDonation); 


//Apply "danger" style if there is an error

var FormatErrors = {
	init: function(){
		var errors = document.getElementById('errors');
		var errors_text = errors.textContent;
		if (Core.hasClass(errors, 'text-valid') && errors_text !== 'No errors yet'){
			Core.removeClass(errors, 'text-valid');
			Core.addClass(errors, 'text-danger');
		}
	}
};
Core.start(FormatErrors);

//Apply style if they have been found duplicated entries

var SelectValidation = {
	init: function(){
		var search_what = []; 
		var fields = document.getElementsByTagName('select'); 
		for (var x=0; x<fields.length; x++){
			var options = fields[x].getElementsByTagName('option'); 
			for (var y = 0; y<options.length; y++){
				if ( options[y].hasAttribute('selected')){ 
						search_what.push(options[y].textContent); 
					var search_where = search_what.slice(); 
				}
			}
		}			
		for (var i=0; i<search_what.length; i++ ){ 
			search_where.splice(0, 1); 
			for (var z = 0; z<search_where.length; z++){
				if (search_what[i] === search_where[z]) {
							Core.addClass(fields[i], 'invalid');
						var alert_msg = document.createElement('div');
							Core.addClass(alert_msg, 'alert alert-danger');
							alert_msg.textContent = 'Duplicated entry: check pairs configuration.';
						var tables = document.getElementsByTagName('table');
							tables[1].appendChild(alert_msg);
						
						
				}
			}
		}
	}
};
Core.start(SelectValidation);

