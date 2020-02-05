<?php

 /**

    * This function can be used to check the sanity of variables
    *
    * @access private
    *
    * @param string $type  The type of variable can be bool, float, numeric, string, array, or object
    * @param string $string The variable name you would like to check
    * @param string $length The maximum length of the variable
    *
    * return bool
    */
function sanityCheck($string, $type, $length){

  // assign the type
  $type = 'is_'.$type;

  if(!$type($string))
    {
    return FALSE;
    }
  // now we see if there is anything in the string
  elseif(empty($string))
    {
    return FALSE;
    }
  // then we check how long the string is
  elseif(strlen($string) > $length)
    {
    return FALSE;
    }
  else
    {
    // if all is well, we return TRUE
    return TRUE;
    }
}


// check number is greater than 0 and $length digits long
  // returns TRUE on success
  function checkNumber($num, $length){
  if($num > 0 && strlen($num) == $length)
    {
    return TRUE;
    }
}

function OnlyWhiteSpaces($str){
    
    if (strlen(trim($str)) == 0){
        return true;
    }
    else{
        return false;
    }
}

function TrimString($str){
    
    $str = trim($str);
    $str = stripslashes($str);
    $str = htmlspecialchars($str);
    
    return $str;
    
}
