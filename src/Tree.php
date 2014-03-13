<?php 
/**
     生成树型菜单 
  
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
class Tree
{ 
 	/**
 	* 生成数组树
 	* https://gist.github.com/mincms/8592495
 	* 
 	* <code>
 	* $arr = [
    *   	['id'=>1,'pid'=>0,'name'=>'aaa'],
    *   	['id'=>6,'pid'=>2,'name'=>'eee'],
    *   	['id'=>2,'pid'=>3,'name'=>'bbb'],
    *   	['id'=>3,'pid'=>0,'name'=>'ccc'],
    *   	['id'=>4,'pid'=>3,'name'=>'ddd'],
    *   	['id'=>5,'pid'=>0,'name'=>'fff'],
    *   ];
    * dump(\app\tools\Tree::tree($arr,'id','pid'));
    * 输出格式如下
    * Array
	* (
	*     [1] => Array
	*         (
	*             [id] => 1
	*             [pid] => 0
	*             [name] => aaa
	*         )
	* 
	*     [3] => Array
	*         (
	*             [id] => 3
	*             [pid] => 0
	*             [name] => ccc
	*             [#child] => Array
	*                 (
	*                     [2] => Array
	*                         (
	*                             [id] => 2
	*                             [pid] => 3
	*                             [name] => bbb
	*                             [#child] => Array
	*                                 (
	*                                     [6] => Array
	*                                         (
	*                                             [id] => 6
	*                                             [pid] => 2
	*                                             [name] => eee
	*                                         )
	* 
	*                                 )
	* 
	*                         )
    * 
	*                     [4] => Array
	*                         (
	*                             [id] => 4
	*                             [pid] => 3
	*                             [name] => ddd
	*                         )
    * 
	*                 )	
	* 
	*         )
	* 
	*     [5] => Array
	*         (
	*             [id] => 5
	*             [pid] => 0
	*             [name] => fff
	*         )
	* 
	* )
	* </code>
 	*/
	static function tree($arr,$id='id',$parent='pid',$root=0){
  		foreach($arr as $v){
  			if($v[$parent] == $root){
  				$tree[$v[$id]] = $v;
  				$vo = static::tree($arr,$id,$parent,$v[$id]);
  				if($vo)
  					$tree[$v[$id]]['#child'] = $vo;
  			}
  		} 
  		return $tree;
  	}
	
 
}