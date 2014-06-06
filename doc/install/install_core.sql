
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`, `create_at`, `update_at`, `uid`) VALUES
(1, 'root', '$2y$13$MM6AwkvJwxKTdxRVBzmeOeATPgua2.S3U/nqa8BEGSZblaS53NrMK', 'yiiphp@qq.com', '2014-04-16 12:24:14', '0000-00-00 00:00:00', '4cd7f917-bf8f-4003-9452-baed0c346ede');

-- --------------------------------------------------------

--
-- 表的结构 `admin_access`
--

CREATE TABLE IF NOT EXISTS `admin_access` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `access` varchar(200) NOT NULL COMMENT '权限真实控制',
  PRIMARY KEY (`id`),
  UNIQUE KEY `access` (`access`),
  UNIQUE KEY `id` (`id`),
  KEY `access_2` (`access`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `admin_group`
--

CREATE TABLE IF NOT EXISTS `admin_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `node_field`
--

CREATE TABLE IF NOT EXISTS `node_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_id` int(11) NOT NULL,
  `label` varchar(50) NOT NULL,
  `memo` varchar(200) NOT NULL COMMENT '提示信息',
  `slug` varchar(200) NOT NULL COMMENT 'users.site_id:sites.name',
  `field` text NOT NULL,
  `widget` text NOT NULL,
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `image_cache`
--

CREATE TABLE IF NOT EXISTS `image_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `set` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;