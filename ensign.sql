-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 03, 2015 at 11:17 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ensign`
--
CREATE DATABASE IF NOT EXISTS `ensign` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ensign`;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `c_id` int(255) NOT NULL AUTO_INCREMENT,
  `date` int(40) NOT NULL,
  `user_id` int(255) DEFAULT NULL,
  `project_id` int(255) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `text` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`c_id`, `date`, `user_id`, `project_id`, `active`, `text`) VALUES
(1, 1411054406, 1, 1, 1, '<p>They need a Pre-Invoice to start</p><p><span class=''label label-default'' >Newbie</span> ---> <span class=''label label-primary'' >waiting for pre-payment</span></p>'),
(2, 1411054543, 1, 2, 1, '<p>The hospital needs an appointment</p><p><span class=''label label-default'' >Newbie</span> ---> <span class=''label label-warning'' >waiting for meeting</span></p>'),
(3, 1411057785, 1, 3, 1, '<p>needs a meeting there</p><p><span class=''label label-default'' >Newbie</span> ---> <span class=''label label-warning'' >waiting for meeting</span></p>'),
(4, 1411058751, 1, 4, 1, '<p>needs a price list</p><p><span class=''label label-default'' >Newbie</span> ---> <span class=''label label-primary'' >waiting for pre-payment</span></p>'),
(5, 1411059970, 1, 4, 1, '<p>pricelist was sent</p><p><span class=''label label-primary'' >waiting for pre-payment</span> ---> <span class=''label label-info'' >waiting for customer reply</span></p>'),
(6, 1411065401, 1, 5, 1, '<p>I needs a pre-invoiced</p><p><span class=''label label-default'' >Newbie</span> ---> <span class=''label label-warning'' >waiting for pre-invoiced</span></p>'),
(7, 1411066628, 1, 5, 1, '<p>pre-invoiced sent</p><p><span class=''label label-warning'' >waiting for pre-invoiced</span> ---> <span class=''label label-primary'' >waiting for pre-payment</span></p>'),
(8, 1411067617, 1, 3, 1, '<p>Meeting set on 09/22/2014</p>'),
(9, 1411067694, 1, 3, 1, '<p>contract signed , this organization need a full signage way-finding system</p><p><span class=''label label-warning'' >waiting for meeting</span> ---> <span class=''label label-warning'' >waiting for pre-invoiced</span></p>'),
(10, 1411067960, 1, 3, 1, '<p>the pre-invoiced was sent to them</p><p><span class=''label label-warning'' >waiting for pre-invoiced</span> ---> <span class=''label label-primary'' >waiting for pre-payment</span></p>');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `c_id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` int(40) NOT NULL,
  `user_id` int(255) NOT NULL,
  `tel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `desc` text COLLATE utf8_unicode_ci,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`c_id`, `name`, `date`, `user_id`, `tel`, `mobile`, `desc`, `email`, `address`) VALUES
(1, 'Pishropars', 1411052747, 1, '0098218601919', '0089124057979', 'Micro company , chemical field', 'freeman@pishropars.com', 'No2,7 street , Valiasr street , Tehran , Iran'),
(2, 'Ghiasi Hospital', 1411053104, 1, '0098217601919', '09123184651', 'Very large Hospital , 12 floor', 'bajoodi@yahoo.com', 'No.5 – Siasi street 40212- Tehran- Iran'),
(3, 'Nabarvari clinic', 1411057640, 1, '009822050788', '00989124057923', 'Baghyatalah hospital introduce us to them', 'kasravi@yahoo.com', 'Vanak square'),
(4, 'Modiriat sanati Org', 1411058651, 1, '009813232323', '009933877645', '2 buildings', 'miri@mbi.ir', 'Valiasr street , near Jam street'),
(5, 'Nia shimi', 1411064904, 1, '00982122601259', '00989121544875', 'small company', 'asghari@yahoo.com', 'Sohrevardi street Tehran , Iran');

-- --------------------------------------------------------

--
-- Table structure for table `dep`
--

CREATE TABLE IF NOT EXISTS `dep` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `dep`
--

INSERT INTO `dep` (`id`, `title`, `status`) VALUES
(3, 'Design', 1),
(4, 'Install', 1),
(5, 'workshop', 1),
(6, 'print', 1),
(7, 'Buy', 1),
(8, 'Sell', 1),
(9, 'Managment', 1),
(10, 'IT', 1);

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `f_id` int(255) NOT NULL AUTO_INCREMENT,
  `date` int(40) NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_id` int(255) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `user_id` int(255) NOT NULL,
  `overal_price` int(11) NOT NULL,
  `final_price` int(11) NOT NULL,
  `remove_total_price` tinyint(4) NOT NULL,
  `details` text COLLATE utf8_unicode_ci,
  `is_decent` tinyint(4) NOT NULL COMMENT 'آیا تخفیف دارد',
  `is_desc` tinyint(4) NOT NULL COMMENT 'توضیحات دارد',
  `alias` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `signiture` tinyint(4) DEFAULT '1',
  `prepayment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prefactor` int(255) DEFAULT NULL,
  `fifty` tinyint(4) DEFAULT '1',
  `should_pay` int(11) DEFAULT '0',
  `invoice_id` int(255) DEFAULT '0',
  PRIMARY KEY (`f_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`f_id`, `date`, `file_name`, `project_id`, `type`, `user_id`, `overal_price`, `final_price`, `remove_total_price`, `details`, `is_decent`, `is_desc`, `alias`, `status`, `signiture`, `prepayment`, `prefactor`, `fifty`, `should_pay`, `invoice_id`) VALUES
(1, 1411059405, NULL, 4, 1, 1, 2600000, 2808000, 1, '{"setting":{"1":{"selltype":"1","selltitle":"tax","sellunit":"1","sellinput":"8","sellprice":"208000"}},"top":"Modiriat sanati Org- Mr Miri\\r\\nAddress: Valiasr street , near Jam street- Tel: 009813232323","bottom":"All of products have guarantee until 30 months\\r\\nPayment due time is 1 month after the invoiced date\\r\\nProject delivery schedule: after receiving an advance payment , until 15 working days after approval form is signed"}', 0, 1, '704-1', 2, 1, '1404000', NULL, 1, 0, 0),
(2, 1411066116, NULL, 5, 1, 1, 14400000, 15582000, 0, '{"setting":{"1":{"selltype":"1","selltitle":"Tax","sellunit":"1","sellinput":"8","sellprice":"1152000"},"2":{"selltype":"1","selltitle":"Transfering","sellunit":"2","sellinput":"30000","sellprice":"30000"}},"top":"Nia shimi- Mrs asghari\\r\\nAddress: Sohrevardi street Tehran , Iran- Tel: 00982122601259","bottom":"All of products have guarantee until 30 months\\r\\nPayment due time is 1 month after the invoiced date\\r\\nProject delivery schedule: after receiving an advance payment , until 15 working days after approval form is signed"}', 0, 1, '705-1', 2, 1, '7791000', NULL, 1, 0, 0),
(3, 1411067915, NULL, 3, 1, 1, 2147483647, 2147483647, 0, '{"setting":{"1":{"selltype":"1","selltitle":"Tax","sellunit":"1","sellinput":"8","sellprice":"656000000"},"2":{"selltype":"1","selltitle":"descent","sellunit":"1","sellinput":"4","sellprice":"328000000"}},"top":"Nabarvari clinic- Kasravi\\r\\nAddress: Vanak square- Tel: 009822050788","bottom":"All of products have guarantee until 30 months\\r\\nPayment due time is 1 month after the invoiced date\\r\\nProject delivery schedule: after receiving an advance payment , until 15 working days after approval form is signed"}', 0, 1, '703-1', 2, 1, '4592000000', NULL, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `files_row`
--

CREATE TABLE IF NOT EXISTS `files_row` (
  `fr_id` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `num` int(255) NOT NULL DEFAULT '1',
  `price` int(255) DEFAULT NULL,
  `total` int(255) DEFAULT NULL,
  `decent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `files_id` int(255) NOT NULL,
  `decent_type` tinyint(4) DEFAULT NULL,
  `desc` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`fr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `files_row`
--

INSERT INTO `files_row` (`fr_id`, `title`, `num`, `price`, `total`, `decent`, `files_id`, `decent_type`, `desc`) VALUES
(1, 'PVC hanging 50*25', 1, 800000, 800000, '0', 1, NULL, ''),
(2, 'Aluminium  20*25', 1, 700000, 700000, '0', 1, NULL, 'install on wall'),
(3, 'Iron 20*25', 1, 600000, 600000, '0', 1, NULL, ''),
(4, 'Plaksy  20*25', 1, 500000, 500000, '0', 1, NULL, ''),
(5, 'Aluminium 20*25', 3, 400000, 1200000, '0', 2, NULL, ''),
(6, 'Aluminium 30*20 ', 15, 700000, 10500000, '0', 2, NULL, ''),
(7, 'Aluminium 40*25 ', 9, 300000, 2700000, '0', 2, NULL, ''),
(8, 'Aluminium 20*25', 40, 40000000, 1600000000, '0', 3, NULL, ''),
(9, 'Aluminium 50*25 ', 50, 80000000, 2147483647, '0', 3, NULL, ''),
(10, 'composite 40*20', 20, 80000000, 1600000000, '0', 3, NULL, ''),
(11, 'Aluminium 20*10', 50, 20000000, 1000000000, '0', 3, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `p_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` int(40) NOT NULL,
  `user_id` int(255) DEFAULT NULL,
  `customer_id` int(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `desc` text COLLATE utf8_unicode_ci,
  `responsible` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`p_id`, `title`, `date`, `user_id`, `customer_id`, `status`, `desc`, `responsible`) VALUES
(1, 'Order 1', 1411052748, 1, 1, 7, 'The company want some indoor plastic signage', 'Mr freeman'),
(2, 'Order 2', 1411053104, 1, 2, 4, 'Need aluminum and PVC signages', 'Mrs Bajoodi'),
(3, 'Order 3', 1411057640, 1, 3, 7, 'needs some wall indoor signage', 'Kasravi'),
(4, 'Order 4', 1411058651, 1, 4, 3, 'They need signage system for all of building', 'Mr Miri'),
(5, 'Order 5', 1411064904, 1, 5, 7, 'need some wall signage for their company', 'Mrs asghari');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE IF NOT EXISTS `setting` (
  `st_id` int(255) NOT NULL AUTO_INCREMENT,
  `option` text COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`st_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`st_id`, `option`, `value`) VALUES
