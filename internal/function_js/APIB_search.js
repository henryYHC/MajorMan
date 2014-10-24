$(document).ready(function() { 
	$("#APIBSearch").keyup(function(){    //keyup => when release the keyboard
		var kw = $("#APIBSearch").val();    //keyword = contents of the input
		if(kw != '')
     	{    
			$.ajax({                         //call ajax
          		type: "GET",              //get contents of the input
          		url: "function_php/APIB_search.php",  //transfer kw to the php as kw
          		data: { kw : kw }, 
          		success: function(data) {        
 				       	$("#APIB_Result").html(data);
             	}  
         	});   
		}  
      	else { 
        	$("#APIB_Result").html("");   
      	}  
		return false;  
	});
});

function addAPIB(No){
	$.ajax({
    type: "POST", 
    url: "function_php/myprofile_update.php",
    data: { APIBno : No },
    async: false,
    success: function(data) { 
      location.reload(true);
    }  
  });  
}

function removeAPIB(No){
	$.ajax({
    type: "POST", 
    url: "function_php/myprofile_update.php",
    data: { removeAPIBno : No }, 
    async: false,
    success: function(data) { 
      location.reload(true);
    }  
  });  
}