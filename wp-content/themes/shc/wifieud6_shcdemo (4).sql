-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2018 at 12:41 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wifieud6_shcdemo`
--

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_cd_notes`
--

CREATE TABLE `wp_shc_cd_notes` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `return_id` int(11) NOT NULL,
  `master_key` varchar(100) NOT NULL,
  `key_value` varchar(100) NOT NULL,
  `key_amount` decimal(15,2) NOT NULL,
  `description` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_cd_notes`
--

INSERT INTO `wp_shc_cd_notes` (`id`, `customer_id`, `bill_id`, `return_id`, `master_key`, `key_value`, `key_amount`, `description`, `created_at`, `modified_at`, `active`) VALUES
(1, 1, 1, 1, 'return_biling', 'debit', '1050.00', '', '2018-03-22 15:23:17', '2018-03-22 15:23:21', 0),
(2, 1, 1, 1, 'return_biling', 'debit', '1050.00', '', '2018-03-22 15:23:21', '2018-03-22 15:23:21', 1),
(3, 0, 5, 2, 'return_biling', 'debit', '525.00', '', '2018-03-23 18:14:48', '2018-03-23 18:14:48', 1),
(4, 0, 3, 3, 'return_biling', 'debit', '1050.00', '', '2018-03-23 18:30:20', '2018-03-23 18:30:30', 0),
(5, 0, 3, 3, 'return_biling', 'debit', '1050.00', '', '2018-03-23 18:30:30', '2018-03-23 18:30:30', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_creditdebit`
--

CREATE TABLE `wp_shc_creditdebit` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_type` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `modified_by` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_creditdebit`
--

