
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET TIME_zone = "+00:00";

-- Create the Database: `airline`
DROP DATABASE IF EXISTS airline ;
CREATE DATABASE IF NOT EXISTS airline ; 
USE airline ; 

###########################################################################################################################
-- Table definition 

-- Table structure for table `employee'
DROP TABLE IF EXISTS `employee`;
CREATE TABLE IF NOT EXISTS `employee` (
  `ssn` VARCHAR(45) NOT NULL,
  `first_name` VARCHAR(45) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `address` VARCHAR(45) NOT NULL,
  `salary` INT(45) NOT NULL,
  PRIMARY KEY (`ssn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for table `pilot`
DROP TABLE IF EXISTS `pilot`;
CREATE TABLE IF NOT EXISTS `pilot` (
  `ssn` VARCHAR(45) NOT NULL,
  `licence` VARCHAR(45) NOT NULL,
  `number_of_flight_hours` INT(11) NOT NULL,
  PRIMARY KEY (`ssn`,`licence`),
  CONSTRAINT `fk_pilot_1`
		FOREIGN KEY (`ssn`)
        REFERENCES `employee` (`ssn`)    
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for table `flight_crew`
DROP TABLE IF EXISTS `flight_crew`;
CREATE TABLE IF NOT EXISTS `flight_crew` (
  `ssn` VARCHAR(45) NOT NULL,
  `position` VARCHAR(45) NOT NULL,
  `number_of_flight_hours` INT(11) NOT NULL,
  PRIMARY KEY (`ssn`),
  CONSTRAINT `fk_flight_crew_1`
		FOREIGN KEY (`ssn`)
        REFERENCES `employee` (`ssn`)  
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for table `client`
DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(45) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `address` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Table structure for table `aircraft`
DROP TABLE IF EXISTS `aircraft`;
CREATE TABLE IF NOT EXISTS `aircraft` (
  `registration_number` VARCHAR(45) NOT NULL,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`registration_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for table `airport`
DROP TABLE IF EXISTS `airport`;
CREATE TABLE IF NOT EXISTS `airport` (
  `code` VARCHAR(45) NOT NULL, # le code peut ne pas etre unique
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for table `flight`
DROP TABLE IF EXISTS `flight`;
CREATE TABLE IF NOT EXISTS `flight` (
  `number` VARCHAR(45) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `departure_time` TIME NOT NULL,
  `arrival_time` TIME NOT NULL,
  `aircraft_registration_number` VARCHAR(45) NOT NULL,
  `airport_code_departure` VARCHAR(45) NOT NULL,
  `airport_code_arrival` VARCHAR(45) NOT NULL, 
  PRIMARY KEY (`number`),
  CONSTRAINT `fk_flight_1`
		FOREIGN KEY (`aircraft_registration_number`)
        REFERENCES `aircraft` (`registration_number`),
  CONSTRAINT `fk_light_2`
		FOREIGN KEY (`airport_code_departure`)
        REFERENCES `airport` (`code`),
  CONSTRAINT `fk_flight_3`
		FOREIGN KEY (`airport_code_arrival`)
        REFERENCES `airport` (`code`)
 ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for table `departure`
DROP TABLE IF EXISTS `departure`;
CREATE TABLE IF NOT EXISTS `departure` (
  `flight_number` VARCHAR(45) NOT NULL,
  `date` DATE NOT NULL,
  `pilot_ssn_1` VARCHAR(45) NOT NULL,
  `pilot_ssn_2` VARCHAR(45) NOT NULL,
  `flight_crew_ssn_1` VARCHAR(45) NOT NULL,
  `flight_crew_ssn_2` VARCHAR(45) NOT NULL,
  `number_of_free_seats` INT(11) NOT NULL,
  `number_seats` INT(11) NOT NULL,
  PRIMARY KEY (`flight_number`,`date`),
  CONSTRAINT `fk_departure_1`
		FOREIGN KEY (`flight_number`)
        REFERENCES `flight` (`number`),
  CONSTRAINT `fk_departure_2`
		FOREIGN KEY (`pilot_ssn_1`)
        REFERENCES `pilot` (`ssn`),
  CONSTRAINT `fk_departure_3`
  		FOREIGN KEY (`pilot_ssn_2`) 
        REFERENCES `pilot` (`ssn`),		
  CONSTRAINT `fk_departure_4`
		FOREIGN KEY (`flight_crew_ssn_1`)
        REFERENCES `flight_crew` (`ssn`),
  CONSTRAINT `fk_departure_5`
		FOREIGN KEY (`flight_crew_ssn_2`)
        REFERENCES `flight_crew` (`ssn`)		
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for table `order`
DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_date` DATE NOT NULL,
  `price` INT(11) NOT NULL,
  `departure_flight_number` VARCHAR(45) NOT NULL,
  `departure_date` DATE NOT NULL,
  `client_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_order_1`
		FOREIGN KEY (`departure_flight_number`)
        REFERENCES `departure` (`flight_number`),
  CONSTRAINT `fk_order_2`
		FOREIGN KEY (`client_id`)
        REFERENCES `client` (`id`)
  # , CONSTRAINT `fk_order_3`
	#	FOREIGN KEY (`departure_date`)
     #   REFERENCES `departure` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;


###########################################################################################################################
-- Filling of the tables 

-- Add data for table `employee`
INSERT INTO `employee` (`ssn`, `first_name`, `last_name`, `address`, `salary`) VALUES
('111111', 'Henri', 'Ducon', '3 chemin des mouilles ', 4000),
('222222', 'Paulo', 'Ducon', '0 chemin des mouilles ', 4000),
('333333', 'Geoffroy', 'Ducon', '8 chemin des mouilles ', 4000),
('444444', 'Nico', 'Ducon', '9 chemin des mouilles ', 4000),
('555555', 'Amandine', 'Ducon', '10 chemin des mouilles ', 4000),
('666666', 'Paul', 'Ducon', '12 chemin des mouilles ', 4000),
('777777', 'Flo', 'Ducon', '2 chemin des mouilles ', 4000),
('888888', 'Bertrand', 'Ducon', '1 chemin des mouilles ', 4000),
('999999', 'Robert', 'Ducon', '11 chemin des mouilles ', 4000),
('121212', 'Bernard', 'Ducon', '13 chemin des mouilles ', 4000),
('131313', 'Arnauld', 'Ducon', '4 chemin des mouilles ', 4000),
('141414', 'Lilou', 'Ducon', '7 chemin des mouilles ', 4000),
('151515', 'Yann', 'Ducon', '6 chemin des mouilles ', 4000),
('161616', 'Sara', 'Ducon', '7 chemin des mouilles ', 4000),
('171717', 'Bernadette', 'Ducon', '5 chemin des mouilles ', 4000);

-- Add data to table `pilot`
INSERT INTO `pilot` (`ssn`, `licence`, `number_of_flight_hours`) VALUES
('111111', 'XXX1', 256),
('777777', 'XXX2', 67),
('888888', 'XXX3', 809),
('131313', 'XXX4', 8),
('171717', 'XXX5', 2000);

-- Add data for table `flight_crew`
INSERT INTO `flight_crew` (`ssn`, `position`, `number_of_flight_hours`) VALUES
('333333', 'steward', 28),
('444444', 'steward', 45),
('555555', 'stewardess', 55),
('999999', 'steward', 14),
('151515', 'steward', 69),
('161616', 'steward', 44);

-- Add data for table `client`
INSERT INTO `client` (`id`, `first_name`, `last_name`, `address`) VALUES
(01, 'Dupont', 'Gabriel', '1 avenue Guy de Collongue'),
(02, 'Dupont', 'Louis', '2 avenue Guy de Collongue'),
(03, 'Dupont', 'Raphael', '3 avenue Guy de Collongue'),
(04, 'Dupont', 'Jean', '4 avenue Guy de Collongue'),
(05, 'Durand', 'Benoit', '5 avenue Guy de Collongue'),
(06, 'Durand', 'Marie', '6 avenue Guy de Collongue'),
(07, 'Durand', 'Léa', '7 avenue Guy de Collongue'),
(08, 'Durand', 'Camille', '8 avenue Guy de Collongue'),
(09, 'Durand', 'Simon', '9 avenue Guy de Collongue'),
(10, 'Durand', 'Clémence', '10 avenue Guy de Collongue');

-- Add data for table `aircraft`
INSERT INTO `aircraft` (`registration_number`, `type`) VALUES
('3D-DFR', 'B777'),
('DF-VJT', 'A320'),
('6H-ZSX', 'A380'),
('FB-QJU', 'B777'),
('4H-KOI', 'A380'),
('DB-BSO', 'A380'),
('CG-QLF', 'B777'),
('0K-LOP', 'B777'),
('1S-LMO', 'A320');

-- add data for table `airport`
INSERT INTO `airport` (`code`, `name`) VALUES
('YUL', 'Montreal INTernational Airport'),
('CDG', 'Paris INTernational Airport'),
('BKK', 'Bangkok INTernational Airport'),
('HKG', 'Hong Kong INTernational Airport'),
('LAX', 'Los Angeles INTernational Airport'),
('LHR', 'London INTernational Airport'),
('JFK', 'New York INTernational Airport'),
('HND', 'Tokyo INTernational Airport'),
('PEK', 'Beijing INTernational Airport');


-- Add data for table `flight`
INSERT INTO `flight` (`number`, `start_date`, `end_date`, `departure_time`, `arrival_time`, `airport_code_departure`, `airport_code_arrival`, `aircraft_registration_number`) VALUES
('AAA01', '2010-01-01', '2099-12-31', '10:00:00', '17:00:00', 'CDG', 'LHR', '0K-LOP'),
('AAA02', '2008-01-01', '2099-12-31', '12:00:00', '15:00:00', 'BKK', 'LAX', 'FB-QJU'),
('AAA03', '2012-01-01', '2099-12-31', '00:00:00', '05:00:00', 'PEK', 'HKG', 'CG-QLF'),
('AAA04', '2014-01-01', '2099-12-31', '13:00:00', '15:00:00', 'JFK', 'YUL', '3D-DFR'),
('AAA05', '2009-01-01', '2099-12-31', '08:00:00', '11:00:00', 'HND', 'PEK', 'DB-BSO'),
('AAA06', '2013-01-01', '2099-12-31', '08:00:00', '16:00:00', 'LHR', 'JFK', '4H-KOI'),
('AAA07', '2015-01-01', '2099-12-31', '09:00:00', '19:00:00', 'LAX', 'HND', '6H-ZSX'),
('AAA08', '2015-01-01', '2099-12-31', '10:00:00', '13:00:00', 'HKG', 'CDG', '1S-LMO'),
('AAA09', '2011-01-01', '2099-12-31', '11:00:00', '21:00:00', 'YUL', 'BKK', 'DF-VJT');

-- Add data for table `departure
INSERT INTO `departure` (`flight_number`, `date`, `pilot_ssn_1`, `pilot_ssn_2`, `flight_crew_ssn_1`, `flight_crew_ssn_2`, `number_of_free_seats`, `number_seats`) VALUES
('AAA04', '2019-12-21', '888888', '131313', '151515', '999999', 454, 455),
('AAA05', '2019-01-01', '777777', '171717', '161616', '333333', 466, 467),
('AAA06', '2020-02-06', '111111', '888888', '333333', '444444', 489, 490),
('AAA07', '2020-03-04', '131313', '888888', '444444', '555555', 418, 420),
('AAA08', '2020-04-05', '171717', '111111', '555555', '161616', 386, 387);

-- Add data to table `order`
INSERT INTO `order` (`id`, `order_date`, `price`, `departure_flight_number`, `departure_date`, `client_id`) VALUES
(01, '2017-01-01', 300, 'AAA04', '2019-12-21', 07),
(02, '2017-02-01', 300, 'AAA05', '2019-01-01', 04),
(03, '2017-03-01', 300, 'AAA06', '2020-02-06', 10),
(04, '2017-04-01', 300, 'AAA07', '2020-03-04', 10),
(05, '2017-05-01', 300, 'AAA08', '2020-04-05', 05),
(06, '2017-06-01', 300, 'AAA07', '2020-03-04', 02);

###########################################################################################################################
-- Setting up Triggers

DELIMITER $$
$$
CREATE TRIGGER after_insert_order AFTER INSERT ON airline.order FOR EACH ROW 
BEGIN 
  DECLARE num_flight VARCHAR(10);
  DECLARE new_date VARCHAR(10);
    
  SET num_flight = NEW.departure_flight_number;
    SET new_date = NEW.departure_date; 
  Update departure Set number_of_free_seats = number_of_free_seats-1 
	WHERE  (`flight_number`, `date`) = (num_flight, new_date); 
   
END$$
DELIMITER ;

DELIMITER $$
$$
CREATE TRIGGER after_delete_order AFTER DELETE ON airline.order FOR EACH ROW 
BEGIN 
  DECLARE num_flight VARCHAR(10);
    DECLARE old_date VARCHAR(10);
    
  SET old_date = OLD.departure_date;
  SET num_flight = OLD.departure_flight_number;
  Update departure Set number_of_free_seats = number_of_free_seats+1 
	WHERE  (`flight_number`, `date`) = (num_flight, old_date); 
END$$
DELIMITER ;


##############################################################################
-- New User for clients (with limited rights)
CREATE USER IF NOT EXISTS 'user'@'localhost' IDENTIFIED BY 'user';
GRANT SELECT, INSERT ON airline.order TO 'user'@'localhost';
GRANT SELECT, INSERT ON airline.client TO 'user'@'localhost';
GRANT SELECT ON airline.departure TO 'user'@'localhost';
