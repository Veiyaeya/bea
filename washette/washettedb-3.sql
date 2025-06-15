-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2025 at 03:18 AM
-- Server version: 9.3.0
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `washettedb`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_transaction` ()   BEGIN
    DECLARE counter INT DEFAULT 1;
    DECLARE validCustomerID INT;
    DECLARE validUA_ID INT;

    -- Ensure we have customers and user accounts before inserting transactions
    IF (SELECT COUNT(*) FROM customer) = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No customers available';
    END IF;

    IF (SELECT COUNT(*) FROM useraccounts) = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No user accounts available';
    END IF;

    WHILE counter <= 1800 DO
        -- Get a valid CustomerID
        SELECT CustomerID INTO validCustomerID 
        FROM customer 
        ORDER BY RAND() LIMIT 1;

        -- Get a valid UA_ID
        SELECT UA_ID INTO validUA_ID 
        FROM useraccounts 
        ORDER BY RAND() LIMIT 1;

        INSERT INTO transaction (
            CustomerID, UA_ID, PaymentMethodID, StatusID, 
            TransacSubTotal, TransacDiscount, TransacTotalAmount, TransactionTimestamp
        )
        VALUES (
            validCustomerID, validUA_ID, 
            FLOOR(RAND() * 5) + 1, FLOOR(RAND() * 3) + 1, 
            ROUND(RAND() * 5000, 2), ROUND(RAND() * 500, 2), 
            ROUND(RAND() * 4500, 2), NOW()
        );

        -- Increment counter
        SET counter = counter + 1;
    END WHILE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_transaction_details` ()   BEGIN
    DECLARE counter INT DEFAULT 1;
    DECLARE validTransactionID INT;
    DECLARE validLaundryID INT;

    WHILE counter <= 1800 DO
        -- Get a valid TransactionID from the Transaction table
        SELECT TransactionID INTO validTransactionID 
        FROM Transaction 
        ORDER BY RAND() LIMIT 1;

        -- Get a valid LaundryID from the LaundryService table
        SELECT LaundryID INTO validLaundryID 
        FROM LaundryService 
        ORDER BY RAND() LIMIT 1;

        -- Insert into TransactionDetails table
        INSERT INTO TransactionDetails (
            TransactionID, LaundryID, TDQuantity
        )
        VALUES (
            validTransactionID, validLaundryID, FLOOR(RAND() * 10) + 1
        );

        -- Increment counter
        SET counter = counter + 1;
    END WHILE;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int NOT NULL,
  `CustomerFN` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CustomerLN` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CustomerPassword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `CustomerCreate_ID` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `CustomerFN`, `CustomerLN`, `CustomerPassword`, `CustomerCreate_ID`) VALUES
(1, 'Achilles Vonn', 'Rabina', '', 1),
(2, 'Prinz Noel', 'Faina', '', 1),
(3, 'Mark Matthew', 'Masalunga', '', 1),
(4, 'Beatriz Gail', 'Solis', '', 1),
(5, 'Sophia Marie', 'Ramos', '', 1),
(6, 'Jei', 'Pastrana', '', 1),
(7, 'Archie', 'Meñisis', '', 1),
(8, 'Joseph Michael', 'Aramil', '', 1),
(9, 'Jayson', 'Guia', '', 1),
(10, 'John Carlo', 'Torres', '', 1),
(100, 'Customer', 'Test', '', NULL),
(101, 'Test', 'Test2', '$2y$10$s5enKBnK1hJbJv9BrZby8.lgVWzbBQZMVDW08qyQe1kX993tLLJzq', NULL),
(102, 'Test', 'Test3', '$2y$10$RKuX3mkCRgWEeeTjEaw3K.rvsELlXvvm7Id3SEBP5nOjcITaZ3fci', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `laundryservice`
--

CREATE TABLE `laundryservice` (
  `LaundryID` int NOT NULL,
  `LaundryService_Type` int NOT NULL,
  `LaundryService_Name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `LaundryService_Desc` text COLLATE utf8mb4_general_ci,
  `StatusID` int NOT NULL DEFAULT '1',
  `Created_At` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated_At` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laundryservice`
--

