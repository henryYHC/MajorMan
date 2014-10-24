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

    var router = Backbone.Router.extend({
    	routes: {
    		"Intro": "DisplayIntro",
    		"Major": "InputMajor",
    		"Minor": "InputMinor",
    		"APIB": "InputAPIB",
    		"ExtraCourse": "InputExtraCourse",
            "Complete": "CompleteTutorial",
    		"*other": "defaultRoute"
    	},
    	DisplayIntro: function(){
    		$.get("getstarted_html/Intro.html", function(data){ $("#content").html(data);});
    	},
    	InputMajor: function(){
    		$.get("getstarted_html/Major.html", function(data){ $("#content").html(data);});
    	},
    	InputMinor: function(){
    		$.get("getstarted_html/Minor.html", function(data){ $("#content").html(data);});
    	},
    	InputAPIB: function(){
    		$.get("getstarted_html/APIB.html", function(data){ $("#content").html(data);});
    	},
    	InputExtraCourse: function(){
    		$.get("getstarted_html/ExtraCourse.html", function(data){ $("#content").html(data);});
    	},
        CompleteTutorial: function(){
            $.get("getstarted_html/Complete.html", function(data){ $("#content").html(data);});
        },
    	defaultRoute: function(other){
    		window.location.href = "#Intro";
    	}
    });

    var getStartedRouter = new router();
    Backbone.history.start();
});