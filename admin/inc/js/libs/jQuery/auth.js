 $(document).ready(
function(){
	if(fb_enabled==1) {
		FB.init({ 
		appId:appid, cookie:true,
	        status:true, xfbml:true 
        	});
	}

	var checkLogin = function() {
		$.get(live_site+"/modules/connect/logged.php", {  }, function(data){ 
		if(data!=0) location.href = live_site+"/index.php";
		else { 
		    $(".error").show();  
		    var error = invalid_login;
		    $(".error").html("<p>"+error+"</p>");
		}
	});
	};

	var extensions={"openid.ns.ax" : "http:\/\/openid.net\/srv\/ax\/1.0", "openid.ax.mode" : "fetch_request", "openid.ax.type.namePerson_friendly" : "http:\/\/axschema.org\/namePerson\/friendly", "openid.ax.type.namePerson_first" : "http:\/\/axschema.org\/namePerson\/first", "openid.ax.type.namePerson_last" : "http:\/\/axschema.org\/namePerson\/last", "openid.ax.type.contact_email" : "http:\/\/axschema.org\/contact\/email", "openid.ax.required" : "namePerson_friendly,namePerson_first,namePerson_last,contact_email" };

	var googleOpener = popupManager.createPopupOpener({
	'realm' : realm,
	'opEndpoint' : 'https://www.google.com/accounts/o8/ud',
	'returnToUrl' : live_site+'/modules/connect/easyauth.php?auth=google',
	'onCloseHandler' : checkLogin,
	'shouldEncodeUrls' : true,
	'extensions' : extensions
	});
 
	$(".facebook").bind("click", facebookLogin);
	$(".google").bind("click", googleLogin);

	function facebookLogin() {
		$(".error").hide();
                $(".facebook").unbind("click");

                FB.getLoginStatus(function(response) {

                    if (response.session) {

                       FBLogin();
                    } else {

                        FB.login(function(response) {

                            if (response.session) {
                                if (response.perms) {
                                    FBLogin();
                                } else {

                                    $(".facebook").bind("click", facebookLogin);
                                }
                            } else {

                                $(".facebook").bind("click", facebookLogin);
                            }
                          }, {perms:'email'});
                    }
                });

	}

	function FBLogin() {
		FB.api('/me', function(response) {

			if(response.error) alert(response.error.message);
			else {

		    $.post(live_site+"/modules/connect/easyauth.php?auth=facebook", { identity: response.id, name: response.name, email: response.email }, function(data){

			var arr = data.split("|");
			var info = arr[0];
			var error = arr[1];
			if(error) { $(".error").show();  $(".error").html("<p>"+error+"</p>");}
			else
				location.href = live_site+"/useraccount.php";
		    });
		}
		});
	}

	function googleLogin() {
		googleOpener.popup(450, 500, '');
	}

// openid

	var openidOpener = popupManager.createPopupOpener({
		'onCloseHandler' : checkLogin
	});

	$("#openid_button").click(function(){ 
		$("#openid_div").slideDown();
	 })

	$("#openid_submit").click(function(){ 
		var oid_val = $("#openid").val();
		if(!oid_val) { 
			$("#oiderror").html(openid_identity_missing)
			$("#oiderror").show();
		}
		else {
			$.post(live_site+"/modules/connect/build_openid_url.php", { identity: oid_val }, function(data){ 

				var arr = data.split("|");
				var url = arr[0];
				var error = arr[1];

				if(error) { 
					$("#oiderror").html(error)
					$("#oiderror").show();
				}
				else {
					$("#oiderror").hide();
					openidOpener.popup(600, 500, url);
				}

			} );

		}
	 })

   });
