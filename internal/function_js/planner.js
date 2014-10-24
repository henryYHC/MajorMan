var Profile;
var planner = {
  "Freshman_F": { Credit: 0 }, "Freshman_S": { Credit: 0 },
  "Sophomore_F": { Credit: 0 }, "Sophomore_S": { Credit: 0 },
  "Junior_F": { Credit: 0 }, "Junior_S": { Credit: 0 },
  "Senior_F": { Credit: 0 }, "Senior_S": { Credit: 0 },
  "Online_course": { Credit: 0 }, "Summer_course": { Credit: 0 }, 
  "GERs": { FSEM: 1, FWRT: 1, WRT: 3, MQR: 1, SNT: 2, HSC: 2, HAP: 2, HAL: 2, HTH: 1, PED: 2 },
  "Major": {},
  "Total_credits": 0, "Errors": { log: {}, Error_count: 0 }
};
var targets = ["Freshman_F", "Freshman_S", "Sophomore_F", "Sophomore_S", "Junior_F", "Junior_S", "Senior_F", "Senior_S"];

$(document).ready(function() {
    
    //Check if browser is maximixed
    /*if($(window).height() < 700) {
      $('#window_size').modal('show');
    }
    $(window).resize(function() {
      if($(window).height() < 700) {
        $('#window_size').modal('show');
      }
      if($(window).height() > 700) {
        $('#window_size').modal('hide');
      }
    })*/
    //DONE
  

    $.ajax({
        type: "GET", 
        url: "function_php/myprofile_fetch.php",
        dataType: "json", 
        success: function(profile){
            $("#username").append(profile['First_name']+ " " +profile['Last_name']);
            
            //Major Stat
            if(profile['Major'] == null){
              $("#major_list").append("<div class='major-entry'>Undeclared</div>");
              $("#major_stat").append("<div class=\"stats-elements-block\">Undeclared</div>");
            }
            else{
              for(var i = 0; i < profile['Major'].length; i++){
                $("#major_list").append("<div class='major-entry'>" + profile['Major'][i] + "<button class=\"btn btn-primary btn-xs pull-right\" style='margin-top:-3px;' onclick=\"autoAddClass('"+profile['MajorNo'][i]+"')\">Auto Add</button></div>");
                $.get("function_php/Major_req.php", {major : profile['MajorNo'][i]}, function(data){
                  var req = JSON.parse(data);
                  var numClass = (req.Req.length+parseInt(req.Ele[0]) >  0)? (req.Req.length+parseInt(req.Ele[0])): "N/A";
                  $("#major_stat").append("<div class=\"stats-elements-block\">"+ req.Name +"<span class=\"pull-right stats-numbers\">"+ numClass +"</span></div>");
                  $("#major_req_stat_sidebar").append("<div class=\"sidebar-sectionheader\">"+ req.Name +"</div>");
                  for(var i = 0; i < req.Req.length; i++)
                    if(req.Req.length == 1) $("#major_req_stat_sidebar").append("<div class='course-grid-entry'>Not available</div>");
                    else $("#major_req_stat_sidebar").append("<div class='course-grid-entry'>" + req.Req[i].replace("/", " or ") + "</div>");
                  
                  $("#major_ele_stat_sidebar").append("<div class=\"sidebar-sectionheader\">"+ req.Name +"</div>");
                  if(req.Ele.length == 1) $("#major_ele_stat_sidebar").append("<div class='course-grid-entry'>Not available</div>");
                  else $("#major_ele_stat_sidebar").append("<div style='font-size:14px'>("+req.Ele[0]+" electives required)</div>");
                  for(var i = 1; i < req.Ele.length; i++)      
                    $("#major_ele_stat_sidebar").append("<div class='course-grid-entry'>" + req.Ele[i].replace("XX", "00 or above") + "</div>");
                  planner.Major[req.No] = req;
                });
              }
            }           
                
            Profile = profile;
        }
    });
    requirementValidation();

    $(".glyphicon-pushpin").click(function(ui){
      $(ui.target.parentElement.nextElementSibling.parentElement).toggleClass("locked");
    })


    var classDraggedID;
    $( ".course-grid-content" ).sortable({
      revert: "invalid", connectWith: ".course-grid-content",
  
      remove: function(event, ui){
        var target = event.target.id;
        var targetC = target+"_C";
        var id = (""+(ui.item[0].id)).replace("P_", "");
        planner[target].Credit -= planner[target][id]['4'];
        planner.Total_credits -= planner[target][id]['4'];
        
        //Credit
        if(planner[target].Credit == 0) $("#"+targetC).html("");
        else $("#"+targetC).html("Credits:  "+planner[target].Credit);
        $("#Total_credit_college").html(planner.Total_credits);
         
        planner.GERs[planner[target][id]['5']]--;
        delete planner[target][id];
        requirementValidation();
      },

      receive: function(event, ui){
        var target = event.target.id;
        var targetC = target+"_C";
        var id = (""+(ui.item[0].id)).replace("P_", "");

        
        $(ui.item[0]/*.lastChild.children[1]*/).attr("onclick", "$('#P_"+id+"').remove(); classRemove("+target+", "+id+");");
        
        /*var curDate = new Date();
        if(curDate.getMonth() < 3 || curDate.getMonth() > 9){
          if(target == targets[(4 - (parseInt(Profile.Class) - curDate.getFullYear())) / 2])
            console.log("Show 1");
        }
        else if(curDate.getMonth() > 2 && curDate.getMonth() < 9){
          if(target == targets[(5 - (parseInt(Profile.Class) - curDate.getFullYear())) / 2 + 1]){
            $.get("function_php/courseinfo_fetch.php", {course : ui.item[0].id, placement: 2}, function(data){
              console.log(data);
            });
          }
        }*/

        $.ajax({
            type: "GET", 
            url: "function_php/courseinfo_fetch_json.php",
            data:{ course : id },
            dataType: "json", 
            async: false,
            success: function(data){
                var info = [id, data["Subject"], data["Course_no"], data["Major"], data["Credit"], data["GER"], data["PreReq"], data["Fall2013"], data["Spring2014"]];
                planner[target][id] = info;

                //Credit
                planner[target].Credit += parseInt(data["Credit"]);
                planner.Total_credits += parseInt(data["Credit"]);
                $("#"+targetC).html("Credit:  "+planner[target].Credit);
                $("#Total_credit_college").html(planner.Total_credits);
                //$("#Total_credit").html(parseInt($("#Total_credit_college").html()) + parseInt($("#Total_credit_transfer").html()));
                $("#Total_credit_remain").html(124-$("#Total_credit_college").html());
                
                //Major
                if(planner.Major[data["Major"]] !== undefined)
                  if(parseInt($(".stats-elements-block:contains('"+planner.Major[data["Major"]].Name+"') > span").html()) - 1 == 0 || $(".stats-elements-block:contains('"+planner.Major[data["Major"]].Name+"') > span").html() == "Done")
                    $(".stats-elements-block:contains('"+planner.Major[data["Major"]].Name+"') > span").html("Done");
                  else
                    $(".stats-elements-block:contains('"+planner.Major[data["Major"]].Name+"') > span").html(parseInt($(".stats-elements-block:contains('"+planner.Major[data["Major"]].Name+"') > span").html()) - 1);
                
                planner.GERs[data["GER"]]--;
            }
        });

        inputId = id.substring(0, id.indexOf(id.match(/\d/))) + " " + id.substring(id.indexOf(id.match(/\d/)));
        var plannerObj = JSON.stringify(planner);
          $.get("function_php/course_validation.php", { planner : plannerObj, targetSemester : target, course : inputId}, function(data){
            if(data != 1){
              $(".error-log-content").append("<div class=\"error-log-entry\">"+data+"</div>");
              planner.Errors.Error_count++;
              $("#error_count").html(planner.Errors.Error_count);
              planner.Errors.log[inputId.replace(" ", "")] = target;
            }
        });
        requirementValidation();
        //plannerValidation(target);
      },
      update: function(event, ui){
          if(ui.item[0].className.indexOf("sidebar-entry") != -1){
            var temp = ui.item[0].outerHTML.substring(ui.item[0].outerHTML.indexOf(">")+1, ui.item[0].outerHTML.indexOf("<div class=\"pull-right\">")).replace(/ /g,'');
            var id = ""+ui.item[0].innerHTML.substring(0, ui.item[0].innerHTML.indexOf("<")).replace(/ /g,'');
            $(ui.item[0]).addClass("course-grid-entry");
            $(ui.item[0]).removeClass("sidebar-entry");
            $(ui.item[0]).attr("id", "P_"+id);
            $(ui.item[0]).removeAttr("onClick");
            $(ui.item[0]).removeAttr("data-toggle");
            $(ui.item[0]).removeAttr("data-target");
            $(ui.item[0].firstElementChild).append("&nbsp;<span class=\"glyphicon glyphicon-info-sign\" onClick=\"courseinfo_fetch('"+temp+"', 1);\"></span><span class=\"glyphicon glyphicon-remove\" onClick=\"$('#P_"+id+"').remove(); classRemove("+event.target.id+","+id+");\"</span>");
          }}
    });
    $( ".course-grid-content" ).disableSelection();


  $("#course_search").keyup(function(){
      var kw = $("#course_search").val();
      if(kw != '')
      {    
        $("#course_result").show('fast');
        $.ajax({
            type: "GET", 
            url: "function_php/course_search.php",
            data: { kw: kw, more: 0}, 
            success: function(result)      
            {        
              $("#course_result").html(result);   
              $("#course_result").scrollTop(0);
              $(".sidebar-entry").draggable({ 
                cursor: 'move',
                appendTo: 'body', 
                helper: "clone", 
                connectToSortable: ".course-grid-content",
                addClasses: false,
                zIndex: 3,
                stack: ".sidebar-entry"
              });
            }
        });    
      }
      else
      { 
        $("#course_result").html("");  
        $("#course_result").hide('fast');
      }  
      return false;   
  });

  $(".sidebar-sectionheader").click(function(event){
    var temp = event.target.id;
    var key = temp.substring(0, temp.length-2);
    var type = temp.substring(temp.length-1);
    if($("#"+key).text() == "")
    {
      $.ajax({
          type: "GET", 
          url: "function_php/planner.php",
          data:{ type : type, key : key },
          success: function(data){
              $("#"+key).html(data);
              $(".sidebar-entry").draggable({ 
                cursor: 'move',
                appendTo: 'body', 
                helper: "clone", 
                connectToSortable: ".course-grid-content",
                addClasses: false,
                zIndex: 3,
                stack: ".sidebar-entry" 
              });
          }
      });
      $("#"+key).toggle();
    }
    else $("#"+key).toggle();
  }); 

});

