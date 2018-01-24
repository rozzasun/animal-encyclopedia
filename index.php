<?php
    $myPDO = new PDO('sqlite:sqlite3/AEdatabase.db');
?>
<html>
	<head>
		<!--info-->
		<meta charset=utf-8>
		<meta name="description" content="WebVR Animal Encyclopedia">
  		<meta name="keywords" content="HTML,CSS,JavaScript">
  		<meta name="author" content="Rosa Sun, Karen Fei">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>WebVr Animal Encyclopedia</title>

		<!--sources/libraries/APIs-->
		<script src="https://aframe.io/releases/0.7.0/aframe.min.js"></script>
		<script src="https://rawgit.com/feiss/aframe-environment-component/master/dist/aframe-environment-component.min.js"></script>
		<script src="https://rawgit.com/chenzlabs/auto-detect-controllers/master/dist/aframe-auto-detect-controllers-component.min.js"></script>
		<script src="https://unpkg.com/aframe-text-geometry-component@^0.5.0/dist/aframe-text-geometry-component.min.js"></script>
    	<script src="js/aframe-extras.js"></script>
    	<script src="js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript">
			
			//function called to open/close the sidebar depending on its current state
			function togNav() {

				//if the side bar is opened, aka its width isn't 0px, then make its width 0px and remove the shadow
				if (document.getElementById("mySidenav").style.width!="0px"){
					document.getElementById("mySidenav").style.width = "0";
			    	document.getElementById("mySidenav").style.boxShadow = "none";

			    //otherwise, change the width from 0 to 250px, and add the boxShadow
				}else{
					document.getElementById("mySidenav").style.width = "250px";
			    	document.getElementById("mySidenav").style.boxShadow = "5px 0px 30px 10px rgba(0, 0, 0, .7)";
				}
			}

			//resets the navigation bar (empties the search bar, hides the blocks and shows the collapsible lists)
			function resetNav(clear) {

				//shows the collapsible lists
				document.getElementById("v_li").style.display='block';
				document.getElementById("i_li").style.display='block'; 

				//declares an "array" which contains the list of animals
				var elems=document.getElementById("all_list").getElementsByTagName("li");
				
				//sets display of all the <li> elements from the above array to none (hides them)
				for (var i=0;i<elems.length;i+=1){
					elems[i].style.display = 'none';
				}

				//clears search bar when the argument passed is "true"
				if (clear){
					document.getElementById("searchBar").value="";					
				}

			}

			//function which updates the results list as the user types (called on keyUp of the <input> element)
			function updateList() {

				//as soon as the function is called from the HTML code, the following line saves the keyCode of the inputed character in a variable named "n"
				n = event.keyCode;

				//input variable contains the string that is currently in the search bar
				var input = document.getElementById("searchBar").value;
				
				//checks if the search bar is empty
				//if it's empty, then the resetNav() function is called to reset the navigation bar to its default state
				if (input==""){

					resetNav(false);

					//return statement used to exit the function
					return;
				
				}

				//hides the default collapsible menu
				document.getElementById("v_li").style.display='none';
				document.getElementById("i_li").style.display='none'; 
					
				//saves all the <li> elements (list of animal names that appear when user searches) in an array called elems
				var elems = document.getElementById("all_list").getElementsByTagName("li");
					
				//hides each element in the elems array by looping through the array
				for (var i=0; i<elems.length; i+=1){

					elems[i].style.display = 'none';
					
				}



				$.ajax({
					
					//path to the other PHP file which accesses the database
					url: 'php_process.php', 
					
					type: 'GET',

					//passes the input variable declared in JS to the PHP file using the name "input"
					data: { input: input }, 

					//the data variable is what the PHP file echoes
					success: function(data) {

						//reads the output of the PHP file (echoed in JSON format) and 
						//transforms the string into a javascript array
						var list=JSON.parse(data);
						
						//if there are results for the user input, display new list of results using the display attribute
						if (list.length!=0){

							for (i=0;i<list.length;i++){

								document.getElementById(list[i]).style.display='block';
								
							}

						//if there are no results for the search query, then reset the navigation to its default display using resetNav()
						}else{

							resetNav(false);

							//if the user pressed the enter key, then page will alert them saying:
							if (n == 13){

								alert("No results match the search request");
								document.getElementById("searchBar").value="";//wipe text field

							}

						}

					}
				});

			}

			function showAnimal(filepath, name, scientificName, diet, category){
				/* for debugging
				console.log("filepath: "+filepath);
				console.log("name: "+name);
				console.log("scientificName: "+scientificName);
				console.log("diet: "+diet);
				console.log("category: "+category);
				*/

				//calls the js script which has the code to display the animal in the correct environment
				$.getScript('models-js/'+filepath.toLowerCase(), function(){

					//following lines change the text in the 3D scene using the setAttribute() function
					document.querySelector('#vr-name').setAttribute('text-geometry', 'value', name.toUpperCase());
					document.querySelector('#vr-s-name').setAttribute('text-geometry', 'value', "Scientific Name: "+scientificName);
					document.querySelector('#vr-diet').setAttribute('text-geometry', 'value', "Diet: "+diet);
					document.querySelector('#vr-category').setAttribute('text-geometry', 'value', "Category: "+category);
				
				});

				//change the text of the animal page based on the results received from the database
				document.getElementById("homeScreen").style.display='none';
				document.getElementById("animalScreen").style.display='block';
				document.getElementById("a-name").innerHTML="Name: "+ name;
				document.getElementById("a-s-name").innerHTML="Scientific Name: "+ scientificName;
				document.getElementById("a-diet").innerHTML="Diet: "+ diet;
				document.getElementById("a-category").innerHTML="Category: "+category;

				
				document.querySelector('#image').setAttribute('src', 'img/all/'+name+'.jpg');

				//since the descriptions stored in the database usually contain commas, quotes or other key JavaScript/php characters, I am unable to pass it as a parameter to the showAnimal() function like I did with the other variables
				//this solution works as when the page loads, <Name>.txt files are dynamically created, containing the description from the database. This jQuery function gets the content of the .txt file and passes it back to the JavaScript as a variable called txt
				jQuery(function($){

				  $.get("description/"+name+".txt", function(txt) {

					$('#a-description').text(txt);

				  })
				})

				//hide the search bar and reset the navigation bar to its default look using the togNav() and reset(true) function
				togNav();
				resetNav(true);

			}
		</script>
		<style type="text/css">
		canvas {
			width: $(window).width() * 0.90;
			height: $(window).height * 0.90;
		}

		body {
			font-family: 'Verdana';
			background-color: #777ab5;
			overflow-x: hidden;
		}

		*{padding:0; margin:0;}

		.topnav {
		  overflow: hidden;
		  position:sticky;
		  background-color: #0f1239;
		  box-shadow: 0px 5px 30px 10px rgba(0, 0, 0, .7);
		  top:0;
		  width:100vw;
		  height:14vh;
		  z-index: 1;
		}

		.topnav a {
		  margin-top:1vh;	
		  float: left;
		  color: #f2f2f2;
		  vertical-align: middle;
		  padding: 0 18px;
		  text-decoration: none;
		}

		#homeScreen p {
			padding-top: 1.5vh;
			margin: 12px 12px 12px 12px;
		}

		.sidenav {
			height: 86vh;
		    overflow-y: auto;
		    overflow-x: hidden;
		    position: fixed;
		    z-index: 2;
		    left: 0;
		    background-color: #0f1239;
		    transition: 0.5s;
		    padding-top: 10px;
		}

		.sidenav a {
		    text-decoration: none;
		    color: #ecf2ff;
		    display: block;
		    transition: 0.3s;
		}

		.sidenav a:hover {
		    color: #f1f1f1;
		    background-color: #1c1e4e;
		}

		.main_nav_ul {
			margin-bottom: 3vh;
		}

		.main_nav_ul a {
			margin:0;
			text-decoration: none;
			display: block;
			padding: 10px 0 10px 20px;
			border-bottom:1px grey;
			font-size: 3.8vh;
		}

		.sidenav {width: 0px; margin: 0;}

		.main_nav_ul ul {display: none;}
		.main_nav_ul li:tap ul {display: block;}
		.sidenav .sub-arrow:after {
			content: '\203A';
			float: right;
			margin-right: 20px;
			transform: rotate(90deg);
			-webkit-transform: rotate(90deg);
			-moz-transform: rotate(90deg);			
		}
		.sidenav .sub-arrow1:after {
			content: '\203A';
			float: right;
			margin-right: 20px;
			transform: rotate(90deg);
			-webkit-transform: rotate(90deg);
			-moz-transform: rotate(90deg);			
		}
		.v_sub li a {
			margin-left: 25px;
			font-size: 3.4vh;
		}

		.i_sub li a {
			margin-left: 25px;
			font-size: 3.4vh;
		}

		.all li {
			padding: 10px 10px 10px 10px;
			display:none;
		}

		.all li a {
			padding: 10px 10px 10px 10px;
		}

		.lbl { 
			color:#fa0;
			font-size:2.6vh;
			font-weight:bold;
			position: absolute;
			bottom:5%; z-index:100;
			text-shadow:#000 3px 3px 3px;
			background-color:rgba(0,0,0,0.3);
			padding:1em;
			display: none;
		}

		#intro:hover {
			text-decoration: underline;
			cursor: pointer;
		}

		.title {
			margin: 1vh 0 1vh 0;
			font-size: 5vh; 
			font-weight: bold; 
			text-align: center;
			text-shadow: 1px 1px #A9A9A9;
			color: #0F1239;
		}

		.subTitle {
			font-size: 3.5vh;
			margin: 4vh 0 2.5vh 0;
			font-weight: bold;
		}

		#info_display { text-align:left; right:0px }
		.c { color:#fff }
		.n { color:#fff }
		.s { color:#fff }
		.d { color:#fff }

		@media screen and (max-height: 450px) {
		  .sidenav {padding-top: 15px;}
		  .sidenav a {font-size: 18px;}
		}

		input[type=text] {
		    width: 100%;
		    box-sizing: border-box;
		    border: 2px solid #ccc;
		    border-radius: 4px;
		    font-size: 3.4vh;
		    background-color: white;
		    background-image: url('img/searchIcon.png');
		    background-size: 3.4vh  3.3vh;
		    background-position: 10px 10px; 
		    background-repeat: no-repeat;
		    padding: 12px 20px 12px 40px;
		}



		input::-webkit-calendar-picker-indicator {
		  display: none;
		}

		nav,body::-webkit-scrollbar {
		    width: 10px;
		}

		/* Track */
		nav,body::-webkit-scrollbar-track {
		    box-shadow: inset 0 0 5px grey; 
		    border-radius: 6px;
		}

		/* Handle */
		nav,body::-webkit-scrollbar-thumb {
		    background: #FFF; 
		    border-radius: 6px;
		}

		/* Handle on hover */
		nav,body::-webkit-scrollbar-thumb:hover {
		    background: rgb(220, 220, 220); 
		}


		</style>
	</head>


	<body>	

		<!--top bar-->
		<div class="topnav">

			<a id="threeLines" style="font-size:7vh;cursor:pointer" onclick="togNav()">&#9776;</a>

			<a><p style="font-size:6vh">Animal Encyclopedia</p>

			<p style="font-size:3vh">WebVr</p></a>

			<a id="intro" style="float:right; font-size: 4vh; margin: 3vh 10px 0 0px; vertical-align: middle" onclick="document.getElementById('animalScreen').style.display='none'; document.getElementById('homeScreen').style.display='block';">Introduction</a>

			<!--div for custom scroll bar-->
			<div class="scrollbar" id="style-1">

			  <div class="force-overflow"></div>
			
			</div>

		</div>


		<!--side navigation bar-->
		<nav id="mySidenav" class="sidenav" style="width:0px;">

			<!--search text field-->
			<input id="searchBar" list="animals" type="text" name="searchBar" placeholder="Search..." onkeyup="updateList()"></input>

			<!--collapsible, categorized list in alphabetical order-->
			<ul id="main_nav_ul" class="main_nav_ul">

				<!--vertebrate list-->
				<li id="v_li"><a id="vertebrates" href="#" onclick="$(this).parent().find('ul.v_sub').toggle();">Vertebrates<span class="sub-arrow"></span></a>
					<ul class="v_sub">

						<!--fish sub list-->
				    	<li><a href="#"  onclick="$(this).parent().find('ul.sub').toggle();"">Fishes</a>
				    		<ul class="sub">

				    			<!--loop through all the fishes from the database and dynamically create an <li> for all of them-->
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Fish' ORDER BY Name");
					    			foreach($result as $row)
									{
										#pass the column values from the database as parameters to the javascript function showAnimal()
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";
										
										$ending='.txt';
										$file="description/{$row["Name"]}{$ending}";
										
										#create/override a file called <Animal Name>.txt in the description/ directory
										file_put_contents($file, $row["Description"]);
									}
								?>
				    		</ul>
				    	</li>

				    	<!--amphibians sub list-->
				    	<li><a href="#"  onclick="$(this).parent().find('ul.sub').toggle();">Amphibians</a>
				    		<ul class="sub">

				    			<!--loop through all the amphibians from the database and dynamically create an <li> for all of them-->
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Amphibian' ORDER BY Name");
					    			foreach($result as $row)
									{

										#pass the column values from the database as parameters to the javascript function showAnimal()
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";

										$ending='.txt';
										$file="description/{$row["Name"]}{$ending}";
								
										#create/override a file called <Animal Name>.txt in the description/ directory
										file_put_contents($file, $row["Description"]);
									}
								?>
				    		</ul>
				    	</li>

				    	<!--reptiles sub list-->
				    	<li><a href="#" onclick="$(this).parent().find('ul.sub').toggle();">Reptiles</a>
				    		<ul class="sub">

				    			<!--loop through all the reptiles from the database and dynamically create an <li> for all of them-->
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Reptile' ORDER BY Name");
					    			foreach($result as $row)
									{

										#pass the column values from the database as parameters to the javascript function showAnimal()
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";

										$ending='.txt';
										$file="description/{$row["Name"]}{$ending}";
										#create/override a file called <Animal Name>.txt in the description/ directory
										file_put_contents($file, $row["Description"]);
									}
								?>
				    		</ul>
				    	</li>

				    	<!--birds sub list-->
				    	<li><a href="#" onclick="$(this).parent().find('ul.sub').toggle();">Birds</a>
				    		<ul class="sub">

				    			<!--loop through all the birds from the database and dynamically create an <li> for all of them-->
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Bird' ORDER BY Name");
					    			foreach($result as $row)
									{

										#pass the column values from the database as parameters to the javascript function showAnimal()
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";

										$ending='.txt';
										$file="description/{$row["Name"]}{$ending}";

										#create/override a file called <Animal Name>.txt in the description/ directory
										file_put_contents($file, $row["Description"]);
									}
								?>
				    		</ul>
				    	</li>

				    	<!--mammals sub list-->
				    	<li><a href="#" onclick="$(this).parent().find('ul.sub').toggle();">Mammals</a>
				    		<ul class="sub">

				    			<!--loop through all the mammals from the database and dynamically create an <li> for all of them-->
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Mammal' ORDER BY Name");
					    			foreach($result as $row)
									{

										#pass the column values from the database as parameters to the javascript function showAnimal()
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";

										$ending='.txt';
										$file="description/{$row["Name"]}{$ending}";
								
										#create/override a file called <Animal Name>.txt in the description/ directory		
										file_put_contents($file, $row["Description"]);
									}
								?>
				    		</ul>
				    	</li>

				    </ul>

				</li>

				<!--invertebrate list-->
				<li id="i_li"><a id="invertebrates" href="#" onclick="$(this).parent().find('ul.i_sub').toggle();">Invertebrates<span class="sub-arrow1"></span></a>
					<ul class="i_sub">

						<!--porifera sub list-->
				    	<li><a href="#" onclick="$(this).parent().find('ul.sub').toggle();">Porifera</a>
				    		<ul class="sub">

				    			<!--loop through all the poriferas from the database and dynamically create an <li> for all of them-->
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Porifera' ORDER BY Name");
					    			foreach($result as $row)
									{

										#pass the column values from the database as parameters to the javascript function showAnimal()
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";

										$ending='.txt';
										$file="description/{$row["Name"]}{$ending}";
								
										#create/override a file called <Animal Name>.txt in the description/ directory
										file_put_contents($file, $row["Description"]);
									}
								?>
				    		</ul>
				    	</li>

				    	<!--cnidarians sub list-->
				    	<li><a href="#" onclick="$(this).parent().find('ul.sub').toggle();">Cnidarians</a>
				    		<ul class="sub">

				    			<!--loop through all the cnidarians from the database and dynamically create an <li> for all of them-->
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Cnidaria' ORDER BY Name");
					    			foreach($result as $row)
									{

										#pass the column values from the database as parameters to the javascript function showAnimal()
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";

										$ending='.txt';
										$file="description/{$row["Name"]}{$ending}";
								
										#create/override a file called <Animal Name>.txt in the description/ directory
										file_put_contents($file, $row["Description"]);
									}
								?>
				    		</ul>
				    	</li>

				    	<!--platyhelminthes sub list-->
				    	<li><a href="#"  onclick="$(this).parent().find('ul.sub').toggle();">Plathyhelminthes</a>
				    		<ul class="sub">

				    			<!--loop through all the platyhelminthes from the database and dynamically create an <li> for all of them-->
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Platyhelminthes' ORDER BY Name");
					    			foreach($result as $row)
									{

										#pass the column values from the database as parameters to the javascript function showAnimal()
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";

										$ending='.txt';
										$file="description/{$row["Name"]}{$ending}";

										#create/override a file called <Animal Name>.txt in the description/ directory
										file_put_contents($file, $row["Description"]);
									}
								?>
				    		</ul>
				    	</li>

				    	<!--nematoda sub list-->
				    	<li><a href="#" onclick="$(this).parent().find('ul.sub').toggle();">Nematoda</a>
				    		<ul class="sub">

				    			<!--loop through all the nematodas from the database and dynamically create an <li> for all of them-->
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Nematoda' ORDER BY Name");
					    			foreach($result as $row)
									{

										#pass the column values from the database as parameters to the javascript function showAnimal()
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";

										$ending='.txt';
										$file="description/{$row["Name"]}{$ending}";
								
										#create/override a file called <Animal Name>.txt in the description/ directory
										file_put_contents($file, $row["Description"]);
									}
								?>
				    		</ul>
				    	</li>

				    	<!--annelida sub list-->
				    	<li><a href="#" onclick="$(this).parent().find('ul.sub').toggle();">Annelida</a>
				    		<ul class="sub">

				    			<!--loop through all the annelidas from the database and dynamically create an <li> for all of them-->
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Annelida' ORDER BY Name");
					    			foreach($result as $row)
									{

										#pass the column values from the database as parameters to the javascript function showAnimal()
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";

										$ending='.txt';
										$file="description/{$row["Name"]}{$ending}";
									
										#create/override a file called <Animal Name>.txt in the description/ directory
										file_put_contents($file, $row["Description"]);
									}
								?>
				    		</ul>
				    	</li>

				    	<!--echinodermata sub list-->
				    	<li><a href="#" onclick="$(this).parent().find('ul.sub').toggle();">Echinodermata</a>
				    		<ul class="sub">

				    			<!--loop through all the echinodermatas from the database and dynamically create an <li> for all of them-->
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Echinodermata' ORDER BY Name");
					    			foreach($result as $row)
									{

										#pass the column values from the database as parameters to the javascript function showAnimal()
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";

										$ending='.txt';
										$file="description/{$row["Name"]}{$ending}";
								
										#create/override a file called <Animal Name>.txt in the description/ directory
										file_put_contents($file, $row["Description"]);
									}
								?>
				    		</ul>
				    	</li>

				    	<!--mollusca sub list-->
				    	<li><a href="#" onclick="$(this).parent().find('ul.sub').toggle();">Mollusca</a>
				    		<ul class="sub">

				    			<!--loop through all the molluscas from the database and dynamically create an <li> for all of them-->
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Mollusca' ORDER BY Name");
					    			foreach($result as $row)
									{

										#pass the column values from the database as parameters to the javascript function showAnimal()
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";

										$ending='.txt';
										$file="description/{$row["Name"]}{$ending}";
								
										#create/override a file called <Animal Name>.txt in the description/ directory
										file_put_contents($file, $row["Description"]);
									}
								?>
				    		</ul>
				    	</li>

				    	<!--arthropoda sub list-->
				    	<li><a href="#" onclick="$(this).parent().find('ul.sub').toggle();">Arthropoda</a>
				    		<ul class="sub">

				    			<!--loop through all the arthropodas from the database and dynamically create an <li> for all of them-->
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Arthropoda' ORDER BY Name");
					    			foreach($result as $row)
									{

										#pass the column values from the database as parameters to the javascript function showAnimal()
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";

										$ending='.txt';
										$file="description/{$row["Name"]}{$ending}";
										
										#create/override a file called <Animal Name>.txt in the description/ directory
										file_put_contents($file, $row["Description"]);
									}
								?>
				    		</ul>
				    	</li>

				    </ul>
				
				</li>
			
			</ul>

			<!--list shown as search results-->
			<ul class="all" id="all_list">

				<!--loop through every single animal from the database in alphabetical order and dynamically create an <li> for each of them-->
				<?php
				    $result = $myPDO->query("SELECT * FROM animals_db ORDER BY Name");
					foreach($result as $row)
					{

						#pass the column values from the database as parameters to the javascript function showAnimal()
						echo "<li id=\"" .$row["Name"]. "\"><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size:3.3vh; text-decoration: underline\"> " .$row["Name"]. "</a></li>";
					}
				?>
			</ul>

		</nav>

		<!--individual animal pages-->
		<div id="animalScreen" style="background-image: url('img/paperBackground.jpg'); border-radius: 1vh; box-shadow: 0px 2px 10px 4px rgba(0, 0, 50, .3); width: 70vw; margin: 4vh 10vw 5vh 10vw; padding: 2vh 5vw 2vh 5vw; position: absolute; top: 16vh; display: none">
			
			<div class="title" id="a-name">Common Name: </div>
			<div id="a-s-name" style="text-align: center; font-size: 3vh;"> Scientific Name: </div>

			<!-- create a 3D using the A-Frame framework-->
			<div id="container" style="margin: 4vh 8vw 4vh 8vw; box-shadow: 0px 2px 10px 4px rgba(0, 0, 50, .3); width:54vw; height:25vw;"">

				<!--embedded prevents it the a-scene from being fullscreen-->	
				<a-scene embedded>

					<!--all assets are retrieved before the scene is loaded, which shortens loading time-->
		     		<a-assets>
		     			<a-asset-item id="optimerBoldFont" src="https://rawgit.com/mrdoob/three.js/dev/examples/fonts/optimer_bold.typeface.json"></a-asset-item>
		     		</a-assets>

		     		<a-image id="image" src="" width="7" height="5" position="-10.5 8.7 -20"></a-image>

		     		<!-- 3D background using the environment component - will be customized for each animal -->
					<a-entity id="environment" environment="skyType: atmosphere; skyColor: #cce8ff; groundTexture: none; groundColor: #4f4226; fog:0.1"></a-entity>

					<!-- 3D animal model, loaded using the three.js JSONLoader -->
			   		<a-entity id="model" cursor-listener scale="5 5 5" position="0 0 -6" json-model="src: room.json" rotation="0 90 0"></a-entity>

					<a-entity auto-detect-controllers></a-entity>

					<!--text entities-->
			   		<a-entity id="vr-name" text-geometry="value: Name: ; font: #optimerBoldFont" material="color: #111" position="-5 10 -20" rotation="0 0 0" size="1"></a-entity>

			   		<a-entity id="vr-s-name"  text-geometry="value: Scientific Name: ; font: #optimerBoldFont" material="color: #000" position="-5 9 -20" rotation="0 0 0" size="1"></a-entity>

			   		<a-entity id="vr-diet"  text-geometry="value: Diet: ; font: #optimerBoldFont" material="color: #000" position="-5 8 -20" rotation="0 0 0" size="1"></a-entity>

			   		<a-entity id="vr-category"  text-geometry="value: Category: ; font: #optimerBoldFont" material="color: #000" position="-5 7 -20" rotation="0 0 0" size="1"></a-entity>

			   		<!--ambient light for scene-->
			    	<a-entity light="color: #ccccff; intensity: 1; type: ambient;" visible="true"></a-entity>

		    
		      	</a-scene>

	        </div>

	        <div id="a-diet" class="subTitle">Diet: </div>

	        <div id="a-category" class="subTitle">Category: </div>

	        <hr></hr>

	        <div id="a-description" class="subInfo" style="margin:2vh 0 2vh 0">Info...</div>


		</div>

		<div id="homeScreen" style="background-image: url('img/paperBackground.jpg'); border-radius: 1vh; box-shadow: 0px 2px 10px 4px rgba(0, 0, 50, .3); width: 70vw; margin: 4vh 10vw 5vh 10vw; padding: 2vh 5vw 2vh 5vw; position: absolute; top: 16vh;">
			
			<p class="title" style="font-size: 5vh; font-weight: bold; text-align: center;">Welcome to the WebVR animal encyclopedia!</p>



			<p class="subTitle">WebVR?</p>
			<hr></hr>

			<p class="subInfo" style="color: #2E2E2E">WebVR is a JavaScript API which provides us the opportunity to experience virtual reality in our browser. This allows developers to transform 2D displays into 3D scenes. The WebVR content of this animal encyclopedia is built using the <a href="https://aframe.io/docs/0.7.0/introduction/">A-Frame web framework</a> and the <a href="https://threejs.org/">three.js library</a></p>


			<p class="subTitle">VR headsets that our program supports:</p>
			<hr></hr>

			<ul class="subInfo" style="color  #2E2E2E ;padding:1vh 0vh 0 3vh;margin:1vh;list-style-type: circle">
				<li>HTC Vive with controllers and trackers</li>
				<li>Oculus Rift with Touch controllers</li>
				<li>Google Daydream with controller</li>
				<li>Samsung GearVR with controller</li>
				<li>Google Cardboard</li>
			</ul>

			<p class="subTitle">You don't own any headsets?</p>
			<hr></hr>
			<p style="color: #2E2E2E">Don't worry! You will still be able to view the WebVR content in the following browsers:</p>
			<ul class="subInfo" style="color: #2E2E2E; padding:1vh 0vh 2vh 3vh;margin:1vh;list-style-type: circle">
				<li>Firefox 55+ for Windows</li>
				<li>Experimental builds of Chromium</li>
				<li>Chrome for Android (Daydream)</li>
				<li>Oculus Carmel (GearVR)</li>
				<li>Samsung Internet (GearVR)</li>
				<li>Microsoft Edge</li>
			</ul>

			<p>Source code: <a href="https://www.github.com/rozzasun/animalEncyclopedia/">www.github.com/rozzasun/animalEncyclopedia</a></p>
		</div>

	</body>
</html>