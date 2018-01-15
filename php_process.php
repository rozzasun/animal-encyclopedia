<?php

	if (isset($_GET['input']))    
	{   
		$input=$_GET['input'];

		$myPDO = new PDO('sqlite:sqlite3/AEdatabase.db');
			
		#selects rows which has columns containing strings LIKE the inputted text
		$result = $myPDO->query("SELECT Name FROM animals_db WHERE Category LIKE '%$input%' OR Diet LIKE '%$input%' OR Tags LIKE '%$input%' OR ScientificName LIKE '%$input%' OR Name LIKE '%$input%' ");
						
		#declares a php array called "names"
		$names = array();

		#loops through the result from the query and append the Name of each row into the "names" array
		foreach($result as $row)
		{
			$names[]=$row["Name"];
		}

	} 

	if(isset($names))
	{
		echo json_encode($names);
		#var_dump(json_encode($names));
	}
?>