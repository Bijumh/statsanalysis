DELIMITER //
DROP PROCEDURE IF EXISTS PerformanceByDay //
CREATE PROCEDURE PerformanceByDay(
	IN startDate DATE,
    IN endDate DATE,
	IN countryISO VARCHAR(2),
	IN publisherID INT
)
BEGIN

	CREATE TEMPORARY TABLE tmp (
		SELECT 	s.day AS Day,
				ROUND(SUM(s.conversions)/SUM(s.impressions) * 100,2) AS Conversion_Rate,
				p.name
		FROM stats s
		INNER JOIN platform p ON s.platform_id = p.id AND p.id <> 4
		WHERE	s.day >= startDate 
				AND s.day <= endDate
				AND CASE WHEN countryISO = '' THEN '' ELSE s.country_iso END = countryISO
				AND CASE WHEN publisherID = 0 THEN 0 ELSE s.publisher_id END = publisherID
		GROUP BY s.day,p.name	
	);

	SELECT	t.Day,
			SUM(CASE WHEN name = "Android" THEN Conversion_Rate END) AS Android,
			SUM(CASE WHEN name = "iPad" THEN Conversion_Rate END) AS iPad,
			SUM(CASE WHEN name = "iPhone" THEN Conversion_Rate END) AS iPhone
	FROM tmp t
	GROUP BY t.Day
	ORDER BY t.day;
    
END //
DELIMITER ;