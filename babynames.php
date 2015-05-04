<?php
$name = $_POST["babyname"];

$type = $_POST["type"];

if($type == "list"){
	Header('Content-type: text/html');
    //header("Content-Type: text/plain");
    $returnStr = "";
    $lines = file_get_contents("list.txt");
    $rows = explode("\n", $lines);
    foreach($rows as $row){
        $returnStr .= "<option value=\"".$row."\" >".$row."</option>";
    }
    echo $returnStr;
}

else if($type == "meaning"){
	Header('Content-type: text/html');
	$name = strtoupper($name);
	$txt_file = file_get_contents('meanings.txt');
	$rows = explode("\n", $txt_file);	
	$found = false;
	$returnStr = "";
	foreach($rows as $row){

		if($found){
			break;
		}

		$content = explode(" ", $row);

		if($content[0]==$name){
			$returnStr .= "<div><p>The name <strong>".$name."</strong> means...</p><hr/><p><q>";
			for($i = 1; $i < sizeof($content); $i++){
				$returnStr .= $content[$i]." ";
			}
			$returnStr .= "</q></p></div>";
			$found=true;	
		}
			
	}
	if($found == false){
		$returnStr = "There is no meaning for the name <strong>".$name."</strong>";	
	}
	echo $returnStr;
}
else if ($type == "rank") {
     $gender = $_POST["gender"];
     $txt_file = file_get_contents('rank.txt');
     $rows = explode("\n", $txt_file);
     $found2 = false;
     $xml = $name;
     $returnStr = $name;
     foreach($rows as $row) {

          if ($found2) {
               break;
          }

          $content = explode(" ", $row);
          if (strcmp($content[0],$name) == 0 && strcmp($content[1],$gender) == 0) {
          
               $xml = new SimpleXMLElement("<baby></baby>");
               $xml->addAttribute('name',$name);
               $xml->addAttribute('gender',$gender);
               $year = 1900 - 10*(sizeof($content)-14);
               for ($i = 2; $i < sizeof($content); $i++) {
                 $rank = $xml->addChild('rank',$content[$i]);
                 $rank->addAttribute('year',$year);
                 $year += 10;
               }//end for loop

               $found2 = true;
          }//end if

     }//end foreach
     Header('Content-type: text/xml');
     echo $xml->asXML();
	 

}//end rank/popularity

else{
	Header('Content-type: text/html');
	$name = $_GET['name'];
	$gender = $_GET['gender'];
	$type = $_GET['type'];
	$actors = '{"actors":[';
	
	$user = 'root';
	$pass = 'databases';
	$dbh = new PDO('mysql:host=localhost;dbname=imdb', $user, $pass);
	
	if($type == "celebs") {
	//Gets the searched actor's id
		$q1 = "SELECT first_name, last_name, film_count
				FROM actors
				WHERE ((first_name LIKE '" . $name . " %' OR first_name = '" . $name . "')
					AND gender = '" . $gender . "') ORDER BY film_count DESC";
		foreach ($dbh->query($q1) as $row) {
			$actors .= '{"firstName": "' . $row[0] . '", "lastName": "' . $row[1] . '", "filmCount": "' . $row[2] . '"},';
		}
		$dbh = null;
		$actors = substr($actors, 0, strlen($actors)-1);
		$actors .= "]}";
		echo $actors;
	}
}
?>