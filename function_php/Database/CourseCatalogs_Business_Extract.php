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

  	$url = "http://goizueta.emory.edu/degree/undergraduate/curriculum/descriptions.html";
  	$Sourcecode = file_get_contents($url);
  	$start = strpos($Sourcecode, "<section class=\"accordion-group\" id=\"section1\">") + strlen("<section class=\"accordion-group\" id=\"section1\">"); 
    $end = strpos($Sourcecode, "<p><em>All courses with a G fulfill the global distribution requirement</em></p></div></div></section></div></div>");
  	$Extracted_html = substr($Sourcecode, $start, ($end - $start));
  	$temp = str_replace('"collapse" href="', "", $Extracted_html);

  	//echo $Sourcecode;

    //echo $Extracted_html;

    for($j = 0; $j < substr_count($Extracted_html, '</li>') - 1; $j++)
    {
      $course_info = courseinfoExtraction($Extracted_html, $j, substr_count($Extracted_html, '</li>'));
      $course_url = "http://goizueta.emory.edu/".courseurlExtraction($temp, $j, substr_count($temp, '</li>'));
      $descriptions = file_get_contents($course_url);
      $starting = strpos($descriptions, '<div class="course-description">') + strlen('<div class="course-description">'); 
      $ending = strpos($descriptions, ".</div>");
      $Extracted_descriptions = substr($descriptions, $starting, ($ending - $starting));

      if (substr($course_info,(strpos($course_info, "BUS")+3),6) == "&#160;") {
      	$course_info = str_replace("&#160;", " ",$course_info);
      }

      if (strpos($course_info, "&amp;") !== false) {
      	$course_info = str_replace("&amp;", "&",$course_info);
      }

      $title = substr($course_info, strpos($course_info, "- ") + 2);
      $course_no = substr($course_info, 4, (strpos($course_info," -")-4));
      $course = array("subject" => "BUS","course_no" => $course_no,"course_title" => $title,"course_description" => $Extracted_descriptions);


      //-----------------------------------Input to Database---------------------------------------------------------------------------
      $insert_query = "INSERT INTO Course_info (Subject, Course_no, Course_name, Credit, GER, PreReq, CoReq, Crosslisted, Course_description)
                                        VALUES ('BUS', '$course_no', '$title', 3, 'NONE', 'NONE', 'NONE', 'NONE', '$Extracted_descriptions')";
      echo $insert_query.'<br>';
      $insert_check = mysql_query($insert_query); 

    }
     //echo $temp;

    function courseinfoExtraction($htmlcode, $sequence, $endsequence) //strip Extracted_html into course info without any html tags
  {
    $start_course = 0; $end_course = 0;
    if($sequence == 0)
    {
      $start_course = strpos($htmlcode, ">BUS") + 1;
      $end_course = strpos($htmlcode, "</a>", $start_course+1);
    }
    else
    {
      for($c = 0; $c < $sequence+1; $c++)
      {
        $start_course = strpos($htmlcode, ">BUS", $end_course) + 1;
        $end_course = strpos($htmlcode, "</a>", $start_course+1);
      }
    }

    if($endsequence-1 == $sequence)
      {$Extracted_course = substr($htmlcode, $start_course);}
    else
      {$Extracted_course = substr($htmlcode, $start_course, ($end_course-$start_course));}
    
    //return preg_replace('#<[^>]+>#', ' ', $Extracted_course);
    return $Extracted_course;
  }

    function courseurlExtraction($htmlcode, $sequence, $endsequence) //strip Extracted_html into course info without any html tags
  {
    $start_url = 0; $end_url = 0;
    if($sequence == 0)
    {
      $start_url = strpos($htmlcode, 'href="', $end_url) + 15;
      $end_url = strpos($htmlcode, '">BUS', $start_url);
    }
    else
    {
      for($c = 0; $c < $sequence+1; $c++)
      {
        $start_url = strpos($htmlcode, 'href="', $end_url) + 15;
        $end_url = strpos($htmlcode, '">BUS', $start_url);
      }
    }

    if($endsequence-1 == $sequence)
      {$Extracted_url = substr($htmlcode, $start_url);}
    else
      {$Extracted_url = substr($htmlcode, $start_url, ($end_url-$start_url));}
    
    //return preg_replace('#<[^>]+>#', ' ', $Extracted_course);
    return $Extracted_url;
  }