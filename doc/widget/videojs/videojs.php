<?php
/**
	 
	https://github.com/videojs/video.js/blob/stable/docs/guides/setup.md
	
	<video id="example_video_1" class="video-js vjs-default-skin"
	  controls preload="auto" width="640" height="264"
	  poster="http://video-js.zencoder.com/oceans-clip.png"
	  data-setup='{"example_option":true}'>
	 <source src="http://video-js.zencoder.com/oceans-clip.mp4" type='video/mp4' />
	 <source src="http://video-js.zencoder.com/oceans-clip.webm" type='video/webm' />
	 <source src="http://video-js.zencoder.com/oceans-clip.ogv" type='video/ogg' />
	</video>

 
*/
namespace Ken\Web\doc\widget\videojs; 
 
class videojs extends \Widget{ 
	 
	function run(){ 
		$url = $this->publish(__DIR__.'/misc'); 
		$this->par = \Json::encode($this->par);
 	 
 		\HTML::code(" 
 			var videojs.options.flash.swf = '".$url."/video-js.swf'; 
 		"); 
 		\HTML::css($url.'video-js.css');
 		\HTML::js($url.'video.js'); 
 		
	}
}