INSERT INTO `laundryservice` (`LaundryID`, `LaundryService_Type`, `LaundryService_Name`, `LaundryService_Desc`, `StatusID`, `Created_At`, `Updated_At`) VALUES
(1, 1, 'Regular', '35 MINS WASH | 40 MINS DRY MAX OF 7 KGS/LOAD', 1, '2025-06-08 16:20:39', '2025-06-08 16:20:39'),
(2, 1, 'Premium', '45 MINS WASH | 50 MINS DRY MAX OF 8 KGS/LOAD', 1, '2025-06-08 16:20:39', '2025-06-08 16:20:39'),
(3, 1, 'Specialized Wash', '35 MINS WASH | 50 MINS DRY LINENS, PILLOW CASES, BLANKETS (MAX OF 4KGS), TOWELS (MAX OF 5 KGS)', 1, '2025-06-08 16:20:39', '2025-06-08 16:20:39'),
(4, 1, 'Comforters', '35 MINS WASH | 50 MINS DRY 1 PIECE ONLY, MAX QUEEN SIZE MEDIUM THICKNESS ONLY', 1, '2025-06-08 16:20:39', '2025-06-08 16:20:39'),
(5, 2, 'Regular', '35 MINS WASH | 1 WASH & 2 RINSE MAX OF 7 KGS/LOAD', 1, '2025-06-08 16:27:11', '2025-06-08 16:27:11'),
(6, 2, 'Premium', '45 MINS WASH | 1 WASH & 3 RINSE MAX OF 8 KGS/LOAD', 1, '2025-06-08 16:27:11', '2025-06-08 16:27:11'),
(7, 2, 'Regular Dry', '40 MINS DRY | MAX OF 7 KGS/ LOAD', 1, '2025-06-08 16:27:11', '2025-06-08 16:27:11'),
(8, 2, 'Heavy Dry', '50 MINS DRY | MAX OF 8 KGS/ LOAD', 1, '2025-06-08 16:27:11', '2025-06-08 16:27:11'),
(9, 2, 'Add Dry', '10 MINS ADDITIONAL DRY ONLY', 1, '2025-06-08 16:27:11', '2025-06-08 16:27:11'),
(10, 2, 'Fold', 'PER LOAD | MAX OF 8 KGS/ LOAD', 1, '2025-06-08 16:27:11', '2025-06-08 16:27:11');

-- --------------------------------------------------------

--
-- Table structure for table `paymentmethod`
--

CREATE TABLE `paymentmethod` (
  `PaymentMethodID` int NOT NULL,
  `PMethodName` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paymentmethod`
--

INSERT INTO `paymentmethod` (`PaymentMethodID`, `PMethodName`) VALUES
(1, 'Cash'),
(2, 'GCash'),
(3, 'E-Wallet'),
(4, 'Bank Transfer');

-- --------------------------------------------------------

--
-- Table structure for table `pricechangelog`
--

CREATE TABLE `pricechangelog` (
  `PriceChangeID` int NOT NULL,
  `LaundryID` int DEFAULT NULL,
  `UA_ID` int DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `Effective_From` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Effective_To` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pricechangelog`
--

INSERT INTO `pricechangelog` (`PriceChangeID`, `LaundryID`, `UA_ID`, `Price`, `Effective_From`, `Effective_To`) VALUES
(1, 1, 1, 180.00, '2025-06-08 08:35:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `StatusID` int NOT NULL,
  `StatusName` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`StatusID`, `StatusName`) VALUES
(1, 'In Progress'),
(2, 'Completed'),
(3, 'Cancelled'),
(6, 'Available'),
(7, 'Unavailable');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `TransactionID` int NOT NULL,
  `CustomerID` int DEFAULT NULL,
  `UA_ID` int DEFAULT NULL,
  `PaymentMethodID` int DEFAULT NULL,
  `StatusID` int DEFAULT NULL,
  `TransacSubTotal` decimal(10,2) DEFAULT NULL,
  `TransacDiscount` decimal(10,2) DEFAULT NULL,
  `TransacTotalAmount` decimal(10,2) DEFAULT NULL,
  `TransactionTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`TransactionID`, `CustomerID`, `UA_ID`, `PaymentMethodID`, `StatusID`, `TransacSubTotal`, `TransacDiscount`, `TransacTotalAmount`, `TransactionTimestamp`) VALUES
(1, 1, 2, 1, 1, 180.00, 0.00, 180.00, '2025-06-08 08:44:54'),
(1804, 102, 2, 1, 1, 300.00, 20.00, 280.00, '2025-06-08 15:37:57'),
(1805, 102, 2, 2, 2, 450.00, 0.00, 450.00, '2025-06-06 15:37:57'),
(1806, 102, 2, 3, 3, 180.00, 10.00, 170.00, '2025-06-03 15:37:57');

-- --------------------------------------------------------

--
-- Table structure for table `transactiondetails`
--

