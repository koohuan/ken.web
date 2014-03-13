类似淘宝分页
======== 
 
	$query = \F::get('db')->table($this->table);
	$row = $query->count()->one();
	
	 
	$paginate = new \Paginate($row->num,1); 
	$paginate->url = $this->url;
	$limit = $paginate->limit;
	$offset = $paginate->offset;
	
	//显示分页条，直接输出 $paginate
	$paginate = $paginate->show();
	
    
    	$query = \F::get('db')
    			->table($this->table)
    			->limit($limit)
    			->offset($offset);
	$posts = $query->all();

	
