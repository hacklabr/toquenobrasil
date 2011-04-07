DROP TABLE IF EXISTS `wp_wpeb_positions`;
CREATE TABLE IF NOT EXISTS `wp_wpeb_positions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `item_order` int(11) DEFAULT NULL,
  `exibir_em` text,
  `tipo_rotacao` varchar(255) DEFAULT NULL,
  `tempo_rotacao` varchar(255) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

INSERT INTO `wp_wpeb_positions` (`ID`, `nome`, `item_order`, `exibir_em`, `tipo_rotacao`, `tempo_rotacao`) VALUES
(3, 'interna superior', 2, 'a:9:{s:4:"blog";s:2:"on";s:8:"universo";s:2:"on";s:14:"artista_single";s:2:"on";s:12:"artista_list";s:2:"on";s:15:"produtor_single";s:2:"on";s:13:"produtor_list";s:2:"on";s:13:"evento_single";s:2:"on";s:11:"evento_list";s:2:"on";s:6:"outras";s:2:"on";}', 'tempo', '0'),
(2, 'top', 1, 'a:10:{s:4:"home";s:2:"on";s:4:"blog";s:2:"on";s:8:"universo";s:2:"on";s:14:"artista_single";s:2:"on";s:12:"artista_list";s:2:"on";s:15:"produtor_single";s:2:"on";s:13:"produtor_list";s:2:"on";s:13:"evento_single";s:2:"on";s:11:"evento_list";s:2:"on";s:6:"outras";s:2:"on";}', 'tempo', '1'),
(4, 'interna inferior', 4, 'a:9:{s:4:"blog";s:2:"on";s:8:"universo";s:2:"on";s:14:"artista_single";s:2:"on";s:12:"artista_list";s:2:"on";s:15:"produtor_single";s:2:"on";s:13:"produtor_list";s:2:"on";s:13:"evento_single";s:2:"on";s:11:"evento_list";s:2:"on";s:6:"outras";s:2:"on";}', 'tempo', '0'),
(5, 'home superior', 5, 'a:1:{s:4:"home";s:2:"on";}', NULL, '0'),
(6, 'home inferior', 6, 'a:1:{s:4:"home";s:2:"on";}', NULL, '0'),
(7, 'banners perfil', 7, 'a:2:{s:14:"artista_single";s:2:"on";s:15:"produtor_single";s:2:"on";}', NULL, '0'),
(8, 'interna meio', 3, 'a:9:{s:4:"blog";s:2:"on";s:8:"universo";s:2:"on";s:14:"artista_single";s:2:"on";s:12:"artista_list";s:2:"on";s:15:"produtor_single";s:2:"on";s:13:"produtor_list";s:2:"on";s:13:"evento_single";s:2:"on";s:11:"evento_list";s:2:"on";s:6:"outras";s:2:"on";}', 'carregamento', '');
