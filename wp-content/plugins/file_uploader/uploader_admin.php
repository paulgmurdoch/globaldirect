<?php

function monthWeek(){
	
	global $wpdb;
		
	$month 	 = '';
	$weekNum = '';
	$query = '';	

	if(isset($_POST['submitWeek'])){
	
		$month 	 = $_POST['month'];
		$weekNum = $_POST['weekNum'];

		# Insert data into database
		$query = $wpdb->update( 
						'wp_dateData', 
						array(
								'month' => $month, 
								'week' => $weekNum 
						), 
						array( 'uid' => 1),
						array(
								'%s',
								'%d'
						));

	}	

	$getdata = $wpdb->get_row('
							SELECT *
							FROM wp_dateData
							WHERE uid = 1
	');

	echo $getdata->month . ' - WEEK ' . $getdata->week;	
	
}

?>

<div class="wrap">

	<h2>Uploader Plugin</h2>		
	
  	<div class="postbox">
	
		<form class="inside" name="week_form" method="post" action="options-general.php?page=uploader-menu">
			
			<h3>Set the month and week number</h3>
			
			<p><strong>Current Week: <?php echo monthWeek(); ?></strong></p>
			
			<label>Month:</label>
			<select name="month">
				<option value=""> </option>
				<option value="JAN" <?php if($_POST['month'] == "JAN"){ echo 'selected=""'; } ?>>JAN</option>
				<option value="FEB" <?php if($_POST['month'] == "FEB"){ echo 'selected=""'; } ?>>FEB</option>
				<option value="MAR" <?php if($_POST['month'] == "MAR"){ echo 'selected=""'; } ?>>MAR</option>
				<option value="APR" <?php if($_POST['month'] == "APR"){ echo 'selected=""'; } ?>>APR</option>
				<option value="MAY" <?php if($_POST['month'] == "MAY"){ echo 'selected=""'; } ?>>MAY</option>
				<option value="JUN" <?php if($_POST['month'] == "JUN"){ echo 'selected=""'; } ?>>JUN</option>
				<option value="JUL" <?php if($_POST['month'] == "JUL"){ echo 'selected=""'; } ?>>JUL</option>
				<option value="AUG" <?php if($_POST['month'] == "AUG"){ echo 'selected=""'; } ?>>AUG</option>
				<option value="SEP" <?php if($_POST['month'] == "SEP"){ echo 'selected=""'; } ?>>SEP</option>
				<option value="OCT" <?php if($_POST['month'] == "OCT"){ echo 'selected=""'; } ?>>OCT</option>
				<option value="NOV" <?php if($_POST['month'] == "NOV"){ echo 'selected=""'; } ?>>NOV</option>
				<option value="DEC" <?php if($_POST['month'] == "DEC"){ echo 'selected=""'; } ?>>DEC</option>
			</select>
			
			<label>Week:</label>
			<select name="weekNum">
				<option value=""> </option>
				<option value="1" <?php if($_POST['weekNum'] == "1"){ echo 'selected=""'; } ?>>1</option>
				<option value="2" <?php if($_POST['weekNum'] == "2"){ echo 'selected=""'; } ?>>2</option>
				<option value="3" <?php if($_POST['weekNum'] == "3"){ echo 'selected=""'; } ?>>3</option>
				<option value="4" <?php if($_POST['weekNum'] == "4"){ echo 'selected=""'; } ?>>4</option>
				<option value="5" <?php if($_POST['weekNum'] == "5"){ echo 'selected=""'; } ?>>5</option>
			</select>
			
			<br><br>
			
			<input class='button button-primary' type='submit' name='submitWeek' value='Update' />
			
		</form>
		
	</div>
  
  	<div class="postbox">
  
	  <form class="inside" name="upload_form" method="post" action="options-general.php?page=uploader-menu" enctype="multipart/form-data">									
			  
		  <h3>Browse for your document and then click the save button to upload to the server.</h3>	
			  
		  <p><strong>Note:</strong> Please save your files in Excel as .xml files before uploading</p>	
	  
		  <?php settings_fields( 'uploader-settings-group' ); ?>
		  
		  <?php do_settings_sections( 'uploader-settings-group' ); ?>			
		  
		  <input type='hidden' name='MAX-FILE-SIZE' value='2000000'/>
				  
		  <label>Upload file:</label>
		  <input id='file' type='file' name='file'/>
				  
		  <br><br>		
	  
		  <input class='button button-primary' type='submit' name='submit' value='Save File' />
		  <?php upload_file(); ?>				
		  
	  </form>
	  
  </div>
	
</div> 

