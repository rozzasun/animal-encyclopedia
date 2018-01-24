<?php

	if (isset($_GET['name']))    
	{   
		$input=$_GET['name'];

		$myPDO = new PDO('sqlite:sqlite3/AEdatabase.db');
			
		#selects the description from the row with the given name
		$result = $myPDO->query("SELECT Description FROM animals_db WHERE Name = $input ");
						
	}

	if(isset($result))
	{
		echo $result;
	}
?>