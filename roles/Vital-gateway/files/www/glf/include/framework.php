<?php

function Line($line) {  return($line."\n\r"); }

function WebLn($line) { return($line.'<br />'); }

function Text($line) { return(WebLn(Line($line))); }

function Identity($x) { return($x); }

function CreateDropdown($name, $arr) 
{
	$ret = '';
	$ret .= Line('<select style="width: 150px" name="'.$name.'">');
        $ret .= Line('<option style="width: 140px" value="">Select...</option>');
	foreach ($arr as $key => $value) 
	{
		$ret .= Line('<option value="'.$key.'">'.$value.'</option>');
	}
	$ret .= Line('</select><br><p>');
	return($ret);
}

function NumberArray($number)
{ 
	$array = array();
	for ($i = 1; $i <= $number; $i++) { $array[$i] = $i; }	
	return($array);
}


function Element($header, $middle, $footer)
{
	return(Line($header.$middle.$footer));
}

function ECMA($script) { return(Element("<script>", $script, "</script>")); }

function BoldLine($str) { return(Element("<b>", $str, "</b>")); }

function Head($title)
{
	$charset = ('<meta charset="utf=8" />');
	return(Element("<head>\n<title>".$charset, $title, "</title>\n</head>"));
}

function Body($str)
{
	return(Element(Line('<body>'), Line($str), '</body>'));
}

function Page($title, $str)
{
	return(Line('<html lang="en">').Head($title).Body($str).'</html>');
}


function TextInput($q, $varname)
{
	return(Line(WebLn(($q.'<input type="text" name="'.$varname.'" />'))));
}

function Form($action, $method, $str) 
{ 
	$top     = Line('<form action='.$action.' method="'.$method.'">'); 
	$bottom  = Line('<input type="submit" value="SUBMIT">');
	$bottom .= Line('</form>'); 
	return(Element($top, $str, $bottom));
}

function H1($str) { return(Line(Element('<h1>', $str, '</h1>'))); }
function H3($str) { return(Line(Element('<h3>', $str, '</h3>'))); }

function Hide($key, $value) 
{
	return(Line('<input name="'.$key.'" type="hidden" id="'.$key.'" value="'.$value.'">'));	
}

//function StickyPost($post)
function StickyPost()
{
	$post = $_POST;
	$ret = '';
	foreach ($post as $key => $value) 
	{
		//echo $key.'=>'.$value.'<br>';
		$ret .= Hide($key, $post[$key]);	
	}
	return($ret);
}


?>
