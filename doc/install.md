#### 下载composer

    curl -sS https://getcomposer.org/installer | php

#### 快速安装


    php composer.phar create-project --prefer-dist --stability=dev ken/web_skeleton  /path/to/application
    


 
 
 
SQL:


		 

	SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
	SET time_zone = "+00:00";
	 

	-- --------------------------------------------------------

	--
	-- 表的结构 `admin`
	--

	CREATE TABLE IF NOT EXISTS `admin` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `username` varchar(50) NOT NULL,
	  `password` varchar(64) NOT NULL,
	  `email` varchar(50) NOT NULL,
	  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  `update_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `uid` varchar(64) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

 
	-- --------------------------------------------------------

	--
	-- 表的结构 `admin_access`
	--

	CREATE TABLE IF NOT EXISTS `admin_access` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `access` varchar(200) NOT NULL COMMENT '权限真实控制',
	  `name` varchar(200) NOT NULL COMMENT '权限名称',
	  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

	-- --------------------------------------------------------

	--
	-- 表的结构 `admin_group`
	--

	CREATE TABLE IF NOT EXISTS `admin_group` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `name` int(11) NOT NULL,
	  `memo` varchar(200) NOT NULL,
	  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组' AUTO_INCREMENT=1 ;

	-- --------------------------------------------------------

	--
	-- 表的结构 `admin_group_access`
	--

	CREATE TABLE IF NOT EXISTS `admin_group_access` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `group_id` int(11) NOT NULL,
	  `access_id` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

	-- --------------------------------------------------------

	--
	-- 表的结构 `admin_group_bind`
	--

	CREATE TABLE IF NOT EXISTS `admin_group_bind` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `admin_id` int(11) NOT NULL COMMENT '管理员ID',
	  `group_id` int(11) NOT NULL COMMENT '用户组ID',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户绑定组' AUTO_INCREMENT=1 ;

	-- --------------------------------------------------------

	--
	-- 表的结构 `files`
	--

	CREATE TABLE IF NOT EXISTS `files` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(200) NOT NULL,
	  `url` varchar(255) NOT NULL,
	  `ext` varchar(50) NOT NULL,
	  `mime` varchar(100) NOT NULL,
	  `size` int(11) NOT NULL,
	  `md5` varchar(32) NOT NULL DEFAULT '',
	  `memo` varchar(200) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

 
	--
	-- 表的结构 `node_field`
	--

	CREATE TABLE IF NOT EXISTS `node_field` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `table_id` int(11) NOT NULL,
	  `label` varchar(50) NOT NULL,
	  `memo` varchar(200) NOT NULL COMMENT '提示信息',
	  `slug` varchar(200) NOT NULL COMMENT 'users.site_id:sites.name',
	  `field` varchar(50) NOT NULL,
	  `widget` varchar(50) NOT NULL,
	  `validate` varchar(255) NOT NULL,
	  `is_form` tinyint(1) NOT NULL DEFAULT '0',
	  `is_index` tinyint(1) NOT NULL DEFAULT '0',
	  `is_search` tinyint(1) NOT NULL DEFAULT '0',
	  `sort` int(11) NOT NULL,
	  `values` varchar(255) NOT NULL COMMENT '默认值',
	  `is_m_value` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否多值，大于1时，为值的数量',
	  `top` int(11) NOT NULL DEFAULT '0' COMMENT '是否置顶',
	  `is_report` tinyint(4) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

	 

	--
	-- 表的结构 `node_table`
	--

	CREATE TABLE IF NOT EXISTS `node_table` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(20) NOT NULL,
	  `slug` varchar(20) NOT NULL,
	  `load` varchar(20) NOT NULL COMMENT 'classes\\content中自动加载的页面',
	  `memo` varchar(20) NOT NULL,
	  `display` tinyint(1) NOT NULL DEFAULT '1',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

 

	-- --------------------------------------------------------

	--
	-- 表的结构 `oauth_config`
	--

	CREATE TABLE IF NOT EXISTS `oauth_config` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(200) NOT NULL,
	  `app_id` varchar(255) NOT NULL,
	  `app_secret` varchar(255) NOT NULL,
	  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
	  `update_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `display` tinyint(1) NOT NULL DEFAULT '1',
	  `sort` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

 
	-- --------------------------------------------------------

	--
	-- 表的结构 `oauth_users`
	--

	CREATE TABLE IF NOT EXISTS `oauth_users` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `oid` varchar(255) NOT NULL COMMENT '来自第三方的用户ID',
	  `name` varchar(255) NOT NULL,
	  `email` varchar(50) DEFAULT NULL,
	  `oauth_id` int(11) NOT NULL COMMENT 'oauth_config中主键ID，登录方',
	  `access_token` varchar(255) NOT NULL,
	  `uid` varchar(64) NOT NULL COMMENT '真实会员ID',
	  `par` varchar(255) DEFAULT NULL COMMENT '其他信息',
	  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
	  `update_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

	-- --------------------------------------------------------

	--
	-- 表的结构 `query_build`
	--

	CREATE TABLE IF NOT EXISTS `query_build` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `slug` varchar(200) NOT NULL,
	  `memo` varchar(200) NOT NULL,
	  `sql` text NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

 	-- --------------------------------------------------------

	--
	-- 表的结构 `users`
	--

	CREATE TABLE IF NOT EXISTS `users` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `email` varchar(255) NOT NULL COMMENT '会员EMAIL',
	  `password` varchar(200) NOT NULL,
	  `token_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `active` tinyint(1) NOT NULL DEFAULT '0',
	  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `update_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
	  `uid` varchar(64) NOT NULL COMMENT '唯一标识',
	  `site_id` int(11) NOT NULL DEFAULT '1',
	  `phone` varchar(50) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员' AUTO_INCREMENT=1 ;

 	-- --------------------------------------------------------

	--
	-- 表的结构 `user_oauth`
	--

	CREATE TABLE IF NOT EXISTS `user_oauth` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `user_id` int(11) NOT NULL COMMENT '用户ID',
	  `oauth_user_id` int(11) NOT NULL COMMENT '第三方ID',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户绑定第三方登录' AUTO_INCREMENT=1 ;

	/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
	/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
	/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
	
 


 
	 