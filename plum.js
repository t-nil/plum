var API_PATH = "api.php";
var API_ASYNC = false;
var RELOAD_INTERVAL = 5000000;

State = {
	overview : 1,
	user : null,
	locSelection : -1,
	status: 1
}

var loginForm;
var rendering = false;

var sid = $.cookie("sid");

$( document ).ready( function () {
	display();
	setInterval(display, RELOAD_INTERVAL);
});

function myAjax( ajax ) {
	//alert(ajax.url);
	var result = $.ajax( ajax );
	if ( result.responseJSON.error == "auth_failed" ) {/*alert("authfailed");*/ sid = ""; document.location = "http://plum.faui2k13.de/login.php"; return;}
	return result;
}

function api_login(name, pw) {
	return myAjax({
		url: API_PATH + "?/login",
		
		type: "POST",
		
		data: {
			name: loginForm.find( "input[name='name']" ).val(),
			pw: loginForm.find( "input[name='pw']" ).val()
		},
		
		dataType: "json",
		
		async: API_ASYNC
	}).responseJSON;
}

function api_me() {
	return myAjax({
		url: API_PATH + "?/get/me",
		
		type: "POST",
		
		data: {
			sid: sid
		},
		
		dataType: "json",
		
		async: API_ASYNC
	}).responseJSON;
}

function api_getLocationTypes() {
	return myAjax({
		url: API_PATH + "?/get/location_types",
		
		type: "POST",
		
		data: {
			sid: sid
		},
		
		dataType: "json",
		
		async: API_ASYNC
	}).responseJSON;
}

function api_getLocations(type) {
	return myAjax({
		url: API_PATH + "?/get/locations",
		
		type: "POST",
		
		data: {
			sid: sid,
			type_id: type
		},
		
		dataType: "json",
		
		async: API_ASYNC
	}).responseJSON;
}

function api_getLocation(id) {
	return myAjax({
		url: API_PATH + "?/get/location",
		
		type: "POST",
		
		data: {
			sid: sid,
			id: id
		},
		
		dataType: "json",
		
		async: API_ASYNC
	}).responseJSON;
}

function api_getUsers(loc) {
	return myAjax({
		url: API_PATH + "?/get/users",
		
		type: "POST",
		
		data: {
			sid: sid,
			location_id: loc
		},
		
		dataType: "json",
		
		async: API_ASYNC
	}).responseJSON;
}

function api_checkin(loc, posx, posy) {
	return myAjax({
		url: API_PATH + "?/checkin",
		
		type: "POST",
		
		data: {
			sid: sid,
			location_id: loc,
			posx: posx,
			posy: posy
		},
		
		dataType: "json",
		
		async: API_ASYNC
	}).responseJSON;
}

function api_checkout() {
	return myAjax({
		url: API_PATH + "?/checkout",
		
		type: "POST",
		
		data: {
			sid: sid,
		},
		
		dataType: "json",
		
		async: API_ASYNC
	}).responseJSON;
}

function api_updateStatus(status) {
	return myAjax({
		url: API_PATH + "?/set/status",
		
		type: "POST",
		
		data: {
			sid: sid,
			status: status
		},
		
		dataType: "json",
		
		async: API_ASYNC
	}).responseJSON;
}

function api_logout() {
	return myAjax({
		url: API_PATH + "?/halt",
		
		type: "POST",
		
		data: {
			sid: sid
		},
		
		dataType: "json",
		
		async: API_ASYNC
	}).responseJSON;
}

 /*function startup_login() {
	loginForm = $( "#loginForm" );
	
	loginForm.on( "submit", function() {
		result = api_login(
			loginForm.find( "input[name='name']" ).val(),
			loginForm.find( "input[name='pw']" ).val()
		)
		
		if ( result.error != "" ) {
			alert("NOPE");
			return false;
		} else {
			sid = result.result.sid;
			user = api_me().result;
			
			loginForm.fadeOut( 400, function() {
				loginForm = loginForm.detach();
			});
			
			$( "body" ).append($( "<p id='welcome'>Welcome, " + user.name + "</p>"));
		}
		
		state = "location_table";
		display();
		
		return false;
	});
	
	loginForm.find( "input.btnRegister" ).on( "click", function () {
		alert( "YOU SHALL NOT PASS!!!11");
	});
}*/

