CREATE TABLE `history_act` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `his_id` int(11) NOT NULL,
  `his_act` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `history_act_payoff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `his_act_id` int(11) NOT NULL,
  `his_payoff` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `history_dt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `d1_trend` tinyint(1) NOT NULL,
  `d1_wave` tinyint(1) NOT NULL,
  `h4_wave` tinyint(1) NOT NULL,
  `h4_boll` tinyint(1) NOT NULL,
  `h4_obv` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `train_act` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `train_id` int(11) NOT NULL,
  `train_act` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `train_act_payoff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `train_act_id` int(11) NOT NULL,
  `train_payoff` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `train_dt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `d1_trend` tinyint(1) NOT NULL,
  `d1_wave` tinyint(1) NOT NULL,
  `h4_wave` tinyint(1) NOT NULL,
  `h4_boll` tinyint(1) NOT NULL,
  `h4_obv` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

//ceshi