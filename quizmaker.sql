DROP TABLE IF EXISTS wcf1_quiz;
CREATE TABLE wcf1_quiz (
    quizID INT(10) NOT NULL auto_increment PRIMARY KEY,
    languageID INT(10) NULL,
    creationDate INT(10) NOT NULL DEFAULT 0,
    type ENUM('fun', 'competition') DEFAULT 'fun',
    title VARCHAR(100) NOT NULL DEFAULT '',
    description TEXT,
    image VARCHAR(35) NOT NULL DEFAULT '',
    isActive TINYINT(1) NOT NULL DEFAULT 0,
    questions SMALLINT(3) NOT NULL DEFAULT 0,
    goals SMALLINT(3) NOT NULL DEFAULT 0,
    KEY (type),
    KEY (languageID),
    KEY (type, languageID)
);

DROP TABLE IF EXISTS wcf1_quiz_question;
CREATE TABLE wcf1_quiz_question (
    questionID INT(10) NOT NULL auto_increment PRIMARY KEY,
    quizID INT(10) NOT NULL,
    position SMALLINT(3),
    question VARCHAR(100),
    optionA VARCHAR(100),
    optionB VARCHAR(100),
    optionC VARCHAR(100),
    optionD VARCHAR(100),
    explanation TEXT,
    answer ENUM('A', 'B', 'C', 'D'),
    KEY (quizID),
    KEY (position)
);

DROP TABLE IF EXISTS wcf1_quiz_goal;
CREATE TABLE wcf1_quiz_goal (
    goalID INT(10) NOT NULL auto_increment PRIMARY KEY,
    quizID INT(10) NOT NULL,
    points SMALLINT(10) NOT NULL DEFAULT 0,
    title VARCHAR(100),
    description TEXT,
    KEY (quizID),
    KEY (quizID, points)
);

-- foreign keys
ALTER TABLE wcf1_quiz ADD FOREIGN KEY (languageID) REFERENCES wcf1_language (languageID) ON DELETE SET NULL;
ALTER TABLE wcf1_quiz_question ADD FOREIGN KEY (quizID) REFERENCES wcf1_quiz (quizID) ON DELETE CASCADE;
ALTER TABLE wcf1_quiz_goal ADD FOREIGN KEY (quizID) REFERENCES wcf1_quiz (quizID) ON DELETE CASCADE;