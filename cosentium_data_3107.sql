INSERT INTO `status` (`id`, `sid`, `name`) VALUES
(1, 0, 'new'),
(2, 1, 'unlocked'),
(3, 2, 'locked_pass'),
(4, 3, 'locked_security'),
(5, 4, 'locked_forgot');

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Sys Admin'),
(2, 'Legal'),
(3, 'Deal Owner'),
(4, 'Executive'),
(5, 'Doc Admin'),
(6, 'Sales Mgr');


INSERT INTO `security_questions` (`id`, `question`) VALUES
(1, '--No Selection--'),
(2, 'What city were you born in?'),
(3, 'What was the name of your first pet?'),
(4, 'What is your father\'s middle name?'),
(5, 'What is the name of the first school you attended?');
