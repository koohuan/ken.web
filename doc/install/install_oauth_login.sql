
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员' AUTO_INCREMENT=1 ;

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