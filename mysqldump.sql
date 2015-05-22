CREATE TABLE asteriskpanel.person (
  id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  username varchar(50) DEFAULT NULL,
  age varchar(50) DEFAULT NULL,
  name varchar(50) DEFAULT NULL,
  family varchar(50) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX username (Username)
)
