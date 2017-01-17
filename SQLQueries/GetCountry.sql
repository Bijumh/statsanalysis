DELIMITER //
DROP PROCEDURE IF EXISTS GetCountry //
CREATE PROCEDURE GetCountry()
BEGIN

	SELECT 	c.iso AS ID,
			c.name AS `Value` 
	FROM country c
	ORDER BY c.name;

END //
DELIMITER ;