(1, 'sell_fields', '{"7":{"selltype":1,"selltitle":"Tax","sellunit":1,"sellinput":8}}');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `s_id` int(255) NOT NULL AUTO_INCREMENT,
  `project_id` int(255) DEFAULT NULL,
  `date` int(40) DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `status_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`s_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`s_id`, `project_id`, `date`, `user_id`, `status_id`) VALUES
(1, 1, 1411054406, 1, 7),
(2, 2, 1411054543, 1, 4),
(3, 3, 1411057785, 1, 4),
(4, 4, 1411058751, 1, 7),
(5, 4, 1411059970, 1, 3),
(6, 5, 1411065401, 1, 6),
(7, 5, 1411066628, 1, 7),
(8, 3, 1411067694, 1, 6),
(9, 3, 1411067960, 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `status_name`
--

CREATE TABLE IF NOT EXISTS `status_name` (
  `sn_id` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(4) DEFAULT NULL,
  `category` tinyint(4) DEFAULT NULL,
  `label` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`sn_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Dumping data for table `status_name`
--

INSERT INTO `status_name` (`sn_id`, `title`, `active`, `category`, `label`) VALUES
(1, 'Newbie', 1, 1, 'default'),
(2, 'waiting for our reply', 1, 1, 'warning'),
(3, 'waiting for customer reply', 1, 1, 'info'),
(4, 'waiting for meeting', 1, 1, 'warning'),
(5, 'waiting for order form', 1, 1, 'info'),
(6, 'waiting for pre-invoiced', 1, 1, 'warning'),
(7, 'waiting for pre-payment', 1, 1, 'primary'),
(8, 'pre-payment paid', 1, 1, 'success'),
(9, 'canceled', 1, 0, 'default'),
(10, 'designing', 1, 2, 'info'),
(11, 'printing', 1, 2, 'info'),
(12, 'assembling', 1, 2, 'info'),
(13, 'Sent', 1, 2, 'info'),
(14, 'Settled', 1, 2, 'success'),
(15, 'Waiting for installation', 1, 2, 'info'),
(16, 'final Settled', 1, 2, 'success'),
(17, 'freeze', 1, 0, 'default');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `t_id` int(255) NOT NULL AUTO_INCREMENT,
  `text` text COLLATE utf8_unicode_ci,
  `dep` int(255) NOT NULL,
  `creator` int(255) NOT NULL,
  `due` int(40) DEFAULT NULL,
  `comment_id` int(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `submitdate` int(40) NOT NULL,
  `later` tinyint(4) NOT NULL,
  `priority` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`t_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`t_id`, `text`, `dep`, `creator`, `due`, `comment_id`, `status`, `submitdate`, `later`, `priority`) VALUES
(1, 'Set an appointment with them', 8, 1, 1411241399, 2, 0, 1411054543, 0, 0),
(2, 'Design a Don`t smoke signage sample for showroom ', 3, 1, 1413923399, 0, 0, 1411054869, 0, 0),
(3, 'Produce a PVC signage sample for showroom ', 5, 1, 1414182599, 0, 0, 1411054869, 0, 0),
(4, 'Need some PVC for signage sample ', 7, 1, 1413923399, 0, 0, 1411054869, 0, 0),
(5, 'Set a appointment ', 8, 1, 1412108999, 3, 0, 1411057785, 0, 0),
(6, 'make a price list for them', 8, 1, 1411241399, 4, 0, 1411058751, 0, 0),
(7, 'Order 5 order needs a pre-invoiced', 8, 1, 1411151801, 6, 0, 1411065401, 0, 0),
(8, 'Order 3 order needs a pre-invoiced', 8, 1, 1411154094, 9, 0, 1411067694, 0, 0),
(9, 'write a new resume for company', 10, 1, 1414787399, 0, 0, 1413261409, 0, 0),
(10, 'send a catalog to me', 10, 1, 1413491399, 0, 0, 1413261445, 0, 0),
(11, 'Re-design the website with products changes', 10, 1, 86399, 0, 0, 1413261524, 0, 0),
(12, 'Send a CD and catalog to Mr Smith', 8, 3, 1413491399, 0, 0, 1413261580, 0, 0),
(13, 'get the samples back from Mr Smith', 8, 3, 0, 0, 0, 1413261616, 1, 0),
(14, 'check the number of Iron block in the stock', 8, 3, 1413664199, 0, 0, 1413261678, 0, 0),
(15, 'Set a meeting with Ms. yasha', 8, 3, 1413404999, 0, 0, 1413262522, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `u_id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `mobile` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_id`, `name`, `username`, `password`, `active`, `mobile`, `email`) VALUES
(1, 'Ahad', 'ahad', 'godmk3', 1, '09124057923', '09124057923'),
(2, 'Ali', 'ali', '123322', 1, '09124057923', '09124057923'),
(3, 'Amirhosein', 'amir', 'amir', 1, '09124057923', '09124057923'),
(4, 'mohammad', 'mohamad', 'mohamad', 1, '09124057923', '09124057923'),
(5, 'Iman', 'iman', 'iman', 1, '09124057923', '09124057923'),
(6, 'Davood', 'davood', 'davood', 1, '09124057923', '09124057923');

-- --------------------------------------------------------

--
-- Table structure for table `user_relation`
--

CREATE TABLE IF NOT EXISTS `user_relation` (
  `r_id` int(255) NOT NULL AUTO_INCREMENT,
  `r_user_id` int(255) NOT NULL,
  `r_dep` int(255) NOT NULL,
  PRIMARY KEY (`r_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `user_relation`
--

INSERT INTO `user_relation` (`r_id`, `r_user_id`, `r_dep`) VALUES
(1, 3, 8),
(2, 4, 4),
(3, 4, 5),
(4, 4, 6),
(5, 4, 7),
(6, 6, 4),
(7, 6, 5),
(8, 6, 6),
(9, 5, 3),
(10, 2, 9),
(11, 1, 10),
(12, 4, 9);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
