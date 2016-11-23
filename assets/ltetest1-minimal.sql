-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2015 at 07:33 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ltetest1`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmins`
--

CREATE TABLE IF NOT EXISTS `tbladmins` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) DEFAULT NULL,
  `ContactNo` bigint(20) DEFAULT NULL,
  `Address` varchar(500) DEFAULT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `IsActive` varchar(50) DEFAULT NULL,
  `DateCreated` date DEFAULT NULL,
  `DateUpdated` date DEFAULT NULL,
  `CreatedBy` varchar(50) DEFAULT NULL,
  `UpdatedBy` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbladmins`
--

INSERT INTO `tbladmins` (`Id`, `Name`, `ContactNo`, `Address`, `Email`, `Password`, `IsActive`, `DateCreated`, `DateUpdated`, `CreatedBy`, `UpdatedBy`) VALUES
(1, 'admin', 1233454657, '0', 'admin@abc.com', '123456', 'false', NULL, NULL, NULL, NULL),
(3, 'test', 1233454657, '0', 'test@abc.com', '123456', 'true', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblclientpayment`
--

CREATE TABLE IF NOT EXISTS `tblclientpayment` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ClientId` int(11) DEFAULT NULL,
  `Paid` int(50) DEFAULT NULL,
  `Payment_Date` date DEFAULT NULL,
  `Created_By` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `tblclientpayment`
--

INSERT INTO `tblclientpayment` (`Id`, `ClientId`, `Paid`, `Payment_Date`, `Created_By`) VALUES
(3, 8, 3000, '2015-04-23', 1),
(7, 8, 1000, '0000-00-00', 3),
(8, 8, 1000, '2015-04-24', 3),
(9, 9, 1000, '2015-04-27', 1),
(10, 10, 1000, '2015-04-27', 1),
(11, 11, 1000, '2015-04-27', 1),
(12, 12, 1000, '2015-04-27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblclients`
--

