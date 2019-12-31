DROP TABLE IF EXISTS wcf1_quiz;
CREATE TABLE wcf1_quiz (
    quizID INT(10) NOT NULL auto_increment PRIMARY KEY,
    languageID INT(10) NULL,
    title VARCHAR(100) NOT NULL DEFAULT '',
    description MEDIUMTEXT,
    type ENUM('fun', 'competition') DEFAULT 'fun',
    hasImage TINYINT(1) NOT NULL DEFAULT 0,
    KEY (quizType),
    KEY (languageID),
    KEY (quizType, languageID)
);

DROP TABLE IF EXISTS wcf1_quiz_question;
CREATE TABLE wcf1_quiz_question (
    questionID INT(10) NOT NULL auto_increment PRIMARY KEY,
    quizID INT(10) NOT NULL,
    orderNo SMALLINT(3),
    question VARCHAR(100),
    optionA VARCHAR(100),
    optionB VARCHAR(100),
    optionC VARCHAR(100),
    optionD VARCHAR(100),
    answer ENUM('A', 'B', 'C', 'D'),
    KEY (quizID),
    KEY (orderNo)
);

DROP TABLE IF EXISTS wcf1_quiz_stage;
CREATE TABLE wcf1_quiz_stage (
    stageID INT(10) NOT NULL auto_increment PRIMARY KEY,
    quizID INT(10) NOT NULL,
    points SMALLINT(10) NOT NULL DEFAULT 0,
    title VARCHAR(100),
    description MEDIUMTEXT,
    KEY (quizID)
);

-- foreign keys
ALTER TABLE wcf1_quiz ADD FOREIGN KEY (languageID) REFERENCES wcf1_language (languageID) ON DELETE SET NULL;
ALTER TABLE wcf1_quiz_question ADD FOREIGN KEY (quizID) REFERENCES wcf1_quiz (quizID) ON DELETE CASCADE;
ALTER TABLE wcf1_quiz_stage ADD FOREIGN KEY (quizID) REFERENCES wcf1_quiz (quizID) ON DELETE CASCADE;