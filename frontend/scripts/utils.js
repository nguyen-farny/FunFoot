var server_address = "http://hanhphuc-sport.com/funfoot-content/"; 

function login(email, password, callback)
{
	$.ajax({
		url: server_address + "backend/user/login.php",
		type: "POST",
		data: {
			'email': email,
			'password' : password
		},
		context: document.body
	}).done(function(data) {
		var result = jQuery.parseJSON(data);
		if(result.id)
			document.cookie = "userId=" + result.id;
		callback(result);
	});
}

function setCookie(sName, sValue) {
	var today = new Date(), expires = new Date();
	expires.setTime(today.getTime() + (30*24*60*60*1000));
	document.cookie = sName + "=" + encodeURIComponent(sValue) + ";expires=" + expires.toGMTString();
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function getUserId()
{
	return getCookie("userId");
}

function validate_mail(mail) {	
	var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;	
	if (filter.test(mail)) {		
		return true;	
	}	
	else {		
		return false;	
	}
}