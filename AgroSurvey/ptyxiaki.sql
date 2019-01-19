

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;



CREATE TABLE IF NOT EXISTS `choices` (
`choice_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `choice_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `imagechoices` (
`image_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `image_name` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `image_path` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `questions` (
`question_id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `question_type` int(11) NOT NULL,
  `question_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `responses` (
`response_id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `ip_address` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `surveys` (
`survey_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `hash` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `useranswers` (
`userAnswers_id` int(11) NOT NULL,
  `response_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `users` (
`user_id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;



INSERT INTO `users` (`user_id`, `username`, `password`, `name`, `surname`) VALUES
(1, 'user', 'user', 'Joey', 'Brown'),
(2, 'user1', 'user1', 'John', 'Papadopoulos'),
(3, 'user2', 'user2', 'Κώστας', 'Νικολάου');


ALTER TABLE `choices`
 ADD PRIMARY KEY (`choice_id`), ADD KEY `question_id` (`question_id`);


ALTER TABLE `imagechoices`
 ADD PRIMARY KEY (`image_id`), ADD KEY `question_id` (`question_id`);


ALTER TABLE `questions`
 ADD PRIMARY KEY (`question_id`), ADD KEY `survey_id` (`survey_id`);


ALTER TABLE `responses`
 ADD PRIMARY KEY (`response_id`), ADD KEY `survey_id` (`survey_id`);


ALTER TABLE `surveys`
 ADD PRIMARY KEY (`survey_id`), ADD KEY `userId` (`user_id`);


ALTER TABLE `useranswers`
 ADD PRIMARY KEY (`userAnswers_id`), ADD KEY `response_id` (`response_id`), ADD KEY `question_id` (`question_id`);


ALTER TABLE `users`
 ADD PRIMARY KEY (`user_id`);


ALTER TABLE `choices`
MODIFY `choice_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `imagechoices`
MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `questions`
MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `responses`
MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `surveys`
MODIFY `survey_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `useranswers`
MODIFY `userAnswers_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;

ALTER TABLE `choices`
ADD CONSTRAINT `choices_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `imagechoices`
ADD CONSTRAINT `imagechoices_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `questions`
ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`survey_id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `responses`
ADD CONSTRAINT `responses_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`survey_id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `surveys`
ADD CONSTRAINT `surveys_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `useranswers`
ADD CONSTRAINT `useranswers_ibfk_1` FOREIGN KEY (`response_id`) REFERENCES `responses` (`response_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `useranswers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
