<?php

/* TODO: make this a regexp, check the number */
function ValidateNumber($number) 
{
	if (preg_match('/\d/', $number)) {
		return($number);
	} else {
		die ("Invalid input");
	}
}

?>
