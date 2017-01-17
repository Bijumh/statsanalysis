DELIMITER //
DROP PROCEDURE IF EXISTS PublisherReport //
CREATE PROCEDURE PublisherReport()
BEGIN

	SELECT 	p.name AS Publisher,
			SUM(s.impressions) AS Impressions,
			SUM(s.conversions) AS Conversions,
			CONCAT(ROUND(SUM(s.conversions)/SUM(s.impressions) * 100,2),'%') AS Conversion_Rate
	FROM stats s
	INNER JOIN publisher p ON s.publisher_id = p.id
	GROUP BY p.name
	ORDER BY Impressions DESC;
    
END //
DELIMITER ;