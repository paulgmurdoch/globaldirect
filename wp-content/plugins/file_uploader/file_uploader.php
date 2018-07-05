<?php
/**
 * Plugin Name: File Uploader
 * https://www.007.theunlimited.co.za
 * Description: This plugin allows uploads of XML documents to a wordpress page.
 * Version: 1.3
 * Author: Dean Thomson
 * Author URI: http://www.theunlmited.co.za
 * 
 */
 
/***************************************************************
 * TO DO:
 * 
 * 1. Clean up code - Remove redundant functions and test code
 * 2. Fix $check_string in upload_file() must differentiate between extentions
 * 
 ****************************************************************/ 
 
 /* ================================
  * Create Admin Section
  * ================================
  */ 


function uploader_admin(){
  	
	add_menu_page('Uploader Plugin Options', 'Uploader Plugin', 'edit_pages', 'uploader-menu', 'uploader_options');
	
}
  
add_action('admin_menu','uploader_menu');
  
function uploader_options(){
  	
	include('uploader_admin.php');
	
}
   
function uploader_menu(){
	
	add_menu_page('Uploader Plugin Options', 'Uploader Plugin', 'edit_pages', 'uploader-menu', 'uploader_options');
	
}

/** ================================
  * File Handler
  * ================================
  */

# Allow XML files to be uploaded
add_filter('upload_mimes', 'custom_upload_xml');
 
function custom_upload_xml($mimes){
    $mimes = array_merge($mimes, array('xml' => 'application/xml'));
    return $mimes;
}

function upload_file(){

   /**
	* upload file to uploads folder. 
	* check if file exists and handle appropriately 
	*/	 
	
	if(isset($_POST['submit'])){
	
		$name  		  = $_FILES['file']['name'];
		$type  		  = $_FILES['file']['type'];
		$size  		  = $_FILES['file']['size'];
		$tmp   		  = $_FILES['file']['tmp_name'];
		$error 		  = $_FILES['file']['error'];
		$savepath 	  = ABSPATH . 'wp-content/uploads/bulletins/';
		$filelocation = $savepath. 'bulletin.xml'; #set name to bulletin.xml 
		$check_string = substr($type, -3); #check extension
		
		wp_upload_bits($tmp, null, file_get_contents($tmp));	
		
		// Upload if no error, file exists and the extension is .xml
		if (file_exists($tmp) && $error == 0 && $check_string == "xml") {	    		
			
			// This will overwrite even if the file exists
		    move_uploaded_file($tmp, $filelocation);
			
			echo "<p>File is valid, and was successfully uploaded.<p>";
	
		} else if($check_string !== "xml"){
	
			echo "
			<p><strong>UPLOAD FAILED. Invalid file format.</strong> ONLY .xml files are to be uploaded<p> 
			<p>Please try again.</p>
			";
	
		}
	}
}

/* ===================================================
 * 	Table Creater Shortcode
 * ===================================================
 */

