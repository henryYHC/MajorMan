var Profile;

//function FetchProfile(){
$(document).ready(function(){
    $.ajax({
        type: "GET", 
        url: "function_php/myprofile_fetch.php",
        dataType: "json", 
        success: function(profile){
            $("#username").append(profile['First_name']+ " " +profile['Last_name']);

            $("#name").append(profile['First_name'] + " " + profile['Last_name']);
            
            if(profile['Gender'] == 'M'){ $("#sex").append('Male');}
            else{ $("#sex").append('Female');}
            
            $("#bday").append(profile['Birthday']);
            $("#school").append(profile['School']);

            var d = new Date(); var y = d.getFullYear(); var m = d.getMonth(); 
            if(m > 6){ profile['Class'] -= y;}else{ profile['Class'] -= (y-1);}
            switch(profile['Class']){ case 1: var profileClass = "Senior"; break;
                                      case 2: var profileClass = "Junior"; break;
                                      case 3: var profileClass = "Sophomore"; break;
                                      case 4: var profileClass = "Freshman"; break;
                                      default: var profileClass = "Unknown";}
            $("#class").append(profileClass);

            $("#email").append(profile['Email']);
            $("#sq").append(profile['Security_Q']);
            

            if(profile['Major'] == null) $("#major_list").append("<div class='entry'>Undeclared</div>");
            else
                for(var i = 0; i < profile['Major'].length; i++)
                    $("#major_list").append("<div class='entry'>" + profile['Major'][i] + "<span class=\"glyphicon glyphicon-remove pull-right\" onclick=\"removeMajor(" + profile['MajorNo'][i] + ")\"></span></div>");
            
            if(profile['Minor'] == null) $("#minor_list").append("<div class='entry'>Undeclared</div>");
            else
                for(var i = 0; i < profile['Minor'].length; i++)
                    $("#minor_list").append("<div class='entry'>" + profile['Minor'][i] + "<span class=\"glyphicon glyphicon-remove pull-right\" onclick=\"removeMinor(" + profile['MinorNo'][i] + ")\"></span></div>");

            if(profile['APIB'] == null) $("#APIB_list").append("<div class='entry'>None</div>");
            else
                for(var i = 0; i < profile['APIB'].length; i++)
                    $("#APIB_list").append("<div class='entry'>" + profile['APIB'][i] + "<span class=\"glyphicon glyphicon-remove pull-right\" onclick=\"removeAPIB(" + profile['APIBno'][i] + ")\"></span></div>");
            
            if(profile['Extra_Course'] == null) $("#Extra_course_list").append("<div class='entry'>None</div>");
            else
                for(var i = 0; i < profile['Extra_Course'].length; i++)
                     $("#Extra_course_list").append("<div class='entry'>" + profile['Extra_Course'][i] + "<div class=\"pull-right\"><span class=\"glyphicon glyphicon-info-sign\" onclick=\"viewExtraCourse(" + profile['Extra_Course_No'][i] + ")\"></span>&nbsp;&nbsp;<span class=\"glyphicon glyphicon-remove\" onclick=\"removeExtraCourse(" + profile['Extra_Course_No'][i] + ")\"></span></div></div>");
            
            if(profile['Confirmed_friend'] == null) $("#friend_list").append("<div class='entry'>NoFriendConnected</div>");
            else
                for(var i = 0; i < profile['Confirmed_friend'].length; i++)
                    $("#friend_list").append("<div class='entry'>" + profile['Confirmed_friend'][i] + "<span class=\"glyphicon glyphicon-remove pull-right\" onclick=\"removeFriend(" + profile['Registration_no'][i] + ")\"></span></div>");

            if(profile['Unconfirmed_friend'] == null) $("#confirmation_list").append("<div class='entry'>YouAreLonely</div>");
            else
                for(var i = 0; i < profile['Unconfirmed_friend'].length; i++)
                    $("#confirmation_list").append("<div class='entry'>" + profile['Unconfirmed_friend'][i] + "<span class=\"glyphicon glyphicon-remove pull-right\" onclick=\"removeFriend(" + profile['Registration_no'][i] + ")\"></span></div>");
        }
    });
});

