$(document).ready(function() {
	$("#Connected_Friend_Search").keyup(function(){
   		var kw = $("#Connected_Friend_Search").val();
   		if(kw != '')
     	{    
			   $.ajax({
          		type: "GET", 
          		url: "function_php/friend_connected.php",
          		data: { kw : kw},      
          		success: function(data) {        
 				 	      $("#connected_results").html(data);
             }  
         });   
		  }  
      else { 
        $("#connected_results").html("");   
      }  
		  return false;   
	});
});