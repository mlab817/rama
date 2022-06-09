ALTER TABLE trips DROP COLUMN route_code;

ALTER TABLE
    trips
ADD COLUMN start_station_id int AFTER start_time,
ADD COLUMN end_station_id int AFTER end_time;
