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

    $Major_req = [ 'No' => 0, 'Name' => "", 'Req' => [], 'numEle' => 0, 'Ele' => [] ];

    $major = $_GET['major'];

    $query = "SELECT * FROM Majors_minors WHERE Major = 1 AND Major_no = " . $major;
    $result = mysql_fetch_row(mysql_query($query), MYSQL_ASSOC);

    $Major_req['No'] = $result['Major_no'];
    $Major_req['Name'] = trim($result['Major_name']);
    $req = explode("\n", $result['Major_req']);
    for($i = 0; $i < count($req); $i++){ $req[$i] = str_replace(" ", "", trim($req[$i])); array_push($Major_req['Req'], $req[$i]); }
    $ele = explode("\n", $result['Major_ele']);
	$Major_req['numEle'] = intval(trim($ele[0]));
	for($i = 0; $i < count($ele); $i++){ $req[$i] = str_replace(" ", "", trim($ele[$i])); array_push($Major_req['Ele'], $ele[$i]); }

    $data = json_encode($Major_req);
	print_r($data);	

?>