-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 03 月 16 日 15:20
-- 服务器版本: 5.5.20
-- PHP 版本: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `oms`
--

-- --------------------------------------------------------

--
-- 表的结构 `oms_customer`
--

CREATE TABLE IF NOT EXISTS `oms_customer` (
  `customer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '名称',
  `category` char(30) NOT NULL COMMENT '购买产品大类',
  `customer_nation` varchar(255) NOT NULL,
  `customer_province_state_city` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL COMMENT '地址',
  `post_code` int(10) NOT NULL COMMENT '邮政编码',
  `web_url` varchar(255) NOT NULL COMMENT '网站地址',
  `web_shop` varchar(255) NOT NULL COMMENT '网店地址',
  `company_email` varchar(200) NOT NULL COMMENT '公司邮箱',
  `company_tel` varchar(100) NOT NULL COMMENT '公司电话',
  `company_fax` char(30) NOT NULL COMMENT '公司传真',
  `business_license_image` varchar(200) NOT NULL COMMENT '营业执照图片',
  `organizational_structure_code_image` varchar(200) NOT NULL COMMENT '组织结构代码图像',
  `tax_registration_certificates_image` varchar(200) NOT NULL COMMENT '税务登记证件图像',
  `bank_of_deposit` char(60) NOT NULL COMMENT '开户银行',
  `account` char(30) NOT NULL COMMENT '账户',
  `legal_person_name` char(30) NOT NULL COMMENT '法人姓名',
  `legal_person_sex` char(30) NOT NULL COMMENT '法人性别',
  `legal_person_tel` char(20) NOT NULL COMMENT '法人电话',
  `legal_person_mobile_phone` char(20) NOT NULL COMMENT '法人手机',
  `legal_person_email` char(30) NOT NULL COMMENT '法人邮箱',
  `technology_person_name` char(30) NOT NULL COMMENT '技术人员姓名',
  `technology_person_sex` char(30) NOT NULL COMMENT '技术人员性别',
  `technology_person_tel` char(20) NOT NULL COMMENT '技术人员电话',
  `technology_person_mobile_phone` char(20) NOT NULL COMMENT '技术人员手机',
  `technology_person_email` char(30) NOT NULL COMMENT '技术人员邮箱',
  `purchase_person_name` varchar(100) NOT NULL COMMENT '采购人员姓名',
  `purchase_person_sex` char(30) NOT NULL COMMENT '采购人员性别',
  `purchase_person_tel` char(20) NOT NULL COMMENT '采购人员电话',
  `purchase_person_mobile_phone` varchar(255) NOT NULL COMMENT '采购人员手机',
  `purchase_person_email` char(30) NOT NULL COMMENT '采购人员邮箱',
  `manufacture_person_name` char(30) NOT NULL COMMENT '制造人员姓名',
  `manufacture_person_sex` char(30) NOT NULL COMMENT '制造人员性别',
  `manufacture_person_tel` char(20) NOT NULL COMMENT '制造人员电话',
  `manufacture_person_mobile_phone` char(20) NOT NULL COMMENT '制造人员手机',
  `manufacture_person_email` char(30) NOT NULL COMMENT '制造人员邮箱',
  `inspection_person_name` char(30) NOT NULL COMMENT '检查人员姓名',
  `inspection_person_sex` char(30) NOT NULL COMMENT '检查人员性别',
  `inspection_person_tel` char(20) NOT NULL COMMENT '检查人员电话',
  `inspection_person_mobile_phone` char(20) NOT NULL COMMENT '检查人员手机',
  `inspection_person_email` char(30) NOT NULL COMMENT '检查人员邮箱',
  `finance_person_name` char(30) NOT NULL COMMENT '财务人员姓名',
  `finance_person_sex` char(30) NOT NULL COMMENT '财务人员性别',
  `finance_person_tel` char(20) NOT NULL COMMENT '财务人员电话',
  `finance_person_mobile_phone` char(20) NOT NULL COMMENT '财务人员手机',
  `finance_person_email` char(30) NOT NULL COMMENT '财务人员邮箱',
  `custom_delivery_special_requirement` text NOT NULL COMMENT '客户发货特殊要求',
  `client_type` char(1) NOT NULL COMMENT '客户类型',
  `client_status` varchar(30) NOT NULL COMMENT '客户状态意向交易睡眠',
  `buy_our_productsd` char(60) NOT NULL COMMENT '购买我们的产品大类',
  `buy_our_products` char(60) NOT NULL COMMENT '购买我们的产品',
  `product_unit_price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '产品单价',
  `buy_our_products_qty` int(10) unsigned NOT NULL COMMENT '购买我们的产品数量',
  `buy_our_products_sum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '购买我们的产品金额',
  `got_money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已回货款',
  `receivable` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '应收货款',
  `interest_in_our_products` char(60) NOT NULL COMMENT '有兴趣我们的产品品种',
  `intention_buy_our_products_qty` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '意向购买我们产品数量',
  `intention_products_price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '意向购买我们产品单价',
  `intention_buy_our_products_sum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '意向购买我们产品金额',
  `our_sales_team_VP` char(20) DEFAULT NULL COMMENT '我方销售主管副总',
  `our_sales_team` char(20) DEFAULT NULL COMMENT '我方销售负责团队',
  `our_sales` char(20) DEFAULT NULL COMMENT '我们的销售人员',
  `create_time` int(10) unsigned NOT NULL,
  `update_time` int(10) unsigned NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `oms_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`customer_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1640 ;

--
-- 转存表中的数据 `oms_customer`
--

INSERT INTO `oms_customer` (`customer_id`, `name`, `category`, `customer_nation`, `customer_province_state_city`, `address`, `post_code`, `web_url`, `web_shop`, `company_email`, `company_tel`, `company_fax`, `business_license_image`, `organizational_structure_code_image`, `tax_registration_certificates_image`, `bank_of_deposit`, `account`, `legal_person_name`, `legal_person_sex`, `legal_person_tel`, `legal_person_mobile_phone`, `legal_person_email`, `technology_person_name`, `technology_person_sex`, `technology_person_tel`, `technology_person_mobile_phone`, `technology_person_email`, `purchase_person_name`, `purchase_person_sex`, `purchase_person_tel`, `purchase_person_mobile_phone`, `purchase_person_email`, `manufacture_person_name`, `manufacture_person_sex`, `manufacture_person_tel`, `manufacture_person_mobile_phone`, `manufacture_person_email`, `inspection_person_name`, `inspection_person_sex`, `inspection_person_tel`, `inspection_person_mobile_phone`, `inspection_person_email`, `finance_person_name`, `finance_person_sex`, `finance_person_tel`, `finance_person_mobile_phone`, `finance_person_email`, `custom_delivery_special_requirement`, `client_type`, `client_status`, `buy_our_productsd`, `buy_our_products`, `product_unit_price`, `buy_our_products_qty`, `buy_our_products_sum`, `got_money`, `receivable`, `interest_in_our_products`, `intention_buy_our_products_qty`, `intention_products_price`, `intention_buy_our_products_sum`, `our_sales_team_VP`, `our_sales_team`, `our_sales`, `create_time`, `update_time`, `state`, `oms_id`) VALUES
(2, '腾讯', '安防产品', '中国', '深圳', '北京经济技术开发区隆庆街9号,11,22,33,44,55,66,77,88,99', 100076, '', '', '', '010-67881199，13641202527', '船政', '', '', '110192600028293', '中行丰台支行东高地分理处', '03651408092001', '', '', '', '', '', '', '', '电话 AAA', '', '', '李晓曼', '', '123123123123123', '13641202527', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '订单号;缠；包装根据合同要求；SR1588NGFZW为大包装，其余小包装；铝塑包装；复印一张合同作为开票用', 'B', '休眠', '', '', 0, 0, 0, 0, 0, '', 0, 0, 0, '黄建华', '黄建华', '辛志荣', 1438242576, 1438311549, 0, 1),
(1, '百度', '安防产品', '中国', '北京', '123', 1, '123', '123', '123', '123', '123', '123', '123', '123', '23', '123', '123', '123', '123', '123', '123', '123', '123', '132', '213', '123', '123', '213', '123', '123', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, '', 0, 0, 0, NULL, NULL, '辛志荣', 0, 0, 0, 1),
(3, '小米', '安防产品', '中国', '上海', '123', 0, '123', '123', '123', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, '', 0, 0, 0, NULL, NULL, '辛志荣', 0, 0, 0, 1),
(4, '苹果', '安防产品', '美国', '旧金山', '北京经济技术开发区隆庆街9号,11,22,33,44,55,66,77,88,99', 100076, '', '', '', '010-67881199，13641202527', '船政', '', '', '110192600028293', '中行丰台支行东高地分理处', '03651408092001', '', '', '', '', '', '', '', '电话 AAA', '', '', '李晓曼', '', '123123123123123', '13641202527', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '订单号;缠；包装根据合同要求；SR1588NGFZW为大包装，其余小包装；铝塑包装；复印一张合同作为开票用', 'B', '休眠', '', '', 0, 0, 0, 0, 0, '', 0, 0, 0, '黄建华', '黄建华', '辛志荣', 1438242576, 1438311549, 0, 1),
(5, '微软', '安防产品', '美国', '旧金山', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, '', 0, 0, 0, NULL, NULL, '辛志荣', 0, 0, 0, 1),
(6, '阿里巴巴', '安防产品', '中国', '杭州', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, '', 0, 0, 0, NULL, NULL, '辛志荣', 0, 0, 0, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
