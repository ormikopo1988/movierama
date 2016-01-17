<?php
if (!is_array($date) ) { 
	$date = array( 'day' => '', 'month' => '', 'year' => '', 'second' => '', 'minute' => '',  'hour' => '',  ); 
}
$dateTimeEdit = '<input type="text" size="4" name="'. $this->name .'1" value="'. $date['day'] .'" class="'. $className .'">/<input type="text" size="4" name="'. $this->name .'2" value="'. $date['month'] .'" class="'. $className .'">/<input type="text" size="8" name="'. $this->name .'3" value="'. $date['year'] .'" class="'. $className .'"> <input type="text" size="4" name="'. $this->name .'4" value="'. $date['hour'] .'" class="'. $className .'">:<input type="text" size="4" name="'. $this->name .'5" value="'. $date['minute'] .'" class="'. $className .'">:<input type="text" size="4" name="'. $this->name .'6" value="'. $date['second'] .'" class="'. $className .'">
';

?>