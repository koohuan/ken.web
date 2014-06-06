<?php namespace Ken\Web\doc\module; 
/**
    内容管理,全为后台使用
	必须配合 module/content 使用
	
	
	hook 支持 
	
 
	namespace classes\content;
	class pay_order{
		//$v是当前字段的值，$vo是整条记录
		static function title($v,$vo){
			
		}
		//最后的字段
		static function _action($vo){
			dump($vo);
		}
	}


	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/ 
class content{ 
	static $_hook;
	//由自定义内容的ID取得对应表的字段
	static function get_coloum_id($id){ 
 		$post = \DB::w()->table('node_table')->pk($id);
 		$table = $post->slug;
 		$cl = \DB::w()->sql("SHOW COLUMNS FROM $table")->all();
 		foreach($cl as $v){
 			$co[$v->field] = $v->field;
 		}
 		return $co;
	}
	//判断_action 这样的hook是否存在，如果不存在就不显示最后的操作列
	static function hook_exists($slug){
		$cls = "\classes\content\\".$slug;
	 	return class_exists($cls) && method_exists($cls,'_action');
	}
	//加载管理员列表中的最后一列，操作列
	static function hook($slug,$method,$value,$vs = null){ 
		$cls = "\classes\content\\".$slug;
		$id  = 'content_'.md5($cls); 
		if(!class_exists($cls)) {
			static::$_hook[$id] = true;
			return NULL;
		}
		if(!method_exists($cls,$method)) return NULL;
 		return $cls::$method($value , $vs = null);
		
	}
 
	/**
	* 返回正确的关联字段信息,显示列表
	*/
	static function data($table_id,$vo,$field){  
		$fs = static::field($table_id);  
		$v = $fs[$field]; 
		$_v = $v->name;
		if(!$_v) return;
		$value = $vo->$_v;  
		//多表关联
		$rel =  \Ken\Web\doc\module\content\field\base::relation($v);
		if($rel){ 
			$a = $rel['a'];
			$all = \DB::w()->table($rel['table'])->where($rel['b']."=?",[$vo->id])->order_by("id asc")->all(); 
			if($all){
				unset($value);
				foreach($all as $list){
					$value[] = $list->$a;
				}
			} 
			goto BOTTOM;
		 
		}
		//blongs to关联字段 
		$slug = static::slug($v->slug);  
		if(strpos($slug,'.')!==false){ 
			$arr = explode('.',$slug); 
			$table = $arr[1];
			$col = $arr[2];
			$co = 'id';
			if($arr[3]) $co = $arr[3];
			$one = \DB::w()->table($table)->where("`".$co."` = ?",[$value])->one(); 
			$value = $one->$col;
		}
		// 默认值显示
		$vo = unserialize($v->values); 
		if(is_array($vo) && count($vo)>0){ 
			$value = $vo[$value];
		} 
		BOTTOM:
		$cls = "\Ken\\Web\\doc\\module\\content\\field\\".$v->field;
	 	if(method_exists($cls,'grid'))
			$value = $cls::grid($value); 
		return $value; 
	} 
	static function slug_field($slug){  
		if(strpos($slug,':')!==false){
			$arr = explode(':',$slug);
			return $arr[1];
		}else{
			$arr = explode('.',$slug); 
			return $arr[0];
		}
	}
	static function slug($name){
		if(strpos($name,':')!==false){
			$arr = explode(':',$name);
			return $arr[0];
		}else{
			return $name;
		}
	}
	static function table($slug){
		$one = \DB::w()->table('node_table')->where('slug=?',[trim($slug)])->one();
		return $one->id;
	}
	
	static function field($table_id){
		$all = \DB::w()->table('node_field')->where('table_id=?',[$table_id])
			->order_by('top desc,sort desc')
			->all();  
		foreach($all as $v){ 
			if(strpos($v->slug,'.')!==false){
				$name = substr($v->slug,0,strpos($v->slug,'.'));
			}else{
				$name = $v->slug;
			}
			$v->name = $name;
			if(strpos($v->slug,':')!==false){
				$arr = explode(':',$v->slug);
				$v->name = $arr[0];
				$name = $arr[1];  
			} 
			$out[$name] = $v; 
		} 
		return $out;
	}
	
	
	/**
	* 组织好数据 
	* $co 是字段
	*/
	static function save($form,$nid=0,$co){  
		foreach($form as $table=>$vo){
			foreach($vo as $k=>$v){  
				$value = \Input::post($v['name']); 
				$class = "\Ken\\Web\\doc\\module\\content\\field\\".$v['field'];
				if(method_exists($class,'save')){
					$value = $class::save($value);
				}
				if($v['relation']){ 
					if(!is_array($value)) continue;  
					$relations[] = [
						'data'=>array_unique($value),
						'save'=>$v['relation']
					];
				}else{
					if(is_array($value)) $value = serialize($value);
					$data[$k] = $value;
				} 
				if($v['_validate']){ 
					$vali = $v['_validate'];    
					$i = 0;
					foreach($vali as $_va=>$_vas){ 
						$o = $_vas;
						array_unshift($o,$_va);
						$_vali[$i] = $o; 
						$i++;
					} 
					\Validate::set($k,$_vali,$value);
				}
			}  
		 
			//验证成功
			if(\Validate::run()){  
				if($co['update_at']){
					$data['update_at'] = date('Y-m-d H:i:s');
				}
				if($nid>0){
					unset($data['create_at']);
					\DB::w()->update($table,$data,"id=?",$nid);
				}else{					
					if($co['create_at']){
						$data['create_at'] = date('Y-m-d H:i:s');
					}
					
					$nid = \DB::w()->insert($table,$data);
					if($co['sort']){
						\DB::w()->update($table,['sort'=>$nid],"id=?",$nid );
					}
				}
				if($relations){ 
					foreach($relations as $vo){
						$a = $vo['save']['a'];
						$b = $vo['save']['b'];
						$table = $vo['save']['table']; 
					 	\DB::w()->delete($table,"$b=?",[$nid]);
					 	$i = 0;
					 	unset($batch);
					 	foreach($vo['data'] as $v){
					 		$batch[$i][$a] = $v;
					 		$batch[$i][$b] = $nid;
					 		$i++;
					 	}  
						\DB::w()->insert_batch($table,$batch);   
						 
					}
				}
			}
			//取得验证错误信息
			$vali = \Validate::message();
			if($vali) $error  = $vali[0]; 
			unset($data);
			if($error) return $error;
			return true;
		}
	}
	
}