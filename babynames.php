<?php
$name = $_POST["babyname"];
$name = strtoupper($name);
$type = $_POST["type"];
if($type == "meaning"){
	$txt_file = file_get_contents('meanings.txt');
	$rows = explode("\n", $txt_file);	
	$found = false;
	$returnStr = "";
	foreach($rows as $row){
		//echo $row;
		//Name found, end loop
		if($found){
			break;
		}

		$content = explode(" ", $row);
		//echo $content[0] ." : ";
		if($content[0]==$name){
			$returnStr .= "<div><p>The name <strong>".$name."</strong> means...</p><hr/><p><q>";
//			echo "here!!!!: ".$content->count();
			for($i = 1; $i < sizeof($content); $i++){
				$returnStr .= $content[$i]." ";
			}
			$returnStr .= "</q></p></div>";
			$found=true;	
		}
			
	}
	echo $returnStr;
}
?>