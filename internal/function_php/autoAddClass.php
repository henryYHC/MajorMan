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

    $autoAdd = ["Freshman_F" => [], "Freshman_S" => [], "Sophomore_F" => [], "Sophomore_S" => [], "Junior_F" => [], "Junior_S" => [], "Senior_F" => [], "Senior_S" => []];

    $planner = $_GET['planner'];
    $majorNo = $_GET['majorNo'];
    $check_seq = ["Freshman_F", "Freshman_S", "Sophomore_F", "Sophomore_S", "Junior_F", "Junior_S", "Senior_F", "Senior_S"];
    $req = major_req($majorNo);
    $req = req_planer_crossCheck($planner, $req);

    //Requirements
	for($i = 0; $i < count($check_seq); $i++){
        for($j = 0; $j < count($req['Req']); $j++){
            if($req['Req'][$j] == null) continue;            
            if(count($req['Req'][$j]) > 1) $req['Req'][$j] = $req['Req'][$j][0];
            $credit = course_check($planner, $check_seq[$i], $req['Req'][$j]);
            if($credit != 0 && $credit + $planner[$check_seq[$i]]['Credit'] <= 21){
                 $planner[$check_seq[$i]][trim(str_replace(" ", "", $req['Req'][$j]))] = course_info($req['Req'][$j]);
                 $autoAdd[$check_seq[$i]][trim(str_replace(" ", "", $req['Req'][$j]))] = course_info($req['Req'][$j]);
                 $planner[$check_seq[$i]]['Credit'] += $credit;
                 $req['Req'][$j] = null;
            }
        }
    }

    //Electives
    /*for($j = 0; $j < $req['numEle']; $j++){
        do $index = rand(0, count($req['Ele']));
        while(course_credit($req['Ele'][$index]) < 3 && $req['Ele'][$index] == null);
        for($i = 0; $i < count($check_seq); $i++){
        	$temp = explode(" ", $req['Ele'][$index]); $class_no = intval(trim($temp[1]));
            $credit = course_check($planner, $check_seq[$i], $req['Ele'][$index]);
            if($credit != 0 && $credit + $planner[$check_seq[$i]]['Credit'] <= 21 && (floor(($class_no/100)) <= floor(($i/2+1)))){
                $planner[$check_seq[$i]][trim(str_replace(" ", "", $req['Ele'][$index]))] = course_info($req['Ele'][$index]);
                $autoAdd[$check_seq[$i]][trim(str_replace(" ", "", $req['Ele'][$index]))] = course_info($req['Ele'][$index]);
                $planner[$check_seq[$i]]['Credit'] += $credit;
                $req['Ele'][$index] = null;
            }
        }
    }*/

    echo json_encode($autoAdd);

    function major_req($major){
    	$Major_req = [ 'No' => 0, 'Name' => "", 'Req' => [], 'numEle' => 0, 'Ele' => [] ];
	    $query = "SELECT * FROM Majors_minors WHERE Major = 1 AND Major_no = " . $major;
	    $result = mysql_fetch_row(mysql_query($query), MYSQL_ASSOC);
	    $Major_req['No'] = $result['Major_no'];
	    $Major_req['Name'] = trim($result['Major_name']);
	    $req = explode("\n", $result['Major_req']);
	    for($i = 0; $i < count($req); $i++){ $req[$i] = trim($req[$i]); array_push($Major_req['Req'], $req[$i]); }
	    $ele = explode("\n", $result['Major_ele']);
		$Major_req['numEle'] = intval(trim($ele[0]));
		for($i = 0; $i < count($ele); $i++){ $req[$i] = trim($ele[$i]); array_push($Major_req['Ele'], $ele[$i]); }
		unset($Major_req['No']); unset($Major_req['Name']); unset($Major_req['Ele'][0]);
        for($i = 0; $i < count($Major_req['Req']); $i++)
            if(strpos($Major_req['Req'][$i], '/') !== false){
                $temp = explode("/", $Major_req['Req'][$i]);
                unset($Major_req['Req'][$i]);
                for($j = 0; $j < count($temp); $j++) $Major_req['Req'][$i][$j] = $temp[$j];
            }
        //Elective Entry
        $entryNo = count($Major_req['Ele']);
        for($i = 0; $i < $entryNo; $i++)
        	if(strpos($Major_req['Ele'][$i], "XX") !== false){
        		preg_match_all('!\d+!', $Major_req['Ele'][$i], $temp); $courseNo = $temp[0][0];
        		$subject = substr($Major_req['Ele'][$i], 0, strpos($Major_req['Ele'][$i], " "));
        		$query = "SELECT * from Course_info WHERE Subject = '".$subject."' AND Course_no LIKE '".$courseNo."%'";
        		
        		$res = mysql_query($query);
    			$count = mysql_num_rows($res);
    			if($count > 0){
			    	$j = 0;
			    	while($row = mysql_fetch_array($res))
			      	{
			      		for($k = 0; $k < count($Major_req['Req']); $k++)
			      			if($Major_req['Req'][$k] == $row['Subject']." ".$row['Course_no']) break;
			      			else if($k == count($Major_req['Req']) - 1) $Major_req['Ele'][] = $row['Subject']." ".$row['Course_no'];
			        	if($j++ == $count) break;
			      	}
			    }
			    $Major_req['Ele'][$i] = 0;
			}

        return $Major_req;}
    function PreReq_fetch($name){
        $temp = explode(" ", $name); $subject = $temp[0]; $class_no = $temp[1];
        $query = "SELECT PreReq FROM Course_info WHERE Subject = '".$subject."' AND Course_no = '".$class_no."'";
        $preReqString = mysql_result(mysql_query($query), 0);

        $preReq = [];
        if(strpos($preReqString, "None") !== false || strpos($preReqString, "NONE") !== false) return "None";
        
        $list = explode("\n", $preReqString);
        for($i = 0; $i < count($list); $i++){
            $preReq[$i] = [];
            if(strpos($list[$i], "/") !== false){
                $or = explode(" / ", $list[$i]);
                for($j = 0; $j < count($or); $j++){
                    $preReq[$i][$j] = [];
                    $temp = explode(" ", $or[$j]);
                    $preReq[$i][$j]['Subject'] = trim($temp[0]);
                    $preReq[$i][$j]['Course_no'] = trim($temp[1]);
                }
            }
            else{
                $preReq[$i][0] = [];
                $temp = explode(" ", $list[$i]);
                $preReq[$i][0]['Subject'] = trim($temp[0]);
                $preReq[$i][0]['Course_no'] = trim($temp[1]);
            }
        }
        ///// Take out AP/IB course before validation

        //AP_IB name apc_fetch(key)
        $regno = $_COOKIE['regno'];
        $query = "SELECT AP_IB FROM User_info WHERE Registration_no = ".$regno;
        $APIB_query = mysql_result(mysql_query($query), 0);
        $APIB_No_array = explode("\n", $APIB_query);
        array_pop($APIB_No_array);
        $APIB_Name_array = [];
        $result = mysql_query("SELECT Equiv_course_no FROM APIB_credit");
        for($i = 0; $i < count($APIB_No_array); $i++) {
           $APIB_equi = mysql_result($result, ($APIB_No_array[$i]) - 1);
           if(strpos($APIB_equi, "/") !== false){
                $or = explode(" / ", $APIB_equi);
                $index = count($APIB_Name_array);
                for($j = 0; $j < count($or); $j++){
                    $temp = explode(" ", $or[$j]);
                    $APIB_Name_array[$index][$j]['Subject'] = trim($temp[0]);
                    $APIB_Name_array[$index][$j]['Course_no'] = trim($temp[1]);
                }
           }
           else{
                $and = explode("\n", $APIB_equi);
                for($j = 0; $j < count($and); $j++){
                    $temp = explode(" ", $and[$j]);
                    $index = count($APIB_Name_array);
                    $APIB_Name_array[$index][0]['Subject'] = trim($temp[0]);
                    $APIB_Name_array[$index][0]['Course_no'] = trim($temp[1]);
                }
           }
        }

        $preReq_size = count($preReq);
        $APIB_array_size = count($APIB_Name_array);
        for($i = 0; $i < $preReq_size; $i++){
            if(count($preReq[$i]) > 1){
                for($j = 0; $j < count($preReq[$i]); $j++)
                {
                    for($k = 0; $k < $APIB_array_size; $k++){
                        if(count($APIB_Name_array[$k]) > 1){
                            for($l = 0; $l < count($APIB_Name_array[$k]); $l++){
                                if($preReq[$i][$j]['Subject'] == $APIB_Name_array[$k][$l]['Subject'] && $preReq[$i][$j]['Course_no'] == $APIB_Name_array[$k][$l]['Course_no'])
                                { unset($preReq[$i]); break; }
                            }
                        }
                        else if($preReq[$i][$j]['Subject'] == $APIB_Name_array[$k][0]['Subject'] && $preReq[$i][$j]['Course_no'] == $APIB_Name_array[$k][0]['Course_no'])
                        { unset($preReq[$i]); break; }
                    }
                }
            }
            else{
                for($j = 0; $j < $APIB_array_size; $j++){
                    if(count($APIB_Name_array[$j]) > 1){
                        for($k = 0; $k < count($APIB_Name_array[$j]); $k++)
                            if($preReq[$i][0]['Subject'] == $APIB_Name_array[$j][$k]['Subject'] && $preReq[$i][0]['Course_no'] == $APIB_Name_array[$j][$k]['Course_no'])
                            { unset($preReq[$i]); break; }
                    }
                    else if($preReq[$i][0]['Subject'] == $APIB_Name_array[$j][0]['Subject'] && $preReq[$i][0]['Course_no'] == $APIB_Name_array[$j][0]['Course_no'])
                    { unset($preReq[$i]); break; }                      
                }
            }
        }
        return $preReq; }
    function req_planer_crossCheck($planner, $req){
    	$check_seq = ["Freshman_F", "Freshman_S", "Sophomore_F", "Sophomore_S", "Junior_F", "Junior_S", "Senior_F", "Senior_S"];
    	//Planner check (req)
    	for($i = 0; $i < count($check_seq); $i++){
    		if(count($planner[$check_seq[$i]]) == 1) continue;
    		else{
    			$keys = array_keys($planner[$check_seq[$i]]); unset($keys[0]);
    			for($j = 1; $j < count($keys)+1; $j++)
    				for($k = 0; $k < count($req['Req']); $k++)
    					if(count($req['Req'][$k]) == 1 && $keys[$j] == str_replace(" ", "", $req['Req'][$k])) $req['Req'][$k] = null;
    					else
    						for($l = 0; $l < count($req['Req'][$l]); $l++)
    			 				if($keys[$j] == str_replace(" ", "", $req['Req'][$k][$l])) $req['Req'][$k] = null;
    		}				}
    	//APIB check
    	$regno = $_COOKIE['regno'];
        $query = "SELECT AP_IB FROM User_info WHERE Registration_no = ".$regno;
        $APIB_query = mysql_result(mysql_query($query), 0);
        $APIB_No_array = explode("\n", $APIB_query);
        array_pop($APIB_No_array);
        $APIB_Name_array = array();
        $result = mysql_query("SELECT Equiv_course_no FROM APIB_credit");
        for($i = 0; $i < count($APIB_No_array); $i++) {
           $APIB_equi = mysql_result($result, ($APIB_No_array[$i]) - 1);
           if(strpos($APIB_equi, "/") !== false){
                $or = explode(" / ", $APIB_equi);
                $temp = count($APIB_Name_array);
                for($j = 0; $j < count($or); $j++){
                    $APIB_Name_array[$temp][$j] = [];
                    $APIB_Name_array[$temp][$j] = trim($or[$j]);
                }
           }
           else{
           		$and = explode("\n", $APIB_equi);
           		for($j = 0; $j < count($and); $j++)
           			$APIB_Name_array[] = trim($and[$j]);		
           }
        }
        for($i = 0; $i < count($req['Req']); $i++)
        	for($j = 0; $j < count($APIB_Name_array); $j++)
        		if(count($APIB_Name_array[$j]) == 1 && $req['Req'][$i] == $APIB_Name_array[$j]) $req['Req'][$i] = null;
        		else
        			for($k = 0; $k < count($APIB_Name_array[$j]); $k++)
        				if($req['Req'][$i] == $APIB_Name_array[$j][$k]) $req['Req'][$i] = null;

    	return $req;}
    function course_check($planner, $semester, $course){
        $check_seq = ["Freshman_F", "Freshman_S", "Sophomore_F", "Sophomore_S", "Junior_F", "Junior_S", "Senior_F", "Senior_S"];
        $check_end = array_search($semester, $check_seq);
        $preReq = PreReq_fetch($course); 
        $preReq_size = count($preReq);
        
        if($preReq != "None"){
            if(empty($preReq)) return course_credit($course);
            if($check_end == 0) return 0;
            $classId = str_replace(" ", "", $course);
            for($i = $check_end-1; $i >= 0; $i--){
                if(count($planner[$check_seq[$i]])-1 == 0) continue;
                //echo "Searching ".$check_seq[$i]."\n";
                $semester = $planner[$check_seq[$i]];
                $arrayKeys = array_keys($semester);
                $arrayKeys_size = count($arrayKeys);
                
                for($k = 0; $k < $preReq_size; $k++){
                    if($preReq[$k] == null) continue;
                    if(count($preReq[$k]) > 1){
                        for($l = 0; $l < count($preReq[$k]); $l++){
                            if(strpos($preReq[$k][$l]['Course_no'], "XX") !== false){
                                for($m = 0; $m < $arrayKeys_size; $m++){
                                    if($arrayKeys[$m] == null) continue;

                                    preg_match_all('/\d+/', $arrayKeys[$m], $temp);
                                    $num = $temp[0][0];
                                    $subject = substr($arrayKeys[$m], 0, strpos($arrayKeys[$m], "".$num));                                    
                                    if($subject == $preReq[$k][$l]['Subject'] && $num >= intval(substr($preReq[$k][$l]['Course_no'], 0, 1)) * 100){
                                        unset($preReq[$k]);
                                        unset($arrayKeys[$m]);
                                    }
                                }
                            }
                            else{
                                $searchClass = $preReq[$k][$l]['Subject'].$preReq[$k][$l]['Course_no'];
                                //echo $semester[$searchClass][0]." <-- ".$searchClass."\n\n";
                                if($semester[$searchClass][0] == $searchClass) unset($preReq[$k]); 
                            }
                        }
                    }
                    else
                    {
                        if(strpos($preReq[$k][0]['Course_no'], "XX") !== false){
                            for($l = 0; $l < $arrayKeys_size; $l++){
                                if($arrayKeys[$l] == null) continue;

                                preg_match_all('/\d+/', $arrayKeys[$l], $temp);
                                $num = $temp[0][0];
                                $subject = substr($arrayKeys[$l], 0, strpos($arrayKeys[$l], "".$num));
                                if($subject == $preReq[$k][0]['Subject'] && $num >= intval(substr($preReq[$k][0]['Course_no'], 0, 1)) * 100){
                                    unset($preReq[$k]);
                                    unset($arrayKeys[$l]);
                                }
                            }
                        }
                        else{
                            $searchClass = $preReq[$k][0]['Subject'].$preReq[$k][0]['Course_no'];
                            //echo $semester[$searchClass][0]." <-- ".$searchClass."\n\n";
                            if($semester[$searchClass][0] == $searchClass) unset($preReq[$k]);  
                        }
                    }
                }
            }
            if(!empty($preReq)) return 0; 
            else return course_credit($course);
        }
        else return course_credit($course);}
    function course_credit($course){
        $temp = explode(" ", $course); $subject = $temp[0]; $class_no = $temp[1];
        $query = "SELECT Credit FROM Course_info WHERE Subject = '".$subject."' AND Course_no = '".$class_no."'";
        $credit = mysql_result(mysql_query($query), 0);
        return intval($credit);}
    function course_info($course){
        $course = mysql_real_escape_string($course);
        $temp = explode(" ", $course); $subject = $temp[0]; $class_no = $temp[1];
        $query = "SELECT * from Course_info WHERE Subject = '".$subject."' AND Course_no = '".$class_no."'";
        $res = mysql_query($query);
        $row = mysql_fetch_assoc($res);
        $result = [$row["Subject"].$row["Course_no"], $row["Subject"], $row["Course_no"], $row['Major'], $row['Credit'], $row["GER"], $row["Fall2013"], $row["Spring2014"], trim($row['Course_name'])];
        return $result;}
?>