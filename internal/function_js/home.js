$(document).ready(function() {
    $.ajax({
        type: "GET", 
        url: "function_php/myprofile_fetch.php",
        dataType: "json", 
        success: function(profile){
            $("#username").append(profile['First_name']+ " " +profile['Last_name']);
            Profile = profile;
        }
    });
});