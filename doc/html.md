HTML类
========

	HTML::js(base_url()."js/jquery.js"); 
	
	//调用 css
	HTML::css($this->theme().'_misc/app.css');
	HTML::css($this->theme().'_misc/docs.css');
	
	//显示css链接
	HTML::link('css');
	//显示js链接
	HTML::link('js');
	//显示css代码
	HTML::code('css');
	//显示js代码
	HTML::code('js'); 