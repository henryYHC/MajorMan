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

  if(isset($_POST['firstname']) && isset($_POST['lastname'])){ updatename();}
  if(isset($_POST['newpwd'])){ updatepwd();}
  if(isset($_POST['SQ']) && isset($_POST['SA'])){ updateSecurityQ();}
  if(isset($_POST['major'])){ updateMajor();}
  if(isset($_POST['removeMajorNo'])){ removeMajor();}
  if(isset($_POST['minor'])){ updateMinor();}
  if(isset($_POST['removeMinorNo'])){ removeMinor();}
  if(isset($_POST['APIBno'])){ updateAPIB();}
  if(isset($_POST['removeAPIBno'])){ removeAPIB();}
  if(isset($_POST['ExtraCourse'])){ updateExtraCourse();}
  if(isset($_POST['removeExtraCourseNo'])){ removeExtraCourse();}
  if(isset($_POST['finishTutorial'])){ updateTutorial();}
  if(isset($_POST['friend'])){ updateFriend();}
  if(isset($_POST['removeFriendNo'])){ removeFriend();}
  if(isset($_POST['friendreq'])){ acceptFriend();}

  function updatename()
  {
  	$regno = $_COOKIE['regno'];
  	$firstname = $_POST['firstname'];
  	$lastname = $_POST['lastname'];
  	$query = "UPDATE User_info SET First_name = '".$firstname."', Last_name = '".$lastname."' WHERE Registration_no = ".$regno;
  	$check = mysql_query($query);
  }

  function updatepwd()
  {
  	$regno = $_COOKIE['regno'];
  	$newpwd = md5($_POST['newpwd']);
  	$query = "UPDATE Login_info SET Password = '".$newpwd."' WHERE Registration_no = ".$regno;
    $check = mysql_query($query);
  }

  function updateSecurityQ()
  {
    $regno = $_COOKIE['regno'];
    $SQ = $_POST['SQ'];
    $SA = $_POST['SA'];
    $query = "UPDATE Login_info SET Security_Q = '".$SQ."', Security_A = '".$SA."' WHERE Registration_no = ".$regno;
    $check = mysql_query($query);
  }

  function updateMajor()
  {
    $regno = $_COOKIE['regno'];
    $major = $_POST['major'];
    $query = "UPDATE User_info SET Majors = CONCAT(Majors, '".$major."\n') WHERE Registration_no = ".$regno;
    $check = mysql_query($query);
  }

  function updateMinor()
  {
    $regno = $_COOKIE['regno'];
    $minor = $_POST['minor'];
    $query = "UPDATE User_info SET Minors = CONCAT(Minors, '".$minor."\n') WHERE Registration_no = ".$regno;
    $check = mysql_query($query);
  }

  function removeMajor(){
    $regno = $_COOKIE['regno'];
    $majorNo = $_POST['removeMajorNo'];
    $query = "UPDATE User_info SET Majors = REPLACE(Majors, '".$majorNo."\n', '') WHERE Registration_no = ".$regno;
    $check = mysql_query($query); 
  }

  function removeMinor(){
    $regno = $_COOKIE['regno'];
    $minorNo = $_POST['removeMinorNo'];
    $query = "UPDATE User_info SET Minors = REPLACE(Minors, '".$minorNo."\n', '') WHERE Registration_no = ".$regno;
    $check = mysql_query($query);
  }

  function updateAPIB(){
    $regno = $_COOKIE['regno'];
    $APIBno = $_POST['APIBno'];
    $query = "UPDATE User_info SET AP_IB = CONCAT(AP_IB, '".$APIBno."\n') WHERE Registration_no = ".$regno;
    $check = mysql_query($query);
  }

  function removeAPIB(){
    $regno = $_COOKIE['regno'];
    $removeAPIBno = $_POST['removeAPIBno'];
    $query = "UPDATE User_info SET AP_IB = REPLACE(AP_IB, '".$removeAPIBno."\n', '') WHERE Registration_no = ".$regno;
    $check = mysql_query($query);
  }

  function updateExtraCourse(){
    $regno = $_COOKIE['regno'];
    $name = $_POST['Extra_name'];
    $subject = ($_POST['Extra_subject'] != "Not applicable")? $_POST['Extra_subject'] : "None";
    $courseNo = ($_POST['Extra_subject'] != "Not applicable" && $_POST['Extra_courseNo'] != null)? $_POST['Extra_courseNo'] : "None";
    $GER = ($_POST['Extra_GER'] != "Not applicable")? $_POST['Extra_GER'] : "None";
    $credit = $_POST['Extra_credit'];
    $courseDes = ($_POST['Extra_courseDes'] != null)? $_POST['Extra_courseDes'] : "None";
    date_default_timezone_set('America/New_York');
    $datetime = date('Y-m-d H:i:s');

    $query = mysql_query("INSERT INTO Extra_Course (Subject, Course_no, Course_title, GER, Credit, Course_description, Created_time)
                                            VALUES ('$subject', '$courseNo', '$name', '$GER', '$credit', '$courseDes', '$datetime')");
    $check = mysql_query($query);

    $result = mysql_query('SELECT * FROM Extra_Course ORDER BY No DESC LIMIT 1');
    $temp = mysql_fetch_assoc($result);
    $entryNum = $temp['No'];

    $query = "UPDATE User_info SET Extra_Course = CONCAT(Extra_Course, '".$entryNum."\n') WHERE Registration_no = ".$regno;
    $check = mysql_query($query);
  }

  function removeExtraCourse(){
    $regno = $_COOKIE['regno'];
    $removeExtraCourseNo = $_POST['removeExtraCourseNo'];
    $query = "DELETE FROM Extra_Course WHERE No = " . $removeExtraCourseNo;
    $check = mysql_query($query);
    $query = "UPDATE User_info SET Extra_Course = REPLACE(Extra_Course, '".$removeExtraCourseNo."\n', '') WHERE Registration_no = ".$regno;
    $check = mysql_query($query);
  }

  function updateTutorial(){
    $regno = $_COOKIE['regno'];
    $query = "UPDATE User_info SET Tutorial = 1 WHERE Registration_no = ".$regno;
    $check = mysql_query($query);
  }

  function updateFriend()     //collision
  {
    $regno = $_COOKIE['regno'];
    $friend = $_POST['friend'];
    $check = mysql_query($current);
    $query = "UPDATE User_info SET Unconfirmed_friend = CONCAT(Unconfirmed_friend, '".$regno."\n') WHERE Registration_no = ".$friend;
    $check = mysql_query($query);
  }

  function removeFriend(){
    $regno = $_COOKIE['regno'];
    $friendNo = $_POST['removeFriendNo'];
    $query = "UPDATE User_info SET Confirmed_friend = REPLACE(Confirmed_friend, '".$friendNo."\n', '') WHERE Registration_no = ".$regno;
    $check = mysql_query($query); 
  }

  function acceptFriend()
  {
    $regno = $_COOKIE['regno'];
    $friend_to_acc = $_POST['friend_to_acc'];
    $query = "UPDATE User_info SET Unconfirmed_friend = REPLACE(Unconfirmed_friend, '".$friend_to_acc."\n', '') WHERE Registration_no = ".$regno;
    $check = mysql_query($query);
    $query = "UPDATE User_info SET Confirmed_friend = CONCAT(Confirmed_friend, '".$regno."\n') WHERE Registration_no = ".$friendreq;
    $check = mysql_query($query);
    $query = "UPDATE User_info SET Confirmed_friend = CONCAT(Confirmed_friend, '".$regno."\n') WHERE Registration_no = ".$regno;
    $check = mysql_query($query);
  }

?>