function editName(){
    $("#name").html("<input type=\"text\" class=\"form-control\" id=\"inputFirstName\" placeholder=\"First name\" value=\""+Profile['First_name']+"\" name=\"Name\"> <input type=\"text\" class=\"form-control\" id=\"inputLastName\" placeholder=\"Last name\" value=\""+Profile['Last_name']+"\" name=\"Name\">");
    $("#name_edit").replaceWith( "<button type=\"button\" class=\"btn btn-success btn-xs form-control\" id=\"name_done\">Done</button>");
    $('#inputFirstName').popover({content: "Please ensure your name only includes letters from A-Z.", container: 'body', placement: 'right', trigger: 'manual'});
    $('#inputFirstName').popover('hide');
    $('#inputLastName').popover({content: "Please ensure your name only includes letters from A-Z.", container: 'body', placement: 'right', trigger: 'manual'});
    $('#inputLastName').popover('hide');

    $("#inputFirstName").keyup(function(){ checkName("#inputFirstName"); });
    $("#inputLastName").keyup(function(){ checkName("#inputLastName"); });

    $("#name_done").click(function(){ if(checkName("#inputFirstName") && checkName("#inputLastName")){ updateName();} });}
function checkName(object){
    re = /^[A-Za-z-]+$/;
    if (re.test($(object).val())) {
        $(object).popover('hide');
        return true;
    } else if($(object).val().substr(0, 1) != ''){
        $(object).popover('show');
        return false;
    }}
function updateName(){
    $('#inputFirstName').popover('hide');
    $('#inputLastName').popover('hide');
    Profile['First_name'] = $("#inputFirstName").val(); Profile['Last_name'] = $("#inputLastName").val();
    $.ajax({
        type: "POST", 
        url: "function_php/myprofile_update.php",
        data: { firstname : Profile['First_name'], lastname : Profile['Last_name']},
        success: function() {}
    });

    $("#name").html($("#inputFirstName").val() + " " + $("#inputLastName").val());
    $("#name_done").replaceWith("<span class=\"glyphicon glyphicon-pencil\" id=\"name_edit\" onclick=\"editName()\"></span>");}

function editPwd(){
    $("#pwd").html("<input type=\"password\" class=\"form-control\" id=\"inputPassword\" placeholder=\"Password\" name=\"password\"> <input type=\"password\" class=\"form-control\" id=\"inputRePassword\" placeholder=\"Re-enter Password\" name=\"repassword\">");
    $("#pwd_edit").replaceWith( "<button type=\"button\" class=\"btn btn-success btn-xs form-control\" id=\"pwd_done\">Done</button>");
    $('#inputPassword').popover({content: "For security purposes, your password must have at least 8 characters and includes at least 3 of the following: uppercase letter, lowercase letter, number, and special character.", container: 'body', placement: 'right', trigger: 'manual'});
    $('#inputPassword').popover('hide');
    $('#inputRePassword').popover({content: "Your passwords do not match.", container: 'body', placement: 'right', trigger: 'manual'});
    $('#inputRePassword').popover('hide');

    $('#inputPassword').keyup(function(){ checkPwd("#inputPassword")});
    $('#inputRePassword').keyup(function(){ checkPwd("#inputRePassword")});
    $("#pwd_done").click(function(){ 
        if($("#inputPassword").val() == "" && $("#inputRePassword").val() == ""){
            $("#pwd").html("Click to edit");
            $("#pwd_done").replaceWith("<span class=\"glyphicon glyphicon-pencil\" id=\"pwd_edit\" onclick=\"editPwd()\"></span>");
        }
        else if(checkPwd()){  updatePwd();}
    });}