INSERT INTO `wp_shc_creditdebit` (`id`, `date`, `customer_id`, `customer_name`, `customer_type`, `amount`, `description`, `type`, `created_by`, `modified_by`, `created_at`, `modified_at`, `active`) VALUES
(1, '2018-03-22', 2, 'Ajna(9864343434)', 'ws', '400.00', 'sdfdsfds', 'credit', 'admin', '', '2018-03-22 11:04:47', '2018-03-22 11:06:25', 0),
(2, '2018-03-22', 2, '9871235223(Evan)', 'retail', '100.00', 'sjhdsjdhsaj', 'credit', 'admin', '', '2018-03-22 15:24:17', '2018-03-22 15:24:17', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_customers`
--

CREATE TABLE `wp_shc_customers` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `secondary_mobile` varchar(100) NOT NULL,
  `landline` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'retail',
  `created_by` varchar(100) NOT NULL,
  `modified_by` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_customers`
--

INSERT INTO `wp_shc_customers` (`id`, `name`, `mobile`, `secondary_mobile`, `landline`, `address`, `type`, `created_by`, `modified_by`, `created_at`, `modified_at`, `active`) VALUES
(1, 'Seegan', '5456864321', '4689761313', '64643131', 'Sbgs', 'retail', 'admin', '', '2018-02-23 17:36:09', '2018-02-23 12:06:09', 1),
(2, 'Evan', '9871235223', '9875421231', '44543154', 'Jsbj', 'retail', 'admin', '', '2018-02-23 17:36:31', '2018-02-23 12:06:31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_lots`
--

CREATE TABLE `wp_shc_lots` (
  `id` int(11) NOT NULL,
  `lot_no` varchar(250) NOT NULL,
  `brand_name` varchar(250) NOT NULL,
  `product_name` varchar(250) NOT NULL,
  `mrp` decimal(15,2) NOT NULL,
  `selling_price` decimal(15,2) NOT NULL,
  `purchase_price` decimal(15,2) NOT NULL,
  `cgst` decimal(15,2) NOT NULL,
  `sgst` decimal(15,2) NOT NULL,
  `hsn` varchar(200) NOT NULL,
  `stock_alert` int(11) NOT NULL,
  `stock_in` int(11) NOT NULL DEFAULT '0',
  `sale_out` int(11) NOT NULL DEFAULT '0',
  `created_by` varchar(100) NOT NULL,
  `modified_by` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `active` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_lots`
--

INSERT INTO `wp_shc_lots` (`id`, `lot_no`, `brand_name`, `product_name`, `mrp`, `selling_price`, `purchase_price`, `cgst`, `sgst`, `hsn`, `stock_alert`, `stock_in`, `sale_out`, `created_by`, `modified_by`, `created_at`, `modified_at`, `active`) VALUES
(1, '1', 'RICE', 'Shc1', '500.00', '450.00', '500.00', '9.00', '9.00', '45454214', 100, 0, 0, 'admin', '', '2018-02-23 16:16:38', '2018-02-23 16:16:38', 1),
(2, '2', 'RICE', 'Shc2', '600.00', '525.00', '600.00', '6.00', '6.00', '45442345', 100, 0, 0, 'admin', '', '2018-02-23 16:19:54', '2018-02-23 16:19:54', 1),
(3, '3', 'TEST', 'Test', '100.00', '38.80', '200.00', '0.00', '0.00', '98213', 1, 0, 0, 'admin', 'admin', '2018-02-28 18:04:05', '2018-03-19 15:06:05', 1),
(4, '4', '4T543543', '6456546', '54.00', '454.00', '454.00', '6.00', '6.00', '556654', 3, 0, 0, 'admin', '', '2018-03-22 11:50:13', '2018-03-22 11:50:13', 1),
(5, '5', 'FFGDGFDGF', 'FWERER', '543.00', '4545.00', '45.00', '9.00', '9.00', '45', 1, 0, 0, 'admin', '', '2018-03-22 11:50:31', '2018-03-22 11:50:31', 1),
(6, '6', '3243243', '535', '35.00', '35.00', '35.00', '14.00', '14.00', '35', 35, 0, 0, 'admin', '', '2018-03-22 11:50:44', '2018-03-22 11:50:44', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_netbank`
--

CREATE TABLE `wp_shc_netbank` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `bank` varchar(255) NOT NULL,
  `account` varchar(255) NOT NULL,
  `ifsc` varchar(255) NOT NULL,
  `account_type` varchar(20) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_netbank`
--

INSERT INTO `wp_shc_netbank` (`id`, `shop_name`, `bank`, `account`, `ifsc`, `account_type`, `branch`, `created_at`, `modified_at`, `active`) VALUES
(1, 'Saravana Health Store', '9456343134', 'Adsfgd', 'Sdfsa', '545AF545456AS45', '', '2018-02-23 16:56:53', '2018-03-18 20:34:38', 0),
(2, 'Dsfsdfsdf', '9456343134', 'Sdfsfsd', 'Fdsfsfs', '545AF545456AS45', '', '2018-03-18 20:34:38', '2018-03-20 11:42:47', 0),
(3, 'SHC', 'city union bank', 'tyastay4324324@@33kda', 'ewqew@!WEewqewq', 'current', 'fgfggr212@@!!', '2018-03-20 11:42:47', '2018-03-20 13:54:20', 0),
(4, 'SHC', 'city union bank', '121443453454354354', 'WEWE0989898', 'Current', 'Ewrwerewr', '2018-03-20 13:54:20', '2018-03-20 13:54:50', 0),
(5, 'SHC', 'City Union Bank1', '121443453454354354', 'WEWE0989898', 'Current', 'Ewrwerewr', '2018-03-20 13:54:50', '2018-03-20 13:54:50', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_profile`
--

CREATE TABLE `wp_shc_profile` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `address2` text NOT NULL,
  `gst_number` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_profile`
--

INSERT INTO `wp_shc_profile` (`id`, `company_name`, `phone_number`, `address`, `address2`, `gst_number`, `created_at`, `modified_at`, `active`) VALUES
(1, 'Saravana Health Store', '9456343134', 'Adsfgd', 'Sdfsa', '545AF545456AS45', '2018-02-23 16:56:53', '2018-02-23 16:56:53', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_return_items`
--

CREATE TABLE `wp_shc_return_items` (
  `id` int(11) NOT NULL,
  `return_id` varchar(200) NOT NULL,
  `inv_id` varchar(100) NOT NULL,
  `search_inv_id` varchar(10) NOT NULL,
  `financial_year` varchar(20) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `cd_id` int(11) NOT NULL DEFAULT '0',
  `total_amount` decimal(15,2) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `modified_by` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `cancel` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_return_items`
--

INSERT INTO `wp_shc_return_items` (`id`, `return_id`, `inv_id`, `search_inv_id`, `financial_year`, `customer_id`, `cd_id`, `total_amount`, `created_by`, `modified_by`, `created_at`, `modified_at`, `active`, `cancel`) VALUES
(1, 'GR 1', '1', '1', '2017', 1, 2, '1050.00', 'admin', 'admin', '2018-03-22 15:23:17', '2018-03-22 15:23:21', 1, 0),
(2, 'GR 2', '5', '5', '2017', 0, 3, '525.00', 'admin', '', '2018-03-23 18:14:48', '2018-03-23 18:14:48', 1, 0),
(3, 'GR 3', '3', '3', '2017', 0, 5, '1050.00', 'admin', 'admin', '2018-03-23 18:30:20', '2018-03-23 18:30:30', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_return_items_details`
--

CREATE TABLE `wp_shc_return_items_details` (
  `id` int(11) NOT NULL,
  `return_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `sale_unit` decimal(15,2) NOT NULL,
  `bal_qty` decimal(15,2) NOT NULL,
  `return_unit` decimal(15,2) NOT NULL,
  `mrp` decimal(15,2) NOT NULL,
  `sale_value` decimal(15,2) NOT NULL,
  `cgst` decimal(15,2) NOT NULL,
  `cgst_value` decimal(15,2) NOT NULL,
  `sgst` decimal(15,2) NOT NULL,
  `sgst_value` decimal(15,2) NOT NULL,
  `sub_total` decimal(15,2) NOT NULL,
  `amt` decimal(15,2) NOT NULL,
  `sale_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `cancel` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_return_items_details`
--

INSERT INTO `wp_shc_return_items_details` (`id`, `return_id`, `sale_id`, `lot_id`, `sale_unit`, `bal_qty`, `return_unit`, `mrp`, `sale_value`, `cgst`, `cgst_value`, `sgst`, `sgst_value`, `sub_total`, `amt`, `sale_update`, `created_at`, `modified_at`, `active`, `cancel`) VALUES
(1, 1, 1, 2, '10.00', '8.00', '2.00', '525.00', '0.00', '6.00', '56.25', '6.00', '56.25', '1050.00', '937.50', '2018-03-22 15:23:21', '2018-03-22 15:23:17', '2018-03-22 15:23:21', 0, 0),
(2, 1, 1, 2, '10.00', '8.00', '2.00', '525.00', '0.00', '6.00', '56.25', '6.00', '56.25', '1050.00', '937.50', '2018-03-22 15:23:21', '2018-03-22 15:23:21', '2018-03-22 15:23:21', 1, 0),
(3, 2, 5, 2, '12.00', '11.00', '1.00', '525.00', '0.00', '6.00', '28.13', '6.00', '28.13', '525.00', '468.75', '2018-03-23 18:14:48', '2018-03-23 18:14:48', '2018-03-23 18:14:48', 1, 0),
(4, 3, 3, 2, '2.00', '0.00', '2.00', '525.00', '0.00', '6.00', '56.25', '6.00', '56.25', '1050.00', '937.50', '2018-03-23 18:30:30', '2018-03-23 18:30:20', '2018-03-23 18:30:30', 0, 0),
(5, 3, 3, 2, '2.00', '0.00', '2.00', '525.00', '0.00', '6.00', '56.25', '6.00', '56.25', '1050.00', '937.50', '2018-03-23 18:30:30', '2018-03-23 18:30:30', '2018-03-23 18:30:30', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_sale`
--

CREATE TABLE `wp_shc_sale` (
  `id` int(11) NOT NULL,
  `financial_year` varchar(20) NOT NULL,
  `inv_id` varchar(250) NOT NULL,
  `order_id` varchar(250) NOT NULL DEFAULT '0',
  `customer_id` int(11) NOT NULL DEFAULT '0',
  `is_delivery` int(11) NOT NULL,
  `home_delivery_name` varchar(100) NOT NULL,
  `home_delivery_mobile` varchar(100) NOT NULL,
  `home_delivery_address` varchar(200) NOT NULL,
  `sub_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `before_total` decimal(15,2) NOT NULL,
  `prev_bal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(100) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL,
  `return_amt` decimal(15,2) NOT NULL,
  `current_bal` decimal(15,2) NOT NULL,
  `payment_type` varchar(200) NOT NULL,
  `payment_details` varchar(200) NOT NULL,
  `payment_date` varchar(200) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `modified_by` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `locked` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '1',
  `cancel` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_sale`
--

INSERT INTO `wp_shc_sale` (`id`, `financial_year`, `inv_id`, `order_id`, `customer_id`, `is_delivery`, `home_delivery_name`, `home_delivery_mobile`, `home_delivery_address`, `sub_total`, `before_total`, `prev_bal`, `discount`, `discount_type`, `paid_amount`, `return_amt`, `current_bal`, `payment_type`, `payment_details`, `payment_date`, `created_by`, `modified_by`, `created_at`, `modified_at`, `locked`, `active`, `cancel`) VALUES
(1, '2017', '1', '22032018A863', 1, 0, 'Seegan', '5456864321', 'Sbgs', '5250.00', '6000.00', '0.00', '0.00', '', '5250.00', '0.00', '0.00', 'cash', '', '', 'admin', 'admin', '2018-03-22 14:47:29', '2018-03-23 15:00:27', 1, 1, 0),
(2, '2017', '2', '23032018DA02', 0, 0, '', '', '', '450.00', '500.00', '0.00', '0.00', '', '450.00', '0.00', '0.00', 'internet_banking', '', '', 'admin', 'admin', '2018-03-23 14:41:45', '2018-03-23 17:37:12', 1, 1, 0),
(3, '2017', '3', '23032018B902', 0, 0, '', '', '', '1050.00', '1200.00', '0.00', '0.00', '', '0.00', '0.00', '0.00', 'credit', '', '', 'admin', 'admin', '2018-03-23 14:42:12', '2018-03-23 14:42:28', 1, 1, 0),
(4, '2017', '4', '23032018BE74', 0, 0, '', '', '', '450.00', '500.00', '0.00', '0.00', '', '780.00', '0.00', '330.00', 'internet_banking', '', '', 'admin', 'admin', '2018-03-23 14:42:41', '2018-03-24 11:49:43', 1, 1, 0),
(5, '2017', '5', '230320182628', 0, 0, '', '', '', '6300.00', '7200.00', '0.00', '0.00', '', '6300.00', '0.00', '0.00', 'cash', '', '', 'admin', 'admin', '2018-03-23 14:46:37', '2018-03-24 11:21:10', 1, 1, 0),
(6, '2017', '6', '0', 0, 0, '', '', '', '0.00', '0.00', '0.00', '0.00', '', '0.00', '0.00', '0.00', '', '', '', '', '', '2018-03-23 17:04:46', '0000-00-00 00:00:00', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_sale_detail`
--

CREATE TABLE `wp_shc_sale_detail` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `sale_unit` int(11) NOT NULL,
  `stock` decimal(15,2) NOT NULL,
  `amt` decimal(15,2) NOT NULL,
  `cgst` decimal(15,2) NOT NULL,
  `sgst` decimal(15,2) NOT NULL,
  `cgst_value` decimal(15,2) NOT NULL,
  `sgst_value` decimal(15,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(100) NOT NULL,
  `sub_total` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `payment_type` varchar(200) NOT NULL,
  `sale_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_delivery` int(1) NOT NULL DEFAULT '0',
  `delivery_count` decimal(15,0) NOT NULL,
  `delivery_date` datetime NOT NULL,
  `item_status` varchar(250) NOT NULL DEFAULT 'open',
  `active` int(2) NOT NULL DEFAULT '1',
  `cancel` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_sale_detail`
--

INSERT INTO `wp_shc_sale_detail` (`id`, `sale_id`, `lot_id`, `sale_unit`, `stock`, `amt`, `cgst`, `sgst`, `cgst_value`, `sgst_value`, `unit_price`, `discount`, `discount_type`, `sub_total`, `total`, `payment_type`, `sale_update`, `is_delivery`, `delivery_count`, `delivery_date`, `item_status`, `active`, `cancel`) VALUES
(1, 1, 2, 10, '202.00', '4687.50', '6.00', '6.00', '281.25', '281.25', '600.00', '525.00', 'each', '5250.00', '6000.00', '', '2018-03-23 15:00:27', 0, '0', '0000-00-00 00:00:00', 'open', 0, 0),
(2, 2, 1, 1, '170.00', '381.36', '9.00', '9.00', '34.32', '34.32', '500.00', '450.00', 'each', '450.00', '500.00', '', '2018-03-23 14:41:57', 0, '0', '0000-00-00 00:00:00', 'open', 0, 0),
(3, 2, 1, 1, '170.00', '381.36', '9.00', '9.00', '34.32', '34.32', '500.00', '450.00', 'each', '450.00', '500.00', '', '2018-03-23 17:37:12', 1, '1', '2018-03-23 14:41:58', 'open', 0, 0),
(4, 3, 2, 2, '194.00', '937.50', '6.00', '6.00', '56.25', '56.25', '600.00', '525.00', 'each', '1050.00', '1200.00', '', '2018-03-23 14:42:28', 0, '0', '0000-00-00 00:00:00', 'open', 0, 0),
(5, 3, 2, 2, '194.00', '937.50', '6.00', '6.00', '56.25', '56.25', '600.00', '525.00', 'each', '1050.00', '1200.00', '', '2018-03-23 14:50:09', 1, '2', '2018-03-23 14:42:37', 'open', 0, 0),
(6, 4, 1, 1, '169.00', '381.36', '9.00', '9.00', '34.32', '34.32', '500.00', '450.00', 'each', '450.00', '500.00', '', '2018-03-23 14:42:52', 0, '0', '0000-00-00 00:00:00', 'open', 0, 0),
(7, 4, 1, 1, '169.00', '381.36', '9.00', '9.00', '34.32', '34.32', '500.00', '450.00', 'each', '450.00', '500.00', '', '2018-03-23 14:57:05', 0, '0', '2018-03-23 14:42:57', 'open', 0, 0),
(8, 5, 2, 12, '192.00', '5625.00', '6.00', '6.00', '337.50', '337.50', '600.00', '525.00', 'each', '6300.00', '7200.00', '', '2018-03-23 14:46:58', 0, '0', '0000-00-00 00:00:00', 'open', 0, 0),
(9, 5, 2, 12, '192.00', '5625.00', '6.00', '6.00', '337.50', '337.50', '600.00', '525.00', 'each', '6300.00', '7200.00', '', '2018-03-23 14:47:37', 0, '0', '2018-03-23 14:47:02', 'open', 0, 0),
(10, 5, 2, 12, '192.00', '5625.00', '6.00', '6.00', '337.50', '337.50', '600.00', '525.00', 'each', '6300.00', '7200.00', '', '2018-03-23 14:47:51', 1, '12', '2018-03-23 14:47:38', 'open', 0, 0),
(11, 5, 2, 12, '192.00', '5625.00', '6.00', '6.00', '337.50', '337.50', '600.00', '525.00', 'each', '6300.00', '7200.00', '', '2018-03-23 15:06:40', 0, '0', '2018-03-23 15:06:34', 'open', 0, 0),
(12, 3, 2, 2, '194.00', '937.50', '6.00', '6.00', '56.25', '56.25', '600.00', '525.00', 'each', '1050.00', '1200.00', '', '2018-03-23 14:50:22', 1, '2', '2018-03-23 14:50:10', 'open', 0, 0),
(13, 3, 2, 2, '194.00', '937.50', '6.00', '6.00', '56.25', '56.25', '600.00', '525.00', 'each', '1050.00', '1200.00', '', '2018-03-23 14:54:11', 0, '0', '2018-03-23 14:50:30', 'open', 0, 0),
(14, 3, 2, 2, '194.00', '937.50', '6.00', '6.00', '56.25', '56.25', '600.00', '525.00', 'each', '1050.00', '1200.00', '', '2018-03-23 14:54:30', 1, '2', '2018-03-23 14:54:30', 'open', 1, 0),
(15, 4, 1, 1, '169.00', '381.36', '9.00', '9.00', '34.32', '34.32', '500.00', '450.00', 'each', '450.00', '500.00', '', '2018-03-23 14:58:40', 0, '0', '2018-03-23 14:58:30', 'open', 0, 0),
(16, 4, 1, 1, '169.00', '381.36', '9.00', '9.00', '34.32', '34.32', '500.00', '450.00', 'each', '450.00', '500.00', '', '2018-03-24 11:49:43', 1, '1', '2018-03-23 15:00:16', 'open', 0, 0),
(17, 1, 2, 10, '202.00', '4687.50', '6.00', '6.00', '281.25', '281.25', '600.00', '525.00', 'each', '5250.00', '6000.00', '', '2018-03-23 15:00:28', 1, '10', '2018-03-23 15:00:28', 'open', 1, 0),
(18, 5, 2, 12, '192.00', '5625.00', '6.00', '6.00', '337.50', '337.50', '600.00', '525.00', 'each', '6300.00', '7200.00', '', '2018-03-23 15:06:52', 0, '0', '2018-03-23 15:06:42', 'open', 0, 0),
(19, 5, 2, 12, '192.00', '5625.00', '6.00', '6.00', '337.50', '337.50', '600.00', '525.00', 'each', '6300.00', '7200.00', '', '2018-03-23 15:13:29', 0, '0', '2018-03-23 15:13:24', 'open', 0, 0),
(20, 5, 2, 12, '192.00', '5625.00', '6.00', '6.00', '337.50', '337.50', '600.00', '525.00', 'each', '6300.00', '7200.00', '', '2018-03-24 11:21:10', 0, '0', '2018-03-23 18:14:22', 'open', 0, 0),
(21, 2, 1, 1, '170.00', '381.36', '9.00', '9.00', '34.32', '34.32', '500.00', '450.00', 'each', '450.00', '500.00', '', '2018-03-23 17:37:13', 1, '1', '2018-03-23 17:37:13', 'open', 1, 0),
(22, 5, 2, 12, '192.00', '5625.00', '6.00', '6.00', '337.50', '337.50', '600.00', '525.00', 'each', '6300.00', '7200.00', '', '2018-03-24 12:14:34', 0, '0', '2018-03-24 12:14:34', 'open', 1, 0),
(23, 4, 1, 1, '169.00', '381.36', '9.00', '9.00', '34.32', '34.32', '500.00', '450.00', 'each', '450.00', '500.00', '', '2018-03-24 11:49:44', 1, '1', '2018-03-24 11:49:44', 'open', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_stock`
--

CREATE TABLE `wp_shc_stock` (
  `id` int(11) NOT NULL,
  `lot_number` varchar(250) NOT NULL,
  `stock_count` int(11) NOT NULL,
  `selling_total` decimal(15,2) NOT NULL,
  `selling_price` decimal(15,2) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `modified_by` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `active` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_stock`
--

INSERT INTO `wp_shc_stock` (`id`, `lot_number`, `stock_count`, `selling_total`, `selling_price`, `created_by`, `modified_by`, `created_at`, `modified_at`, `active`) VALUES
(1, '1', 100, '45000.00', '450.00', 'admin', '', '2018-02-23 16:26:49', '2018-02-23 16:26:49', 1),
(2, '2', 200, '105000.00', '525.00', 'admin', '', '2018-02-23 16:28:07', '2018-02-23 16:28:07', 1),
(3, '1', 1, '450.00', '450.00', 'admin', '', '2018-02-26 12:53:15', '2018-02-26 12:53:15', 1),
(4, '2', 2, '1050.00', '525.00', 'admin', '', '2018-02-26 13:35:43', '2018-02-26 13:35:43', 1),
(5, '3', 12, '1680.00', '140.00', 'admin', '', '2018-02-28 18:04:16', '2018-03-22 11:11:48', 0),
(6, '3', 100, '3880.00', '38.80', 'admin', '', '2018-03-19 15:06:51', '2018-03-22 11:11:41', 0),
(7, '1', 100, '45000.00', '450.00', 'admin', '', '2018-03-20 17:53:15', '2018-03-20 17:53:15', 1),
(8, '3', 12, '465.60', '38.80', 'admin', '', '2018-03-22 11:12:30', '2018-03-22 11:12:30', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_wholesale_customer`
--

CREATE TABLE `wp_shc_wholesale_customer` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(200) NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `secondary_mobile` varchar(100) NOT NULL,
  `landline` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `gst_number` varchar(200) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `modified_by` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_wholesale_customer`
--

INSERT INTO `wp_shc_wholesale_customer` (`id`, `customer_name`, `company_name`, `mobile`, `secondary_mobile`, `landline`, `address`, `gst_number`, `created_by`, `modified_by`, `created_at`, `modified_at`, `active`) VALUES
(1, 'Mary', 'Ajna1', '8665633453', '6464631314', '45555555', 'Snkskk', '12SAD5242222222', 'admin', 'admin', '2018-02-23 16:33:16', '2018-03-22 14:41:42', 1),
(2, 'Sowmi', 'Ajna', '9864343434', '6565464343', '45555555', 'Smld', '443457656431SA4', 'admin', 'admin', '2018-02-23 16:34:06', '2018-02-23 17:35:15', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_ws_cd_notes`
--

CREATE TABLE `wp_shc_ws_cd_notes` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `return_id` int(11) NOT NULL,
  `master_key` varchar(100) NOT NULL,
  `key_value` varchar(100) NOT NULL,
  `key_amount` decimal(15,2) NOT NULL,
  `description` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_ws_cd_notes`
--

INSERT INTO `wp_shc_ws_cd_notes` (`id`, `customer_id`, `bill_id`, `return_id`, `master_key`, `key_value`, `key_amount`, `description`, `created_at`, `modified_at`, `active`) VALUES
(1, 2, 1, 1, 'return_biling', 'debit', '2475.00', '', '2018-03-22 11:02:58', '2018-03-22 11:03:04', 0),
(2, 2, 1, 1, 'return_biling', 'debit', '2475.00', '', '2018-03-22 11:03:04', '2018-03-22 11:05:45', 0),
(3, 0, 2, 2, 'return_biling', 'debit', '500.00', '', '2018-03-22 15:19:50', '2018-03-22 15:19:55', 0),
(4, 0, 2, 2, 'return_biling', 'debit', '500.00', '', '2018-03-22 15:19:55', '2018-03-22 15:19:55', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_ws_return_items`
--

CREATE TABLE `wp_shc_ws_return_items` (
  `id` int(11) NOT NULL,
  `return_id` varchar(200) NOT NULL,
  `inv_id` varchar(100) NOT NULL,
  `search_inv_id` varchar(10) NOT NULL,
  `financial_year` varchar(20) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `cd_id` int(11) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `modified_by` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `cancel` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_ws_return_items`
--

INSERT INTO `wp_shc_ws_return_items` (`id`, `return_id`, `inv_id`, `search_inv_id`, `financial_year`, `customer_id`, `cd_id`, `total_amount`, `created_by`, `modified_by`, `created_at`, `modified_at`, `active`, `cancel`) VALUES
(1, 'GR 1', '1', '1', '2017', 2, 2, '2475.00', 'admin', 'admin', '2018-03-22 11:02:58', '2018-03-22 11:05:45', 0, 1),
(2, 'GR 1', '2', '2', '2017', 0, 4, '500.00', 'admin', 'admin', '2018-03-22 15:19:50', '2018-03-22 15:19:55', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_ws_return_items_details`
--

CREATE TABLE `wp_shc_ws_return_items_details` (
  `id` int(11) NOT NULL,
  `return_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `sale_unit` decimal(15,2) NOT NULL,
  `bal_qty` decimal(15,2) NOT NULL,
  `return_unit` decimal(15,2) NOT NULL,
  `mrp` decimal(15,2) NOT NULL,
  `sale_value` decimal(15,2) NOT NULL,
  `cgst` decimal(15,2) NOT NULL,
  `cgst_value` decimal(15,2) NOT NULL,
  `sgst` decimal(15,2) NOT NULL,
  `sgst_value` decimal(15,2) NOT NULL,
  `sub_total` decimal(15,2) NOT NULL,
  `amt` decimal(15,2) NOT NULL,
  `sale_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL DEFAULT '1',
  `cancel` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_ws_return_items_details`
--

INSERT INTO `wp_shc_ws_return_items_details` (`id`, `return_id`, `sale_id`, `lot_id`, `sale_unit`, `bal_qty`, `return_unit`, `mrp`, `sale_value`, `cgst`, `cgst_value`, `sgst`, `sgst_value`, `sub_total`, `amt`, `sale_update`, `created_at`, `modified_at`, `active`, `cancel`) VALUES
(1, 1, 1, 1, '10.00', '5.00', '5.00', '495.00', '0.00', '9.00', '188.77', '9.00', '188.77', '2475.00', '2097.46', '2018-03-22 11:03:04', '2018-03-22 11:02:58', '2018-03-22 11:03:04', 0, 0),
(2, 1, 1, 1, '10.00', '5.00', '5.00', '495.00', '0.00', '9.00', '188.77', '9.00', '188.77', '2475.00', '2097.46', '2018-03-22 11:05:45', '2018-03-22 11:03:04', '2018-03-22 11:05:45', 0, 1),
(3, 2, 2, 1, '10.00', '9.00', '1.00', '500.00', '0.00', '9.00', '38.14', '9.00', '38.14', '500.00', '423.73', '2018-03-22 15:19:55', '2018-03-22 15:19:50', '2018-03-22 15:19:55', 0, 0),
(4, 2, 2, 1, '10.00', '9.00', '1.00', '500.00', '0.00', '9.00', '38.14', '9.00', '38.14', '500.00', '423.73', '2018-03-22 15:19:55', '2018-03-22 15:19:55', '2018-03-22 15:19:55', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_ws_sale`
--

CREATE TABLE `wp_shc_ws_sale` (
  `id` int(11) NOT NULL,
  `financial_year` varchar(20) NOT NULL,
  `inv_id` varchar(250) NOT NULL,
  `order_id` varchar(250) NOT NULL DEFAULT '0',
  `customer_id` int(11) NOT NULL DEFAULT '0',
  `cd_id` int(11) NOT NULL,
  `cd_check` int(11) NOT NULL,
  `home_delivery` int(11) NOT NULL DEFAULT '0',
  `home_delivery_name` varchar(100) NOT NULL,
  `home_delivery_mobile` varchar(100) NOT NULL,
  `home_delivery_address` varchar(200) NOT NULL,
  `sub_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `before_total` decimal(15,2) NOT NULL,
  `prev_bal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(15,2) NOT NULL,
  `return_amt` decimal(15,2) NOT NULL,
  `current_bal` decimal(15,2) NOT NULL,
  `payment_type` varchar(200) NOT NULL,
  `payment_details` varchar(200) NOT NULL,
  `payment_date` varchar(200) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `modified_by` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `locked` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '1',
  `cancel` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_ws_sale`
--

INSERT INTO `wp_shc_ws_sale` (`id`, `financial_year`, `inv_id`, `order_id`, `customer_id`, `cd_id`, `cd_check`, `home_delivery`, `home_delivery_name`, `home_delivery_mobile`, `home_delivery_address`, `sub_total`, `before_total`, `prev_bal`, `discount`, `paid_amount`, `return_amt`, `current_bal`, `payment_type`, `payment_details`, `payment_date`, `created_by`, `modified_by`, `created_at`, `modified_at`, `locked`, `active`, `cancel`) VALUES
(1, '2017', '1', '220320187628', 2, 0, 0, 0, 'Ajna', '9864343434', 'Smld', '4950.00', '5000.00', '0.00', '1.00', '0.00', '4950.00', '0.00', 'internet_banking', '', '', 'admin', 'admin', '2018-03-22 11:01:13', '2018-03-22 11:06:09', 1, 0, 1),
(2, '2017', '2', '22032018A883', 0, 0, 0, 0, '', '', '', '5000.00', '5000.00', '0.00', '0.00', '5000.00', '0.00', '0.00', 'cash', '', '', 'admin', 'admin', '2018-03-22 11:32:46', '2018-03-22 11:33:11', 1, 1, 0),
(3, '2017', '3', '220320188EF6', 2, 0, 0, 0, 'Ajna', '9864343434', 'Smld', '6000.00', '6000.00', '0.00', '0.00', '6000.00', '0.00', '0.00', 'cash', '', '', 'admin', 'admin', '2018-03-22 14:40:35', '2018-03-22 14:40:55', 1, 1, 0),
(4, '2017', '4', '2203201826E5', 1, 0, 0, 0, 'Ajna', '8665633453', 'Snkskk', '5000.00', '5000.00', '0.00', '0.00', '5000.00', '0.00', '0.00', 'cash', '', '', 'admin', 'admin', '2018-03-22 14:41:02', '2018-03-22 14:41:21', 1, 1, 0),
(5, '2017', '5', '0', 0, 0, 0, 0, '', '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '', '', '', '', '', '2018-03-23 17:37:36', '0000-00-00 00:00:00', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wp_shc_ws_sale_detail`
--

CREATE TABLE `wp_shc_ws_sale_detail` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `sale_unit` int(11) NOT NULL,
  `stock` decimal(15,2) NOT NULL,
  `amt` decimal(15,2) NOT NULL,
  `cgst` decimal(15,2) NOT NULL,
  `sgst` decimal(15,2) NOT NULL,
  `cgst_value` decimal(15,2) NOT NULL,
  `sgst_value` decimal(15,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(100) NOT NULL,
  `sub_total` decimal(15,2) NOT NULL,
  `payment_type` varchar(200) NOT NULL,
  `sale_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_delivery` int(1) NOT NULL DEFAULT '0',
  `delivery_count` decimal(15,0) NOT NULL,
  `delivery_date` datetime NOT NULL,
  `item_status` varchar(250) NOT NULL DEFAULT 'open',
  `active` int(2) NOT NULL DEFAULT '1',
  `cancel` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_shc_ws_sale_detail`
--

INSERT INTO `wp_shc_ws_sale_detail` (`id`, `sale_id`, `lot_id`, `sale_unit`, `stock`, `amt`, `cgst`, `sgst`, `cgst_value`, `sgst_value`, `unit_price`, `discount`, `discount_type`, `sub_total`, `payment_type`, `sale_update`, `is_delivery`, `delivery_count`, `delivery_date`, `item_status`, `active`, `cancel`) VALUES
(1, 1, 1, 10, '201.00', '4194.92', '9.00', '9.00', '377.54', '377.54', '500.00', '495.00', 'whole', '4950.00', '', '2018-03-22 11:02:30', 0, '0', '0000-00-00 00:00:00', 'open', 0, 0),
(2, 1, 1, 10, '201.00', '4194.92', '9.00', '9.00', '377.54', '377.54', '500.00', '495.00', 'whole', '4950.00', '', '2018-03-22 11:06:09', 1, '10', '2018-03-22 11:02:30', 'open', 0, 1),
(3, 2, 1, 10, '201.00', '4237.29', '9.00', '9.00', '381.36', '381.36', '500.00', '500.00', 'whole', '5000.00', '', '2018-03-22 11:33:11', 0, '0', '0000-00-00 00:00:00', 'open', 0, 0),
(4, 2, 1, 10, '201.00', '4237.29', '9.00', '9.00', '381.36', '381.36', '500.00', '500.00', 'whole', '5000.00', '', '2018-03-22 11:33:12', 1, '10', '2018-03-22 11:33:12', 'open', 1, 0),
(5, 3, 1, 12, '191.00', '5084.75', '9.00', '9.00', '457.63', '457.63', '500.00', '500.00', 'whole', '6000.00', '', '2018-03-22 14:40:55', 0, '0', '0000-00-00 00:00:00', 'open', 0, 0),
(6, 3, 1, 12, '191.00', '5084.75', '9.00', '9.00', '457.63', '457.63', '500.00', '500.00', 'whole', '6000.00', '', '2018-03-22 14:40:56', 1, '12', '2018-03-22 14:40:56', 'open', 1, 0),
(7, 4, 1, 10, '179.00', '4237.29', '9.00', '9.00', '381.36', '381.36', '500.00', '500.00', 'whole', '5000.00', '', '2018-03-22 14:41:21', 0, '0', '0000-00-00 00:00:00', 'open', 0, 0),
(8, 4, 1, 10, '179.00', '4237.29', '9.00', '9.00', '381.36', '381.36', '500.00', '500.00', 'whole', '5000.00', '', '2018-03-24 13:38:06', 1, '10', '2018-03-24 09:46:06', 'open', 0, 0),
(9, 4, 1, 10, '179.00', '4237.29', '9.00', '9.00', '381.36', '381.36', '500.00', '500.00', 'whole', '5000.00', '', '2018-03-24 13:39:20', 1, '10', '2018-03-24 13:39:20', 'open', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wp_shc_cd_notes`
--
ALTER TABLE `wp_shc_cd_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_shc_creditdebit`
--
ALTER TABLE `wp_shc_creditdebit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_shc_customers`
--
ALTER TABLE `wp_shc_customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_shc_lots`
--
ALTER TABLE `wp_shc_lots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_shc_netbank`
--
ALTER TABLE `wp_shc_netbank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_shc_profile`
--
ALTER TABLE `wp_shc_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_shc_return_items`
--
ALTER TABLE `wp_shc_return_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_shc_return_items_details`
--
ALTER TABLE `wp_shc_return_items_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_shc_sale`
--
ALTER TABLE `wp_shc_sale`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_shc_sale_detail`
--
ALTER TABLE `wp_shc_sale_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_shc_stock`
--
ALTER TABLE `wp_shc_stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_shc_wholesale_customer`
--
ALTER TABLE `wp_shc_wholesale_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_shc_ws_cd_notes`
--
ALTER TABLE `wp_shc_ws_cd_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_shc_ws_return_items`
--
ALTER TABLE `wp_shc_ws_return_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_shc_ws_return_items_details`
--
ALTER TABLE `wp_shc_ws_return_items_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_shc_ws_sale`
--
ALTER TABLE `wp_shc_ws_sale`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_shc_ws_sale_detail`
--
ALTER TABLE `wp_shc_ws_sale_detail`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wp_shc_cd_notes`
--
ALTER TABLE `wp_shc_cd_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `wp_shc_creditdebit`
--
ALTER TABLE `wp_shc_creditdebit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `wp_shc_customers`
--
ALTER TABLE `wp_shc_customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `wp_shc_lots`
--
ALTER TABLE `wp_shc_lots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `wp_shc_netbank`
--
ALTER TABLE `wp_shc_netbank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `wp_shc_profile`
--
ALTER TABLE `wp_shc_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `wp_shc_return_items`
--
ALTER TABLE `wp_shc_return_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `wp_shc_return_items_details`
--
ALTER TABLE `wp_shc_return_items_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `wp_shc_sale`
--
ALTER TABLE `wp_shc_sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `wp_shc_sale_detail`
--
ALTER TABLE `wp_shc_sale_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `wp_shc_stock`
--
ALTER TABLE `wp_shc_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `wp_shc_wholesale_customer`
--
ALTER TABLE `wp_shc_wholesale_customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `wp_shc_ws_cd_notes`
--
ALTER TABLE `wp_shc_ws_cd_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `wp_shc_ws_return_items`
--
ALTER TABLE `wp_shc_ws_return_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `wp_shc_ws_return_items_details`
--
ALTER TABLE `wp_shc_ws_return_items_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `wp_shc_ws_sale`
--
ALTER TABLE `wp_shc_ws_sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `wp_shc_ws_sale_detail`
--
ALTER TABLE `wp_shc_ws_sale_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
