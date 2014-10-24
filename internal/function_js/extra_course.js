function addExtra_Course(){
  var className = $("#Extra_ClassName").val();
  var subject = $("#Extra_Subject").val();
  var CourseNo = $("#Extra_CourseNo").val();
  var GER = $("#Extra_GER").val();
  var credit = $("#Extra_Credit").val();
  var CourseDes = $("#Extra_CourseDes").val();

  var errMsg = "";
  if(className == ""){
    $("#Extra_ClassName").attr("class","form-control has-error");
    errMsg += "- Please input the title of the extra course that you wish to add.<br>";
  } else $("#Extra_ClassName").attr("class","form-control");
  if(credit == ""){
    $("#Extra_Credit").attr("class","form-control has-error");
    errMsg += "- Please input the credit number of the extra course that you wish to add.<br>";
  } else $("#Extra_Credit").attr("class","form-control");

  if(errMsg != ""){ 
    if(className == "" && credit == ""); else errMsg += "<br>";
    $("#errorMsg").html(errMsg); 
    return false; 
  }
  
  $.ajax({
    type: "POST", 
    url: "function_php/myprofile_update.php",
    data: { ExtraCourse : 1, Extra_name : className, Extra_subject : subject, Extra_courseNo : CourseNo, Extra_GER : GER, Extra_credit : credit, Extra_courseDes : CourseDes }, 
    success: function(data) { 
      location.reload(true);
    }  
  });  
}

function removeExtraCourse(No){
  $.ajax({
    type: "POST", 
    url: "function_php/myprofile_update.php",
    data: { removeExtraCourseNo : No }, 
    success: function(data) { 
      location.reload(true);
    }  
  });  
}

function viewExtraCourse(No){
  $.ajax({
    type: "GET", 
    url: "function_php/extra_course_search.php",
    data: { ExtraCourseNo : No }, 
    success: function(data) { 
      var modalName = "#ExtraCourse"+No+"-modal"; 
      $('body').append(data);
      $(modalName).modal('show');
    }  
  });  
}
