$(document).ready(function() {
	$("#FriendSearch").keyup(function(){
   		var kw = $("#FriendSearch").val();
   		if(kw != '')
     	{    
			   $.ajax({
          		type: "GET", 
          		url: "function_php/myfriend.php",
          		data: { kw : kw},      
          		success: function(data) {        
 				 	      $("#friend_result").html(data);
             }  
         });   
		  }  
      else { 
        $("#friend_result").html("");   
      }  
		  return false;   
	});
});


function addFriend(friend){
  $.ajax({
    type: "POST", 
    url: "function_php/myprofile_update.php",
    data: { friend : friend }, 
    success: function(data) { 
      location.reload(true);       
    }  
  });   
}
