var fncount = 2, lncount = 2, pwcount = 2, emcount = 2, bdaycount = 2, repwcount = 0;
function RegForm() {
	$(function(){
		//popovers
		$('#inputFN').popover({content: "Please ensure your name only includes letters from A-Z.", container: 'body', placement: 'right', trigger: 'manual'});
		$('#inputFN').popover('hide');
		$('#inputLN').popover({content: "Please ensure your name only includes letters from A-Z.", container: 'body', placement: 'right', trigger: 'manual'});
		$('#inputLN').popover('hide');
        $('#bday').popover({content: "Please input the correct format of your birthday.", container: 'body', placement: 'right', trigger: 'manual'});
        $('#bday').popover('hide');
		//$('#inputSch').popover({content: "MajorMan currently only services Emory University.", container: 'body', placement: 'right', trigger: 'manual'});
		//$('#inputSch').popover('hide');
		$('#inputEmail').popover({content: "You must be an Emory student to use MajorMan. Please enter your Emory email address.", container: 'body', placement: 'right', trigger: 'manual'});
		$('#inputEmail').popover('hide');
		$('#inputPassword').popover({content: "For security purposes, your password must have at least 8 characters and includes at least 3 of the following: uppercase letter, lowercase letter, number, and special character.", container: 'body', placement: 'right', trigger: 'manual'});
		$('#inputPassword').popover('hide');
		$('#inputRePassword').popover({content: "Your passwords do not match.", container: 'body', placement: 'right', trigger: 'manual'});
		$('#inputRePassword').popover('hide');

		//hide alert box
        //$('#formerror').hide();

        $("#inputFN").keyup(function checkFN(){
  			
                //firstname + lastname
                re = /^[A-Za-z-]+$/;
                if (re.test($("#inputFN").val())) {
    				$("#FN").attr("class","form-group");
    				$('#inputFN').popover('hide');
                    fncount = 1;
    			} else if($('#inputFN').val().substr(0, 1) != ''){
    				$("#FN").attr("class","form-group has-error");
                    $('#inputFN').popover('show');
                    fncount = 2;
    			}
    	})
    	$("#inputLN").keyup(function checkLN(){
    			re = /^[A-Za-z-]+$/;
                if (re.test($("#inputLN").val())) {
    				$("#LN").attr("class","form-group");
                    lncount = 1;
                    $('#inputLN').popover('hide');
    			} else if($('#inputLN').val().substr(0, 1) != ''){
    				$("#LN").attr("class","form-group has-error"); 
                    $('#inputLN').popover('show');
                    lncount = 2;
    			}
    	})
    	/*$("#inputSch").change(function(){	
                //school
                var school = $("#inputSch").val();
                if (/Emory University/.test(school) == true) {
                    $("#school").attr("class","form-group");
                    shcount = 1;
                    $('#inputSch').popover('hide');
                }
                else {
                    $("#school").attr("class","form-group has-error");
                    shcount = 2;
                     $('#inputSch').popover('show');
                }
        })*/
        $("#inputEmail").keyup(function checkemail(){
                //email
                var mail = $("#inputEmail").val();
                    
                    if (/emory.edu/.test(mail) == true) {
                        $("#email").attr("class","form-group");
                        emcount = 1;
                        $('#inputEmail').popover('hide');
                    }
                    else if(mail.indexOf('@') !== -1 && mail.substr(0,1) != ''){
                        $("#inputEmail").change(function(){ if(emcount != 1) $("#email").attr("class","form-group has-error");});
                        emcount = 2;
                        $('#inputEmail').popover('show');
                    }
                    else if (mail.substr(1,2) != '') $('#inputEmail').popover('hide');
                    else if(mail == '' || mail.substr(0,1) != '') $('#inputEmail').popover('show');
        })
        var pwd;
        $("#inputPassword").keyup(function checkpwd(){
                //password
                pwd = $("#inputPassword").val();
                if(ok<=3) $("#pwd").attr("class","form-group");
                var ok = 0;
                if (pwd.match(/[A-Z]/)) ok++;
                if (pwd.match(/[a-z]/)) ok++;
                if (pwd.match(/[0-9]/)) ok++;
                if (pwd.match(/[@#$%&!*)(-+=^]/)) ok ++;
                if (pwd.length >= 8) ok++;
                if (ok > 3) {
                    $("#pwd").attr("class","form-group has-success");
                    //$("#pwd").attr("class","form-group");
                    pwcount = 1;
                    $('#inputPassword').popover('hide');
                }
                else {
                    pwcount = 2;
                    $("#inputPassword").change(function(){ if(pwcount == 2)$("#pwd").attr("class","form-group has-error");});
                    if(pwd != '') $('#inputPassword').popover('show');
                }
                })
        $("#inputRePassword").keyup(function checkrepwd(){
                //repassword
                var repwd = $("#inputRePassword").val();
                if (repwd == pwd && pwcount == 1) {
                    $("#repwd").attr("class","form-group has-success");
                    repwcount = 1;
                    $('#inputRePassword').popover('hide');
                }
                else if(repwd.substr(0, 1) != ''){
                    $("#repwd").attr("class","form-group has-error");
                    repwcount = 2;
                    if(pwcount == 1) $('#inputRePassword').popover('show');
                }

    	})

        $('#bday').change(function checkbday(){
            if($('#bday').val().length == 10) $("#birthday").attr("class","form-group");
            bdaycount = 1;
        })
        $('#Ans').keyup(function(){
            if($('#Ans').val() != '') $("#SecurityAns").attr("class","form-group");
        })        

        //error alert box
        $('#testbtn').click(function() {
            re = /^[A-Za-z-]+$/;
            if (re.test($("#inputFN").val())) fncount = 1;
            if (re.test($("#inputLN").val())) lncount = 1;
            if (/emory.edu/.test($("#inputEmail").val()) == true) emcount = 1;
            var ok = 0;
            var pwd = $("#inputPassword").val();
                if (pwd.match(/[A-Z]/)) ok++;
                if (pwd.match(/[a-z]/)) ok++;
                if (pwd.match(/[0-9]/)) ok++;
                if (pwd.match(/[@#$%&!*)(-+=^]/)) ok ++;
                if (pwd.length >= 8) ok++;
                if (ok > 3) pwcount = 1;
            if ($("#inputRePassword").val() == pwd) repwcount = 1;
            if($('#bday').val().length == 10) bdaycount = 1;

            if (fncount == 2 || lncount == 2 || pwcount == 2 || emcount == 2 || repwcount == 2 || bdaycount == 2 || $('#Ans').val() == '') {
                $('#inputFN').popover('hide');
                $('#inputLN').popover('hide');
                //$('#inputSch').popover('hide');
                $('#bday').popover('hide');
                $('#inputEmail').popover('hide');
                $('#inputPassword').popover('hide');
                $('#inputRePassword').popover('hide');
                
                $('#formerror').show('fast', function() {});
                if(fncount == 2) $("#FN").attr("class","form-group has-error");
                if(lncount == 2) $("#LN").attr("class","form-group has-error");
                //if(shcount == 2) $("#school").attr("class","form-group has-error");
                if(pwcount == 2) $("#pwd").attr("class","form-group has-error");
                if(emcount == 2) $("#email").attr("class","form-group has-error");
                if(repwcount == 2) $("#repwd").attr("class","form-group has-error");
                if($('#bday').val().length != 10) $("#birthday").attr("class","form-group has-error");
                if($('#Ans').val() == '') $("#SecurityAns").attr("class","form-group has-error");
                $(window).scrollTop(0);
                return false;
            }
            //window.location="http://emorysolutions.org/majorman/function_php/Register_manjorman.php";
        });
    });	
}
