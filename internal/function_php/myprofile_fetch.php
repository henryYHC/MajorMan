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
    //Basic info fetch
  	$regno = $_COOKIE['regno'];
  	$Profile = mysql_fetch_array(mysql_query("SELECT * FROM User_info WHERE Registration_no = $regno"));
  	$loginInfo = mysql_fetch_array(mysql_query("SELECT * FROM Login_info WHERE Registration_no = $regno"));
    $Profile['Security_Q'] = $loginInfo['Security_Q'];

    //Major name fetch
    $majorNo_temp = $Profile['Majors'];
    unset($Profile['Majors']);
    $majorNo_array = explode("\n", $majorNo_temp);
    array_pop($majorNo_array);
    $majorName_array = array();
    $result = mysql_query("SELECT Major_name FROM Majors_minors");
    for($i=0; $i < count($majorNo_array); $i++) {
      $majorName_array[] = mysql_result($result, ($majorNo_array[$i] - 1));
    }
    for($j=0; $j < count($majorName_array); $j++) {
      $Profile['Major'][$j] = $majorName_array[$j];
      $Profile['MajorNo'][$j] = $majorNo_array[$j];
    }

    //Minor name fetch
    $minorNo_temp = $Profile['Minors'];
    unset($Profile['Minors']);
    $minorNo_array = explode("\n", $minorNo_temp);
    array_pop($minorNo_array);
    $minorName_array = array();
    $result = mysql_query("SELECT Major_name FROM Majors_minors");
    for($i=0; $i < count($minorNo_array); $i++) {
      $minorName_array[] = mysql_result($result, ($minorNo_array[$i] - 1));
    }
    for($j=0; $j < count($minorName_array); $j++) {
      $Profile['Minor'][$j] = $minorName_array[$j];
      $Profile['MinorNo'][$j] = $minorNo_array[$j];
    }

    //AP_IB name fetch
    $APIB_No_temp = $Profile['AP_IB'];
    unset($Profile['AP_IB']);
    $APIB_No_array = explode("\n", $APIB_No_temp);
    array_pop($APIB_No_array);
    $APIB_Name_array = array();
    $result_name = mysql_query("SELECT Name FROM APIB_credit");
    $result_APIB = mysql_query("SELECT APIB FROM APIB_credit");
    for($i = 0; $i < count($APIB_No_array); $i++) {
       $APIB_Name_array[$i] = mysql_result($result_APIB, ($APIB_No_array[$i]) - 1);
       $APIB_Name_array[$i] .= " ".mysql_result($result_name, ($APIB_No_array[$i]) - 1);
    }
    for($j=0; $j < count($APIB_No_array); $j++) {
       $Profile['APIB'][$j] = $APIB_Name_array[$j];
       $Profile['APIBno'][$j] = $APIB_No_array[$j];
    }

    //Extra Course fetch
    $Extra_No_temp = $Profile['Extra_Course'];
    unset($Profile['Extra_Course']);
    $Extra_No_array = explode("\n", $Extra_No_temp);
    array_pop($Extra_No_array);
    $Extra_Name_array = array();
    for($i=0; $i < count($Extra_No_array); $i++) {
      $result = mysql_query("SELECT Course_title FROM Extra_Course WHERE No = ".$Extra_No_array[$i]);
      $Extra_Name_array[] = mysql_result($result, 0);
    }
    for($j=0; $j < count($Extra_Name_array); $j++) {
      $Profile['Extra_Course'][$j] = $Extra_Name_array[$j];
      $Profile['Extra_Course_No'][$j] = $Extra_No_array[$j];
    }

    //Friend name fetch
    $friendNo_temp = $Profile['Confirmed_friend'];
    unset($Profile['Confirmed_friend']);
    $friendNo_array = explode("\n", $friendNo_temp);
    array_pop($friendNo_array);
    $friendName_array = array();
    $result = mysql_query("SELECT CONCAT(CONCAT(First_name,' '), Last_name) FROM User_info");
    for($i=0; $i < count($friendNo_array); $i++) {
      $friendName_array[] = mysql_result($result, ($friendNo_array[$i] - 1));
    }
    for($j=0; $j < count($friendName_array); $j++) {
      $Profile['Confirmed_friend'][$j] = $friendName_array[$j];
      $Profile['Registration_no'][$j] = $friendNo_array[$j];
    }

    $friendNo_temp = $Profile['Unconfirmed_friend'];
    unset($Profile['Unconfirmed_friend']);
    $friendNo_array = explode("\n", $friendNo_temp);
    array_pop($friendNo_array);
    $friendName_array = array();
    $result = mysql_query("SELECT CONCAT(CONCAT(First_name,' '), Last_name) FROM User_info");
    for($i=0; $i < count($friendNo_array); $i++) {
      $friendName_array[] = mysql_result($result, ($friendNo_array[$i] - 1));
    }
    for($j=0; $j < count($friendName_array); $j++) {
      $Profile['Unconfirmed_friend'][$j] = $friendName_array[$j];
      $Profile['Registration_no'][$j] = $friendNo_array[$j];
    }

    echo json_encode($Profile);	
 ?>