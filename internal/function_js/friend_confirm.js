$(document).ready(function() {
	$("#MajorSearch").keyup(function(){
   		var kw = $("#MajorSearch").val();
   		if(kw != '')
     	{    
			   $.ajax({
          		type: "GET", 
          		url: "function_php/major_search.php",
          		data: { kw : kw, major : 1}, 
          		success: function(data) {        
 				 	      $("#major_results").html(data);
             }  
         });   
		  }  
      else { 
        $("#major_results").html("");   
      }  
		  return false;   
	});

});

function addMajor(major){
  $.ajax({
    type: "POST", 
    url: "function_php/myprofile_update.php",
    data: { major : major }, 
    success: function(data) { 
      location.reload(true);
    }  
  });   
}
