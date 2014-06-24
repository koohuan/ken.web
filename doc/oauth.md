-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL COMMENT '会员EMAIL',
  `password` varchar(100) NOT NULL,
  `create_at` timestamp NOT NULL,
  `update_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `users_third`
--

CREATE TABLE IF NOT EXISTS `users_third` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL COMMENT '来自第三方的用户ID',
  `name` varchar(255) NOT NULL,
  `oauth_id` tinyint(4) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `par` varchar(255) DEFAULT NULL COMMENT '其他信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user_id`
--

CREATE TABLE IF NOT EXISTS `user_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` int(3) NOT NULL,
  `create_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户统一ID' AUTO_INCREMENT=1 ;