function checkPwd(object){
    var ok = 0;
    pwd = $("#inputPassword").val();        
    if (pwd.match(/[A-Z]/)) ok++;
    if (pwd.match(/[a-z]/)) ok++;
    if (pwd.match(/[0-9]/)) ok++;
    if (pwd.match(/[@#$%&!*)(-+=^]/)) ok ++;
    if (pwd.length >= 8) ok++;
    if (ok > 3) { $("#inputPassword").popover('hide');}
        else if(pwd != ''){ $("#inputPassword").popover('show');}
    
    var repwd = $("#inputRePassword").val();
    if ((repwd == $("#inputPassword").val()  && $("#inputPassword").val() != "") || ok <= 3 ) { $("#inputRePassword").popover('hide'); return true;}
    else if(repwd.substr(0, 1) != ''){ $("#inputRePassword").popover('show'); return false;}
    else return false;}
function updatePwd(){
    var newpwd = $("#inputPassword").val();
    $.ajax({
        type: "POST", 
        url: "function_php/myprofile_update.php",
        data: { newpwd : newpwd},
        success: function(){}
    });

    $("#pwd").html("Click to edit");
    $("#pwd_done").replaceWith("<span class=\"glyphicon glyphicon-pencil\" id=\"pwd_edit\" onclick=\"editPwd()\"></span>");}

function editSecurityQ()
{
    $('#inputSA').popover({content: "Please enter your answer for security question.", container: 'body', placement: 'right', trigger: 'manual'});
    $('#inputSA').popover('hide');
    $("#sq").html("<select class=\"form-control\" id=\"inputSQ\" name=\"security_question_no\"> <option value=\"What is your mother\\\'s maiden name?\">What is your mother's maiden name?</option> <option value=\"What is your favorite food?\">What is your favorite food?</option> <option value=\"What is your pet\\\'s name?\">What is your pet's name?</option> <option value=\"Where did you first have sex?\">Where did you first have sex?</option> </select>");
    $("#sq_edit").replaceWith( "<button type=\"button\" class=\"btn btn-success btn-xs form-control\" id=\"sq_done\">Done</button> <button type=\"button\" class=\"btn btn-primary btn-xs form-control\" id=\"sq_cancel\">Cancel</button>");
    $("#sa_row").show('fast', function(){ $('#inputSA').popover('show');});

    $("#sq_cancel").click(function(){ 
        $("#sq").html(Profile['Security_Q']);
        $("#sq_done").replaceWith("<span class=\"glyphicon glyphicon-pencil\" id=\"sq_edit\" onclick=\"editSecurityQ()\"></span>");
        $("#sq_cancel").remove();
        $('#inputSA').popover('hide');
        $("#inputSA").val('');
        $("#sa_row").hide();});
    $("#inputSA").keyup(function(){ if($("#inputSA").val() != ""){ $('#inputSA').popover('hide');} });
    $("#sq_done").click(function(){ if($("#inputSA").val() != ""){ updateSecurityQ();} });
}
function updateSecurityQ()
{
    Profile['Security_Q'] = $("#inputSQ").val(); Profile['Security_A'] = $("#inputSA").val();
    $.ajax({
        type: "POST", 
        url: "function_php/myprofile_update.php",
        data: { SQ : Profile['Security_Q'], SA : Profile['Security_A']},
        success: function(){}
    });
    Profile['Security_Q'] = Profile['Security_Q'].replace('\\', '');
    $("#sq").html(Profile['Security_Q']);
    $("#sq_done").replaceWith("<span class=\"glyphicon glyphicon-pencil\" id=\"sq_edit\" onclick=\"editSecurityQ()\"></span>");
    $('#inputSA').popover('hide');  
    $("#sq_cancel").remove();
    $("#inputSA").val('');
    $("#sa_row").hide();
}

function removeMajor(majorNo){
    $.ajax({
    type: "POST", 
    url: "function_php/myprofile_update.php",
    data: { removeMajorNo : majorNo },
    async: false,
    success: function(data) { 
      location.reload(true);       
    }  
  });
}

function removeMinor(minorNo){
    $.ajax({
    type: "POST", 
    url: "function_php/myprofile_update.php",
    data: { removeMinorNo : minorNo },
    async: false,
    success: function(data) { 
      location.reload(true);       
    }  
  });
}

function removeFriend(Registration_no){
    $.ajax({
    type: "POST", 
    url: "function_php/myprofile_update.php",
    data: { removeFriendNo : Registration_no },
    async: false,
    success: function(data) { 
      location.reload(true);       
    }  
  });
}