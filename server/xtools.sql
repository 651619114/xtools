/*
 Navicat Premium Data Transfer

 Source Server         : 47.95.231.54
 Source Server Type    : MySQL
 Source Server Version : 50731
 Source Host           : 47.95.231.54:3306
 Source Schema         : xtools

 Target Server Type    : MySQL
 Target Server Version : 50731
 File Encoding         : 65001

 Date: 31/01/2021 23:04:15
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu`  (
  `menu_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `root_id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `display` int(11) NOT NULL,
  `class_func` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `is_menu` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`menu_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_menu
-- ----------------------------
INSERT INTO `admin_menu` VALUES (1, 0, '首页', 0, '/index/index', 1);
INSERT INTO `admin_menu` VALUES (2, 0, '系统管理', 1, '#', 1);
INSERT INTO `admin_menu` VALUES (4, 2, '用户管理', 1, '/sys/user', 1);
INSERT INTO `admin_menu` VALUES (12, 2, '菜单管理', 1, '/sys/menu', 1);
INSERT INTO `admin_menu` VALUES (28, 0, '工具集', 0, '#', 1);
INSERT INTO `admin_menu` VALUES (29, 28, 'OCR文字识别', 0, '/tool/ocrtool', 1);

-- ----------------------------
-- Table structure for admin_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE `admin_role`  (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色名称',
  `role_menu` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色目录',
  PRIMARY KEY (`role_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_role
-- ----------------------------
INSERT INTO `admin_role` VALUES (1, '超级管理员', '*');
INSERT INTO `admin_role` VALUES (2, '普通用户', '1,28,29');

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '邮箱',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `remember_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `role_id` int(11) NOT NULL COMMENT '角色id',
  `updated_at` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created_at` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` tinyint(2) NOT NULL COMMENT '1:正常2：停用',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES (15, '123@qq.com', '202cb962ac59075b964b07152d234b70', '1', 1, '1603262565', '1600410504', 1, '邢明峰', '15530302234');

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `connection` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `queue` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for tools_ocr
-- ----------------------------
DROP TABLE IF EXISTS `tools_ocr`;
CREATE TABLE `tools_ocr`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `to_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `updated_at` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tools_ocr
-- ----------------------------
INSERT INTO `tools_ocr` VALUES (6, 'img/15/6016c3728aac1.jpg', 'txt/15/6016c43abd3d1.txt', 2, '1612104563', '1612104563', 15, '6016c43abd3d1.txt');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
