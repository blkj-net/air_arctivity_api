/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80012
 Source Host           : localhost:3306
 Source Schema         : hyperf-admin

 Target Server Type    : MySQL
 Target Server Version : 80012
 File Encoding         : 65001

 Date: 28/05/2021 10:43:25
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2021_03_06_065713_create_sys_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2021_03_06_080802_create_sys_roles_table', 1);
INSERT INTO `migrations` VALUES (3, '2021_03_06_083248_create_sys_apis_table', 1);
INSERT INTO `migrations` VALUES (4, '2021_03_06_083927_create_sys_menus_table', 1);
INSERT INTO `migrations` VALUES (5, '2021_03_06_085805_create_sys_user_roles_table', 1);
INSERT INTO `migrations` VALUES (6, '2021_03_06_090225_create_sys_role_menus_table', 1);
INSERT INTO `migrations` VALUES (7, '2021_03_06_090739_create_sys_role_apis_table', 1);
INSERT INTO `migrations` VALUES (8, '2021_03_06_091139_create_sys_operation_records_table', 1);
INSERT INTO `migrations` VALUES (9, '2021_03_19_150223_create_api_docs_table', 1);
INSERT INTO `migrations` VALUES (10, '2021_03_19_151621_create_api_docs_records_table', 1);
INSERT INTO `migrations` VALUES (11, '2021_04_10_190420_create_sys_departments_table', 1);
INSERT INTO `migrations` VALUES (12, '2021_04_10_190906_create_sys_user_departments_table', 1);
INSERT INTO `migrations` VALUES (13, '2021_05_06_172840_create_airports_table', 1);
INSERT INTO `migrations` VALUES (14, '2021_05_06_173004_create_airline_companys_table', 1);

