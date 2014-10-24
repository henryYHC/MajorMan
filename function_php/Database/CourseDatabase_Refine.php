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

	// Delete duplicated entries

	$query = "SELECT * FROM Course_info";
	$rowno = mysql_num_rows(mysql_query($query));

	for($i = 0; $i < $rowno; $i++){
		$query = "SELECT * FROM Course_info WHERE No = " . ($i+1);
		$row = mysql_fetch_array(mysql_query($query), MYSQL_ASSOC);

		$query = "SELECT * FROM Course_info WHERE Subject = '" . $row['Subject'] . "' AND Course_no = '" . $row['Course_no'] . "'  LIMIT 18446744073709551615 OFFSET 1";
		$entryRows = mysql_num_rows(mysql_query($query));
		$query = "DELETE FROM Course_info WHERE Subject = '" . $row['Subject'] . "' AND Course_no = '" . $row['Course_no'] . "' AND No != " . ($i+1);
		$check = mysql_query($query);
		$rowno -= $entryRows;
		echo ($i+1) . "<br>";
	}
?>