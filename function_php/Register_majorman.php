  <?php
  session_start();
  $_SESSION['session'] = 1;
  $link = mysql_connect ('localhost', 'emorysolutions', 'henrychenniharparikh', 'majorman') or die (mysql_error());

  if(!@mysql_select_db('majorman', $link))
  {
    echo "<p>This is an error message: System cannot connect to database.</p>";
    echo "<p><strong>" . mysql_error() . "</strong></p>";
    echo "Please email emorysolutions@emorysolutions.org for support.";
  }

  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $gender = $_POST['gender'];
  $birthday = $_POST['bday'];
  $school = $_POST['school'];
  $collegeclass = $_POST['collegeclass'];
    $today = getdate();
    if($today['mon'] < 8){ $collegeclass = $today['year'] + 4 - $collegeclass;}
    else{ $collegeclass = $today['year'] + 5 - $collegeclass;}
  $email = $_POST['email'];
  $password = $_POST['password'];
  $security_question_no = str_replace('\'', '\\\'', $_POST['security_question_no']);
  $security_question_ans = $_POST['security_question_ans'];
  str_replace("\"", "\\\"", $security_question_ans);
  str_replace('\'', '\\\'', $security_question_ans);

  date_default_timezone_set('America/New_York');
  $datetime = date('Y-m-d H:i:s');

//----------------------------Input into database-----------------------------------------------
  $query = "INSERT INTO User_info (First_name, Last_name, Gender, Birthday, School, Class, Email, Created_time) VALUES ('$firstname', '$lastname', '$gender', '$birthday', '$school', '$collegeclass', '$email', '$datetime')";
  $check_user = mysql_query($query);
  
  $encrptedpassword = md5($password);
  $query = "INSERT INTO Login_info (User_name, Password, Security_Q, Security_A) VALUES ('$email', '$encrptedpassword', '$security_question_no', '$security_question_ans')";
  
  $check_login = mysql_query($query);
//-----------------------------------------------------------------------------------------------

//----------------------------Email confirmation email------------------------------------------- 
 /* $headers = "Content-type: text/html; charset=iso-8859-1\r\n";
     //$headers .= "To: ".$firstname." ".$lastname."<".$email.">\r\n";
     $headers .= "From: Emory Solutions <noreply@emorysolutions.org>\r\n"; 
     $headers .= "Reply-To: Emory Solutions <emorysolutions@emorysolutions.org>\r\n"; 
     $headers .= "Return-Path: Emory Solutions <emorysolutions@emorysolutions.org>\r\n"; 
     $headers .= "Organization: Emory Solutions\r\n";
  $To = $email;
  $Subject = "Manjorman registration confirmation";
  $confirmURL = "http://emorysolutions.org/majorman/internal/Confirm_registration.php?id=".encrypt($email);
  $content = 
  "
  Dear ".$firstname.",
  <p style=\"text-indent: 2.5em;\">
    Thank you for your registration at Major man. Please click on the link below to activate your account: <a href=\"".
  $confirmURL."\"target=\"_blank\">Activate my account</a>.<br>
  </p>
  Emory Solutions<br>
  (Please do not reply to this email directly. If you have any question, please email emorysolutions@emorysolutions.org)
  ";
  
  $check_email = mail($To, $Subject, $content, $headers);*/
//------------------------------------------------------------------------------------------------
  header("Location: http://emorysolutions.org/majorman/sucess.html");
?>
