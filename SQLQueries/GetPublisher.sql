DELIMITER //
DROP PROCEDURE IF EXISTS GetPublisher //
CREATE PROCEDURE GetPublisher()
BEGIN

	SELECT 	p.id AS ID,
			p.name AS `Value`
	FROM publisher p
	ORDER BY p.name;

END //
DELIMITER ;