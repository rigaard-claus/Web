CREATE TABLE `errorlog` (
 `ErrorLogID` int(11) NOT NULL AUTO_INCREMENT,
 `Type` varchar(25) NOT NULL,
 `Message` varchar(1000) NOT NULL,
 `ErrFile` varchar(500) NOT NULL,
 `ErrorDate` datetime NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`ErrorLogID`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4