-- ----------------------------
-- Table structure for sys_apis
-- ----------------------------
DROP TABLE IF EXISTS `sys_apis`;
CREATE TABLE `sys_apis`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '查询：read，添加：create，更新：update，删除：delete',
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '名称（查询、添加、更新、删除）',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'api路径',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'api中文描述',
  `api_group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'api组',
  `method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'POST' COMMENT '请求方式',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间 null未删除',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sys_apis_path_index`(`path`) USING BTREE,
  INDEX `sys_apis_api_group_index`(`api_group`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 61 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统—请求接口表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_apis
-- ----------------------------
INSERT INTO `sys_apis` VALUES (1, 'list', '查询', 'manage/user/list', '用户列表', 'user', 'GET', NULL, '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (3, 'disable', '禁用/启用', 'manage/user/disable', '禁用或启用用户', 'user', 'POST', NULL, '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (4, 'list', '查询', 'manage/menu/list', '菜单列表', 'menu', 'GET', NULL, '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (5, 'create', '添加', 'manage/menu/create', '菜单添加', 'menu', 'POST', NULL, '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (6, 'update', '更新', 'manage/menu/update', '菜单更新', 'menu', 'PUT', NULL, '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (7, 'delete', '删除', 'manage/menu/delete', '菜单删除', 'menu', 'DELETE', NULL, '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (8, 'list', '查询', 'manage/role/list', '角色列表', 'role', 'GET', NULL, '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (9, 'create', '添加', 'manage/role/create', '角色添加', 'role', 'POST', NULL, '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (10, 'update', '更新', 'manage/role/update', '角色更新', 'role', 'PUT', NULL, '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (11, 'delete', '删除', 'manage/role/delete', '角色删除', 'role', 'DELETE', NULL, '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (12, 'list', '查询', 'manage/department/list', '部门列表', 'department', 'GET', NULL, '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (13, 'create', '添加', 'manage/department/create', '部门添加', 'department', 'POST', NULL, '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (14, 'update', '更新', 'manage/department/update', '部门更新', 'department', 'PUT', NULL, '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (15, 'delete', '删除', 'manage/department/delete', '部门删除', 'department', 'DELETE', NULL, '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (16, 'update', '更新', 'manage/user/update', '', 'user', 'PUT', NULL, '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (17, 'info', '详情', 'manage/user/info', '', 'user', 'GET', '2021-05-21 09:33:43', '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (18, 'select', '下拉选择', 'manage/department/select', '', 'department', 'GET', '2021-05-21 09:35:33', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (20, 'select', '下拉选择', 'manage/role/select', '', 'role', 'GET', '2021-05-21 09:35:33', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (21, 'list', '查询', 'manage/api/list', '', 'api', 'GET', '2021-05-21 09:35:33', '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (22, 'select', '下拉选择', 'manage/api/select', '', 'api', 'GET', '2021-05-21 09:35:33', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (23, 'role_list', '角色权限列表', 'manage/permisson/role_list', '', 'permisson', 'GET', '2021-05-21 09:35:33', '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (24, 'department_list', '部门权限列表', 'manage/permisson/department_list', '', 'permisson', 'GET', '2021-05-21 09:35:33', '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (25, 'list', '查询', 'manage/subject/list', '', 'subject', 'GET', '2021-05-21 09:35:33', '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (28, 'create', '添加', 'manage/user/create', '', 'user', 'POST', '2021-05-21 09:35:33', '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (29, 'setting_role', '', 'manage/user/setting_role', '', 'user', 'POST', '2021-05-21 09:35:33', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (30, 'role_auth', '分配角色权限', 'manage/permisson/role_auth', '', 'permisson', 'POST', '2021-05-21 09:35:33', '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (31, 'department_auth', '分配部门权限', 'manage/permisson/department_auth', '', 'permisson', 'POST', '2021-05-21 09:35:33', '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (32, 'create', '添加', 'manage/subject/create', '', 'subject', 'POST', '2021-05-21 09:35:33', '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (33, 'update', '更新', 'manage/subject/update', '', 'subject', 'PUT', '2021-05-21 09:35:33', '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (34, 'batch_del', '批量删除', 'manage/role/batch_del', '', 'role', 'DELETE', '2021-05-21 09:35:33', '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (35, 'delete', '删除', 'manage/subject/delete', '', 'subject', 'DELETE', '2021-05-21 09:35:33', '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (36, 'batch_del', '批量删除', 'manage/subject/batch_del', '', 'subject', 'DELETE', '2021-05-21 09:35:33', '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (37, 'delete', '删除', 'manage/user/delete', '', 'user', 'DELETE', '2021-05-21 14:13:02', '2021-05-21 14:17:06', NULL);
INSERT INTO `sys_apis` VALUES (38, 'render_list', '', 'manage/menu/render_list', '', 'menu', 'GET', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (39, 'list', '查询', 'manage/platform/list', '', 'platform', 'GET', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (40, 'select', '下拉选择', 'manage/platform/select', '', 'platform', 'GET', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (41, 'list', '查询', 'manage/shop/list', '', 'shop', 'GET', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (42, 'list', '查询', 'manage/aviation/airline/list', '', 'aviation', 'GET', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (43, 'list', '查询', 'manage/aviation/airports/list', '', 'aviation', 'GET', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (44, 'logout', '', 'manage/logout', '', 'logout', 'POST', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (45, 'create', '添加', 'manage/platform/create', '', 'platform', 'POST', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (46, 'create', '添加', 'manage/shop/create', '', 'shop', 'POST', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (47, 'create', '添加', 'manage/aviation/airline/create', '', 'aviation', 'POST', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (48, 'create', '添加', 'manage/aviation/airports/create', '', 'aviation', 'POST', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (49, 'update', '更新', 'manage/platform/update', '', 'platform', 'PUT', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (50, 'update', '更新', 'manage/shop/update', '', 'shop', 'PUT', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (51, 'update', '更新', 'manage/aviation/airline/update', '', 'aviation', 'PUT', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (52, 'update', '更新', 'manage/aviation/airports/update', '', 'aviation', 'PUT', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (53, 'delete', '删除', 'manage/platform/delete', '', 'platform', 'DELETE', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (54, 'batch_del', '批量删除', 'manage/platform/batch_del', '', 'platform', 'DELETE', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (55, 'delete', '删除', 'manage/shop/delete', '', 'shop', 'DELETE', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (56, 'batch_del', '批量删除', 'manage/shop/batch_del', '', 'shop', 'DELETE', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (57, 'delete', '删除', 'manage/aviation/airline/delete', '', 'aviation', 'DELETE', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (58, 'batch_del', '批量删除', 'manage/aviation/airline/batch_del', '', 'aviation', 'DELETE', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (59, 'delete', '删除', 'manage/aviation/airports/delete', '', 'aviation', 'DELETE', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);
INSERT INTO `sys_apis` VALUES (60, 'batch_del', '批量删除', 'manage/aviation/airports/batch_del', '', 'aviation', 'DELETE', '2021-05-26 11:51:12', '2021-05-26 11:51:12', NULL);

-- ----------------------------
-- Table structure for sys_config
-- ----------------------------
DROP TABLE IF EXISTS `sys_config`;
CREATE TABLE `sys_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'key值',
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'value值',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '备注',
  `group` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '组',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `sys_operation_records_key_unique`(`key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统配置表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_config
-- ----------------------------
INSERT INTO `sys_config` VALUES (1, 'system_log_level', '2', '系统日志等级', '', NULL, NULL);
INSERT INTO `sys_config` VALUES (2, 'page', '1', '非必须', '', '2021-05-27 13:38:31', '2021-05-27 17:20:48');
INSERT INTO `sys_config` VALUES (3, 'size', '10', '分页size', '', '2021-05-27 13:38:57', '2021-05-27 13:38:57');

-- ----------------------------
-- Table structure for sys_department_menus
-- ----------------------------
DROP TABLE IF EXISTS `sys_department_menus`;
CREATE TABLE `sys_department_menus`  (
  `department_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '部门ID',
  `menu_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '菜单ID',
  `ability` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限：序列化[\"READ\",\"WRITE\",\"UPDATE\",\"DELETE\",\"IMPORT\",\"EXPRORT\"]',
  INDEX `sys_role_menus_role_id_index`(`department_id`) USING BTREE,
  INDEX `sys_role_menus_menu_id_index`(`menu_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统—部门菜单关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_department_menus
-- ----------------------------
INSERT INTO `sys_department_menus` VALUES (2, 1, '');
INSERT INTO `sys_department_menus` VALUES (2, 4, 'read,create,update,disable,delete');
INSERT INTO `sys_department_menus` VALUES (2, 14, 'read,create,update,delete');
INSERT INTO `sys_department_menus` VALUES (5, 20, 'list');
INSERT INTO `sys_department_menus` VALUES (5, 22, 'role_list,department_list,role_auth,department_auth');
INSERT INTO `sys_department_menus` VALUES (5, 2, 'list,create,update,delete,select');
INSERT INTO `sys_department_menus` VALUES (5, 3, 'list,create,update,delete,select');
INSERT INTO `sys_department_menus` VALUES (5, 4, 'list,create,disable,update,info,select,setting_role,delete');
INSERT INTO `sys_department_menus` VALUES (5, 19, 'list,create,update,delete');
INSERT INTO `sys_department_menus` VALUES (5, 23, 'list,create,update,delete,batch_del');
INSERT INTO `sys_department_menus` VALUES (17, 20, 'list');
INSERT INTO `sys_department_menus` VALUES (17, 22, 'role_list,department_list,role_auth,department_auth');
INSERT INTO `sys_department_menus` VALUES (17, 2, 'list,create,update,delete,select');
INSERT INTO `sys_department_menus` VALUES (17, 3, 'list,create,update,delete,select');
INSERT INTO `sys_department_menus` VALUES (17, 4, 'list,create,disable,update,info,select,setting_role,delete');
INSERT INTO `sys_department_menus` VALUES (17, 19, 'list,create,update,delete');
INSERT INTO `sys_department_menus` VALUES (17, 23, 'list,create,update,delete,batch_del');

-- ----------------------------
-- Table structure for sys_departments
-- ----------------------------
DROP TABLE IF EXISTS `sys_departments`;
CREATE TABLE `sys_departments`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '部门名称',
  `parent_id` int(11) NOT NULL DEFAULT 0 COMMENT '父级部门ID(不继承角色权限)',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '备注',
  `role_ids` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '部门拥有的角色权限ID【逗号分隔】,部门公共角色权限',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `all_parent_id` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '所有上级id',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sys_departments_department_name_index`(`department_name`) USING BTREE,
  INDEX `sys_departments_parent_id_index`(`parent_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统—部门表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_departments
-- ----------------------------
INSERT INTO `sys_departments` VALUES (2, '研发部1', 5, '', '', '2021-05-17 07:20:06', '2021-05-24 20:24:30', '5');
INSERT INTO `sys_departments` VALUES (3, '研发部2组', 5, '', '', '2021-05-17 07:35:06', '2021-05-24 20:26:27', '5');
INSERT INTO `sys_departments` VALUES (4, '研发部3组', 5, '', '', '2021-05-17 07:35:11', '2021-05-24 20:26:49', '5');
INSERT INTO `sys_departments` VALUES (5, '设计部', 0, '', '', '2021-05-17 07:35:42', '2021-05-26 16:57:48', NULL);
INSERT INTO `sys_departments` VALUES (6, '测试部', 2, '', '', '2021-05-17 07:35:48', '2021-05-17 07:35:48', NULL);
INSERT INTO `sys_departments` VALUES (7, '产品部', 5, '', '', '2021-05-17 07:36:04', '2021-05-24 20:27:01', '5');
INSERT INTO `sys_departments` VALUES (8, '运营部', 0, '', '', '2021-05-17 07:36:26', '2021-05-17 07:36:26', NULL);
INSERT INTO `sys_departments` VALUES (13, 'a', 0, '', '', '2021-05-21 16:57:04', '2021-05-21 16:57:04', NULL);
INSERT INTO `sys_departments` VALUES (14, 'aa', 17, '123213', '', '2021-05-21 16:57:15', '2021-05-27 10:34:07', '17');
INSERT INTO `sys_departments` VALUES (15, 'aaa', 14, '', '', '2021-05-21 16:57:24', '2021-05-21 16:57:24', '13,14');
INSERT INTO `sys_departments` VALUES (16, 'aaaa', 15, '', '', '2021-05-21 16:57:33', '2021-05-21 16:57:33', '13,14,15');
INSERT INTO `sys_departments` VALUES (17, '55566', 0, '', '', '2021-05-23 00:05:20', '2021-05-24 16:56:19', NULL);
INSERT INTO `sys_departments` VALUES (18, 'bbbb', 15, '', '', '2021-05-24 14:25:45', '2021-05-24 14:25:45', '13,14,15');

-- ----------------------------
-- Table structure for sys_menu_apis
-- ----------------------------
DROP TABLE IF EXISTS `sys_menu_apis`;
CREATE TABLE `sys_menu_apis`  (
  `menu_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '菜单ID',
  `api_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '请求接口ID',
  `ability_key` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'read,create,update',
  `ability_value` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '查询，添加，更新',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '接口路由',
  INDEX `sys_role_apis_role_id_index`(`menu_id`) USING BTREE,
  INDEX `sys_role_apis_api_id_index`(`api_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统—角色请求接口关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_menu_apis
-- ----------------------------
INSERT INTO `sys_menu_apis` VALUES (22, 23, 'role_list', '角色权限列表', 'manage/permisson/role_list');
INSERT INTO `sys_menu_apis` VALUES (22, 24, 'department_list', '部门权限列表', 'manage/permisson/department_list');
INSERT INTO `sys_menu_apis` VALUES (22, 30, 'role_auth', '分配角色权限', 'manage/permisson/role_auth');
INSERT INTO `sys_menu_apis` VALUES (22, 31, 'department_auth', '分配部门权限', 'manage/permisson/department_auth');
INSERT INTO `sys_menu_apis` VALUES (3, 8, 'list', '查询', 'manage/role/list');
INSERT INTO `sys_menu_apis` VALUES (3, 9, 'create', '添加', 'manage/role/create');
INSERT INTO `sys_menu_apis` VALUES (3, 10, 'update', '更新', 'manage/role/update');
INSERT INTO `sys_menu_apis` VALUES (3, 11, 'delete', '删除', 'manage/role/delete');
INSERT INTO `sys_menu_apis` VALUES (3, 20, 'select', '选择列表', 'manage/role/select');
INSERT INTO `sys_menu_apis` VALUES (4, 1, 'list', '查询', 'manage/user/list');
INSERT INTO `sys_menu_apis` VALUES (4, 2, 'create', '添加', 'manager/user/create');
INSERT INTO `sys_menu_apis` VALUES (4, 3, 'disable', '禁用/启用', 'manage/user/disable');
INSERT INTO `sys_menu_apis` VALUES (4, 16, 'update', '更新', 'manage/user/update');
INSERT INTO `sys_menu_apis` VALUES (4, 17, 'info', '详情', 'manage/user/info');
INSERT INTO `sys_menu_apis` VALUES (4, 18, 'select', '选择列表', 'manage/department/select');
INSERT INTO `sys_menu_apis` VALUES (4, 29, 'setting_role', '分配角色', 'manage/user/setting_role');
INSERT INTO `sys_menu_apis` VALUES (4, 37, 'delete', '删除', 'manage/user/delete');
INSERT INTO `sys_menu_apis` VALUES (29, 21, 'list', '查询', 'manage/api/list');
INSERT INTO `sys_menu_apis` VALUES (19, 12, 'list', '查询', 'manage/department/list');
INSERT INTO `sys_menu_apis` VALUES (19, 13, 'create', '添加', 'manage/department/create');
INSERT INTO `sys_menu_apis` VALUES (19, 14, 'update', '更新', 'manage/department/update');
INSERT INTO `sys_menu_apis` VALUES (19, 15, 'delete', '删除', 'manage/department/delete');
INSERT INTO `sys_menu_apis` VALUES (2, 4, 'list', '查询', 'manage/menu/list');
INSERT INTO `sys_menu_apis` VALUES (2, 5, 'create', '添加', 'manage/menu/create');
INSERT INTO `sys_menu_apis` VALUES (2, 6, 'update', '更新', 'manage/menu/update');
INSERT INTO `sys_menu_apis` VALUES (2, 7, 'delete', '删除', 'manage/menu/delete');
INSERT INTO `sys_menu_apis` VALUES (2, 22, 'select', '选择列表', 'manage/api/select');
INSERT INTO `sys_menu_apis` VALUES (20, 21, 'list', '查询', 'manage/api/list');
INSERT INTO `sys_menu_apis` VALUES (23, 25, 'list', '查询', 'manage/subject/list');
INSERT INTO `sys_menu_apis` VALUES (23, 32, 'create', '添加', 'manage/subject/create');
INSERT INTO `sys_menu_apis` VALUES (23, 33, 'update', '更新', 'manage/subject/update');
INSERT INTO `sys_menu_apis` VALUES (23, 35, 'delete', '删除', 'manage/subject/delete');
INSERT INTO `sys_menu_apis` VALUES (23, 36, 'batch_del', '批量删除', 'manage/subject/batch_del');

-- ----------------------------
-- Table structure for sys_menus
-- ----------------------------
DROP TABLE IF EXISTS `sys_menus`;
CREATE TABLE `sys_menus`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '路由name',
  `menu_level` int(11) NOT NULL DEFAULT 0 COMMENT '菜单等级，0表示顶级菜单',
  `parent_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '父菜单ID',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '路由path',
  `hidden` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否显示在菜单中显示路由（默认值：false）',
  `component` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '对应前端文件路径',
  `sort` int(11) NOT NULL DEFAULT 99 COMMENT '排序标记',
  `keep_alive` tinyint(1) NOT NULL DEFAULT 1 COMMENT '附加属性:当前路由是否缓存（默认值：true）',
  `default_menu` tinyint(1) NOT NULL DEFAULT 0 COMMENT '附加属性:',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '附加属性:标题',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '附加属性:菜单描述',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '附加属性:icon',
  `close_tab` tinyint(1) NOT NULL DEFAULT 0 COMMENT '附加属性:是否隐藏关闭按钮',
  `hidden_tab` tinyint(1) NOT NULL DEFAULT 0 COMMENT '附加属性:是否不显示多标签页',
  `role_mode` tinyint(4) NOT NULL DEFAULT 1 COMMENT '附加属性: 1[oneOf] 数组内拥有任一角色，返回True(等价第1种数据);2[allOf] 数组内所有角色都拥有，返回True; 3[except] 不拥有数组内任一角色，返回True(取反)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间 null未删除',
  `redirect` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '跳转路径',
  `disable` tinyint(1) NULL DEFAULT 0 COMMENT '是否禁用，0：否，1：是',
  `all_parent_id` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '所有父级id',
  `always_show` tinyint(1) NULL DEFAULT 1 COMMENT '当只有一级子路由时是否显示父路由是否显示在菜单中显示路由（默认值：1），必填项',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sys_menus_parent_id_index`(`parent_id`) USING BTREE,
  INDEX `sys_menus_path_index`(`path`) USING BTREE,
  INDEX `sys_menus_name_index`(`name`) USING BTREE,
  INDEX `sys_menus_title_index`(`title`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 51 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统—菜单表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_menus
-- ----------------------------
INSERT INTO `sys_menus` VALUES (1, '系统管理', 0, 0, '/system', 0, 'Layout', 99, 1, 0, '系统管理', '', '', 0, 0, 1, '2021-05-14 06:11:16', '2021-05-21 15:16:45', NULL, NULL, 0, NULL, 1);
INSERT INTO `sys_menus` VALUES (2, '菜单管理', 1, 30, 'meun', 0, '@/views/system/permission-setting/meuns', 99, 1, 0, '菜单管理', '', '', 0, 0, 1, '2021-05-14 06:12:15', '2021-05-24 17:29:41', NULL, NULL, 0, '1,22', 1);
INSERT INTO `sys_menus` VALUES (3, '角色管理', 1, 30, 'roles', 0, '@/views/system/permission-setting/roles', 99, 1, 0, '角色管理', '', '', 0, 0, 1, '2021-05-14 06:15:03', '2021-05-21 15:16:57', NULL, NULL, 0, '1', 1);
INSERT INTO `sys_menus` VALUES (4, '用户管理', 1, 30, 'staff', 0, '@/views/system/permission-setting/staff', 99, 1, 0, '员工管理', '', '', 0, 0, 1, '2021-05-14 06:15:21', '2021-05-21 15:16:59', NULL, NULL, 0, '1', 1);
INSERT INTO `sys_menus` VALUES (19, 'HfGyxpXfasdANqxcy45QPaiFBYk7fHqK', 0, 30, 'department', 0, '@/views/system/permission-setting/departments', 99, 0, 0, '部门管理', '', '', 0, 0, 1, '2021-05-21 11:44:28', '2021-05-24 17:18:00', NULL, NULL, 0, '1', 1);
INSERT INTO `sys_menus` VALUES (20, 'sbydIuV2nsHyNsccqawlNqLjb30skiYZ', 0, 30, 'api', 0, '@/views/system/permission-setting/apis', 0, 0, 0, '接口管理', '', '', 0, 0, 1, '2021-05-21 11:47:51', '2021-05-25 09:30:19', NULL, '', 0, '1,30', 1);
INSERT INTO `sys_menus` VALUES (22, 'NxbURsWXmve7MLjtlSk612QjAzTSRLEs', 0, 30, 'permission', 0, '@/views/system/permission-setting/permission', 0, 0, 0, '权限管理', '', '', 0, 0, 1, '2021-05-21 12:00:57', '2021-05-21 15:16:52', NULL, NULL, 0, '1', 1);
INSERT INTO `sys_menus` VALUES (23, 'pBHLU2gZ5nPZJuFW9cOQRbwL5av1g49n', 0, 31, 'subject', 0, '@/views/system/sell/subject', 0, 0, 0, '收支科目', '', '', 0, 0, 1, '2021-05-21 13:13:39', '2021-05-26 16:45:21', NULL, NULL, 0, '1,31', 1);
INSERT INTO `sys_menus` VALUES (24, 'rMbgQiSMqQI2xVrtITp7ZnMIWznetJv3', 0, 0, '/', 0, 'Layout', 0, 0, 0, '仪表盘', '', '', 0, 0, 1, '2021-05-21 13:15:32', '2021-05-21 17:05:55', NULL, '/welcome', 0, NULL, 1);
INSERT INTO `sys_menus` VALUES (25, 'rMbgQiSMqQI2xVrtITp7ZnMIWznetJv3', 0, 24, 'welcome', 0, '@/views/dashboard/welcome/index', 0, 0, 0, '欢迎页', '', '', 0, 0, 1, '2021-05-21 13:16:04', '2021-05-21 13:16:04', NULL, NULL, 0, NULL, 1);
INSERT INTO `sys_menus` VALUES (30, 'cunR0YZUAgRF2A5WqUz6xp7kKRwflVAa', 0, 1, 'permission-setting', 0, '@/views/system/permission-setting', 0, 0, 0, '权限设置', '', '', 0, 0, 1, '2021-05-24 17:03:33', '2021-05-25 17:30:23', NULL, '', 0, '1', 1);
INSERT INTO `sys_menus` VALUES (31, 'q7ICCKn4ppHOw44ZDxo6yBkkA39pkode', 0, 1, 'sell', 0, '@/views/system/sell', 0, 0, 0, '销售配置', '', '', 0, 0, 1, '2021-05-24 18:17:27', '2021-05-25 17:30:16', NULL, '', 0, '1', 1);
INSERT INTO `sys_menus` VALUES (32, 'uLTCbQoTRFhiMYhxFfMuCqzOJfENFT5R', 0, 31, 'paltform', 1, '@/views/system/sell/paltform', 2, 0, 0, '平台管理', '', '', 0, 0, 1, '2021-05-25 15:25:43', '2021-05-26 16:44:25', NULL, '', 0, '1,31', 1);
INSERT INTO `sys_menus` VALUES (33, 'uLTCbQoTRFhiMYhxFfMuCqzOJfENFT5N', 0, 31, 'shop', 0, '@/views/system/sell/shop', 0, 0, 0, '店铺管理', '', '', 0, 0, 1, '2021-05-25 15:26:30', '2021-05-25 15:26:30', NULL, '', 0, '1,31', 1);
INSERT INTO `sys_menus` VALUES (34, 'Oqvc4r6TZ1qtnhlTQZuCTgNyhCOi45d7', 0, 0, 'air-data', 0, 'Layout', 0, 0, 0, '航空数据', '', '', 0, 0, 1, '2021-05-26 09:39:58', '2021-05-26 09:39:58', NULL, '', 0, NULL, 1);
INSERT INTO `sys_menus` VALUES (35, 'Oqvc4r6TZ1qtnhlTQZuCTgNyhCOi45d8', 0, 34, 'airport-management', 0, '@/views/air-data/airport-management', 0, 0, 0, '机场管理', '', '', 0, 0, 1, '2021-05-26 09:43:04', '2021-05-26 11:35:04', NULL, '/air-data/airport-management/airport', 0, '34', 1);
INSERT INTO `sys_menus` VALUES (36, 'Oqvc4r6TZ1qtnhlTQZuCTgNyhCOi45d1', 0, 35, 'airport', 0, '@/views/air-data/airport-management/airport', 0, 0, 0, '机场管理', '', '', 0, 0, 1, '2021-05-26 09:54:03', '2021-05-26 11:34:35', NULL, '', 0, '34,35', 1);
INSERT INTO `sys_menus` VALUES (37, 'Oqvc4r6TZ1qtnhlTQZuCTgNyhCOi45d3', 0, 35, 'airline', 0, '@/views/air-data/airport-management/airline', 0, 0, 0, '航司管理', '', '', 0, 0, 1, '2021-05-26 09:56:48', '2021-05-26 09:56:48', NULL, '', 0, '34,35', 1);
INSERT INTO `sys_menus` VALUES (38, 'RHvCLVX1MgL4owtlyUdggTbEf8ErRmGz', 0, 34, 'cnninf', 0, '@/views/air-data/cnninf', 0, 0, 0, '携带儿童婴儿', '', '', 0, 0, 1, '2021-05-27 13:39:17', '2021-05-27 14:38:38', NULL, '', 0, '34', 1);
INSERT INTO `sys_menus` VALUES (39, 'YskUD3qQCtlG5mvhweoDB5AAJl7sypPh', 0, 34, 'fuel', 0, '@/views/air-data/fuel', 0, 0, 0, '燃油费', '', '', 0, 0, 1, '2021-05-27 13:41:32', '2021-05-27 14:58:59', NULL, '', 0, '34', 1);
INSERT INTO `sys_menus` VALUES (40, 'ApDSYUmauWnrNi3GDLcPM0QxxU7KCr5y', 0, 34, 'models', 0, '@/views/air-data/machine-table', 0, 0, 0, '机型表', '', '', 0, 0, 1, '2021-05-27 13:44:17', '2021-05-27 16:10:39', NULL, '', 0, '34', 1);
INSERT INTO `sys_menus` VALUES (41, '', 0, 34, 'guest-gauge', 0, '@/views/air-data/guest-gauge-management', 0, 0, 0, '客规管理', '', '', 0, 0, 1, '2021-05-27 13:47:13', '2021-05-27 13:47:13', NULL, '', 0, '34', 1);
INSERT INTO `sys_menus` VALUES (42, '', 0, 41, 'time-type', 0, '@/views/test', 0, 0, 0, '客规时间类型', '', '', 0, 0, 1, '2021-05-27 13:48:20', '2021-05-27 13:48:20', NULL, '', 0, '34,41', 1);
INSERT INTO `sys_menus` VALUES (43, '', 0, 41, 'index', 0, '@/views/test', 0, 0, 0, '客规管理', '', '', 0, 0, 1, '2021-05-27 13:48:56', '2021-05-27 13:48:56', NULL, '', 0, '34,41', 1);
INSERT INTO `sys_menus` VALUES (44, '', 0, 34, 'positions-manage', 0, '@/views/air-data/positions-manage', 0, 0, 0, '仓位管理', '', '', 0, 0, 1, '2021-05-27 13:50:49', '2021-05-27 13:50:49', NULL, '', 0, '34', 1);
INSERT INTO `sys_menus` VALUES (45, '', 0, 44, 'tables', 0, '@/views/test', 0, 0, 0, '仓位表', '', '', 0, 0, 1, '2021-05-27 13:52:14', '2021-05-27 13:52:14', NULL, '', 0, '34,44', 1);
INSERT INTO `sys_menus` VALUES (46, '', 0, 44, 'level', 0, '@/views/test', 0, 0, 0, '仓位等级', '', '', 0, 0, 1, '2021-05-27 13:52:27', '2021-05-27 13:52:27', NULL, '', 0, '34,44', 1);
INSERT INTO `sys_menus` VALUES (47, '', 0, 44, 'blacklist', 0, '@/views/test', 0, 0, 0, '仓位黑名单', '', '', 0, 0, 1, '2021-05-27 13:52:47', '2021-05-27 13:52:47', NULL, '', 0, '34,44', 1);
INSERT INTO `sys_menus` VALUES (48, 'FiQC8jnQEYerPF978GCeOdznOlWCX34d', 0, 1, 'logs', 0, '@/views/system/logs', 0, 0, 0, '日志管理', '', '', 0, 0, 1, '2021-05-27 16:37:14', '2021-05-27 16:37:14', NULL, '', 0, '1', 1);
INSERT INTO `sys_menus` VALUES (49, 'vW04blWREnp1kA4yucNYOi4hl0d4QFMC', 0, 1, 'setting', 0, '@/views/system/setting/', 0, 0, 0, '系统设置', '', '', 0, 0, 1, '2021-05-27 17:08:13', '2021-05-27 17:08:13', NULL, '', 0, '1', 1);
INSERT INTO `sys_menus` VALUES (50, 'U6d2JxnUxPZgWfsxe5G5Qrb9rVepr4WJ', 0, 49, 'base', 0, '@/views/system/setting/configuration', 0, 0, 0, '基础数据', '', '', 0, 0, 1, '2021-05-27 17:08:56', '2021-05-27 17:08:56', NULL, '', 0, '1,49', 1);

-- ----------------------------
-- Table structure for sys_operation_records
-- ----------------------------
DROP TABLE IF EXISTS `sys_operation_records`;
CREATE TABLE `sys_operation_records`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求ip',
  `method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求路径',
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '请求状态',
  `latency` int(11) NOT NULL DEFAULT 0 COMMENT '延迟',
  `agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '代理',
  `error_message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '错误信息',
  `request_body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '请求Body',
  `response_body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '响应Body',
  `user_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '用户ID',
  `level` tinyint(1) NULL DEFAULT NULL COMMENT '1:增删改查，2：增删改，3：删改',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间 null未删除',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sys_operation_records_ip_index`(`ip`) USING BTREE,
  INDEX `sys_operation_records_path_index`(`path`) USING BTREE,
  INDEX `sys_operation_records_status_index`(`status`) USING BTREE,
  INDEX `sys_operation_records_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统—运行记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_operation_records
-- ----------------------------
INSERT INTO `sys_operation_records` VALUES (1, '192.168.31.119', 'POST', 'manage/configure/create', 200, 0, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36', '', '{\"key\":\"1\",\"value\":\"1\",\"remark\":\"\",\"group\":\"\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":[]}', 1, 2, '2021-05-27 17:20:24', '2021-05-27 17:20:24', NULL);
INSERT INTO `sys_operation_records` VALUES (2, '192.168.31.119', 'PUT', 'manage/configure/update', 200, 0, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36', '', '{\"id\":4,\"key\":\"1\",\"value\":\"2\",\"remark\":\"\",\"group\":\"\",\"created_at\":\"2021-05-27 17:20:24\",\"updated_at\":\"2021-05-27 17:20:24\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":[]}', 1, 2, '2021-05-27 17:20:28', '2021-05-27 17:20:28', NULL);
INSERT INTO `sys_operation_records` VALUES (3, '192.168.31.119', 'DELETE', 'manage/configure/delete', 200, 0, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36', '', '{\"id\":4}', '{\"code\":0,\"msg\":\"successful\",\"data\":[]}', 1, 2, '2021-05-27 17:20:30', '2021-05-27 17:20:30', NULL);
INSERT INTO `sys_operation_records` VALUES (4, '172.17.0.1', 'PUT', 'manage/configure/update', 200, 0, 'PostmanRuntime/7.28.0', '', '{\"id\":\"2\",\"key\":\"page\",\"value\":\"1\",\"remark\":\"非必须\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":[]}', 1, 2, '2021-05-27 17:20:48', '2021-05-27 17:20:48', NULL);
INSERT INTO `sys_operation_records` VALUES (5, '192.168.31.119', 'POST', 'login', 200, 0, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36', '', '{\"username\":\"admin\",\"password\":\"123456\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":{\"id\":1,\"email\":\"291846153@qq.com\",\"phone\":\"18202821916\",\"username\":\"admin\",\"nickname\":\"\",\"realname\":\"超级管理员\",\"avatar\":\"\",\"lastLoginAt\":\"2021-05-27 17:22:56\",\"status\":1,\"lastIp\":\"192.168.31.119\",\"updatedAt\":\"2021-05-27 17:22:56\",\"roleIds\":[1],\"roleName\":[\"系统管理员\"],\"departmentId\":0,\"accessToken\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJkZWZhdWx0XzYwYWY2NGYwYjcxY2YyLjYyODk4ODgzIiwiaWF0IjoxNjIyMTA3Mzc2LCJuYmYiOjE2MjIxMDczNzYsImV4cCI6MTYyMjE5Mzc3NiwiaWQiOjEsImVtYWlsIjoiMjkxODQ2MTUzQHFxLmNvbSIsInBob25lIjoiMTgyMDI4MjE5MTYiLCJ1c2VybmFtZSI6ImFkbWluIiwibmlja25hbWUiOiIiLCJyZWFsbmFtZSI6Ilx1OGQ4NVx1N2VhN1x1N2JhMVx1NzQwNlx1NTQ1OCIsInBhc3N3b3JkIjoiJDJ5JDEwJHZhMTNjNDVTR3RUNjRjd3djLmJPaC5WWmtmcWxqRzVkaGN3Z0ZUOHhXSnFpRlZ5NllWXC90aSIsImF2YXRhciI6IiIsImxhc3RfbG9naW5fYXQiOiIyMDIxLTA1LTI3IDE3OjIyOjU2Iiwic3RhdHVzIjoxLCJsYXN0X2lwIjoiMTkyLjE2OC4zMS4xMTkiLCJ1cGRhdGVkX2F0IjoiMjAyMS0wNS0yNyAxNzoyMjo1NiIsInJvbGVfaWRzIjpbMV0sInJvbGVfbmFtZSI6WyJcdTdjZmJcdTdlZGZcdTdiYTFcdTc0MDZcdTU0NTgiXSwiZGVwYXJ0bWVudF9pZCI6MCwiZGVwYXJ0bWVudF9uYW1lIjoiIiwiand0X3NjZW5lIjoiZGVmYXVsdCJ9.SoxMQKou9U2mLpr1787hl6jR_3QUDS3ZZVEoBubidDY\",\"expireToken\":86400,\"departmentName\":\"\"}}', 1, 2, '2021-05-27 17:22:56', '2021-05-27 17:22:56', NULL);
INSERT INTO `sys_operation_records` VALUES (6, '172.17.0.1', 'POST', 'login', 200, 0, 'PostmanRuntime/7.28.0', '', '{\"username\":\"admin\",\"password\":\"123456\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":{\"id\":1,\"email\":\"291846153@qq.com\",\"phone\":\"18202821916\",\"username\":\"admin\",\"nickname\":\"\",\"realname\":\"超级管理员\",\"avatar\":\"\",\"lastLoginAt\":\"2021-05-27 17:36:38\",\"status\":1,\"lastIp\":\"172.17.0.1\",\"updatedAt\":\"2021-05-27 17:36:38\",\"roleIds\":[1],\"roleName\":[\"系统管理员\"],\"departmentId\":0,\"accessToken\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJkZWZhdWx0XzYwYWY2ODI2OTg2NGEzLjc4Mjc2NjY2IiwiaWF0IjoxNjIyMTA4MTk4LCJuYmYiOjE2MjIxMDgxOTgsImV4cCI6MTYyMjExNTM5OCwiaWQiOjEsImVtYWlsIjoiMjkxODQ2MTUzQHFxLmNvbSIsInBob25lIjoiMTgyMDI4MjE5MTYiLCJ1c2VybmFtZSI6ImFkbWluIiwibmlja25hbWUiOiIiLCJyZWFsbmFtZSI6Ilx1OGQ4NVx1N2VhN1x1N2JhMVx1NzQwNlx1NTQ1OCIsInBhc3N3b3JkIjoiJDJ5JDEwJHZhMTNjNDVTR3RUNjRjd3djLmJPaC5WWmtmcWxqRzVkaGN3Z0ZUOHhXSnFpRlZ5NllWXC90aSIsImF2YXRhciI6IiIsImxhc3RfbG9naW5fYXQiOiIyMDIxLTA1LTI3IDE3OjM2OjM4Iiwic3RhdHVzIjoxLCJsYXN0X2lwIjoiMTcyLjE3LjAuMSIsInVwZGF0ZWRfYXQiOiIyMDIxLTA1LTI3IDE3OjM2OjM4Iiwicm9sZV9pZHMiOlsxXSwicm9sZV9uYW1lIjpbIlx1N2NmYlx1N2VkZlx1N2JhMVx1NzQwNlx1NTQ1OCJdLCJkZXBhcnRtZW50X2lkIjowLCJkZXBhcnRtZW50X25hbWUiOiIiLCJqd3Rfc2NlbmUiOiJkZWZhdWx0In0.2MMp7inO6xK9SrfGoZ8Bl_Qpb8b30vGVNGTGOw8po3M\",\"expireToken\":7200,\"departmentName\":\"\"}}', 1, 2, '2021-05-27 17:36:38', '2021-05-27 17:36:38', NULL);
INSERT INTO `sys_operation_records` VALUES (7, '172.17.0.1', 'POST', 'login', 200, 0, 'PostmanRuntime/7.28.0', '', '{\"username\":\"admin\",\"password\":\"123456\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":{\"id\":1,\"email\":\"291846153@qq.com\",\"phone\":\"18202821916\",\"username\":\"admin\",\"nickname\":\"\",\"realname\":\"超级管理员\",\"avatar\":\"\",\"lastLoginAt\":\"2021-05-27 17:36:41\",\"status\":1,\"lastIp\":\"172.17.0.1\",\"updatedAt\":\"2021-05-27 17:36:41\",\"roleIds\":[1],\"roleName\":[\"系统管理员\"],\"departmentId\":0,\"accessToken\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJkZWZhdWx0XzYwYWY2ODI5NTcyYmUxLjkyMDI2MzcyIiwiaWF0IjoxNjIyMTA4MjAxLCJuYmYiOjE2MjIxMDgyMDEsImV4cCI6MTYyMjExNTQwMSwiaWQiOjEsImVtYWlsIjoiMjkxODQ2MTUzQHFxLmNvbSIsInBob25lIjoiMTgyMDI4MjE5MTYiLCJ1c2VybmFtZSI6ImFkbWluIiwibmlja25hbWUiOiIiLCJyZWFsbmFtZSI6Ilx1OGQ4NVx1N2VhN1x1N2JhMVx1NzQwNlx1NTQ1OCIsInBhc3N3b3JkIjoiJDJ5JDEwJHZhMTNjNDVTR3RUNjRjd3djLmJPaC5WWmtmcWxqRzVkaGN3Z0ZUOHhXSnFpRlZ5NllWXC90aSIsImF2YXRhciI6IiIsImxhc3RfbG9naW5fYXQiOiIyMDIxLTA1LTI3IDE3OjM2OjQxIiwic3RhdHVzIjoxLCJsYXN0X2lwIjoiMTcyLjE3LjAuMSIsInVwZGF0ZWRfYXQiOiIyMDIxLTA1LTI3IDE3OjM2OjQxIiwicm9sZV9pZHMiOlsxXSwicm9sZV9uYW1lIjpbIlx1N2NmYlx1N2VkZlx1N2JhMVx1NzQwNlx1NTQ1OCJdLCJkZXBhcnRtZW50X2lkIjowLCJkZXBhcnRtZW50X25hbWUiOiIiLCJqd3Rfc2NlbmUiOiJkZWZhdWx0In0.dfWZ7bx-926r9KbdL-Kb9Cenb_x9XQx3Z7nq6GQtCCU\",\"expireToken\":7200,\"departmentName\":\"\"}}', 1, 2, '2021-05-27 17:36:41', '2021-05-27 17:36:41', NULL);
INSERT INTO `sys_operation_records` VALUES (8, '172.17.0.1', 'POST', 'login', 200, 0, 'PostmanRuntime/7.28.0', '', '{\"username\":\"admin\",\"password\":\"123456\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":{\"id\":1,\"email\":\"291846153@qq.com\",\"phone\":\"18202821916\",\"username\":\"admin\",\"nickname\":\"\",\"realname\":\"超级管理员\",\"avatar\":\"\",\"lastLoginAt\":\"2021-05-27 17:36:42\",\"status\":1,\"lastIp\":\"172.17.0.1\",\"updatedAt\":\"2021-05-27 17:36:42\",\"roleIds\":[1],\"roleName\":[\"系统管理员\"],\"departmentId\":0,\"accessToken\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJkZWZhdWx0XzYwYWY2ODJhMjRiNDc3LjY3NjI3NzA2IiwiaWF0IjoxNjIyMTA4MjAyLCJuYmYiOjE2MjIxMDgyMDIsImV4cCI6MTYyMjExNTQwMiwiaWQiOjEsImVtYWlsIjoiMjkxODQ2MTUzQHFxLmNvbSIsInBob25lIjoiMTgyMDI4MjE5MTYiLCJ1c2VybmFtZSI6ImFkbWluIiwibmlja25hbWUiOiIiLCJyZWFsbmFtZSI6Ilx1OGQ4NVx1N2VhN1x1N2JhMVx1NzQwNlx1NTQ1OCIsInBhc3N3b3JkIjoiJDJ5JDEwJHZhMTNjNDVTR3RUNjRjd3djLmJPaC5WWmtmcWxqRzVkaGN3Z0ZUOHhXSnFpRlZ5NllWXC90aSIsImF2YXRhciI6IiIsImxhc3RfbG9naW5fYXQiOiIyMDIxLTA1LTI3IDE3OjM2OjQyIiwic3RhdHVzIjoxLCJsYXN0X2lwIjoiMTcyLjE3LjAuMSIsInVwZGF0ZWRfYXQiOiIyMDIxLTA1LTI3IDE3OjM2OjQyIiwicm9sZV9pZHMiOlsxXSwicm9sZV9uYW1lIjpbIlx1N2NmYlx1N2VkZlx1N2JhMVx1NzQwNlx1NTQ1OCJdLCJkZXBhcnRtZW50X2lkIjowLCJkZXBhcnRtZW50X25hbWUiOiIiLCJqd3Rfc2NlbmUiOiJkZWZhdWx0In0.kHnJtPx1qjd6oZxXHO05SzBwheVhghh3sanqW5KwuIQ\",\"expireToken\":7200,\"departmentName\":\"\"}}', 1, 2, '2021-05-27 17:36:42', '2021-05-27 17:36:42', NULL);
INSERT INTO `sys_operation_records` VALUES (9, '192.168.33.1', 'POST', 'login', 200, 0, 'PostmanRuntime/7.28.0', '', '{\"username\":\"admin\",\"password\":\"123456\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":{\"id\":1,\"email\":\"291846153@qq.com\",\"phone\":\"18202821916\",\"username\":\"admin\",\"nickname\":\"\",\"realname\":\"超级管理员\",\"avatar\":\"\",\"lastLoginAt\":\"2021-05-27 20:17:14\",\"status\":1,\"lastIp\":\"192.168.33.1\",\"updatedAt\":\"2021-05-27 20:17:14\",\"roleIds\":[1],\"roleName\":[\"系统管理员\"],\"departmentId\":0,\"accessToken\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJkZWZhdWx0XzYwYWY4ZGNhOTBmNzAwLjcyNTExNDM0IiwiaWF0IjoxNjIyMTE3ODM0LCJuYmYiOjE2MjIxMTc4MzQsImV4cCI6MTYyMjEyNTAzNCwiaWQiOjEsImVtYWlsIjoiMjkxODQ2MTUzQHFxLmNvbSIsInBob25lIjoiMTgyMDI4MjE5MTYiLCJ1c2VybmFtZSI6ImFkbWluIiwibmlja25hbWUiOiIiLCJyZWFsbmFtZSI6Ilx1OGQ4NVx1N2VhN1x1N2JhMVx1NzQwNlx1NTQ1OCIsInBhc3N3b3JkIjoiJDJ5JDEwJHZhMTNjNDVTR3RUNjRjd3djLmJPaC5WWmtmcWxqRzVkaGN3Z0ZUOHhXSnFpRlZ5NllWXC90aSIsImF2YXRhciI6IiIsImxhc3RfbG9naW5fYXQiOiIyMDIxLTA1LTI3IDIwOjE3OjE0Iiwic3RhdHVzIjoxLCJsYXN0X2lwIjoiMTkyLjE2OC4zMy4xIiwidXBkYXRlZF9hdCI6IjIwMjEtMDUtMjcgMjA6MTc6MTQiLCJyb2xlX2lkcyI6WzFdLCJyb2xlX25hbWUiOlsiXHU3Y2ZiXHU3ZWRmXHU3YmExXHU3NDA2XHU1NDU4Il0sImRlcGFydG1lbnRfaWQiOjAsImRlcGFydG1lbnRfbmFtZSI6IiIsImp3dF9zY2VuZSI6ImRlZmF1bHQifQ.upgwNsNWbldT3gOM57pym8lEDkZEPAaHtiD5c0Ox1zY\",\"expireToken\":7200,\"departmentName\":\"\"}}', 1, 2, '2021-05-27 20:17:14', '2021-05-27 20:17:14', NULL);
INSERT INTO `sys_operation_records` VALUES (10, '192.168.33.1', 'POST', 'login', 200, 0, 'PostmanRuntime/7.28.0', '', '{\"username\":\"admin\",\"password\":\"123456\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":{\"id\":1,\"email\":\"291846153@qq.com\",\"phone\":\"18202821916\",\"username\":\"admin\",\"nickname\":\"\",\"realname\":\"超级管理员\",\"avatar\":\"\",\"lastLoginAt\":\"2021-05-27 20:25:28\",\"status\":1,\"lastIp\":\"192.168.33.1\",\"updatedAt\":\"2021-05-27 20:25:29\",\"roleIds\":[1],\"roleName\":[\"系统管理员\"],\"departmentId\":0,\"accessToken\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJkZWZhdWx0XzYwYWY4ZmI5MmU0MmUzLjY2NTg2MDQ2IiwiaWF0IjoxNjIyMTE4MzI5LCJuYmYiOjE2MjIxMTgzMjksImV4cCI6MTYyMjEyNTUyOSwiaWQiOjEsImVtYWlsIjoiMjkxODQ2MTUzQHFxLmNvbSIsInBob25lIjoiMTgyMDI4MjE5MTYiLCJ1c2VybmFtZSI6ImFkbWluIiwibmlja25hbWUiOiIiLCJyZWFsbmFtZSI6Ilx1OGQ4NVx1N2VhN1x1N2JhMVx1NzQwNlx1NTQ1OCIsInBhc3N3b3JkIjoiJDJ5JDEwJHZhMTNjNDVTR3RUNjRjd3djLmJPaC5WWmtmcWxqRzVkaGN3Z0ZUOHhXSnFpRlZ5NllWXC90aSIsImF2YXRhciI6IiIsImxhc3RfbG9naW5fYXQiOiIyMDIxLTA1LTI3IDIwOjI1OjI4Iiwic3RhdHVzIjoxLCJsYXN0X2lwIjoiMTkyLjE2OC4zMy4xIiwidXBkYXRlZF9hdCI6IjIwMjEtMDUtMjcgMjA6MjU6MjkiLCJyb2xlX2lkcyI6WzFdLCJyb2xlX25hbWUiOlsiXHU3Y2ZiXHU3ZWRmXHU3YmExXHU3NDA2XHU1NDU4Il0sImRlcGFydG1lbnRfaWQiOjAsImRlcGFydG1lbnRfbmFtZSI6IiIsImp3dF9zY2VuZSI6ImRlZmF1bHQifQ.iA9DpmFCCMPdQ1YQjTFYAXp8luL9xKZ78KwILCqlzBU\",\"expireToken\":7200,\"departmentName\":\"\"}}', 1, 2, '2021-05-27 20:25:29', '2021-05-27 20:25:29', NULL);
INSERT INTO `sys_operation_records` VALUES (11, '192.168.33.1', 'POST', 'login', 200, 0, 'PostmanRuntime/7.28.0', '', '{\"username\":\"admin\",\"password\":\"123456\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":{\"id\":1,\"email\":\"291846153@qq.com\",\"phone\":\"18202821916\",\"username\":\"admin\",\"nickname\":\"\",\"realname\":\"超级管理员\",\"avatar\":\"\",\"lastLoginAt\":\"2021-05-27 20:26:43\",\"status\":1,\"lastIp\":\"192.168.33.1\",\"updatedAt\":\"2021-05-27 20:26:43\",\"roleIds\":[1],\"roleName\":[\"系统管理员\"],\"departmentId\":0,\"accessToken\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJkZWZhdWx0XzYwYWY5MDAzZTk2MTU5LjYxMzkzODg1IiwiaWF0IjoxNjIyMTE4NDAzLCJuYmYiOjE2MjIxMTg0MDMsImV4cCI6MTYyMjEyNTYwMywiaWQiOjEsImVtYWlsIjoiMjkxODQ2MTUzQHFxLmNvbSIsInBob25lIjoiMTgyMDI4MjE5MTYiLCJ1c2VybmFtZSI6ImFkbWluIiwibmlja25hbWUiOiIiLCJyZWFsbmFtZSI6Ilx1OGQ4NVx1N2VhN1x1N2JhMVx1NzQwNlx1NTQ1OCIsInBhc3N3b3JkIjoiJDJ5JDEwJHZhMTNjNDVTR3RUNjRjd3djLmJPaC5WWmtmcWxqRzVkaGN3Z0ZUOHhXSnFpRlZ5NllWXC90aSIsImF2YXRhciI6IiIsImxhc3RfbG9naW5fYXQiOiIyMDIxLTA1LTI3IDIwOjI2OjQzIiwic3RhdHVzIjoxLCJsYXN0X2lwIjoiMTkyLjE2OC4zMy4xIiwidXBkYXRlZF9hdCI6IjIwMjEtMDUtMjcgMjA6MjY6NDMiLCJyb2xlX2lkcyI6WzFdLCJyb2xlX25hbWUiOlsiXHU3Y2ZiXHU3ZWRmXHU3YmExXHU3NDA2XHU1NDU4Il0sImRlcGFydG1lbnRfaWQiOjAsImRlcGFydG1lbnRfbmFtZSI6IiIsImp3dF9zY2VuZSI6ImRlZmF1bHQifQ.X6kgHTqIW1XqfLDgESj-JBfUz6wkXoqTeD62KFXSRXo\",\"expireToken\":7200,\"departmentName\":\"\"}}', 1, 2, '2021-05-27 20:26:44', '2021-05-27 20:26:44', NULL);
INSERT INTO `sys_operation_records` VALUES (12, '192.168.33.1', 'POST', 'login', 200, 0, 'PostmanRuntime/7.28.0', '', '{\"username\":\"admin\",\"password\":\"123456\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":{\"id\":1,\"email\":\"291846153@qq.com\",\"phone\":\"18202821916\",\"username\":\"admin\",\"nickname\":\"\",\"realname\":\"超级管理员\",\"avatar\":\"\",\"lastLoginAt\":\"2021-05-27 20:27:58\",\"status\":1,\"lastIp\":\"192.168.33.1\",\"updatedAt\":\"2021-05-27 20:27:58\",\"roleIds\":[1],\"roleName\":[\"系统管理员\"],\"departmentId\":0,\"accessToken\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJkZWZhdWx0XzYwYWY5MDRlYzUzNDczLjk5NDE3NTAwIiwiaWF0IjoxNjIyMTE4NDc4LCJuYmYiOjE2MjIxMTg0NzgsImV4cCI6MTYyMjEyNTY3OCwiaWQiOjEsImVtYWlsIjoiMjkxODQ2MTUzQHFxLmNvbSIsInBob25lIjoiMTgyMDI4MjE5MTYiLCJ1c2VybmFtZSI6ImFkbWluIiwibmlja25hbWUiOiIiLCJyZWFsbmFtZSI6Ilx1OGQ4NVx1N2VhN1x1N2JhMVx1NzQwNlx1NTQ1OCIsInBhc3N3b3JkIjoiJDJ5JDEwJHZhMTNjNDVTR3RUNjRjd3djLmJPaC5WWmtmcWxqRzVkaGN3Z0ZUOHhXSnFpRlZ5NllWXC90aSIsImF2YXRhciI6IiIsImxhc3RfbG9naW5fYXQiOiIyMDIxLTA1LTI3IDIwOjI3OjU4Iiwic3RhdHVzIjoxLCJsYXN0X2lwIjoiMTkyLjE2OC4zMy4xIiwidXBkYXRlZF9hdCI6IjIwMjEtMDUtMjcgMjA6Mjc6NTgiLCJyb2xlX2lkcyI6WzFdLCJyb2xlX25hbWUiOlsiXHU3Y2ZiXHU3ZWRmXHU3YmExXHU3NDA2XHU1NDU4Il0sImRlcGFydG1lbnRfaWQiOjAsImRlcGFydG1lbnRfbmFtZSI6IiIsImp3dF9zY2VuZSI6ImRlZmF1bHQifQ.FsjhmR2ykdqV3pWmLE4a4N97ABQHsvahOQ_HyKSXG8Y\",\"expireToken\":7200,\"departmentName\":\"\"}}', 1, 2, '2021-05-27 20:27:58', '2021-05-27 20:27:58', NULL);
INSERT INTO `sys_operation_records` VALUES (13, '192.168.33.1', 'POST', 'login', 200, 0, 'PostmanRuntime/7.28.0', '', '{\"username\":\"admin\",\"password\":\"123456\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":{\"id\":1,\"email\":\"291846153@qq.com\",\"phone\":\"18202821916\",\"username\":\"admin\",\"nickname\":\"\",\"realname\":\"超级管理员\",\"avatar\":\"\",\"lastLoginAt\":\"2021-05-27 20:29:08\",\"status\":1,\"lastIp\":\"192.168.33.1\",\"updatedAt\":\"2021-05-27 20:29:08\",\"roleIds\":[1],\"roleName\":[\"系统管理员\"],\"departmentId\":0,\"accessToken\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJkZWZhdWx0XzYwYWY5MDk0M2I5Y2Y0LjE5ODQ4MjIzIiwiaWF0IjoxNjIyMTE4NTQ4LCJuYmYiOjE2MjIxMTg1NDgsImV4cCI6MTYyMjEyNTc0OCwiaWQiOjEsImVtYWlsIjoiMjkxODQ2MTUzQHFxLmNvbSIsInBob25lIjoiMTgyMDI4MjE5MTYiLCJ1c2VybmFtZSI6ImFkbWluIiwibmlja25hbWUiOiIiLCJyZWFsbmFtZSI6Ilx1OGQ4NVx1N2VhN1x1N2JhMVx1NzQwNlx1NTQ1OCIsInBhc3N3b3JkIjoiJDJ5JDEwJHZhMTNjNDVTR3RUNjRjd3djLmJPaC5WWmtmcWxqRzVkaGN3Z0ZUOHhXSnFpRlZ5NllWXC90aSIsImF2YXRhciI6IiIsImxhc3RfbG9naW5fYXQiOiIyMDIxLTA1LTI3IDIwOjI5OjA4Iiwic3RhdHVzIjoxLCJsYXN0X2lwIjoiMTkyLjE2OC4zMy4xIiwidXBkYXRlZF9hdCI6IjIwMjEtMDUtMjcgMjA6Mjk6MDgiLCJyb2xlX2lkcyI6WzFdLCJyb2xlX25hbWUiOlsiXHU3Y2ZiXHU3ZWRmXHU3YmExXHU3NDA2XHU1NDU4Il0sImRlcGFydG1lbnRfaWQiOjAsImRlcGFydG1lbnRfbmFtZSI6IiIsImp3dF9zY2VuZSI6ImRlZmF1bHQifQ.icO1cYyCPjbO2AetqELhmQRwYAxtZ3s7O37O87KTnUI\",\"expireToken\":7200,\"departmentName\":\"\"}}', 1, 2, '2021-05-27 20:29:08', '2021-05-27 20:29:08', NULL);
INSERT INTO `sys_operation_records` VALUES (14, '192.168.33.1', 'POST', 'login', 200, 0, 'PostmanRuntime/7.28.0', '', '{\"username\":\"admin\",\"password\":\"123456\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":{\"id\":1,\"email\":\"291846153@qq.com\",\"phone\":\"18202821916\",\"username\":\"admin\",\"nickname\":\"\",\"realname\":\"超级管理员\",\"avatar\":\"\",\"lastLoginAt\":\"2021-05-27 20:30:46\",\"status\":1,\"lastIp\":\"192.168.33.1\",\"updatedAt\":\"2021-05-27 20:30:46\",\"roleIds\":[1],\"roleName\":[\"系统管理员\"],\"departmentId\":0,\"accessToken\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJkZWZhdWx0XzYwYWY5MGY2ODc5ZjgxLjc5MDE5MTM3IiwiaWF0IjoxNjIyMTE4NjQ2LCJuYmYiOjE2MjIxMTg2NDYsImV4cCI6MTYyMjEyNTg0NiwiaWQiOjEsImVtYWlsIjoiMjkxODQ2MTUzQHFxLmNvbSIsInBob25lIjoiMTgyMDI4MjE5MTYiLCJ1c2VybmFtZSI6ImFkbWluIiwibmlja25hbWUiOiIiLCJyZWFsbmFtZSI6Ilx1OGQ4NVx1N2VhN1x1N2JhMVx1NzQwNlx1NTQ1OCIsInBhc3N3b3JkIjoiJDJ5JDEwJHZhMTNjNDVTR3RUNjRjd3djLmJPaC5WWmtmcWxqRzVkaGN3Z0ZUOHhXSnFpRlZ5NllWXC90aSIsImF2YXRhciI6IiIsImxhc3RfbG9naW5fYXQiOiIyMDIxLTA1LTI3IDIwOjMwOjQ2Iiwic3RhdHVzIjoxLCJsYXN0X2lwIjoiMTkyLjE2OC4zMy4xIiwidXBkYXRlZF9hdCI6IjIwMjEtMDUtMjcgMjA6MzA6NDYiLCJyb2xlX2lkcyI6WzFdLCJyb2xlX25hbWUiOlsiXHU3Y2ZiXHU3ZWRmXHU3YmExXHU3NDA2XHU1NDU4Il0sImRlcGFydG1lbnRfaWQiOjAsImRlcGFydG1lbnRfbmFtZSI6IiIsImp3dF9zY2VuZSI6ImRlZmF1bHQifQ.rrY_V7BkvwTCkZTGIcFNBCbFCb8oEXE8Eh6s2g6Fz1M\",\"expireToken\":7200,\"departmentName\":\"\"}}', 1, 2, '2021-05-27 20:30:46', '2021-05-27 20:30:46', NULL);
INSERT INTO `sys_operation_records` VALUES (15, '192.168.33.1', 'POST', 'login', 200, 0, 'PostmanRuntime/7.28.0', '', '{\"username\":\"admin\",\"password\":\"123456\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":{\"id\":1,\"email\":\"291846153@qq.com\",\"phone\":\"18202821916\",\"username\":\"admin\",\"nickname\":\"\",\"realname\":\"超级管理员\",\"avatar\":\"\",\"lastLoginAt\":\"2021-05-27 20:32:44\",\"status\":1,\"lastIp\":\"192.168.33.1\",\"updatedAt\":\"2021-05-27 20:32:44\",\"roleIds\":[1],\"roleName\":[\"系统管理员\"],\"departmentId\":0,\"accessToken\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJkZWZhdWx0XzYwYWY5MTZjYjA4NGM4LjA4MDgwNDk0IiwiaWF0IjoxNjIyMTE4NzY0LCJuYmYiOjE2MjIxMTg3NjQsImV4cCI6MTYyMjEyNTk2NCwiaWQiOjEsImVtYWlsIjoiMjkxODQ2MTUzQHFxLmNvbSIsInBob25lIjoiMTgyMDI4MjE5MTYiLCJ1c2VybmFtZSI6ImFkbWluIiwibmlja25hbWUiOiIiLCJyZWFsbmFtZSI6Ilx1OGQ4NVx1N2VhN1x1N2JhMVx1NzQwNlx1NTQ1OCIsInBhc3N3b3JkIjoiJDJ5JDEwJHZhMTNjNDVTR3RUNjRjd3djLmJPaC5WWmtmcWxqRzVkaGN3Z0ZUOHhXSnFpRlZ5NllWXC90aSIsImF2YXRhciI6IiIsImxhc3RfbG9naW5fYXQiOiIyMDIxLTA1LTI3IDIwOjMyOjQ0Iiwic3RhdHVzIjoxLCJsYXN0X2lwIjoiMTkyLjE2OC4zMy4xIiwidXBkYXRlZF9hdCI6IjIwMjEtMDUtMjcgMjA6MzI6NDQiLCJyb2xlX2lkcyI6WzFdLCJyb2xlX25hbWUiOlsiXHU3Y2ZiXHU3ZWRmXHU3YmExXHU3NDA2XHU1NDU4Il0sImRlcGFydG1lbnRfaWQiOjAsImRlcGFydG1lbnRfbmFtZSI6IiIsImp3dF9zY2VuZSI6ImRlZmF1bHQifQ.i9gEVyVhqhfKlzcOSPuRGvyY7nd9JRDufZGuF4Oeatk\",\"expireToken\":7200,\"departmentName\":\"\"}}', 1, 2, '2021-05-27 20:32:44', '2021-05-27 20:32:44', NULL);
INSERT INTO `sys_operation_records` VALUES (16, '192.168.33.1', 'POST', 'login', 200, 0, 'PostmanRuntime/7.28.0', '', '{\"username\":\"admin\",\"password\":\"123456\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":{\"id\":1,\"email\":\"291846153@qq.com\",\"phone\":\"18202821916\",\"username\":\"admin\",\"nickname\":\"\",\"realname\":\"超级管理员\",\"avatar\":\"\",\"lastLoginAt\":\"2021-05-27 20:33:41\",\"status\":1,\"lastIp\":\"192.168.33.1\",\"updatedAt\":\"2021-05-27 20:33:42\",\"roleIds\":[1],\"roleName\":[\"系统管理员\"],\"departmentId\":0,\"accessToken\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJkZWZhdWx0XzYwYWY5MWE2MjJkNTQxLjkyNDE3MzQzIiwiaWF0IjoxNjIyMTE4ODIyLCJuYmYiOjE2MjIxMTg4MjIsImV4cCI6MTYyMjEyNjAyMiwiaWQiOjEsImVtYWlsIjoiMjkxODQ2MTUzQHFxLmNvbSIsInBob25lIjoiMTgyMDI4MjE5MTYiLCJ1c2VybmFtZSI6ImFkbWluIiwibmlja25hbWUiOiIiLCJyZWFsbmFtZSI6Ilx1OGQ4NVx1N2VhN1x1N2JhMVx1NzQwNlx1NTQ1OCIsInBhc3N3b3JkIjoiJDJ5JDEwJHZhMTNjNDVTR3RUNjRjd3djLmJPaC5WWmtmcWxqRzVkaGN3Z0ZUOHhXSnFpRlZ5NllWXC90aSIsImF2YXRhciI6IiIsImxhc3RfbG9naW5fYXQiOiIyMDIxLTA1LTI3IDIwOjMzOjQxIiwic3RhdHVzIjoxLCJsYXN0X2lwIjoiMTkyLjE2OC4zMy4xIiwidXBkYXRlZF9hdCI6IjIwMjEtMDUtMjcgMjA6MzM6NDIiLCJyb2xlX2lkcyI6WzFdLCJyb2xlX25hbWUiOlsiXHU3Y2ZiXHU3ZWRmXHU3YmExXHU3NDA2XHU1NDU4Il0sImRlcGFydG1lbnRfaWQiOjAsImRlcGFydG1lbnRfbmFtZSI6IiIsImp3dF9zY2VuZSI6ImRlZmF1bHQifQ.eC5ZeV9qv3s-axAMFu9ZbBP3Hzci2DE3mQyafOk6eFk\",\"expireToken\":7200,\"departmentName\":\"\"}}', 1, 2, '2021-05-27 20:33:42', '2021-05-27 20:33:42', NULL);
INSERT INTO `sys_operation_records` VALUES (17, '192.168.33.1', 'POST', 'login', 200, 0, 'PostmanRuntime/7.28.0', '', '{\"username\":\"admin\",\"password\":\"123456\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":{\"id\":1,\"email\":\"291846153@qq.com\",\"phone\":\"18202821916\",\"username\":\"admin\",\"nickname\":\"\",\"realname\":\"超级管理员\",\"avatar\":\"\",\"lastLoginAt\":\"2021-05-27 20:34:56\",\"status\":1,\"lastIp\":\"192.168.33.1\",\"updatedAt\":\"2021-05-27 20:34:56\",\"roleIds\":[1],\"roleName\":[\"系统管理员\"],\"departmentId\":0,\"accessToken\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJkZWZhdWx0XzYwYWY5MWYwOWIwY2E1LjAyNzUxNDI1IiwiaWF0IjoxNjIyMTE4ODk2LCJuYmYiOjE2MjIxMTg4OTYsImV4cCI6MTYyMjEyNjA5NiwiaWQiOjEsImVtYWlsIjoiMjkxODQ2MTUzQHFxLmNvbSIsInBob25lIjoiMTgyMDI4MjE5MTYiLCJ1c2VybmFtZSI6ImFkbWluIiwibmlja25hbWUiOiIiLCJyZWFsbmFtZSI6Ilx1OGQ4NVx1N2VhN1x1N2JhMVx1NzQwNlx1NTQ1OCIsInBhc3N3b3JkIjoiJDJ5JDEwJHZhMTNjNDVTR3RUNjRjd3djLmJPaC5WWmtmcWxqRzVkaGN3Z0ZUOHhXSnFpRlZ5NllWXC90aSIsImF2YXRhciI6IiIsImxhc3RfbG9naW5fYXQiOiIyMDIxLTA1LTI3IDIwOjM0OjU2Iiwic3RhdHVzIjoxLCJsYXN0X2lwIjoiMTkyLjE2OC4zMy4xIiwidXBkYXRlZF9hdCI6IjIwMjEtMDUtMjcgMjA6MzQ6NTYiLCJyb2xlX2lkcyI6WzFdLCJyb2xlX25hbWUiOlsiXHU3Y2ZiXHU3ZWRmXHU3YmExXHU3NDA2XHU1NDU4Il0sImRlcGFydG1lbnRfaWQiOjAsImRlcGFydG1lbnRfbmFtZSI6IiIsImp3dF9zY2VuZSI6ImRlZmF1bHQifQ.Q8DJWPWqqS6hH58TLjI1fUY_2o7FGNrIcLWYcCTSw68\",\"expireToken\":7200,\"departmentName\":\"\"}}', 1, 2, '2021-05-27 20:34:56', '2021-05-27 20:34:56', NULL);
INSERT INTO `sys_operation_records` VALUES (18, '172.17.0.1', 'POST', 'login', 200, 0, 'PostmanRuntime/7.28.0', '', '{\"username\":\"admin\",\"password\":\"123456\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":{\"id\":1,\"email\":\"291846153@qq.com\",\"phone\":\"18202821916\",\"username\":\"admin\",\"nickname\":\"\",\"realname\":\"超级管理员\",\"avatar\":\"\",\"lastLoginAt\":\"2021-05-28 09:03:09\",\"status\":1,\"lastIp\":\"172.17.0.1\",\"updatedAt\":\"2021-05-28 09:03:09\",\"roleIds\":[1],\"roleName\":[\"系统管理员\"],\"departmentId\":0,\"accessToken\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJkZWZhdWx0XzYwYjA0MTRkMzg4MjIzLjkxODUyMDg1IiwiaWF0IjoxNjIyMTYzNzg5LCJuYmYiOjE2MjIxNjM3ODksImV4cCI6MTYyMjE3MDk4OSwiaWQiOjEsImVtYWlsIjoiMjkxODQ2MTUzQHFxLmNvbSIsInBob25lIjoiMTgyMDI4MjE5MTYiLCJ1c2VybmFtZSI6ImFkbWluIiwibmlja25hbWUiOiIiLCJyZWFsbmFtZSI6Ilx1OGQ4NVx1N2VhN1x1N2JhMVx1NzQwNlx1NTQ1OCIsInBhc3N3b3JkIjoiJDJ5JDEwJHZhMTNjNDVTR3RUNjRjd3djLmJPaC5WWmtmcWxqRzVkaGN3Z0ZUOHhXSnFpRlZ5NllWXC90aSIsImF2YXRhciI6IiIsImxhc3RfbG9naW5fYXQiOiIyMDIxLTA1LTI4IDA5OjAzOjA5Iiwic3RhdHVzIjoxLCJsYXN0X2lwIjoiMTcyLjE3LjAuMSIsInVwZGF0ZWRfYXQiOiIyMDIxLTA1LTI4IDA5OjAzOjA5Iiwicm9sZV9pZHMiOlsxXSwicm9sZV9uYW1lIjpbIlx1N2NmYlx1N2VkZlx1N2JhMVx1NzQwNlx1NTQ1OCJdLCJkZXBhcnRtZW50X2lkIjowLCJkZXBhcnRtZW50X25hbWUiOiIiLCJqd3Rfc2NlbmUiOiJkZWZhdWx0In0.M_t9a1Kybs548YqJ8OUyKBGT-5IdQQEGrNG-uRwaLQU\",\"expireToken\":7200,\"departmentName\":\"\"}}', 1, 2, '2021-05-28 09:03:09', '2021-05-28 09:03:09', NULL);
INSERT INTO `sys_operation_records` VALUES (19, '172.17.0.1', 'POST', 'manage/platform/create', 200, 0, 'PostmanRuntime/7.28.0', '', '{\"platform_name\":\"飞猪01\"}', '{\"code\":0,\"msg\":\"successful\",\"data\":[]}', 1, 2, '2021-05-28 09:54:59', '2021-05-28 09:54:59', NULL);

-- ----------------------------
-- Table structure for sys_role_menus
-- ----------------------------
DROP TABLE IF EXISTS `sys_role_menus`;
CREATE TABLE `sys_role_menus`  (
  `role_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '角色ID',
  `menu_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '菜单ID',
  `ability` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限：序列化[\"READ\",\"WRITE\",\"UPDATE\",\"DELETE\",\"IMPORT\",\"EXPRORT\"]',
  INDEX `sys_role_menus_role_id_index`(`role_id`) USING BTREE,
  INDEX `sys_role_menus_menu_id_index`(`menu_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统—角色菜单关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_role_menus
-- ----------------------------
INSERT INTO `sys_role_menus` VALUES (17, 24, '');
INSERT INTO `sys_role_menus` VALUES (17, 25, '');
INSERT INTO `sys_role_menus` VALUES (17, 26, '');
INSERT INTO `sys_role_menus` VALUES (17, 27, '');
INSERT INTO `sys_role_menus` VALUES (17, 28, '');
INSERT INTO `sys_role_menus` VALUES (17, 1, '');
INSERT INTO `sys_role_menus` VALUES (17, 20, '');
INSERT INTO `sys_role_menus` VALUES (17, 22, '');
INSERT INTO `sys_role_menus` VALUES (17, 23, '');
INSERT INTO `sys_role_menus` VALUES (17, 2, '');
INSERT INTO `sys_role_menus` VALUES (17, 3, 'list,create,update,delete');
INSERT INTO `sys_role_menus` VALUES (17, 4, 'list,create,disable,update,info');
INSERT INTO `sys_role_menus` VALUES (17, 19, '');
INSERT INTO `sys_role_menus` VALUES (20, 1, '');
INSERT INTO `sys_role_menus` VALUES (20, 20, '');
INSERT INTO `sys_role_menus` VALUES (20, 29, 'list');
INSERT INTO `sys_role_menus` VALUES (3, 24, '');
INSERT INTO `sys_role_menus` VALUES (3, 25, '');
INSERT INTO `sys_role_menus` VALUES (3, 1, '');
INSERT INTO `sys_role_menus` VALUES (3, 20, '');
INSERT INTO `sys_role_menus` VALUES (3, 30, '');
INSERT INTO `sys_role_menus` VALUES (3, 22, '');
INSERT INTO `sys_role_menus` VALUES (3, 2, '');
INSERT INTO `sys_role_menus` VALUES (3, 3, '');
INSERT INTO `sys_role_menus` VALUES (3, 4, 'create,disable,update,delete');
INSERT INTO `sys_role_menus` VALUES (3, 31, '');
INSERT INTO `sys_role_menus` VALUES (21, 20, 'list');
INSERT INTO `sys_role_menus` VALUES (21, 22, '');
INSERT INTO `sys_role_menus` VALUES (21, 2, '');
INSERT INTO `sys_role_menus` VALUES (21, 3, '');
INSERT INTO `sys_role_menus` VALUES (21, 4, '');
INSERT INTO `sys_role_menus` VALUES (21, 19, 'list,create,update,delete');
INSERT INTO `sys_role_menus` VALUES (21, 23, 'list,create,update,delete,batch_del');
INSERT INTO `sys_role_menus` VALUES (1, 20, 'list');
INSERT INTO `sys_role_menus` VALUES (1, 22, 'role_list,department_list,role_auth,department_auth');
INSERT INTO `sys_role_menus` VALUES (1, 2, 'list,create,update,delete,select');
INSERT INTO `sys_role_menus` VALUES (1, 3, 'list,create,update,delete,select');
INSERT INTO `sys_role_menus` VALUES (1, 4, 'list,create,disable,update,info,select,setting_role,delete');
INSERT INTO `sys_role_menus` VALUES (1, 19, 'list,create,update,delete');
INSERT INTO `sys_role_menus` VALUES (1, 23, 'list,create,update,delete,batch_del');

-- ----------------------------
-- Table structure for sys_roles
-- ----------------------------
DROP TABLE IF EXISTS `sys_roles`;
CREATE TABLE `sys_roles`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色名',
  `parent_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '父角色ID（父级包含子级角色权限）',
  `default_router` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dashboard' COMMENT '默认菜单',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `all_parent_id` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '所有上级id',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sys_roles_role_name_index`(`role_name`) USING BTREE,
  INDEX `sys_roles_parent_id_index`(`parent_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统—角色表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_roles
-- ----------------------------
INSERT INTO `sys_roles` VALUES (1, '系统管理员', '0', 'dashboard', '111', NULL, '2021-05-21 14:29:27', NULL);
INSERT INTO `sys_roles` VALUES (3, '订单管理员', '1', 'dashboard', '', '2021-05-17 06:05:51', '2021-05-21 13:52:19', '1');
INSERT INTO `sys_roles` VALUES (4, '菜单管理员', '1', 'dashboard', '', NULL, NULL, '1');
INSERT INTO `sys_roles` VALUES (21, 'aa', '0', 'dashboard', '222', '2021-05-24 10:40:34', '2021-05-24 15:42:21', NULL);
INSERT INTO `sys_roles` VALUES (22, 'bb', '21', 'dashboard', '111', '2021-05-24 10:40:53', '2021-05-24 10:40:53', '21');
INSERT INTO `sys_roles` VALUES (23, 'ccc', '22', 'dashboard', 'ccc', '2021-05-24 10:41:22', '2021-05-24 10:41:22', '21,22');
INSERT INTO `sys_roles` VALUES (27, '王者管理员', '1', 'dashboard', '', '2021-05-26 10:50:38', '2021-05-26 10:50:38', '1');

-- ----------------------------
-- Table structure for sys_user_departments
-- ----------------------------
DROP TABLE IF EXISTS `sys_user_departments`;
CREATE TABLE `sys_user_departments`  (
  `user_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '用户ID',
  `department_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '部门ID',
  INDEX `sys_user_departments_user_id_index`(`user_id`) USING BTREE,
  INDEX `sys_user_departments_department_id_index`(`department_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统—用户部门关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_user_departments
-- ----------------------------

-- ----------------------------
-- Table structure for sys_user_roles
-- ----------------------------
DROP TABLE IF EXISTS `sys_user_roles`;
CREATE TABLE `sys_user_roles`  (
  `user_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '用户ID',
  `role_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '角色ID',
  INDEX `sys_user_roles_user_id_index`(`user_id`) USING BTREE,
  INDEX `sys_user_roles_role_id_index`(`role_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统—用户角色关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_user_roles
-- ----------------------------
INSERT INTO `sys_user_roles` VALUES (14, 0);
INSERT INTO `sys_user_roles` VALUES (15, 1);
INSERT INTO `sys_user_roles` VALUES (15, 4);
INSERT INTO `sys_user_roles` VALUES (1, 16);
INSERT INTO `sys_user_roles` VALUES (1, 1);
INSERT INTO `sys_user_roles` VALUES (5, 1);

-- ----------------------------
-- Table structure for sys_users
-- ----------------------------
DROP TABLE IF EXISTS `sys_users`;
CREATE TABLE `sys_users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户UUID',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '邮箱',
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '手机号',
  `email_verified_at` timestamp NULL DEFAULT NULL COMMENT '邮箱验证时间',
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户登录名',
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '昵称',
  `realname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '实名',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '头像',
  `last_login_at` timestamp NULL DEFAULT NULL COMMENT '最后登录日期',
  `last_token` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '最新登录token',
  `last_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '最后登录IP',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态: 1=启用 0=禁用',
  `department_id` int(11) NULL DEFAULT NULL COMMENT '部门ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间 null未删除',
  `position` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '岗位',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `sys_users_uuid_unique`(`uuid`) USING BTREE,
  UNIQUE INDEX `sys_users_phone_unique`(`phone`) USING BTREE,
  UNIQUE INDEX `sys_users_email_unique`(`email`) USING BTREE,
  INDEX `sys_users_uuid_index`(`uuid`) USING BTREE,
  INDEX `sys_users_email_index`(`email`) USING BTREE,
  INDEX `sys_users_username_index`(`username`) USING BTREE,
  INDEX `sys_users_nickname_index`(`nickname`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统—用户表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_users
-- ----------------------------
INSERT INTO `sys_users` VALUES (1, '609b6e38ced4a', '291846153@qq.com', '18202821916', NULL, 'admin', '', '超级管理员', '$2y$10$va13c45SGtT64cwwc.bOh.VZkfqljG5dhcwgFT8xWJqiFVy6YV/ti', '', '2021-05-28 09:03:09', '', '172.17.0.1', 1, NULL, NULL, '2021-05-28 09:03:09', NULL, '1');
INSERT INTO `sys_users` VALUES (5, '609b7b2ae1b44', '291846152@qq.com', '18202821917', NULL, 'caogang', '', '曹刚', '$2y$10$vSxsqR27.93mE7.S.GV5zOaYl8rOU4qDQcG/LO0pV4sFykz9U00j6', '', '2021-05-27 10:34:48', '', '172.17.0.1', 1, 5, NULL, '2021-05-27 10:34:48', NULL, '哦哦');
INSERT INTO `sys_users` VALUES (9, '597d06b2141a743a443e2adf5f230b77', NULL, NULL, NULL, 'caogang2', '', '', '$2y$10$GjYwWEvsS.0aWKABqkHE7./TZFDPnKhvfWmQfufODQ5K0Z8WrjLly', '', NULL, '', '', 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sys_users` VALUES (10, 'c52974283474694ace477a635a59d465', NULL, NULL, NULL, 'caogang3', '', '', '$2y$10$LeMCYRb29xmdEqG28WhpgO0XN6/MWtyPsZrbTLoE9rruOP15X48xq', '', NULL, '', '', 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sys_users` VALUES (11, '06d825d0654c3bf74ff2320b18bce618', NULL, NULL, NULL, 'caogang4', '', '', '$2y$10$A4Q1My6EeZHeNE0pFqtIdOM7rXU/sWbtvMJChlj2ispt0BufjjU6G', '', NULL, '', '', 1, NULL, NULL, '2021-05-22 13:45:03', NULL, NULL);
INSERT INTO `sys_users` VALUES (12, 'b909eaaf181de110fca09e96ddb0da88', NULL, NULL, NULL, 'caogang05', '', '', '$2y$10$XO1GSfe1M/luVqJJSkvPuuhzPAmyqjzvRUtZZEUml0UZLRlYjJUVa', '', NULL, '', '', 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sys_users` VALUES (15, '9be52b9b161ed9d09fcbbd9149873def', NULL, NULL, NULL, 'asd', '', 'test', '$2y$10$PgyiIYr0f1BknsQGN/tGGOa8AAAmKf2F.iw98crAzBrgAGCTlyWq.', '', NULL, '', '', 1, NULL, '2021-05-21 13:27:15', '2021-05-22 12:19:22', NULL, '14');
INSERT INTO `sys_users` VALUES (16, 'd4670de2d3a1e97f5494372c0c15d047', NULL, NULL, NULL, '1', '', '1', '$2y$10$XlwJtYJrVT8qkyZPEmwDHukmsUXYxXQh8/xKf.TS0twq0nfheIaSC', '', NULL, '', '', 1, 2, '2021-05-22 12:28:58', '2021-05-24 20:28:25', NULL, '111111');
INSERT INTO `sys_users` VALUES (17, 'be40626361a18de0380a6aba8ce978fb', NULL, NULL, NULL, 'caogang06', '', '曹刚', '$2y$10$pe.rlH11YBLG6Q7222Ih9ulZRYrH43SfcmggBK2Bz.D2Px7/dtwjG', '', NULL, '', '', 1, 3, '2021-05-24 17:18:00', '2021-05-26 16:43:55', NULL, 'php');

SET FOREIGN_KEY_CHECKS = 1;