function courseinfo_fetch(course, placement)
{
  //$("#"+course+"-modal").remove();
  if($("#"+course+"-modal").length){
    if(placement == 1){ 
      $.get("function_php/courseinfo_fetch.php", {course : course, placement: placement}, function(data){
        course_modal = "#"+course+"-modal"; 
        $('body').prepend(data);
        $(course_modal).modal('show');
      });
    }
  }
  else
    $.get("function_php/courseinfo_fetch.php", { course : course, placement: placement} , function(data){
      course_modal = "#"+course+"-modal"; 
      $('body').prepend(data);
      $(course_modal).modal('show');
    });
}

function course_search_more(){
  var kw = $("#course_search").val();
      if(kw != '')
      {    
        $("#course_result").show('fast');
        $.ajax({
            type: "GET", 
            url: "function_php/course_search.php",
            data: { kw: kw, more: 1 }, 
            success: function(result)      
            {        
              $("#course_result").html(result);   
              $("#course_result").scrollTop(0);
              $(".sidebar-entry").draggable({ 
                cursor: 'move',
                appendTo: 'body', 
                helper: "clone", 
                connectToSortable: ".course-grid-content",
                addClasses: false,
                zIndex: 3,
                stack: ".sidebar-entry"
              });
            }
        });    
      }
      else
      { 
        $("#course_result").html("");  
        $("#course_result").hide('fast');
      }  
      return false;   
}

