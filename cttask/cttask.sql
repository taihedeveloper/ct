/*
Navicat MySQL Data Transfer

Source Server         : 192.168.3.228
Source Server Version : 50620
Source Host           : 192.168.3.228:3318
Source Database       : cttask

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2017-03-10 15:54:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ct_group`
-- ----------------------------
DROP TABLE IF EXISTS `ct_group`;
CREATE TABLE `ct_group` (
`id`  int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id' ,
`group_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '服务组名' ,
`host_id`  varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '机器id' ,
`host_name`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '机器名' ,
`create_time`  datetime NOT NULL COMMENT '创建时间' ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='服务组'
AUTO_INCREMENT=124

;

-- ----------------------------
-- Table structure for `ct_group_host`
-- ----------------------------
DROP TABLE IF EXISTS `ct_group_host`;
CREATE TABLE `ct_group_host` (
`id`  bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键id' ,
`group_id`  int(10) NOT NULL COMMENT '组id' ,
`host_id`  int(10) NOT NULL COMMENT '机器id' ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='机器组关联表'
AUTO_INCREMENT=12512

;

-- ----------------------------
-- Table structure for `ct_host`
-- ----------------------------
DROP TABLE IF EXISTS `ct_host`;
CREATE TABLE `ct_host` (
`id`  int(11) NOT NULL AUTO_INCREMENT COMMENT '机器id' ,
`group_id`  int(11) NOT NULL COMMENT '机器组id' ,
`ip`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`host_name`  varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '机器名称' ,
`create_time`  datetime NOT NULL ,
PRIMARY KEY (`id`),
UNIQUE INDEX `ip` (`ip`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='机器表'
AUTO_INCREMENT=931

;

-- ----------------------------
-- Table structure for `ct_shield_host`
-- ----------------------------
DROP TABLE IF EXISTS `ct_shield_host`;
CREATE TABLE `ct_shield_host` (
`id`  bigint(20) NOT NULL AUTO_INCREMENT ,
`task_id`  bigint(20) NOT NULL COMMENT '任务id' ,
`group_id`  bigint(20) NOT NULL COMMENT '组id' ,
`ip`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'ip地址' ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='屏蔽机器表'
AUTO_INCREMENT=165

;

-- ----------------------------
-- Table structure for `ct_task`
-- ----------------------------
DROP TABLE IF EXISTS `ct_task`;
CREATE TABLE `ct_task` (
`id`  bigint(20) NOT NULL AUTO_INCREMENT COMMENT '任务id' ,
`task_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '任务名称' ,
`run_command`  varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '运行命令' ,
`crontab_time`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '调度时间(crontab时间格式)' ,
`run_user`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '账号' ,
`service_node`  varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '服务节点id' ,
`service_node_type`  int(11) NOT NULL COMMENT '服务节点类型(1:机器;2:机器组))' ,
`host_name`  varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '机器' ,
`run_condition`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '运行判断条件' ,
`wait_timeout_time`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '等待超时时间' ,
`run_timeout_time`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '运行超时时间' ,
`run_fail_num`  int(11) NOT NULL COMMENT '运行失败次数(rd)' ,
`run_fail_num_leader`  int(11) NOT NULL DEFAULT 0 COMMENT '运行失败次数(leader)' ,
`run_fail_num_op`  int(11) NOT NULL DEFAULT 0 COMMENT '运行失败次数(op)' ,
`fail_num`  int(11) NOT NULL COMMENT '失败次数' ,
`default_email`  tinyint(2) UNSIGNED NOT NULL COMMENT '默认邮箱 0 不实用默认邮箱, 1 使用默认邮箱' ,
`alarm_email`  varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '报警人接收邮件(rd)' ,
`alarm_email_leader`  varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '报警人接收邮件(leader)' ,
`alarm_email_op`  varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '报警人接收邮件(op)' ,
`alarm_note`  varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '报警接收人短信' ,
`manager`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '管理者' ,
`status`  int(11) NOT NULL DEFAULT 2 COMMENT '上线下状态(1:上线;2:下线;默认是下线)' ,
`create_time`  datetime NOT NULL COMMENT '创建时间' ,
`create_user`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '创建人' ,
`update_time`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间' ,
`update_user`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '最后更新人' ,
`auth`  tinyint(4) NOT NULL DEFAULT 1 COMMENT '权限' ,
`task_level`  tinyint(2) NOT NULL COMMENT '任务级别 1 一级任务, 2 二级任务, 3 三级任务' ,
`task_group`  tinyint(2) NOT NULL COMMENT '任务分组' ,
`task_desc`  varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '任务描述' ,
`is_del`  tinyint(2) NOT NULL DEFAULT 1 COMMENT '是否删除 1 未删除  0 已删除' ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='CT任务基本信息'
AUTO_INCREMENT=705

;

-- ----------------------------
-- Table structure for `ct_task_group`
-- ----------------------------
DROP TABLE IF EXISTS `ct_task_group`;
CREATE TABLE `ct_task_group` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`group_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '名称' ,
`group_desc`  varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述' ,
`update_user`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '最后操作人' ,
`update_time`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间' ,
`create_user`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建人' ,
`create_time`  datetime NOT NULL COMMENT '创建时间' ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='任务分组'
AUTO_INCREMENT=36

;

-- ----------------------------
-- Table structure for `ct_task_log`
-- ----------------------------
DROP TABLE IF EXISTS `ct_task_log`;
CREATE TABLE `ct_task_log` (
`id`  bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键id' ,
`uuid`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'uuid' ,
`task_id`  int(11) NOT NULL COMMENT '任务id' ,
`task_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '任务名' ,
`host_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '机器名称' ,
`begin_time`  datetime NOT NULL COMMENT '开始时间' ,
`end_time`  datetime NOT NULL COMMENT '结束时间' ,
`run_time`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '运行耗时' ,
`return_info`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '返回信息' ,
`run_status`  int(11) NOT NULL COMMENT '执行状态' ,
PRIMARY KEY (`id`),
INDEX `task_name` (`task_name`) USING BTREE ,
INDEX `host_name` (`host_name`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='CT任务日志'
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `ct_task_logs`
-- ----------------------------
DROP TABLE IF EXISTS `ct_task_logs`;
CREATE TABLE `ct_task_logs` (
`uuid`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'uuid' ,
`task_id`  int(11) NOT NULL COMMENT '任务id' ,
`task_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '任务名' ,
`host_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '机器名称' ,
`begin_time`  datetime NOT NULL COMMENT '开始时间' ,
`end_time`  datetime NOT NULL COMMENT '结束时间' ,
`run_time`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '运行耗时' ,
`return_info`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '返回信息' ,
`run_status`  int(11) NOT NULL COMMENT '执行状态' ,
`create_time`  int(11) NOT NULL COMMENT '创建时间' ,
INDEX `task_name` (`task_name`) USING BTREE ,
INDEX `host_name` (`host_name`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='CT任务日志'

;