// Get the data from the uploaded file and insert into tables
function create_tables($content = null){
		
	/**
	 * check for XML file and translate it into data
	 */		
	$url = "http://gd.theunlimited.co.za/wp-content/uploads/bulletins/bulletin.xml";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);    // get the url contents
  	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/cert/BuiltinObjectToken-GoDaddyClass2CA.crt");
	
	$data = curl_exec($ch); // execute curl request
	curl_close($ch);
	
	$xml = simplexml_load_string($data);
	// $sxml = new SimpleXMLElement($xml);
	
	# Set variables
	$i 				= 0;
	$page_title 	= get_the_title();
	$worksheets 	= $xml->Worksheet;
	$rows 			= $xml->Worksheet[$i]->Table->Row;		
	
	# Cycle through all worksheets in uploaded XML file
	foreach($worksheets as $worksheet){	
		
		# Set row count $r to 0 at the start of each worksheet
		$r = 0;
		
		/**
		 * Check to see if the heading in each Worksheet matches the page title
		 * if it does echo the page title and start building the table with the 
		 * data in that specific worksheet
		 */
		if($xml->Worksheet[$i]->Table->Row->Cell[0]->Data->B->Font == $page_title || $xml->Worksheet[$i]->Table->Row->Cell[0]->Data == $page_title){
			
				echo "
					
					<table class='table table-hover table-striped'>
						<thead>\n
					";	
		  
		  			$r++;
				
			foreach($rows as $row){
								
				/**
				 * Cycle through the rows in the table within the worksheet
				 * get data and display on the page as a table
				 */

				for($n = 0; $n < count($xml->Worksheet[$i]->Table->Row[$r]); $n++){																				
							
					if($r == 1){
						
						/** 
						 * check if 2nd row then set as table head row, 
						 * then close table head and start table body
						 */
						
						echo "
								<tr>
									<th>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[0]->Data."</th>
									<th>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[1]->Data."</th>
									<th>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[2]->Data."</th>
								</tr>\n
							
							</thead>
							<tbody>
						";

						$r++;
					
					} else if($row->Cell[0]->Data == '' && $row->Cell[1]->Data == '' && $row->Cell[2]->Data == '' || $xml->Worksheet[$i]->Table->Row[$r]->Cell[0]->Data == $page_title) {
							
						# If all of the cells are empty then don't display any table data and increment to next row					
						echo " ";

						$r++;
							
					} else {

						# check if the last cell type is a number
						$num = (string) $xml->Worksheet[$i]->Table->Row[$r]->Cell[2]->Data->attributes('ss', TRUE)->Type;
						
						foreach($xml->Worksheet[$i]->Table->Row[$r]->Cell->Data as $cell){							
							
							# count the rows that have data
							for($c = 0; $c < count($xml->Worksheet[$i]->Table->Row) - 2; $c++){																

								# if last cell is a number round it up
								if($num == 'Number'){
									
									if($i == 2) {
										echo "
											<tr>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[0]->Data."</td>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[1]->Data."</td>
												<td>".round((float)$xml->Worksheet[$i]->Table->Row[$r]->Cell[2]->Data, 2)."</td>
											</tr>\n
										";	
									} else {
										echo "
											<tr>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[0]->Data."</td>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[1]->Data."</td>
												<td>".ceil($xml->Worksheet[$i]->Table->Row[$r]->Cell[2]->Data)."</td>
											</tr>\n
										";	
									}
		
									
	
								} else {
										
									echo "
											<tr>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[0]->Data."</td>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[1]->Data."</td>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[2]->Data."</td>
											</tr>\n
									";									
									
								}
							  
							  	$r++;
							  
							}
						}
			
					} // END if								
				
				} // END for
				
			} // END foreach
			
			echo "
			
						</tbody>
					</table>
			
				";
			
		} 
		
		# Increment Worksheet count
		$i++;
	
	}	

}// End function

add_shortcode('table','create_tables');

/* ===================================================
 * 	All Content Shortcode - the All Content page
 * ===================================================
 */

