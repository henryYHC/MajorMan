$(document).ready(function() {

    var regno = getCookie("regno");
    if (regno == null || regno == "" || regno == 0)
    	window.location = "http://emorysolutions.org/majorman/index.html";

}); 

function getCookie(c_name)
{
	var c_value = document.cookie;
	var c_start = c_value.indexOf(" " + c_name + "=");
	if (c_start == -1)
	{
	  c_start = c_value.indexOf(c_name + "=");
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
	return c_value;
}

function logout()
{
  $.ajax({
        type: "POST", 
        url: "function_php/login_function.php",
        success: function(check){ if(check == 1){ window.location = "http://emorysolutions.org/majorman/index.html";}}
  });
} 