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

	$check_seq = ["Freshman_F", "Freshman_S", "Sophomore_F", "Sophomore_S", "Junior_F", "Junior_S", "Senior_F", "Senior_S"];

	$planner = json_decode($_GET['planner']);
	$semester = $_GET['targetSemester'];
	$course = $_GET['course'];

	$check_end = array_search($semester, $check_seq);
	$preReq = PreReq_fetch($course); 
	$preReq_size = count($preReq);

	if($preReq != "None"){
		$classId = str_replace(" ", "", $course);
		for($i = $check_end-1; $i >= 0; $i--){
			if(count(get_object_vars($planner->$check_seq[$i]))-1 == 0) continue;
			
			$semester = get_object_vars($planner->$check_seq[$i]);
			$arrayKeys = array_keys($semester);
			$arrayKeys_size = count($arrayKeys);

			for($k = 0; $k < $preReq_size; $k++){
				if($preReq[$k] == null) continue;
				// The "Or" condition
				if(count($preReq[$k]) > 1){
					for($l = 0; $l < count($preReq[$k]); $l++){
						if(strpos($preReq[$k][$l]['Course_no'], "XX") !== false){
							for($m = 1; $m < $arrayKeys_size; $m++){
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
							if($semester[$searchClass][0] == $searchClass) unset($preReq[$k]); 
						}
					}
				}
				// The "And" condition
				else
				{
					if(strpos($preReq[$k][0]['Course_no'], "XX") !== false){
						for($l = 1; $l < $arrayKeys_size; $l++){
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
						if($semester[$searchClass][0] == $searchClass) unset($preReq[$k]);	
					}
				}
			}
		}
		if(empty($preReq)) echo 1;
		else{
			for ($i = 0; $i < $preReq_size; $i++) { 
				if($preReq[$i] == null) continue;
				if(count($preReq[$i]) > 1){
					echo "- Missing ";
					for($j = 0; $j < count($preReq[$i]); $j++){
						if($j == count($preReq[$i]) - 1)
							echo $preReq[$i][$j]['Subject']." ".$preReq[$i][$j]['Course_no']." (".$course.")"."<br>";
						else
							echo $preReq[$i][$j]['Subject']." ".$preReq[$i][$j]['Course_no']." or ";
					}
				}
				else
				{
					echo "- Missing ".$preReq[$i][0]['Subject']." ".$preReq[$i][0]['Course_no']." (".$course.")"."<br>";
				}
			}
		}
	}
	else echo 1;

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

		return $preReq; 
	}
?>