CREATE DATABASE IF NOT EXISTS terrawatch
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE terrawatch;

CREATE TABLE IF NOT EXISTS incidents (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(180) NOT NULL,
    hazard_type ENUM('Earthquake','Flood','Wildfire','Cyclone','Volcanic Activity','Landslide','Extreme Weather','Other') NOT NULL DEFAULT 'Other',
    severity ENUM('Low','Moderate','High','Critical') NOT NULL DEFAULT 'Moderate',
    status ENUM('Monitoring','Active','Contained','Resolved','Unverified') NOT NULL DEFAULT 'Monitoring',
    location VARCHAR(180) NOT NULL,
    region VARCHAR(120) DEFAULT NULL,
    latitude DECIMAL(10,7) DEFAULT NULL,
    longitude DECIMAL(10,7) DEFAULT NULL,
    magnitude DECIMAL(5,2) DEFAULT NULL,
    occurred_at DATETIME NOT NULL,
    summary TEXT NOT NULL,
    source_name VARCHAR(180) DEFAULT 'TerraWatch demo dataset',
    source_url VARCHAR(500) DEFAULT NULL,
    is_demo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_hazard_type (hazard_type),
    KEY idx_severity (severity),
    KEY idx_status (status),
    KEY idx_occurred_at (occurred_at),
    FULLTEXT KEY ft_incident_search (title, location, region, summary)
) ENGINE=InnoDB;

INSERT INTO incidents
(title, hazard_type, severity, status, location, region, latitude, longitude, magnitude, occurred_at, summary, source_name, is_demo)
VALUES
('Offshore seismic event — North Pacific', 'Earthquake', 'High', 'Monitoring', 'North Pacific Ocean', 'Pacific Rim', 38.2200000, 142.3600000, 6.1, DATE_SUB(NOW(), INTERVAL 4 HOUR), 'Demonstration record representing an offshore seismic event. Confirm any real event with an authoritative seismic agency.', 'TerraWatch demo dataset', 1),
('River level watch — Eastern basin', 'Flood', 'High', 'Active', 'Eastern River Basin', 'South Asia', 25.5941000, 85.1376000, NULL, DATE_SUB(NOW(), INTERVAL 9 HOUR), 'Sample flood-monitoring entry. No real-time river gauge data is connected in this starter.', 'TerraWatch demo dataset', 1),
('Vegetation fire watch — Western range', 'Wildfire', 'Moderate', 'Monitoring', 'Western Range', 'North America', 39.7392000, -104.9903000, NULL, DATE_SUB(NOW(), INTERVAL 1 DAY), 'Sample wildfire record for demonstrating hazard filters and incident details.', 'TerraWatch demo dataset', 1),
('Tropical system tracking example', 'Cyclone', 'Critical', 'Active', 'Open Waters', 'Western Pacific', 16.2000000, 132.5000000, NULL, DATE_SUB(NOW(), INTERVAL 7 HOUR), 'Illustrative cyclone entry only. It is not an official forecast or warning.', 'TerraWatch demo dataset', 1),
('Volcanic observatory watch example', 'Volcanic Activity', 'Moderate', 'Monitoring', 'Island Arc', 'Southeast Asia', -7.5400000, 110.4400000, NULL, DATE_SUB(NOW(), INTERVAL 2 DAY), 'Sample volcanic activity entry. Consult the relevant geological agency for verified conditions.', 'TerraWatch demo dataset', 1),
('Slope instability report example', 'Landslide', 'Low', 'Unverified', 'Highland Road Corridor', 'South Asia', 27.7172000, 85.3240000, NULL, DATE_SUB(NOW(), INTERVAL 3 DAY), 'Illustrative community report awaiting verification. This is not a confirmed incident.', 'TerraWatch demo dataset', 1),
('Severe weather watch example', 'Extreme Weather', 'High', 'Monitoring', 'Central Plains', 'Europe', 50.1109000, 8.6821000, NULL, DATE_SUB(NOW(), INTERVAL 12 HOUR), 'Sample severe-weather record used to demonstrate the monitoring interface.', 'TerraWatch demo dataset', 1),
('After-action record — coastal flooding', 'Flood', 'Low', 'Resolved', 'Coastal District', 'South Asia', 19.0760000, 72.8777000, NULL, DATE_SUB(NOW(), INTERVAL 8 DAY), 'Example resolved record for status filtering and historical incident display.', 'TerraWatch demo dataset', 1);
