SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for video_banner
-- ----------------------------
DROP TABLE IF EXISTS `video_banner`;
CREATE TABLE `video_banner` (
  `banid` int(5) NOT NULL AUTO_INCREMENT COMMENT '轮播图id',
  `pic` varchar(100) NOT NULL COMMENT '轮播图地址',
  `name` varchar(50) NOT NULL COMMENT '轮播图跳转地址',
  `info` varchar(100) NOT NULL COMMENT '简介',
  PRIMARY KEY (`banid`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='轮播图';

-- ----------------------------
-- Records of video_banner
-- ----------------------------
INSERT INTO `video_banner` VALUES ('15', '/static/uploads/20180205\\ca99f636062d7760e8dbf6048a64fa9c.jpg', '柴犬', '狗狗');
INSERT INTO `video_banner` VALUES ('16', '/static/uploads/20180205\\6231310dd86cfa70f001cd06cf43df40.jpg', '火影忍者', '火影');
INSERT INTO `video_banner` VALUES ('19', '/static/uploads/20180205\\68f0d74fdbcba0edcb658acc2f75a53e.jpg', '桃子与阿狸', '阿狸');
INSERT INTO `video_banner` VALUES ('20', '/static/uploads/20180205\\6147ad7c69242c14f7278749d1171ef2.jpg', '兰博基尼', '一款炮车');

-- ----------------------------
-- Table structure for video_block
-- ----------------------------
DROP TABLE IF EXISTS `video_block`;
CREATE TABLE `video_block` (
  `bid` int(5) NOT NULL AUTO_INCREMENT COMMENT '版块的id',
  `pid` int(5) NOT NULL DEFAULT '0' COMMENT '父级版块id',
  `classname` varchar(30) NOT NULL COMMENT '版块名称',
  `count` int(20) NOT NULL DEFAULT '0' COMMENT '视频数量',
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='板块表';

-- ----------------------------
-- Records of video_block
-- ----------------------------
INSERT INTO `video_block` VALUES ('1', '0', '体育世界', '0');
INSERT INTO `video_block` VALUES ('2', '0', '写真时代', '0');
INSERT INTO `video_block` VALUES ('3', '0', '游戏世界', '0');
INSERT INTO `video_block` VALUES ('4', '0', '综艺生活', '0');
INSERT INTO `video_block` VALUES ('8', '3', '王者荣耀', '0');
INSERT INTO `video_block` VALUES ('9', '4', '奔跑吧兄弟', '0');
INSERT INTO `video_block` VALUES ('10', '2', '日本美女', '0');
INSERT INTO `video_block` VALUES ('11', '1', '篮球世界', '0');
INSERT INTO `video_block` VALUES ('12', '1', '足球世界', '0');

-- ----------------------------
-- Table structure for video_buy
-- ----------------------------
DROP TABLE IF EXISTS `video_buy`;
CREATE TABLE `video_buy` (
  `buyid` int(5) NOT NULL AUTO_INCREMENT COMMENT '购买id',
  `uid` int(11) NOT NULL COMMENT '购买者id',
  `sid` int(11) NOT NULL COMMENT '购买视频id',
  PRIMARY KEY (`buyid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of video_buy
-- ----------------------------

-- ----------------------------
-- Table structure for video_idea
-- ----------------------------
DROP TABLE IF EXISTS `video_idea`;
CREATE TABLE `video_idea` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '评论的id',
  `uid` int(10) NOT NULL COMMENT '发表用户的id',
  `sid` int(10) NOT NULL COMMENT '视频的id',
  `content` mediumtext NOT NULL COMMENT '评论的内容',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '评论时间',
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '评论的父id',
  `hid` int(10) DEFAULT '0' COMMENT '评论的谁的id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COMMENT='评论表';

-- ----------------------------
-- Records of video_idea
-- ----------------------------
INSERT INTO `video_idea` VALUES ('1', '9', '2', ' 我就是试试品论能否成功！\r\n   ', '2018-02-06 10:17:41', '0', '0');
INSERT INTO `video_idea` VALUES ('28', '10', '2', '对吗', '2018-02-06 17:36:52', '9', '1');
INSERT INTO `video_idea` VALUES ('29', '10', '2', ' 再试一下\r\n               ', '2018-02-06 19:06:13', '0', '0');
INSERT INTO `video_idea` VALUES ('30', '10', '2', '再试试看看行不行', '2018-02-06 19:25:15', '9', '1');
INSERT INTO `video_idea` VALUES ('31', '10', '2', '这个是谁的', '2018-02-06 19:28:52', '9', '1');
INSERT INTO `video_idea` VALUES ('32', '10', '2', '杨洋的行不行', '2018-02-06 19:48:49', '10', '29');
INSERT INTO `video_idea` VALUES ('33', '10', '2', '大萨达所', '2018-02-06 19:49:36', '9', '1');
INSERT INTO `video_idea` VALUES ('34', '10', '2', '打算打', '2018-02-06 19:59:59', '10', '29');
INSERT INTO `video_idea` VALUES ('35', '10', '2', '回复的回复', '2018-02-06 20:24:06', '10', '32');
INSERT INTO `video_idea` VALUES ('36', '9', '3', ' \r\n          这个能不能\r\n', '2018-02-07 08:46:01', '0', '0');
INSERT INTO `video_idea` VALUES ('37', '9', '5', ' \r\n                    这个行不行', '2018-02-07 08:59:50', '0', '0');
INSERT INTO `video_idea` VALUES ('38', '9', '5', '回复一下', '2018-02-07 09:00:05', '9', '37');
INSERT INTO `video_idea` VALUES ('39', '10', '5', '回复的回复', '2018-02-07 09:00:55', '9', '38');
INSERT INTO `video_idea` VALUES ('40', '10', '5', '有点问题', '2018-02-07 09:01:36', '10', '39');
INSERT INTO `video_idea` VALUES ('41', '10', '5', '你再看看', '2018-02-07 09:01:58', '9', '38');

-- ----------------------------
-- Table structure for video_link
-- ----------------------------
DROP TABLE IF EXISTS `video_link`;
CREATE TABLE `video_link` (
  `lid` int(5) NOT NULL AUTO_INCREMENT COMMENT '链接id',
  `lname` varchar(50) NOT NULL COMMENT '链接名称',
  `url` varchar(50) NOT NULL COMMENT 'url地址',
  `sort` int(10) NOT NULL DEFAULT '1' COMMENT '排序',
  PRIMARY KEY (`lid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='友情链接表';

-- ----------------------------
-- Records of video_link
-- ----------------------------
INSERT INTO `video_link` VALUES ('1', '百度一下', 'http://www.baidu.com', '2');
INSERT INTO `video_link` VALUES ('2', '博客', 'blog.xfirst.top', '1');

-- ----------------------------
-- Table structure for video_shipin
-- ----------------------------
DROP TABLE IF EXISTS `video_shipin`;
CREATE TABLE `video_shipin` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `cid` int(10) DEFAULT NULL COMMENT '父级id',
  `utime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '上传时间',
  `play` int(10) NOT NULL DEFAULT '0' COMMENT '点赞',
  `picture` varchar(255) DEFAULT NULL COMMENT '显示的图片',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `content` varchar(300) DEFAULT NULL COMMENT '电影简介',
  `ispay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1为付费视频',
  `count` int(10) NOT NULL DEFAULT '0' COMMENT '观看次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='视频表';

-- ----------------------------
-- Records of video_shipin
-- ----------------------------
INSERT INTO `video_shipin` VALUES ('16', 'http://p3pucp0o4.bkt.clouddn.com/3.MP4', '11', '2018-02-07 16:38:42', '0', 'http://p3pucp0o4.bkt.clouddn.com/3.MP4?vframe/jpg/offset/1', '蘑菇头', '小蘑菇头', '0', '0');
INSERT INTO `video_shipin` VALUES ('15', 'http://p3pucp0o4.bkt.clouddn.com/1.MP4', '9', '2018-02-07 16:37:54', '0', 'http://p3pucp0o4.bkt.clouddn.com/1.MP4?vframe/jpg/offset/1', '搞笑', '小孩喝奶', '0', '0');
INSERT INTO `video_shipin` VALUES ('12', 'http://p3pucp0o4.bkt.clouddn.com/111.mp4', '8', '2018-02-07 14:46:36', '0', 'http://p3pucp0o4.bkt.clouddn.com/111.mp4?vframe/jpg/offset/1', '阿斯达', null, '0', '0');
INSERT INTO `video_shipin` VALUES ('14', 'http://p3pucp0o4.bkt.clouddn.com/4.MP4', '10', '2018-02-07 16:35:33', '0', 'http://p3pucp0o4.bkt.clouddn.com/4.MP4?vframe/jpg/offset/1', '哎', null, '0', '0');
INSERT INTO `video_shipin` VALUES ('17', 'http://p3pucp0o4.bkt.clouddn.com/5.MP4', '12', '2018-02-07 16:39:40', '0', 'http://p3pucp0o4.bkt.clouddn.com/5.MP4?vframe/jpg/offset/1', '美女哦', '美女  你不看吗？', '0', '0');
INSERT INTO `video_shipin` VALUES ('18', 'http://p3pucp0o4.bkt.clouddn.com/7.MP4', '12', '2018-02-07 16:40:21', '0', 'http://p3pucp0o4.bkt.clouddn.com/7.MP4?vframe/jpg/offset/1', '小狗狗', '一只小狗狗', '0', '0');
INSERT INTO `video_shipin` VALUES ('19', 'http://p3pucp0o4.bkt.clouddn.com/6.MP4', '8', '2018-02-07 16:43:34', '0', 'http://p3pucp0o4.bkt.clouddn.com/6.MP4?vframe/jpg/offset/1', '美女', '看看吧', '0', '0');

-- ----------------------------
-- Table structure for video_site
-- ----------------------------
DROP TABLE IF EXISTS `video_site`;
CREATE TABLE `video_site` (
  `sid` int(2) NOT NULL AUTO_INCREMENT,
  `sitename` varchar(100) NOT NULL DEFAULT '牛逼小视频网站' COMMENT '网站名称',
  `tag` varchar(50) NOT NULL DEFAULT '牛逼，黄，视频' COMMENT '关键词',
  `descript` varchar(80) NOT NULL DEFAULT '本网站主要是一些视频，短视频，电影，娱乐等等的，望各位不要介意' COMMENT '网站描述',
  `copy` varchar(50) NOT NULL DEFAULT '© 2017 Top' COMMENT '版权',
  `record` varchar(50) NOT NULL DEFAULT '京ICP备88888888号' COMMENT '备案号',
  `close` int(3) NOT NULL DEFAULT '0' COMMENT '站点是否关闭',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='站点信息表';

-- ----------------------------
-- Records of video_site
-- ----------------------------
INSERT INTO `video_site` VALUES ('1', '牛逼小视频网站', '牛逼，黄，视频', '本网站主要是一些视频，短视频，电影，娱乐等等的，望各位不要介意', '© 2017 Top', '京ICP备88888888号', '0');

-- ----------------------------
-- Table structure for video_uaccess
-- ----------------------------
DROP TABLE IF EXISTS `video_uaccess`;
CREATE TABLE `video_uaccess` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `rid` int(5) NOT NULL COMMENT '角色id',
  `nid` int(5) NOT NULL COMMENT '权限id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of video_uaccess
-- ----------------------------
INSERT INTO `video_uaccess` VALUES ('1', '1', '2');
INSERT INTO `video_uaccess` VALUES ('2', '1', '3');
INSERT INTO `video_uaccess` VALUES ('3', '1', '4');
INSERT INTO `video_uaccess` VALUES ('4', '1', '5');
INSERT INTO `video_uaccess` VALUES ('5', '1', '6');
INSERT INTO `video_uaccess` VALUES ('6', '1', '7');
INSERT INTO `video_uaccess` VALUES ('7', '1', '30');
INSERT INTO `video_uaccess` VALUES ('38', '1', '8');
INSERT INTO `video_uaccess` VALUES ('39', '1', '9');
INSERT INTO `video_uaccess` VALUES ('40', '1', '10');
INSERT INTO `video_uaccess` VALUES ('41', '1', '17');
INSERT INTO `video_uaccess` VALUES ('42', '1', '19');
INSERT INTO `video_uaccess` VALUES ('43', '1', '22');
INSERT INTO `video_uaccess` VALUES ('44', '1', '25');
INSERT INTO `video_uaccess` VALUES ('45', '1', '31');
INSERT INTO `video_uaccess` VALUES ('46', '1', '20');
INSERT INTO `video_uaccess` VALUES ('47', '1', '26');
INSERT INTO `video_uaccess` VALUES ('81', '2', '3');
INSERT INTO `video_uaccess` VALUES ('82', '2', '4');
INSERT INTO `video_uaccess` VALUES ('83', '2', '5');
INSERT INTO `video_uaccess` VALUES ('84', '2', '6');
INSERT INTO `video_uaccess` VALUES ('85', '2', '30');
INSERT INTO `video_uaccess` VALUES ('86', '2', '7');
INSERT INTO `video_uaccess` VALUES ('87', '2', '17');
INSERT INTO `video_uaccess` VALUES ('88', '2', '19');
INSERT INTO `video_uaccess` VALUES ('89', '2', '22');
INSERT INTO `video_uaccess` VALUES ('90', '2', '25');
INSERT INTO `video_uaccess` VALUES ('91', '2', '31');
INSERT INTO `video_uaccess` VALUES ('92', '2', '26');

-- ----------------------------
-- Table structure for video_unode
-- ----------------------------
DROP TABLE IF EXISTS `video_unode`;
CREATE TABLE `video_unode` (
  `nid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限id',
  `name` varchar(100) NOT NULL COMMENT '权限名称',
  `uris` varchar(100) NOT NULL COMMENT '结构',
  `level` int(5) NOT NULL COMMENT '类型',
  `pid` int(5) NOT NULL COMMENT '父级的id',
  PRIMARY KEY (`nid`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='权限(节点)表';

-- ----------------------------
-- Records of video_unode
-- ----------------------------
INSERT INTO `video_unode` VALUES ('1', '后台管理', 'admin', '1', '0');
INSERT INTO `video_unode` VALUES ('2', '权限管理', 'root', '2', '1');
INSERT INTO `video_unode` VALUES ('3', '用户管理', 'user', '2', '1');
INSERT INTO `video_unode` VALUES ('4', '轮播管理', 'banner', '2', '1');
INSERT INTO `video_unode` VALUES ('5', '版块管理', 'block', '2', '1');
INSERT INTO `video_unode` VALUES ('6', '评论管理', 'idea', '2', '1');
INSERT INTO `video_unode` VALUES ('7', '视频管理', 'video', '2', '1');
INSERT INTO `video_unode` VALUES ('8', '管理员列表', '/admin/root/adminlist', '3', '2');
INSERT INTO `video_unode` VALUES ('9', '角色列表', '/admin/root/adminrole', '3', '2');
INSERT INTO `video_unode` VALUES ('10', '权限列表', '/admin/root/adminrule', '3', '2');
INSERT INTO `video_unode` VALUES ('17', '用户列表', '/admin/user/userlist', '3', '3');
INSERT INTO `video_unode` VALUES ('19', '轮播列表', '/admin/index/bannerlist', '3', '4');
INSERT INTO `video_unode` VALUES ('20', '站点信息', '/admin/site/siteinfo', '3', '30');
INSERT INTO `video_unode` VALUES ('22', '分类列表', '/admin/index/category', '3', '5');
INSERT INTO `video_unode` VALUES ('25', '评论列表', '/admin/index/commentlist', '3', '6');
INSERT INTO `video_unode` VALUES ('26', '友情链接', '/admin/site/sitelink', '3', '30');
INSERT INTO `video_unode` VALUES ('30', '系统设置', 'site', '2', '1');
INSERT INTO `video_unode` VALUES ('31', '视频列表', '/admin/index/videolist', '3', '7');

-- ----------------------------
-- Table structure for video_urole
-- ----------------------------
DROP TABLE IF EXISTS `video_urole`;
CREATE TABLE `video_urole` (
  `rid` int(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `rolename` varchar(30) NOT NULL COMMENT '角色名称',
  `roledesc` text NOT NULL COMMENT '权限描述',
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of video_urole
-- ----------------------------
INSERT INTO `video_urole` VALUES ('1', '超级管理员', '拥有最舒适的服务！');
INSERT INTO `video_urole` VALUES ('2', '管理员', '一人之下，万人之上');
INSERT INTO `video_urole` VALUES ('3', 'VIP用户', '有钱就行，享受就对了');
INSERT INTO `video_urole` VALUES ('4', '普通用户', '我用双手成就你的梦想');

-- ----------------------------
-- Table structure for video_urole_user
-- ----------------------------
DROP TABLE IF EXISTS `video_urole_user`;
CREATE TABLE `video_urole_user` (
  `uid` int(10) NOT NULL COMMENT '用户id',
  `rid` int(10) NOT NULL DEFAULT '4' COMMENT '角色id',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户角色表';

-- ----------------------------
-- Records of video_urole_user
-- ----------------------------
INSERT INTO `video_urole_user` VALUES ('1', '1');
INSERT INTO `video_urole_user` VALUES ('9', '4');
INSERT INTO `video_urole_user` VALUES ('10', '4');
INSERT INTO `video_urole_user` VALUES ('11', '2');
INSERT INTO `video_urole_user` VALUES ('12', '2');
INSERT INTO `video_urole_user` VALUES ('16', '3');

-- ----------------------------
-- Table structure for video_user
-- ----------------------------
DROP TABLE IF EXISTS `video_user`;
CREATE TABLE `video_user` (
  `uid` int(5) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` varchar(32) DEFAULT NULL COMMENT '用户密码',
  `email` varchar(50) NOT NULL DEFAULT '0' COMMENT '用户邮箱',
  `level` int(5) NOT NULL DEFAULT '4' COMMENT '区分用户等级',
  `grade` int(10) NOT NULL DEFAULT '0' COMMENT '积分',
  `phone` varchar(50) NOT NULL DEFAULT '0' COMMENT '手机号',
  `picture` varchar(300) NOT NULL DEFAULT '/static/admin/images/picture.png' COMMENT '用户头像',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册时间',
  `allowlogin` int(2) NOT NULL DEFAULT '0' COMMENT '是否可以登录',
  `sex` int(2) NOT NULL DEFAULT '3' COMMENT '性别',
  `type` varchar(20) DEFAULT NULL COMMENT '注册方式',
  `error` int(5) NOT NULL DEFAULT '0',
  `birthday` varchar(50) DEFAULT NULL,
  `place` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of video_user
-- ----------------------------
INSERT INTO `video_user` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '0', '1', '0', '0', '/static/admin/images/wukong.gif', '2018-02-03 16:55:18', '0', '3', null, '0', null, null);
INSERT INTO `video_user` VALUES ('9', '松狮', null, '0', '4', '10', '0', 'http://q.qlogo.cn/qqapp/100378832/9DF0F61319AB5F18AFE306E54374E817/100', '2018-02-01 14:18:10', '0', '1', 'QQ', '0', null, null);
INSERT INTO `video_user` VALUES ('10', '杨洋', '8d4646eb2d7067126eb08adb0672f7bb', '1223@qq.com', '4', '30', '12345678911', '/uploads/20180201\\dc3766a7144eb387b6c2e4811ba6ca9b.jpg', '2018-02-01 14:52:33', '0', '2', null, '0', null, null);
INSERT INTO `video_user` VALUES ('11', 'zlxzlx', '9ad0cc6ac82bdb9d57a541b9f4e44947', '123123@qq.com', '2', '0', '13912311231', '/static/admin/images/picture.png', '2018-02-02 21:19:19', '0', '3', null, '0', null, null);
INSERT INTO `video_user` VALUES ('12', 'zxzxzx', 'ddcf4466a7ee29215b8595e30b8bfbe4', '123456@qq.com', '2', '0', '18735159721', '/static/admin/images/picture.png', '2018-02-02 21:24:05', '0', '3', null, '0', null, null);
INSERT INTO `video_user` VALUES ('16', '旺旺汪', '4297f44b13955235245b2497399d7a93', '12345@qq.com', '3', '10', '13234342323', '/static/admin/images/picture.png', '2018-02-03 15:32:22', '0', '3', null, '0', null, null);
