<?php
/** 
 	可使用F注册
 	Cache 仅支持memcache 或 memcached 如果存在 memecached 将优先使用
 	
 	use PHP\Classes\Cache;
 	$cache = new Cache([['host'=>'127.0.0.1','port'=>11211,'weight'=>60]]);
 	$cache->get($key);
	$cache->set($key,$data = []);
	$cache->increment($key,$data = []);
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web;
 
class Cache{
	
 	public $cache; 
 	public $active = true;
 	public $type = 'memcache';
 	public function __construct($servers = []) 
    {     
		if (extension_loaded('memcached')) {
			$this->type = "memcached";
			$this->cache = new \Memcached; 
		}else if(extension_loaded ('memcache'))
			$this->cache = new \Memcache;  
		else{
			$this->active = false;
			Log::error('Cache not support!');
			return false;
		}
		foreach ($servers as $server)
		{
			$this->cache->addServer($server['host'], $server['port'], $server['weight']?:60);
		}   
    }
    
    
    /**
    	取得缓存值
    */
    public function get($key){  
    	if($this->active !== true) return;
		$data = $this->cache->get( $key );    
		return $data;
	} 
	/**
    	设置缓存，默认永不过期
    */
	public function set($key, $value, $minutes = 0){ 
		if($this->active !== true) return;
		if(!$value) return ; 
		if( $minutes > 0) {
			$minutes = time() + $minutes;
		} 
		if($this->type == 'memcache')
			$this->cache->set( $key, $value,false, $minutes ); 
		else
			$this->cache->set( $key, $value, $minutes ); 
	}
	/**
    	自增长缓存，默认步幅为1
    */
 	public function increment($key, $value = 1){   
 		if($this->active !== true) return;		
 		return $this->cache->increment( $key );
 	}
 	/**
    	自减缓存，默认步幅为1
    */
 	public function decrement($key, $value = 1){ 
 		if($this->active !== true) return;
 		return $this->cache->decrement( $key );
 	}
 	/**
    	删除缓存，如果$key没有值 将清空所有缓存
    */
	public function delete($key = null){ 
		if($this->active !== true) return;
		if(!$key) $this->cache->flush( );
		$this->cache->delete( $key );
	}
 	
 	  
}