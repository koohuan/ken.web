自定义内容简要说明 
========
	HOOK 事例

	   <?php
		namespace classes\content;
		class article{
			/**
			* 加载action
			*/
		 	static function _action($v){
		 		switch($v->type){
		 			case 1:
		 				$str = "<span style='color:red'>TEST</span>";
		 				break;
		 			case 2:
		 				$str = "TEST2";
		 				break;
		 		}
		 		
		 		return "<a href='".hook_action($v->id,'article','doit')."'>$str</a>";
		 	}
		 	
		 	static function doit($table,$nid){
		 		$one = \DB::w()->from($table)->pk($nid); 
		 		$type = 1;
		 		if($one->type == 1){
		 			$type = 2;
		 		}  
		 		\DB::w()->update($table,['type'=>$type],'id=?',$nid); 
		 		\Session::flash('success',__('Success')); 
				redirect(hook_action());
		 	}
		}


