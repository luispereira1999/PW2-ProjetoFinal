-- CRIAR TABELA "vote_types"
CREATE TABLE IF NOT EXISTS `vote_types` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- CRIAR TABELA "users"
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `first_name` varchar(50) DEFAULT '',
  `last_name` varchar(50) DEFAULT '',
  `city` varchar(30) DEFAULT '',
  `country` varchar(100) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- CRIAR TABELA "posts"
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `date` datetime NOT NULL,
  `votes_amount` int(10) NOT NULL DEFAULT 0,
  `comments_amount` int(10) NOT NULL DEFAULT 0,
  `user_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_id_p` (`user_id`),
  CONSTRAINT `fk_user_id_p` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- CRIAR TABELA "comments"
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` varchar(500) NOT NULL,
  `votes_amount` int(10) NOT NULL DEFAULT 0,
  `user_id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_id_c` (`user_id`),
  KEY `fk_post_id_c` (`post_id`),
  CONSTRAINT `fk_post_id_c` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_id_c` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- CRIAR TABELA "posts_votes"
CREATE TABLE IF NOT EXISTS `posts_votes` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `vote_type_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`post_id`,`user_id`),
  KEY `fk_post_id_pv` (`post_id`),
  KEY `fk_user_id_pv` (`user_id`),
  KEY `fk_vote_type_id_pv` (`vote_type_id`),
  CONSTRAINT `fk_post_id_pv` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_id_pv` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_vote_type_id_pv` FOREIGN KEY (`vote_type_id`) REFERENCES `vote_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- CRIAR TABELA "comments_votes"
CREATE TABLE IF NOT EXISTS `comments_votes` (
  `comment_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `vote_type_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`comment_id`,`user_id`),
  KEY `fk_user_id_cv` (`user_id`),
  KEY `fk_comment_id_cv` (`comment_id`),
  KEY `fk_vote_type_id_cv` (`vote_type_id`),
  CONSTRAINT `fk_comment_id_cv` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_id_cv` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_vote_type_id_cv` FOREIGN KEY (`vote_type_id`) REFERENCES `vote_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
