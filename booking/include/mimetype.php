<?php
$charset = "utf-8";
$mime = "text/html";

function fix_code($buffer) {
   return (str_replace(" />", ">", $buffer));
}

if(stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml")) {
   # if there's a Q value for "application/xhtml+xml" then also 
   # retrieve the Q value for "text/html"
   if(preg_match("/application\/xhtml\+xml;q=0(\.[1-9]+)/i",
                 $_SERVER["HTTP_ACCEPT"], $matches)) {
      $xhtml_q = $matches[1];
      if(preg_match("/text\/html;q=0(\.[1-9]+)/i",
                    $_SERVER["HTTP_ACCEPT"], $matches)) {
         $html_q = $matches[1];
         # if the Q value for XHTML is greater than or equal to that 
         # for HTML then use the "application/xhtml+xml" mimetype
         if($xhtml_q >= $html_q) {
            $mime = "application/xhtml+xml";
         }
      }
   # if there was no Q value, then just use the 
   # "application/xhtml+xml" mimetype
   } else {
      $mime = "application/xhtml+xml";
   }
}

# special check for the W3C_Validator
if (stristr($_SERVER["HTTP_USER_AGENT"],"W3C_Validator")) {
   $mime = "application/xhtml+xml";
}

# set the prolog_type according to the mime type which was determined
if($mime == "application/xhtml+xml") {
   $prolog_type = '<?xml version="1.0" encoding="' . $charset . '" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
';
} else {
   ob_start("fix_code");
   $prolog_type = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
';
}

# finally, output the mime type and prolog type
header("Content-Type: $mime;charset=$charset");
header("Vary: Accept");
print $prolog_type;
?>