function display() {
	if (rendering) return;
	rendering = true;
	
	State.user = api_me().result;
	State.checkedInAt = State.user.checked_in_at == null ? -1 : State.user.checked_in_at;

	var body = $( "body" );
	var contentDiv = $( document.createElement("div") ).attr( "id", "content" );
	
	if ( State.overview ) {
		var locTable = $( document.createElement("table") );
		var locTypes = api_getLocationTypes().result;
		var tableContent = "";
		var totalUsers = 0;
		
		locTypes.forEach( function ( locType ) {
			tableContent += "<tr class='locType'><td colspan=2>" + locType.name + "</td><td>" + (locType.usercount == null ? 0 : locType.usercount) + "</td></tr>";
			var locs = api_getLocations(locType.id).result;
			
			locs.forEach( function ( loc ) {
				var checkinLink = loc.id == State.checkedInAt
						? "<a href='' onclick='checkout(); return false'>checkout</a>"
						: "<a href='' onClick='checkin(" + loc.id + "); return false;'>checkin</a>";
				var locNameWithLink = loc.usercount <= 0 ? loc.name : "<a href='' onClick='listUsers(" + loc.id + "); return false;'>" + loc.name + "</a>";
				tableContent += "<tr class='loc'><td>" + checkinLink + "</td><td>" + locNameWithLink + "</td><td>" + loc.usercount + "</td></tr>";
				totalUsers += parseInt(loc.usercount);
			});
		});
		
		tableContent += "<tr class='total'><td colspan=2>total users:</td><td>" + totalUsers + " of max</td></tr>";
		
		locTable.html( tableContent );
		var locDiv = $( document.createElement("div") );
		locDiv.attr({"id": "locs"});
		locDiv.append( locTable );
		
		contentDiv.append( locDiv );
	}
	
	if ( State.locSelection != -1 ) {
		var loc = api_getLocation(State.locSelection).result;
		if ( loc.usercount > 0 ) {
			var userTable = $( document.createElement("table") );
			var users = api_getUsers(State.locSelection).result;
			var tableContent = "";
			tableContent += "<tr><th colspan=3>" + loc.name + "</th><th>" + loc.usercount + "</th></tr>";
			
			users.forEach( function ( user ) {
				tableContent += "<tr><td>" + user.name + "</td><td>" + user.checked_in_at_time + "</td><td class='status'>"
					+ escape(user.status) + "</td></tr>";
			});
			
			userTable.html( tableContent );
			var userDiv = $( document.createElement("div") );
			userDiv.attr({"id": "loc"});
			userDiv.append( userTable );
			
			contentDiv.append( userDiv );
		}
	}
	
	if ( State.status ) {
		$( document.createElement("div") )
			.attr("id", "status")
			.append( $( document.createElement("table") )
				.html("<tr><td>current status:</td><td>" + escape(State.user.status) + "</td></tr>"
					+ "<tr><td>new status:</td><td><input name='newStatus' type='text' /><a href='' onClick='updateStatus(); return false'>update</a></td></tr>")
			)
			.appendTo( contentDiv );
	}
	
	body.html( "" )
		.append( "<div id='logo'><img src='/media/plumanimated2.gif' /></div>" )
		.append( "<div id='welcome'>welcome, " + State.user.name + "</div>" )
		.append( contentDiv )
		.append( "<div id='logout'><a href='' onClick='logout(); return false'>logout</a></div>" );
		
	rendering = false;
}

function checkin(loc) {
	var result = api_checkin(loc, 0, 0);
	if ( result.error != "" ) {
		alert("Error occurred while checking in!\n" + result.error);
	} else {
		State.checkedInAt = loc;
		//alert("Successfully checked in!");
		display();
	}
}

function checkout() {
	api_checkout();
	State.checkedInAt = -1;
	display();
}

function listUsers(loc) {
	State.locSelection = loc;
	display();
}

function updateStatus() {
	var newStatus = $( "input[name='newStatus']" ).val();
	
	if ( api_updateStatus(newStatus).error != "" ) {
		alert("Error occurred while updating! Please check your status.");
		return;
	}
	
	display();
}

function logout() {
	api_logout();
	sid = "";
	display();
}

function escape(str) {
	return  $( document.createElement("td") ).text( str ).html();
}

function displayError(errorMsg) {
	$( document.createElement("div") ).attr( "class", "error" ).text( errorMsg ).prependTo( $( "body" ) );
}