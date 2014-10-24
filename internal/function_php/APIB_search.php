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

	if(isset($_GET['kw']) && $_GET['kw'] != '')
  	{
	    $kws = $_GET['kw'];
	    $kws = mysql_real_escape_string($kws);
		$query = "SELECT * from APIB_credit WHERE Name like '".$kws."%'";

		$res = mysql_query($query);
    	$count = mysql_num_rows($res);
		
    	if($count > 0)
	    {
	      $i = 0;
	      while($row = mysql_fetch_array($res))
	      {
	        echo "<div class=\"search_results\">".$row['APIB']." ".$row['Name']."<button class=\"btn-primary btn-xs pull-right\" onclick=\"addAPIB('".$row['No']."')\">Add</button></div>";
	        $i++;
	        if($i == $count || $i == 5) break;
	      }
	    }
	    else
	    {
	      echo "No result found!";
	    }
	}

?>