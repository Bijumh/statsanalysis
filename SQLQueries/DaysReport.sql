DELIMITER //
DROP PROCEDURE IF EXISTS DaysReport //
CREATE PROCEDURE DaysReport(
	IN startDate DATE,
    IN endDate DATE
)
BEGIN

	SELECT 	s.day AS Day,
			SUM(s.impressions) AS Impressions,
			SUM(s.conversions) AS Conversions,
			CONCAT(ROUND(SUM(s.conversions)/SUM(s.impressions) * 100,2),'%') AS Conversion_Rate
    FROM stats s
    WHERE s.day >= startDate AND s.day <= endDate
    GROUP BY s.day
	ORDER BY s.day;
    
END //
DELIMITER ;
