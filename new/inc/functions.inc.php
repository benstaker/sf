<?php

function checkVariable($var=false, $allowEmpty=false){

	// If variable is set.
	if(isset($var)){
		// If variable is not false.
		if($var!==false){
			// If allowEmpty variable is not true.
			if(!$allowEmpty){
				// If variable is not empty.
				if(!empty($var)) return true;
				else return false;
			} else return true;
		} else return false;
	} else return false;

}

function redirect($location=false, $time=false){
	// If Location is valid.
	if(isset($location) && $location!==false && !empty($location)){
		// If Time has been supplied, output JavaScript.
		if(isset($time) && $time!==false && !empty($time)){
			
			ob_start();
			?>
			<script>
			<!--
			setTimeout("location.href = '<?php echo $location; ?>';", <?php echo $time; ?>);
			-->
			</script>
			<?php
			echo ob_get_clean();

		} else header("Location: ".$location);
	}
}