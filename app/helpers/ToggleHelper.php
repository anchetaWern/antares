<?php
class ToggleHelper {

	public static function showWhenCurrentDateIsNotEqualtoPrevious($current_key, $array){
	
		if($current_key == 0){
			return '';
		}else{
			$previous_date = Carbon::createFromFormat('Y-m-d H:i:s', $array[$current_key - 1]->timestamp)->toDateString(); 
			$current_date = Carbon::createFromFormat('Y-m-d H:i:s', $array[$current_key]->timestamp)->toDateString();
			if($previous_date != $current_date){
				return '';
			}
		}
		return 'hidden';
		
	}

}