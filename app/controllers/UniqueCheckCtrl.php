<?php


class UniqueCheckCtrl extends \BaseController {
	
	public function getIndex($id=NULL) 
	{	
		$input_value = Input::get('value');
		$input_table = Input::get('table');
		if ($id && $input_value && $input_table) {
			if (Schema::hasColumn($input_table, $id) && 
				!DB::table($input_table)->where($id, $input_value)->first())
			{
				$res = new LvResponse(array('isValid'=>true));
				return $res->respond();
			}
		}
		$res = new LvResponse(array('isValid'=>false));
		return $res->respond();
	}
	
	public function missingMethod($parameters)
	{
	    return 0;
	}
}