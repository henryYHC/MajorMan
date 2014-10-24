$(document).ready(function()
{
	$("#username").bind("keydown", function(event) { var keycode = (event.keyCode ? event.keyCode : (event.which ? event.which : event.charCode)); if (keycode == 13){ $('#login').click();} });
	$("#password").bind("keydown", function(event) { var keycode = (event.keyCode ? event.keyCode : (event.which ? event.which : event.charCode)); if (keycode == 13){ $('#login').click();} });

	$('#login').click(function(){
		var username = $('#username').val();
		var password = $('#password').val();
		var em = $("#login_remember").prop("checked");
		if(em) em = 1; else em = 0;
		$.ajax
		({
			type: "GET",
			url: "function_php/login.php",
			data: { username : username, password : password, em : em},
			success: function(check)
			{
				if(check == 0)
				{ 
					alert("Login Failed."); 
					return false;
				}
				else if(check == -1){ window.location = "internal/getstarted.html";}
				else				{ window.location = "internal/planner.html";}
			}
		});
	});
});

function load_em(){
	var c_value = document.cookie;
	var c_start = c_value.indexOf(" " + "username" + "=");
	if (c_start == -1)
	{
	  c_start = c_value.indexOf("username" + "=");
	}
	if (c_start == -1)
	{
	  c_value = null;
	}
	else
	{
	  c_start = c_value.indexOf("=", c_start) + 1;
	  var c_end = c_value.indexOf(";", c_start);
	  if (c_end == -1)
	  {
		c_end = c_value.length;
	  }
		c_value = unescape(c_value.substring(c_start,c_end));
	}
	
	if(c_value != null)
	{
		$('#username').val(c_value);
		$("#login_remember").attr("checked", "checked");
	}
}