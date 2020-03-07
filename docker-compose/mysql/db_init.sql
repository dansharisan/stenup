CREATE TABLE IF NOT EXISTS `stenup`;
DROP TABLE IF EXISTS `stenup_test`;
CREATE DATABASE `stenup_test`;
CREATE USER 'tester'@'db' IDENTIFIED BY '123456';
GRANT ALL PRIVILEGES ON stenup_test . * TO 'tester'@'db';
