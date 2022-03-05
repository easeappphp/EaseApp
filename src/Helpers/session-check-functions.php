<?php
  

//check to know if there is an active session and redirect them accordingly (page which works before login).
function if_session_is_valid($valid_session_keyword, $to_be_loaded_filename, $login_function_project_main_filename)
{
  if( (!isset($valid_session_keyword)) )
  {
		$valid_session_keyword = "no";
		//echo "you are not authorised to access this page";
		return $to_be_loaded_filename;
  }
  elseif( (isset($valid_session_keyword)) && ($valid_session_keyword != 'yes') )
	{
		$valid_session_keyword = "no";
		return $to_be_loaded_filename; 
	}
  elseif( (isset($valid_session_keyword)) && ($valid_session_keyword == 'yes') )
	{
		return $login_function_project_main_filename;
	}
			
}

//check to know if there is an active session and route the request to pages (which open only to logged in users)
function is_session_valid($valid_session_keyword, $to_be_loaded_filename){
  if( (!isset($valid_session_keyword)) )
  {
		$valid_session_keyword = "no";
		//echo "you are not authorised to access this page";
		return "need-authentication.php";
  }
  elseif( (isset($valid_session_keyword)) && ($valid_session_keyword != 'yes') )
	{
		$valid_session_keyword = "no";
		//echo "you are not authorised to access this page";
		return "need-authentication.php"; 
	}
  elseif( (isset($valid_session_keyword)) && ($valid_session_keyword == 'yes') )
	{
		return $to_be_loaded_filename;
	}
			
}
?>