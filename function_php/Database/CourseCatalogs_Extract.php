<?php
  // 1. Delete any space in the variables (in front of the first letter)
  session_start();
  $_SESSION['session'] = 1;
  $link = mysql_connect ('localhost', 'emorysolutions', 'henrychenniharparikh', 'majorman') or die (mysql_error());

  if(!@mysql_select_db('majorman', $link))
  {
    echo "<p>This is an error message: System cannot connect to database.</p>";
    echo "<p><strong>" . mysql_error() . "</strong></p>";
    echo "Please email emorysolutions@emorysolutions.org for support.";
  }

  $subjectKeys = array(
    "AAS", "AFS", "AMST", "ANCMD", "ANT", "ARAB", "ARTHIST", "ARTVIS", "BIOL", "CBSC", "CHEM", "CHN", "CL", "CPLT", 
    "CS", "DANC", "EAS", "ECON", "ECS", "EDS", "ENG", "ENGCW", "ENVS", "FILM", "FREN", "GER", "GHCS", "GRK", "HEBR",
    "HIST", "HLTH", "HNDI", "IDS", "ITAL", "JPN", "JRNL", "JS", "KRN", "LACS", "LAT", "LING", "MATH", "MESAS", "MUS",
    "NBB", "PACE", "PE", "PERS", "PHIL", "PHYS", "POLS", "PORT", "PSYC", "QTM", "REES", "SOC", "SPAN", "TBT", "THEA", "WGS", "YDD");

// Main Body
//---------------------Extract Deparment Data (Department name + url)---------------------------------------------------------------
    $Extracted_html = sourcecodeExtraction("http://catalog.college.emory.edu/department-program/departments/index.html", "Index");
    $department = array(); $department_url = array();

  while(strpos($Extracted_html, "<a") !== false)
  {
    $start = strpos($Extracted_html, "<a"); 
    $end = strpos($Extracted_html, "</a>"); 
    $temp = substr($Extracted_html, $start, ($end + 4 - $start));

    $department[] = substr($temp, strpos($temp, "\">") + 2, (strpos($temp, "</a>") - (strpos($temp, "\">") + 2)));
    $department_url[] = substr($temp, strpos($temp, "href=\"") + 6, (strpos($temp, "\">") - (strpos($temp, "href=\"") + 6)));
    $Extracted_html = str_replace($temp, "", $Extracted_html);
  }
//-----------------------------------------------------------------------------------------------------------------------------------
//---------------------Extract Courses Data from Departments-------------------------------------------------------------------------
  $numofDepartments = count($department);

