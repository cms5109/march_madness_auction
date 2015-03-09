
create database marchmadnes;
use marchmadness;
create table bids (timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP, name TEXT, team_id INTEGER, amount REAL);
create table current_team (timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP, team_id INTEGER);

CREATE USER 'march'@'localhost' IDENTIFIED BY 'madness';
GRANT ALL PRIVILEGES ON marchmadness.* TO 'march'@'localhost';
