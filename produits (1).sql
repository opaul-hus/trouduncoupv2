

INSERT INTO `categories`(`id`, `nom`) VALUES
 ('1','Bâtons'),
('2','Balles'),
('3','Vêtements'),
('4','Chariots et Sacs'),
('5','Accessoires')





INSERT INTO `produits` (`id`, `categorie_id`, `nom`, `prix`, `description`, `quan_stock`, `quan_min`) VALUES
(38, 2, 'Balle Control Master', 39.99, 'Balle offrant un excellent contrôle sur les coups', 80, 8),
(36, 2, 'Balle Distance Max', 29.99, 'Balle conçue pour une distance maximale sur le parcours', 150, 15),
(37, 2, 'Balle SoftFeel', 34.99, 'Balle à compression douce pour une sensation agréable au toucher', 120, 12),
(33, 1, 'Bâton ajustable ProFlex', 349.99, 'Bâton ajustable en longueur pour sadapter à chaque golfeur', 18, 2),
(34, 1, 'Wedge spécial bunkers', 109.99, 'Wedge conçu spécialement pour les sorties de bunkers', 30, 3),
(32, 1, 'Set de bâtons complet (14 pièces)', 899.99, 'Ensemble complet pour couvrir toutes les situations sur le parcours', 10, 1),
(30, 1, 'Bâton junior en graphite', 79.99, 'Bâton adapté aux jeunes golfeurs en graphite léger', 15, 2),
(31, 1, 'Bâton pour femme RoseGolf', 249.99, 'Bâton spécialement conçu pour les golfeuses avec un design élégant', 35, 3),
(29, 1, 'Wedge Spin Master', 89.99, 'Wedge avec technologie de spin avancée pour les approches', 50, 5),
(27, 1, 'Putter Tour Pro', 129.99, 'Putter de qualité professionnelle pour les coups roulés', 40, 4),
(28, 1, 'Hybride X-Factor', 179.99, 'Hybride polyvalent pour les coups difficiles', 25, 5),
(26, 1, 'Fers en acier forgé (ensemble de 8)', 599.99, 'Ensemble de fers forgés pour une précision exceptionnelle', 20, 2),
(25, 1, 'Driver Titan', 299.99, 'Driver avec tête en titane pour des coups puissants', 30, 3),
(35, 2, 'Balle Tour Pro V1', 49.99, 'Balle haut de gamme pour des performances exceptionnelles', 100, 10),
(16, 3, 'Polo de golf', 39.99, 'Polo élégant pour le golf', 100, 10),
(17, 3, 'Short de golf', 29.99, 'Short confortable pour le golf', 80, 8),
(18, 3, 'Casquette de golf', 19.99, 'Casquette ajustable pour le soleil', 50, 5),
(19, 4, 'Chariot standard', 129.99, 'Chariot de golf standard avec support pour le sac', 50, 5),
(20, 4, 'Chariot électrique', 299.99, 'Chariot de golf électrique avec fonction de contrôle à distance', 30, 3),
(21, 4, 'Chariot pliable', 179.99, 'Chariot de golf pliable pour un rangement facile', 40, 4),
(39, 2, 'Balle colorée NeonDrive', 19.99, 'Balle colorée pour une meilleure visibilité sur le parcours', 200, 20),
(40, 3, 'Gants de golf en cuir', 24.99, 'Gants de golf en cuir pour une prise ferme', 60, 6),
(41, 4, 'Sac de golf professionnel', 199.99, 'Sac de golf avec plusieurs compartiments et poches', 20, 2),
(42, 3, 'Parapluie de golf résistant au vent', 29.99, 'Parapluie conçu pour résister aux vents forts sur le parcours', 40, 4);