CREATE TABLE `transactiondetails` (
  `TransactionDetailsID` int NOT NULL,
  `TransactionID` int DEFAULT NULL,
  `LaundryID` int DEFAULT NULL,
  `TDQuantity` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactiondetails`
--

INSERT INTO `transactiondetails` (`TransactionDetailsID`, `TransactionID`, `LaundryID`, `TDQuantity`) VALUES
(1, 1, 1, 2),
(2, 1, 6, 1),
(1807, 1804, 1, 2),
(1808, 1804, 6, 1),
(1809, 1805, 2, 1),
(1810, 1805, 4, 1),
(1811, 1806, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `useraccounts`
--

CREATE TABLE `useraccounts` (
  `UA_ID` int NOT NULL,
  `UAUsername` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `UAFirstName` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `UALastName` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `UAPassword` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `UARole` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `useraccounts`
--

INSERT INTO `useraccounts` (`UA_ID`, `UAUsername`, `UAFirstName`, `UALastName`, `UAPassword`, `UARole`) VALUES
(1, 'Owner1', 'Admin', 'Owner', 'password', 'Admin'),
(2, 'Employee1', 'Admin', 'Employee', 'password', 'Employee'),
(7, NULL, 'Employee2', 'Test', '$2y$10$ZZAfB.LRuGdlE9QPsfAC/umHYI/w9V/gnbxwZTPJ9tmLy0u5mBO56', 'Employee'),
(8, NULL, 'Employee3', 'Test', '$2y$10$uJPrOdQbdyMMcNG3tR7Gju/Av8wKZGs5VQws33/vSkYnt238LprXe', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`),
  ADD KEY `CustomerCreate_ID` (`CustomerCreate_ID`);

--
-- Indexes for table `laundryservice`
--
ALTER TABLE `laundryservice`
  ADD PRIMARY KEY (`LaundryID`),
  ADD KEY `StatusID` (`StatusID`);

--
-- Indexes for table `paymentmethod`
--
ALTER TABLE `paymentmethod`
  ADD PRIMARY KEY (`PaymentMethodID`);

--
-- Indexes for table `pricechangelog`
--
ALTER TABLE `pricechangelog`
  ADD PRIMARY KEY (`PriceChangeID`),
  ADD KEY `LaundryID` (`LaundryID`),
  ADD KEY `UA_ID` (`UA_ID`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`StatusID`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`TransactionID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `UA_ID` (`UA_ID`),
  ADD KEY `PaymentMethodID` (`PaymentMethodID`),
  ADD KEY `StatusID` (`StatusID`);

--
-- Indexes for table `transactiondetails`
--
ALTER TABLE `transactiondetails`
  ADD PRIMARY KEY (`TransactionDetailsID`),
  ADD KEY `TransactionID` (`TransactionID`),
  ADD KEY `LaundryID` (`LaundryID`);

--
-- Indexes for table `useraccounts`
--
ALTER TABLE `useraccounts`
  ADD PRIMARY KEY (`UA_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `laundryservice`
--
ALTER TABLE `laundryservice`
  MODIFY `LaundryID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT for table `paymentmethod`
--
ALTER TABLE `paymentmethod`
  MODIFY `PaymentMethodID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pricechangelog`
--
ALTER TABLE `pricechangelog`
  MODIFY `PriceChangeID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `StatusID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `TransactionID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1807;

--
-- AUTO_INCREMENT for table `transactiondetails`
--
ALTER TABLE `transactiondetails`
  MODIFY `TransactionDetailsID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1812;

--
-- AUTO_INCREMENT for table `useraccounts`
--
ALTER TABLE `useraccounts`
  MODIFY `UA_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`CustomerCreate_ID`) REFERENCES `useraccounts` (`UA_ID`);

--
-- Constraints for table `laundryservice`
--
ALTER TABLE `laundryservice`
  ADD CONSTRAINT `laundryservice_ibfk_1` FOREIGN KEY (`StatusID`) REFERENCES `status` (`StatusID`);

--
-- Constraints for table `pricechangelog`
--
ALTER TABLE `pricechangelog`
  ADD CONSTRAINT `pricechangelog_ibfk_1` FOREIGN KEY (`LaundryID`) REFERENCES `laundryservice` (`LaundryID`),
  ADD CONSTRAINT `pricechangelog_ibfk_2` FOREIGN KEY (`UA_ID`) REFERENCES `useraccounts` (`UA_ID`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`),
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`UA_ID`) REFERENCES `useraccounts` (`UA_ID`),
  ADD CONSTRAINT `transaction_ibfk_3` FOREIGN KEY (`PaymentMethodID`) REFERENCES `paymentmethod` (`PaymentMethodID`),
  ADD CONSTRAINT `transaction_ibfk_4` FOREIGN KEY (`StatusID`) REFERENCES `status` (`StatusID`);

--
-- Constraints for table `transactiondetails`
--
ALTER TABLE `transactiondetails`
  ADD CONSTRAINT `transactiondetails_ibfk_1` FOREIGN KEY (`TransactionID`) REFERENCES `transaction` (`TransactionID`),
  ADD CONSTRAINT `transactiondetails_ibfk_2` FOREIGN KEY (`LaundryID`) REFERENCES `laundryservice` (`LaundryID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
