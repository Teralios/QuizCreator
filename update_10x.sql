-- category table
DROP TABLE IF EXISTS wcf1_quiz_category;
CREATE TABLE wcf1_quiz_category (
    categoryID INT(10) NOT NULL auto_increment PRIMARY KEY,
    position SMALLINT(3) NOT NULL default 0,
    name VARCHAR(191) NOT NULL DEFAULT ''
);

-- drop old indices.
ALTER TABLE wcf1_quiz DROP INDEX isActive;
ALTER TABLE wcf1_quiz DROP INDEX isActive_2;
ALTER TABLE wcf1_quiz DROP INDEX isActive_3;
ALTER TABLE wcf1_quiz DROP INDEX isActive_4;
ALTER TABLE wcf1_quiz DROP INDEX title;
ALTER TABLE wcf1_quiz DROP INDEX languageID;
ALTER TABLE wcf1_quiz DROP INDEX type;
ALTER TABLE wcf1_quiz DROP INDEX played;
ALTER TABLE wcf1_quiz DROP INDEX creationDate;

-- drop old fields
ALTER TABLE wcf1_quiz DROP COLUMN type;

-- new indices
-- category
ALTER TABLE wcf1_quiz_category ADD INDEX sort_position(position);

-- quiz
ALTER TABLE wcf1_quiz ADD INDEX sort_time(creationDate);
ALTER TABLE wcf1_quiz ADD INDEX stats_played(played);
ALTER TABLE wcf1_quiz ADD INDEX category(categoryID);
ALTER TABLE wcf1_quiz ADD INDEX quizListView_1(isActive, categoryID);
ALTER TABLE wcf1_quiz ADD INDEX quizListView_2(isActive, languageID);
ALTER TABLE wcf1_quiz ADD INDEX quizListView_3(isActive, categoryID, languageID);

-- user table new columns
ALTER TABLE wcf1_user ADD COLUMN quizMin50 SMALLINT(5) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD COLUMN quizMin75 SMALLINT(5) NOT NULL DEFAULT 0;


