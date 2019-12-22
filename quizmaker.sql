DROP TABLE IF EXISTS wcf1_quiz;
CREATE TABLE wcf1_quiz (
    quizID INT(10) NOT NULL auto_increment PRIMARY KEY,
    languageID INT(10) NULL,
    title VARCHAR(80) NOT NULL DEFAULT '',
    description MEDIUMTEXT,
    quizType ENUM('fun', 'competition') DEFAULT 'fun',
    image TINYINT(1) NOT NULL DEFAULT 0,
    KEY (quizType)
);

DROP TABLE IF EXISTS wcf1_quiz_question;
CREATE TABLE wcf1_quiz_question (
    questionID INT(10) NOT NULL auto_increment PRIMARY KEY,
    quizID INT(10) NOT NULL,
    orderNo SMALLINT(3),
    question VARCHAR(1000),
    optionA VARCHAR(1000),
    optionB VARCHAR(1000),
    optionC VARCHAR(1000),
    optionD VARCHAR(1000),
    answer ENUM('A', 'B', 'C', 'D'),
    KEY (orderNo)
);