// Get the data from the uploaded file and insert into tables
function all_content($content = null){
		
	/**
	 * check for XML file and translate it into data
	 */		
	$url = "http://gd.theunlimited.co.za/wp-content/uploads/bulletins/bulletin.xml";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);    // get the url contents
  	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/cert/BuiltinObjectToken-GoDaddyClass2CA.crt");
	
	$data = curl_exec($ch); // execute curl request
	curl_close($ch);
	
	$xml = simplexml_load_string($data);
	
	
	# Set variables
	$i 				= 0;
	$page_title 	= get_the_title();
	$worksheets 	= $xml->Worksheet;
	$rows 			= $xml->Worksheet[$i]->Table->Row;		
	
	# Cycle through all worksheets in uploaded XML file
	foreach($worksheets as $worksheet){	
		
		# Set row count $r to 0 at the start of each worksheet
		$r = 0;			
		
		# Kill process after the 6th Worksheet has been displayed
		if($i != 6){ 		
		if($xml->Worksheet[$i]->Table->Row->Cell[0]->Data->B->Font){
			
				echo "
					<div class='row print-only'>
					  <div class='top-head'>
						  <div class='round-left'></div>
						  <h3 class='h3'>".$xml->Worksheet[$i]->Table->Row->Cell[0]->Data->B->Font."</h3>
						  <div class='round-right'></div>
					  </div>
					</div>
					
					<table class='table table-hover table-striped print-only'>
						<thead>\n
					";

		  			$r++;
		  
			foreach($rows as $row){
								
				/**
				 * Cycle through the rows in the table within the worksheet
				 * get data and display on the page as a table
				 */

				for($n = 0; $n < count($xml->Worksheet[$i]->Table->Row[$r]); $n++){									
						
					if($r == 1){
						
						/** 
						 * if any of the rows have 'Name','NAME' or 'Franchise Owner name' then set as 
						 * table head row, then close table head and start table body
						 */
						
						echo "
					
								<tr>
									<th>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[0]->Data."</th>
									<th>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[1]->Data."</th>
									<th>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[2]->Data."</th>
								</tr>\n
							
							</thead>
							<tbody>
						";
						
						$r++;
						
					} else if($row->Cell[0]->Data == '' && $row->Cell[1]->Data == '' && $row->Cell[2]->Data == '' || $xml->Worksheet[$i]->Table->Row[$r]->Cell[2]->Data == '') {
							
						# If all of the cells are empty then don't display any table data and increment to next row					
						echo " ";
						
						$r++;
						
					} else {
							
						# check if the last cell type is a number
						$num = (string) $xml->Worksheet[$i]->Table->Row[$r]->Cell[2]->Data->attributes('ss', TRUE)->Type;

						foreach($xml->Worksheet[$i]->Table->Row[$r]->Cell->Data as $cell){							
							
							# count the rows that have data
							for($c = 0; $c < count($xml->Worksheet[$i]->Table->Row) - 2; $c++){																

								# if last cell is a number round it up
								if($num == 'Number'){
		
									echo "
											<tr>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[0]->Data."</td>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[1]->Data."</td>
												<td>".round((float)$xml->Worksheet[$i]->Table->Row[$r]->Cell[2]->Data, 2)."</td>
											</tr>\n
									";
	
								} else {
										
									echo "
											<tr>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[0]->Data."</td>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[1]->Data."</td>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[2]->Data."</td>
											</tr>\n
									";
									
								}
							  
							  	$r++;
							  
							}
						}
			
					} // END if								
				
				} // END for
				
			} // END foreach						 
		
		} else if($xml->Worksheet[$i]->Table->Row->Cell[0]->Data){
			
				echo "
					<div class='row print-only'>
					  <div class='top-head'>
						  <div class='round-left'></div>
						  <h3 class='h3'>".$xml->Worksheet[$i]->Table->Row->Cell[0]->Data."</h3>
						  <div class='round-right'></div>
					  </div>
					</div>
					
					<table class='table table-hover table-striped print-only'>
						<thead>\n
					";
		  
		  			$r++;

			foreach($rows as $row){
								
				/**
				 * Cycle through the rows in the table within the worksheet
				 * get data and display on the page as a table
				 */

				for($n = 0; $n < count($xml->Worksheet[$i]->Table->Row[$r]); $n++){									
						
					if($r == 1){
						
						/** 
						 * if any of the rows have 'Name','NAME' or 'Franchise Owner name' then set as 
						 * table head row, then close table head and start table body
						 */
						
						echo "
					
								<tr>
									<th>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[0]->Data."</th>
									<th>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[1]->Data."</th>
									<th>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[2]->Data."</th>
								</tr>\n
							
							</thead>
							<tbody>
						";
						
						$r++;
						
					} else if($row->Cell[0]->Data == '' && $row->Cell[1]->Data == '' && $row->Cell[2]->Data == '' || $xml->Worksheet[$i]->Table->Row[$r]->Cell[2]->Data == '') {
							
						# If all of the cells are empty then don't display any table data and increment to next row					
						echo " ";
						
						$r++;
						
					} else {
						# check if the last cell type is a number
						$num = (string) $xml->Worksheet[$i]->Table->Row[$r]->Cell[2]->Data->attributes('ss', TRUE)->Type;

						foreach($xml->Worksheet[$i]->Table->Row[$r]->Cell->Data as $cell){							
							
							# count the rows that have data
							for($c = 0; $c < count($xml->Worksheet[$i]->Table->Row) - 2; $c++){																

								# if last cell is a number round it up
								if($num == 'Number'){
		
									if($i == 2) {
										echo "
											<tr>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[0]->Data."</td>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[1]->Data."</td>
												<td>".round((float)$xml->Worksheet[$i]->Table->Row[$r]->Cell[2]->Data, 2)."</td>
											</tr>\n
										";	
									} else {
										echo "
											<tr>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[0]->Data."</td>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[1]->Data."</td>
												<td>".ceil($xml->Worksheet[$i]->Table->Row[$r]->Cell[2]->Data)."</td>
											</tr>\n
										";	
									}
	
								} else {
										
									echo "
											<tr>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[0]->Data."</td>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[1]->Data."</td>
												<td>".$xml->Worksheet[$i]->Table->Row[$r]->Cell[2]->Data."</td>
											</tr>\n
									";
									
								}
							  
							  	$r++;
							  
							}
						}
			
					} // END if								
				
				} // END for
				
			} // END foreach						
			
		}
		}
	    
		
		echo "
			
						</tbody>
					</table>
			
				";
		
		# Increment Worksheet count
		$i++;
	
	}	

	# error checking
	// ini_set('display_errors', true);
	// error_reporting(E_ALL);

}// End function

add_shortcode('all','all_content');

?>