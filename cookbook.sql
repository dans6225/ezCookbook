-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 08, 2017 at 03:05 AM
-- Server version: 10.0.27-MariaDB
-- PHP Version: 7.0.7

--
-- Database: `cookbook`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `categories_id` int(11) UNSIGNED NOT NULL,
  `categories_parent` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `categories_name` varchar(255) NOT NULL,
  `categories_image` varchar(255) NOT NULL,
  `categories_description` text NOT NULL,
  `categories_keywords` text NOT NULL,
  `last_mod` int(11) UNSIGNED NOT NULL
) ENGINE=Aria DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categories_id`, `categories_parent`, `categories_name`, `categories_image`, `categories_description`, `categories_keywords`, `last_mod`) VALUES
(1, 0, 'None Assigned', '', 'Un-Categorized recipes.', '', 1476661076),
(2, 0, 'Appetizers', '', 'Appetizers', 'Cheese Sticks', 1476672983),
(3, 0, 'Desserts', '', 'Desserts', 'Chocolate, Cookies, Cakes, Tortes, Pies', 1476674316),
(4, 0, 'Meats', '', 'Meats', 'Beef, Hamburger, Pork, Bacon, Ham, Poultry, Chicken, Turkey, Fish, Roast, Hot Dogs', 1476671315);

-- --------------------------------------------------------

--
-- Table structure for table `cooking_info`
--

DROP TABLE IF EXISTS `cooking_info`;
CREATE TABLE `cooking_info` (
  `cooking_info_id` int(11) NOT NULL,
  `cooking_info_name` varchar(255) DEFAULT NULL,
  `cooking_info_notes` text,
  `cooking_info` text,
  `cooking_info_image` varchar(255) DEFAULT NULL,
  `last_mod` int(11) DEFAULT NULL,
  `cooking_info_keywords` text
) ENGINE=Aria DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cooking_info`
--

INSERT INTO `cooking_info` (`cooking_info_id`, `cooking_info_name`, `cooking_info_notes`, `cooking_info`, `cooking_info_image`, `last_mod`, `cooking_info_keywords`) VALUES
(1, 'Test Info', 'Test info notes', 'Test info goes here now.', NULL, 1486936204, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

DROP TABLE IF EXISTS `recipes`;
CREATE TABLE `recipes` (
  `recipes_id` int(11) UNSIGNED NOT NULL,
  `recipes_name` varchar(255) NOT NULL,
  `recipes_images` text NOT NULL,
  `last_mod` int(11) UNSIGNED NOT NULL,
  `favorite` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `ingredients_left` text NOT NULL,
  `ingredients_right` text NOT NULL,
  `directions` text NOT NULL,
  `notes` text NOT NULL
) ENGINE=Aria DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`recipes_id`, `recipes_name`, `recipes_images`, `last_mod`, `favorite`, `ingredients_left`, `ingredients_right`, `directions`, `notes`) VALUES
(1, 'Dev 1', 'category_action.gif', 1476674345, 0, '1 cup This\r\n2 Tbsp. That', '1 Tsp. The Other Thing\r\n1 lb What ever you have', 'Mix it all up and let it cook till done.\r\nServe promptly.', 'Might not kill you.'),
(2, 'Dev 2', 'category_action.gif', 1476674066, 1, '1 cup Fridge Experiment 1\r\n2 Tbsp. From that pile of stuff that has collected in the back corner of the corner cupboard.', '2/3 Pint Anything liquid\r\n1/13 lb Flour if you have that much.', 'Put Fridge experiment in the oven for a while then saute the whole works together for as long as you can stand the smell.\r\nLet stand uncovered for 2-3 hours at room temperature (or until completely covered with hoplessly trapped flies) then serve.', 'More likely to kill you than not. Probably.');

-- --------------------------------------------------------

--
-- Table structure for table `recipes_categories`
--

DROP TABLE IF EXISTS `recipes_categories`;
CREATE TABLE `recipes_categories` (
  `recipes_id` int(11) UNSIGNED NOT NULL,
  `categories_id` int(11) UNSIGNED NOT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL
) ENGINE=Aria DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recipes_categories`
--

INSERT INTO `recipes_categories` (`recipes_id`, `categories_id`, `sort_order`) VALUES
(1, 3, 1),
(2, 4, 2);

-- --------------------------------------------------------


--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categories_id`),
  ADD KEY `idx_categories_parent` (`categories_parent`) USING BTREE;
ALTER TABLE `categories` ADD FULLTEXT KEY `fulltext_categories_name` (`categories_name`,`categories_description`,`categories_keywords`);

--
-- Indexes for table `cooking_info`
--
ALTER TABLE `cooking_info`
  ADD PRIMARY KEY (`cooking_info_id`);
ALTER TABLE `cooking_info` ADD FULLTEXT KEY `fulltext_cookbook_info` (`cooking_info_name`,`cooking_info_notes`,`cooking_info`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`recipes_id`),
  ADD KEY `idx_favorite` (`favorite`) USING BTREE;
ALTER TABLE `recipes` ADD FULLTEXT KEY `fulltext_recipes_info` (`recipes_name`,`ingredients_left`,`ingredients_right`,`directions`,`notes`);

--
-- Indexes for table `recipes_categories`
--
ALTER TABLE `recipes_categories`
  ADD PRIMARY KEY (`recipes_id`,`categories_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categories_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `cooking_info`
--
ALTER TABLE `cooking_info`
  MODIFY `cooking_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `recipes_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