function classRemove(target, id, change){
  id = $(id).html().substring(0, $(id).html().indexOf("<div")).replace(/ /g, "");
  
  //Credit
  planner[target.id].Credit -= planner[target.id][id]['4'];
  planner.Total_credits -= planner[target.id][id]['4'];
  if(planner[target.id].Credit == 0) $("#"+target.id+"_C").html("");
  else $("#"+target.id+"_C").html("Credits:  "+planner[target.id].Credit);
  planner.GERs[planner[target.id][id]['5']]++;

  //Major
  var majorNo = planner[target.id][id]['3'];
  if(planner.Major[majorNo] !== undefined)
    if(parseInt($(".stats-elements-block:contains('"+planner.Major[majorNo].Name+"') > span").html()) == 0 || $(".stats-elements-block:contains('"+planner.Major[majorNo].Name+"') > span").html() == "Done")
      $(".stats-elements-block:contains('"+planner.Major[majorNo].Name+"') > span").html("1");
    else
      $(".stats-elements-block:contains('"+planner.Major[majorNo].Name+"') > span").html(parseInt($(".stats-elements-block:contains('"+planner.Major[majorNo].Name+"') > span").html()) + 1);

  requirementValidation();
  delete planner[target.id][id];
  $("#Total_credit_college").html(planner.Total_credits);
  //$("#Total_credit").html(parseInt($("#Total_credit_college").html()) + parseInt($("#Total_credit_transfer").html()));
  $("#Total_credit_remain").html(124-$("#Total_credit_college").html());
}