Ken\Web 使用说明
=========
#### [在线手册](doc)

如果你热爱简洁，或许可以试试ken.web 

可以当独立的类来使用，也可以是完整的PHP框架，`MODEL`类我们不提供。

### 独立类使用方法

在composer.json的require中加入，并执行 php composer.phar  install

    "ken/web": "dev-master"  
 

#### 下载composer

    curl -sS https://getcomposer.org/installer | php

#### 项目生成【方法一】

    php composer.phar create-project --prefer-dist --stability=dev ken/web_skeleton  /path/to/application

#### 项目生成【方法二】 
  
直接复制项目到core目录，创建自己的`composer.json`，

`public/index.php` 代码请看 `https://github.com/nonetaichi/ken.web_skeleton`

composer.json

	"require": {
        "php": ">=5.4",
	"ken/web": "dev-master",
	"ken/module": "dev-master",
	"ken/widget": "dev-master"
   },
   "autoload": {   
          "psr-4":{
            "module\\": "module/",  
 	    "widget\\": "widget/",
 	    "tool\\": "tool/",
	    "classes\\": "classes/",
 	    "third\\": "third/"
          } 
    }  

 
----------


UNDER BSD LICENSE




    