CREATE TABLE IF NOT EXISTS `tblclients` (
  `Id` int(50) NOT NULL AUTO_INCREMENT,
  `ClientID` varchar(50) DEFAULT NULL,
  `ClientName` varchar(50) DEFAULT NULL,
  `ClientCompany` varchar(100) NOT NULL,
  `DealerType` varchar(50) DEFAULT NULL,
  `ClientContact` bigint(50) DEFAULT NULL,
  `ClientAddress` varchar(500) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Package` varchar(50) DEFAULT NULL,
  `Paid` int(50) DEFAULT NULL,
  `TotalLeads` int(50) DEFAULT NULL,
  `AssignedLeads` int(50) DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `IsActive` varchar(50) DEFAULT NULL,
  `DateCreated` date DEFAULT NULL,
  `DateUpdated` date DEFAULT NULL,
  `CreatedBy` varchar(50) DEFAULT NULL,
  `UpdatedBy` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `email` (`Email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `tblclients`
--

INSERT INTO `tblclients` (`Id`, `ClientID`, `ClientName`, `ClientCompany`, `DealerType`, `ClientContact`, `ClientAddress`, `Email`, `Package`, `Paid`, `TotalLeads`, `AssignedLeads`, `Password`, `IsActive`, `DateCreated`, `DateUpdated`, `CreatedBy`, `UpdatedBy`) VALUES
(1, NULL, 'admin', 'IT Solutions', 'Software', 354456576, 'IT Solutions', 'admin@abc.com', '20 clients at 10000', NULL, 20, 1, '123456', 'true', '2015-04-08', NULL, '1', NULL),
(3, NULL, 'LMS', 'IT', 'IT', 2334343454, 'IT Solutions, Santacruz West', 'test@test.com', '10000', NULL, 50, 1, '123456', 'true', '2015-04-11', NULL, '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblleads`
--

CREATE TABLE IF NOT EXISTS `tblleads` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ClientId` int(11) DEFAULT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Contact` bigint(20) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Address` varchar(500) DEFAULT NULL,
  `Description` varchar(500) DEFAULT NULL,
  `CreatedBy` varchar(50) DEFAULT NULL,
  `DateCreated` date DEFAULT NULL,
  `UpdatedBy` varchar(50) DEFAULT NULL,
  `DateUpdated` date DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tblleads`
--

INSERT INTO `tblleads` (`Id`, `ClientId`, `Name`, `Contact`, `Email`, `Address`, `Description`, `CreatedBy`, `DateCreated`, `UpdatedBy`, `DateUpdated`) VALUES
(1, 3, 'test', 2343544565, 'test@gmail.com', 'IT Solutions', 'Client Assigbed to IT for softwares.', '1', '2015-04-12', NULL, NULL),
(2, 1, 'admin', 2334343454, 'admin@gmail.com', 'IT Solutions', 'Lead Assigned To IT Solutions.', '1', '2015-04-12', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbltelecallers`
--

CREATE TABLE IF NOT EXISTS `tbltelecallers` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `TelecallerId` varchar(50) DEFAULT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `ContactNo` bigint(20) DEFAULT NULL,
  `Address` varchar(500) DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `IsActive` varchar(50) DEFAULT NULL,
  `DateCreated` date DEFAULT NULL,
  `DateUpdated` date DEFAULT NULL,
  `CreatedBy` varchar(50) DEFAULT NULL,
  `UpdatedBy` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbltelecallers`
--

INSERT INTO `tbltelecallers` (`Id`, `TelecallerId`, `Name`, `ContactNo`, `Address`, `Password`, `IsActive`, `DateCreated`, `DateUpdated`, `CreatedBy`, `UpdatedBy`) VALUES
(1, 'admin', 'admin', 1232343456, 'IT Solutions, Complete Cinema', '123456', 'true', '2015-04-05', NULL, NULL, NULL),
(2, NULL, 'admin2', 2434463456, 'IT Solutions', '123456', 'true', '2015-04-09', NULL, NULL, NULL),
(3, 'admin', 'admin', 1235446667, ' IT Solutions', 'admin', 'true', '2015-04-24', NULL, NULL, NULL),
(4, 'test', 'test', 4354563453, 'IT Solutions, Santacruz East', '123456', 'true', '2015-04-24', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblvisitorentry`
--

CREATE TABLE IF NOT EXISTS `tblvisitorentry` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `TeleCallerID` int(11) DEFAULT NULL,
  `CompanyName` varchar(100) DEFAULT NULL,
  `VisitorName` varchar(50) DEFAULT NULL,
  `VisitorType` varchar(50) DEFAULT NULL,
  `VisitorContact` bigint(20) DEFAULT NULL,
  `VisitorAddress` varchar(500) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `EntryDate` date DEFAULT NULL,
  `Status` varchar(50) NOT NULL,
  `Transfered` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tblvisitorentry`
--

INSERT INTO `tblvisitorentry` (`Id`, `TeleCallerID`, `CompanyName`, `VisitorName`, `VisitorType`, `VisitorContact`, `VisitorAddress`, `Email`, `EntryDate`, `Status`, `Transfered`) VALUES
(1, 1, 'LMS', 'LMS', 'Client', 23343434, 'LMS', 'LMS@lms.com', '2015-04-07', 'Pending', 'false'),
(2, 1, 'IT', 'LMS', 'IT', 2334343454, 'IT Solutions, Santacruz West', 'test@test.in', '2015-04-09', 'Approved', 'false');

-- --------------------------------------------------------

--
-- Table structure for table `tblvisitorhistory`
--

CREATE TABLE IF NOT EXISTS `tblvisitorhistory` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `VisitorId` int(11) NOT NULL,
  `TeleCallerId` int(11) NOT NULL,
  `ReminderDate` date NOT NULL,
  `VisitorRemark` varchar(500) NOT NULL,
  `viewed` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `tblvisitorhistory`
--

INSERT INTO `tblvisitorhistory` (`Id`, `VisitorId`, `TeleCallerId`, `ReminderDate`, `VisitorRemark`, `viewed`) VALUES
(1, 0, 0, '0000-00-00', 'Ok Ready for the deal.', 'false'),
(2, 0, 0, '0000-00-00', 'Lets do this deal', NULL),
(3, 2, 1, '0000-00-00', 'Not Interested', NULL),
(4, 3, 1, '0000-00-00', 'Busy right now', NULL),
(5, 4, 1, '0000-00-00', 'Ready', NULL),
(6, 5, 1, '2015-04-25', 'Not Interested', 'true'),
(7, 5, 1, '2015-04-28', 'Busy right now, call me later', 'true'),
(8, 5, 1, '2015-04-28', 'Will think', NULL),
(9, 6, 1, '2015-04-27', 'Busy Right now.', 'true'),
(10, 7, 1, '2015-04-27', 'Busy', 'false'),
(11, 6, 1, '2015-04-28', 'Still Busy', 'true'),
(12, 0, 0, '0000-00-00', 'ready', 'true'),
(13, 6, 1, '0000-00-00', 'Ready', 'true');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2015 at 02:10 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ltetest1`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmins`
--

CREATE TABLE IF NOT EXISTS `tbladmins` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) DEFAULT NULL,
  `ContactNo` bigint(20) DEFAULT NULL,
  `Address` varchar(500) DEFAULT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `IsActive` varchar(50) DEFAULT NULL,
  `DateCreated` date DEFAULT NULL,
  `DateUpdated` date DEFAULT NULL,
  `CreatedBy` varchar(50) DEFAULT NULL,
  `UpdatedBy` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbladmins`
--

INSERT INTO `tbladmins` (`Id`, `Name`, `ContactNo`, `Address`, `Email`, `Password`, `IsActive`, `DateCreated`, `DateUpdated`, `CreatedBy`, `UpdatedBy`) VALUES
(1, 'Hetul', 2342342453, 'LMS', 'admin@lms.com', 'admin', 'true', '2015-05-05', NULL, 'System', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblclientpackagehistory`
--

CREATE TABLE IF NOT EXISTS `tblclientpackagehistory` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ClientId` int(11) NOT NULL,
  `Package` int(11) NOT NULL DEFAULT '0',
  `Paid` int(11) NOT NULL DEFAULT '0',
  `TotalLeads` int(11) NOT NULL DEFAULT '0',
  `AssignedLeads` int(11) NOT NULL DEFAULT '0',
  `Package_From` date DEFAULT NULL,
  `Pacakge_To` date DEFAULT NULL,
  `CreatedBy` varchar(50) DEFAULT NULL,
  `UpdatedBy` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tblclientpayment`
--

CREATE TABLE IF NOT EXISTS `tblclientpayment` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ClientId` int(11) DEFAULT NULL,
  `Paid` int(50) DEFAULT NULL,
  `Payment_Date` date DEFAULT NULL,
  `Created_By` int(11) DEFAULT NULL,
  `Old` varchar(50) NOT NULL DEFAULT 'false',
  `Package_From` date DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tblclients`
--

CREATE TABLE IF NOT EXISTS `tblclients` (
  `Id` int(50) NOT NULL AUTO_INCREMENT,
  `ClientID` varchar(50) DEFAULT NULL,
  `ClientName` varchar(50) DEFAULT NULL,
  `ClientCompany` varchar(100) NOT NULL,
  `DealerType` varchar(50) DEFAULT NULL,
  `ClientContact` bigint(50) DEFAULT NULL,
  `ClientAddress` varchar(500) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Package` varchar(50) DEFAULT NULL,
  `Paid` int(50) DEFAULT NULL,
  `TotalLeads` int(50) DEFAULT NULL,
  `AssignedLeads` int(50) DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `IsActive` varchar(50) DEFAULT NULL,
  `DateCreated` date DEFAULT NULL,
  `DateUpdated` date DEFAULT NULL,
  `CreatedBy` varchar(50) DEFAULT NULL,
  `UpdatedBy` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tblleads`
--

CREATE TABLE IF NOT EXISTS `tblleads` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ClientId` int(11) DEFAULT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Contact` bigint(20) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Address` varchar(500) DEFAULT NULL,
  `Description` varchar(500) DEFAULT NULL,
  `CreatedBy` varchar(50) DEFAULT NULL,
  `DateCreated` date DEFAULT NULL,
  `UpdatedBy` varchar(50) DEFAULT NULL,
  `DateUpdated` date DEFAULT NULL,
  `Old` varchar(50) NOT NULL DEFAULT 'false',
  `Package_From` date DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbltelecallers`
--

CREATE TABLE IF NOT EXISTS `tbltelecallers` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `TelecallerId` varchar(50) DEFAULT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `ContactNo` bigint(20) DEFAULT NULL,
  `Address` varchar(500) DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `IsActive` varchar(50) DEFAULT NULL,
  `DateCreated` date DEFAULT NULL,
  `DateUpdated` date DEFAULT NULL,
  `CreatedBy` varchar(50) DEFAULT NULL,
  `UpdatedBy` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbltelecallers`
--

INSERT INTO `tbltelecallers` (`Id`, `TelecallerId`, `Name`, `ContactNo`, `Address`, `Password`, `IsActive`, `DateCreated`, `DateUpdated`, `CreatedBy`, `UpdatedBy`) VALUES
(1, 'telecaller', 'Telecaller', 2324334343, 'LMS', '123456', 'true', '2015-05-05', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblvisitorentry`
--

CREATE TABLE IF NOT EXISTS `tblvisitorentry` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `TeleCallerID` int(11) DEFAULT NULL,
  `CompanyName` varchar(100) DEFAULT NULL,
  `VisitorName` varchar(50) DEFAULT NULL,
  `VisitorType` varchar(50) DEFAULT NULL,
  `VisitorContact` bigint(20) DEFAULT NULL,
  `VisitorAddress` varchar(500) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `EntryDate` date DEFAULT NULL,
  `Status` varchar(50) NOT NULL,
  `Transfered` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tblvisitorentry`
--

INSERT INTO `tblvisitorentry` (`Id`, `TeleCallerID`, `CompanyName`, `VisitorName`, `VisitorType`, `VisitorContact`, `VisitorAddress`, `Email`, `EntryDate`, `Status`, `Transfered`) VALUES
(1, 1, 'test', 'Bhavesh', 'Softwares', 3446567778, '201, Sai Chembar  ,Near Bus Depot, Santacruz East, Mumbai', 'support@technovaitsolutions.com', '2015-05-05', 'Pending', 'false');

-- --------------------------------------------------------

--
-- Table structure for table `tblvisitorhistory`
--

CREATE TABLE IF NOT EXISTS `tblvisitorhistory` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `VisitorId` int(11) NOT NULL,
  `TeleCallerId` int(11) NOT NULL,
  `ReminderDate` date NOT NULL,
  `VisitorRemark` varchar(500) NOT NULL,
  `viewed` varchar(50) DEFAULT NULL,
  `DateCreated` date DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tblvisitorhistory`
--

INSERT INTO `tblvisitorhistory` (`Id`, `VisitorId`, `TeleCallerId`, `ReminderDate`, `VisitorRemark`, `viewed`, `DateCreated`) VALUES
(1, 1, 1, '2015-05-05', 'Bit Busy Right now.', 'false', '2015-05-05');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