// Extract each departmants' html codes
  for($i = 0; $i < $numofDepartments; $i++) //Loop to run every department
  {
    $Extracted_html = sourcecodeExtraction("http://catalog.college.emory.edu/department-program/departments/" . $department_url[$i] , "Department");
    $Extracted_html = substr($Extracted_html, strpos($temp, "<dt>"));
// Extract course info
    for($j = 0; $j < substr_count($Extracted_html, "<dd class=\"course\">"); $j++)
    {
      $course_info = courseinfoExtraction($Extracted_html, $j, substr_count($Extracted_html, "<dd class=\"course\">"));
      
      $title = substr($course_info, 0, strpos($course_info, "General Information"));
          $courseid = str_replace(" ", "", substr($title, 0, strpos($title, ":")));
          for($c = 0; $c < strlen($courseid); $c++){ if($courseid{$c} < '9' && $courseid{$c} > '0'){ break;}}  
      $subject = substr($courseid, 0, $c);
      $course_no = substr($courseid, $c);
      $course_name = substr($title, strpos($title, ": ")+2);
            $course_name = str_replace("\\", "\\\\", $course_name);
            $course_name = str_replace("\"", "\\\"", $course_name);
            $course_name = str_replace('\'', '\\\'', $course_name);
      $credit = substr($course_info, strpos($course_info, "Credit Hours ")+13, 2);
      
      if(strpos($course_info, "Variable GER") !== false)
      { $GER = substr($course_info, strpos($course_info, "GERs ")+4, (strpos($course_info, "Variable GER") - (strpos($course_info, "GERs ")+4)));
        $VariableGER = substr($course_info, strpos($course_info, "Variable GER ")+13, (strpos($course_info, "Pre-Requisites") - (strpos($course_info, "Variable GER ")+13)));}
      else { $GER = substr($course_info, strpos($course_info, "GERs ")+4, (strpos($course_info, "Pre-Requisites") - (strpos($course_info, "GERs ")+4)));}
          $GER = trim($GER);


      $PreReq = substr($course_info, strpos($course_info, "Pre-Requisites ")+15, (strpos($course_info, "Co-Requisites") - (strpos($course_info, "Pre-Requisites ")+15)));
          $PreReq = trim($PreReq);
      $CoReq = substr($course_info, strpos($course_info, "Co-Requisites ")+14, (strpos($course_info, "Cross-Listed") - (strpos($course_info, "Co-Requisites ")+14)));
          $CoReq = trim($CoReq);
      $CrossListed = substr($course_info, strpos($course_info, "Cross-Listed ")+13, (strpos($course_info, "Course Description") - (strpos($course_info, "Cross-Listed ")+13)));
            $CrossListed = str_replace("\\", "\\\\", $CrossListed);
            $CrossListed = str_replace("\"", "\\\"", $CrossListed);
            $CrossListed = str_replace('\'', '\\\'', $CrossListed);
          $CrossListed = trim($CrossListed);
      $course_description = substr($course_info, strpos($course_info, "Course Description ")+19, (strpos($course_info, "Contact Hour Information") - (strpos($course_info, "Course Description ")+19)));
            $course_description = str_replace("\\", "\\\\", $course_description);
            $course_description = str_replace("\"", "\\\"", $course_description);
            $course_description = str_replace('\'', '\\\'', $course_description);
          $course_description = trim($course_description);
          if($course_description == NULL){ $course_description = "None";}
      //echo $subject." ".$course_no." ".$course_name." ".$credit." ".$GER." ".$PreReq." ".$CoReq." ".$CrossListed."<br>".$course_description."<br>";
      //echo $course_info;    
    //-----------------------------------Input to Database---------------------------------------------------------------------------
      $insert_query = "INSERT INTO Course_info (Subject, Course_no, Course_name, Credit, GER, PreReq, CoReq, Crosslisted, Course_description)
                                        VALUES ('$subject', '$course_no', '$course_name', '$credit', '$GER', '$PreReq', '$CoReq', '$CrossListed', '$course_description')";
      $insert_check = mysql_query($insert_query); 
    //-----------------------------------Entry Error Report--------------------------------------------------------------------------
      if(!$insert_check){ echo $courseid." input failed"."<br>"; echo $subject." ".$course_no." ".$course_name." ".$credit." ".$GER." ".$PreReq." ".$CoReq." ".$CrossListed."<br>".$course_description."<br>";}
    }
  }
//-----------------------------------------------------------------------------------------------------------------------------------





//Functions
//-----------------------------------------------------------------------------------------------------------------------------------
  function sourcecodeExtraction($url, $type) // $type = "Index"(Isolate departments) / "Department" (isolate courses)
  {
    $Sourcecode = file_get_contents($url);

    if($type == "Index")
    {
      $start = strpos($Sourcecode, "<div id=\"department-listing\""); 
        $end = strpos($Sourcecode, "<div id=\"rightCol\">");
        return substr($Sourcecode, $start, ($end - $start));
      }
      else if($type == "Department")
      {
        $start = strpos($Sourcecode, "<h2>Courses</h2>");
        $temp = substr($Sourcecode, $start+16);

        if(strpos($temp, "<h2>") !== true)
        { return substr($temp, 0, strpos($temp, "<h2>"));}
        else
        { return substr($temp, 0, strpos($temp, "<script"));}
      } 
  }

  function courseinfoExtraction($htmlcode, $sequence, $endsequence) //strip Extracted_html into course info without any html tags
  {
    $start_course = 0; $end_course = 0;
    if($sequence == 0)
    {
      $start_course = strpos($htmlcode, "<dd class=\"course\">");
      $end_course = strpos($htmlcode, "<dd class=\"course\">", $start_course+19);
    }
    else
    {
      for($c = 0; $c < $sequence+1; $c++)
      {
        $start_course = strpos($htmlcode, "<dd class=\"course\">", $end_course);
        $end_course = strpos($htmlcode, "<dd class=\"course\">", $start_course+19);
      }
    }

    if($endsequence-1 == $sequence)
      {$Extracted_course = substr($htmlcode, $start_course);}
    else
      {$Extracted_course = substr($htmlcode, $start_course, ($end_course-$start_course));}
    
    return preg_replace('#<[^>]+>#', ' ', $Extracted_course);
  } 
//-----------------------------------------------------------------------------------------------------------------------------------
?>