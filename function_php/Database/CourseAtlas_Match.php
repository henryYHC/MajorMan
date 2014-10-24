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
// Main Body
//---------------------Extract Course Atlas source code (Semester + url)---------------------------------------------------------------
 	$semester = "Fall2013";
 	$url = "http://atlas.college.emory.edu/fall2013/section/index.html";
 	$Sourcecode = file_get_contents($url);
//-------------------------------------------------------------------------------------------------------------------------------------
//---------------------Retrieve course data from database------------------------------------------------------------------------------ 	
	$numofClass = mysql_num_rows(mysql_query("SELECT * FROM Course_info"));
	for($i = 1; $i < $numofClass+1; $i++)
	{
		$row = mysql_fetch_array(mysql_query("SELECT * FROM Course_info WHERE No = $i"));
		$Cinfo[$i-1] = $row;
	}
//-------------------------------------------------------------------------------------------------------------------------------------
//---------Matching course atlas with database (whether it offers in the semester)-----------------------------------------------------
	for($i = 0; $i < $numofClass; $i++)
	{
		$key1 = ">".$Cinfo[$i][1].$Cinfo[$i][2]."<";
		$key1_S = ">".$Cinfo[$i][1]." ".$Cinfo[$i][2];
		if($i != $numofClass-1){ $key2 = ">".$Cinfo[$i+1][1].$Cinfo[$i+1][2]."<"; $key2_S = ">".$Cinfo[$i+1][1]." ".$Cinfo[$i+1][2]."<";}
		echo $key1." ";
		if(strpos($Sourcecode, $key1) !== false && strpos($Sourcecode, $key1) != strpos($Sourcecode, $key2))
		{ 
			$no = $i + 1;
			$query = "UPDATE Course_info SET ".$semester." = 1 WHERE No = '$no'";
			$check = mysql_query($query);
			echo "O<br>";
		}
		else if (strpos($Sourcecode, $key1_S) !== false && strpos($Sourcecode, $key1_S) != strpos($Sourcecode, $key2_S))
		{
			$no = $i + 1;
			$query = "UPDATE Course_info SET ".$semester." = 1 WHERE No = '$no'";
			$check = mysql_query($query);
			echo "O<br>";
		}
		else
		{ 
			$no = $i + 1;
			$query = "UPDATE Course_info SET ".$semester." = 0 WHERE No = '$no'";
			$check = mysql_query($query);
			echo "X<br>";
		}
	}
//-------------------------------------------------------------------------------------------------------------------------------------
?>