-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 28, 2021 at 09:39 AM
-- Server version: 8.0.26-0ubuntu0.20.04.2
-- PHP Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_chat`
--

CREATE TABLE `activity_chat` (
  `id` int NOT NULL,
  `posted_by_id` int NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `posted_at` datetime NOT NULL,
  `topic_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_chat`
--

INSERT INTO `activity_chat` (`id`, `posted_by_id`, `content`, `posted_at`, `topic_id`) VALUES
(2, 1, '<p>so i need help on these task<br></p>', '2021-08-03 08:59:50', 2),
(3, 3, '<p>atekeleda<br></p>', '2021-08-03 09:00:42', 2),
(4, 4, '<p>yemren new eko</p>', '2021-08-03 09:00:52', 2),
(5, 1, '<p>yemren new eko</p>', '2021-08-03 09:00:52', 2),
(6, 4, '<p>seralegn eko<br></p>', '2021-08-03 09:58:03', 2),
(7, 1, '<p>abo mereregn eko</p>', '2021-08-03 10:03:21', 2),
(8, 3, '<p>sdfsdfsdf</p>', '2021-08-03 10:03:31', 2),
(10, 1, '<p>@Amsalu tasekaleh eko<br></p>', '2021-08-03 10:34:33', 2),
(11, 1, '<p>;lklk;k;l<br></p>', '2021-08-27 16:21:11', 3);

-- --------------------------------------------------------

--
-- Table structure for table `activity_files`
--

CREATE TABLE `activity_files` (
  `id` int NOT NULL,
  `activity_id` int NOT NULL,
  `uploaded_by_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_at` datetime NOT NULL,
  `is_public` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_progress`
--

CREATE TABLE `activity_progress` (
  `id` int NOT NULL,
  `activity_id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `rating` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_user`
--

CREATE TABLE `activity_user` (
  `id` int NOT NULL,
  `activity_id` int NOT NULL,
  `user_id` int NOT NULL,
  `assigned_by_id` int NOT NULL,
  `assignment_description` longtext COLLATE utf8mb4_unicode_ci,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` int NOT NULL,
  `assigned_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_verification`
--

CREATE TABLE `activity_verification` (
  `id` int NOT NULL,
  `activity_user_id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`id`, `name`, `abbreviation`) VALUES
(1, 'Birr', 'Br.'),
(2, 'US Dollars', 'USD.');

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20210723101046', '2021-07-23 13:11:01', 51617);

-- --------------------------------------------------------

--
-- Table structure for table `email_template`
--

CREATE TABLE `email_template` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_template`
--

INSERT INTO `email_template` (`id`, `name`, `code`, `description`, `is_active`, `content`) VALUES
(1, 'Assign Individual to a project', 'add_individual_to_project', 'this is used to add a person into a project', 1, '<p><b>Dear $user</b>\r\n</p><p>\r\nGreetings! You have been assigned to a project named <b>$myproject</b> with a role of <b>$role</b>. </p><p>kindly follow up your project and we will communicate with you regarding on the next step.</p><p>With Regards,</p><p><i>This email is an automated email and do not reply to this email.</i><br></p>'),
(2, 'Activity Has been assigned to you!', 'activity_assignment', 'assigning people to activity', 1, '<p>Dear $user</p><p>You have been assigned on activity named $activity with a due date of $duedate.<br></p>'),
(3, 'Project Plan has been Submitted.', 'project_plan_submitted', 'sent when a project plan is submitted.', 1, '<p>Greetings!</p><p>A plan for a project named <b>$project</b> has been submitted under your unit. Kindly review the plan and approve or disapprove the request as soon as possible.</p><p><i>This is an automated email and do not reply to this email.</i><br></p>'),
(4, 'Project Plan status Update', 'project_plan_status_update', 'sent when a project plan status is changed', 1, '<p>Greetings!</p><p>The project titled $project has been $status.<br></p>'),
(5, 'Project Implementation Started', 'project_implementation_started', 'sent when a project implementation is started', 1, '<p>Greetings!</p><p>The implementation of project named <b>$project</b> has been started. You will be receiving activities assigned to you soon.</p><p><i>This is an automated email. Do not reply to this email.</i><br></p>'),
(6, 'Project Plan Modification Request', 'plan_modification_request', 'sent when a project plan needs to be modified before being approved', 1, '<p>Greetings!</p><p>A project named <b>$project</b> has submitted a modification request due to the following reasons.</p><p>$comment</p><p><i>This is an automated email. Do not reply to this email.</i><br></p>'),
(7, 'Plan Modification Request Status Update', 'plan_modification_status_update', 'sent when a project plan modification status changes', 1, '<p>Greetings!</p><p>The plan modification request for the project named <b>$project</b> has been updated. The decision is $decision for the following reason. </p><p>$reason<br></p>');

-- --------------------------------------------------------

--
-- Table structure for table `goal`
--

CREATE TABLE `goal` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goal`
--

INSERT INTO `goal` (`id`, `name`) VALUES
(1, 'Strengthening Existing Infrastructur to unlock the Digital Economy'),
(2, 'Facilitate Digital Interaction Among Government, Citizens and Businesses'),
(3, 'Developing Enabling System'),
(4, 'Strengthen The Digital Ecosystem'),
(5, 'Develop Capacities to Implment The Digital Transformation Strategy'),
(6, 'Mobilizing Resources to Implment The Strategy');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `record_id` int DEFAULT NULL,
  `actiontime` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ipaddress` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `modified` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `objective`
--

CREATE TABLE `objective` (
  `id` int NOT NULL,
  `goal_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `objective`
--

INSERT INTO `objective` (`id`, `goal_id`, `name`) VALUES
(1, 5, 'Develop Internal Human Resource Capacities'),
(2, 5, 'Develop Working Manual and Procedures'),
(3, 3, 'Support efforts to introduce a Digital ID in Ethiopia'),
(4, 3, 'Launch Digital payment pilots in G2C portals & E-commerce to accelerate adoption'),
(5, 3, 'Developing frameworks ,roadmaps and tools  for national awareness campaigns to promote Ethiopian\'s Cbyer Security and welness'),
(6, 4, 'Operationalize access to early-stage risk capital through National Innovation Fund and other funding mechanisms'),
(7, 4, 'Develop and Implment Job-oriented digital skilling program accompanied by a job match platform'),
(8, 4, 'Design and pilot a holistic digital literacy initiative'),
(9, 4, 'Formalize  government engagement with ICT players'),
(10, 4, 'Refine and Expand incubator services by accounting for start-up needs'),
(11, 4, 'Develop and promote Legal and regulatory mechanisms to enable the Digital Economy'),
(12, 6, 'Establish Strategic Relation Ship with Development Partners and Stake holders'),
(13, 6, 'Develop Proposals and  Grant Requestes'),
(14, 2, 'E-Government - Building User-centric Portals & Transaction enabled e-Government Systems'),
(15, 2, 'E-commerce Development: Unlock high impact market opportunities'),
(16, 1, 'Speed up the Telecom Reform'),
(17, 1, 'Deregulating Mobile Phone Market'),
(18, 1, 'Upgrade and Modernization Government Backbone'),
(19, 1, 'Implement Universal Access'),
(20, 1, 'Improving e-Commerce Logistics');

-- --------------------------------------------------------

--
-- Table structure for table `organization`
--

CREATE TABLE `organization` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acronym` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organization`
--

INSERT INTO `organization` (`id`, `name`, `acronym`, `logo`) VALUES
(1, 'Ministry of Innovation and Technology', 'MinT', NULL),
(2, 'Ministry of Peace', 'MoP', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `organization_unit`
--

CREATE TABLE `organization_unit` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organization_id` int NOT NULL,
  `head_id` int DEFAULT NULL,
  `reports_to_id` int DEFAULT NULL,
  `acronym` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organization_unit`
--

INSERT INTO `organization_unit` (`id`, `name`, `organization_id`, `head_id`, `reports_to_id`, `acronym`) VALUES
(2, 'Digital Service Unit', 1, 3, NULL, 'DSU'),
(3, 'Application and Coding', 1, NULL, NULL, 'AAC'),
(4, 'Communication and Resource Mobilization', 1, NULL, NULL, 'CRM');

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `name`, `code`, `description`) VALUES
(1, 'Permission Create', 'permission_create', 'Allows user to create permissions'),
(5, 'Sponsor Create', 'sponsor_create', 'Allows users to create Sponsor'),
(6, 'Sponsor Edit', 'sponsor_edit', 'Allows users to edit Sponsor'),
(7, 'Sponsor Delete', 'sponsor_delete', 'Allows users to delete Sponsor'),
(8, 'Currency Create', 'currency_create', 'Allows users to create Currency'),
(9, 'Currency Edit', 'currency_edit', 'Allows users to edit Currency'),
(10, 'Currency Delete', 'currency_delete', 'Allows users to delete Currency'),
(11, 'Resource Type Create', 'resource_type_create', 'Allows users to create Resource Type'),
(12, 'Resource Type Edit', 'resource_type_edit', 'Allows users to edit Resource Type'),
(13, 'Resource Type Delete', 'resource_type_delete', 'Allows users to delete Resource Type'),
(17, 'Program Create', 'program_create', 'Allows users to create Program'),
(18, 'Program Edit', 'program_edit', 'Allows users to edit Program'),
(19, 'Program Delete', 'program_delete', 'Allows users to delete Program'),
(20, 'Program Show', 'program_show', 'Allows users to view Program'),
(21, 'Program List', 'program_index', 'Allows users to list Program'),
(22, 'Project Create', 'project_create', 'Allows users to create Project'),
(23, 'Project Edit', 'project_edit', 'Allows users to edit Project'),
(24, 'Project Delete', 'project_delete', 'Allows users to delete Project'),
(25, 'Project Show', 'project_show', 'Allows users to view Project'),
(26, 'Project List', 'project_index', 'Allows users to list Project'),
(27, 'Project Version Create', 'project_version_create', 'Allows users to create Project Version'),
(28, 'Project Version Edit', 'project_version_edit', 'Allows users to edit Project Version'),
(29, 'Project Version Delete', 'project_version_delete', 'Allows users to delete Project Version'),
(30, 'Project Version Show', 'project_version_show', 'Allows users to view Project Version'),
(31, 'Project Version List', 'project_version_index', 'Allows users to list Project Version'),
(32, 'Project Sponsor Create', 'project_sponsor_create', 'Allows users to create Project Sponsor'),
(33, 'Project Sponsor Edit', 'project_sponsor_edit', 'Allows users to edit Project Sponsor'),
(34, 'Project Sponsor Delete', 'project_sponsor_delete', 'Allows users to delete Project Sponsor'),
(35, 'Project Sponsor Show', 'project_sponsor_show', 'Allows users to view Project Sponsor'),
(36, 'Project Sponsor List', 'project_sponsor_index', 'Allows users to list Project Sponsor'),
(37, 'Project Resource Create', 'project_resource_create', 'Allows users to create Project Resource'),
(38, 'Project Resource Edit', 'project_resource_edit', 'Allows users to edit Project Resource'),
(39, 'Project Resource Delete', 'project_resource_delete', 'Allows users to delete Project Resource'),
(40, 'Project Resource Show', 'project_resource_show', 'Allows users to view Project Resource'),
(41, 'Project Resource List', 'project_resource_index', 'Allows users to list Project Resource'),
(47, 'Project Member Create', 'project_member_create', 'Allows users to create Project Member'),
(48, 'Project Member Edit', 'project_member_edit', 'Allows users to edit Project Member'),
(49, 'Project Member Delete', 'project_member_delete', 'Allows users to delete Project Member'),
(50, 'Project Member Show', 'project_member_show', 'Allows users to view Project Member'),
(51, 'Project Member List', 'project_member_index', 'Allows users to list Project Member'),
(52, 'Project Deliverable Create', 'project_deliverable_create', 'Allows users to create Project Deliverable'),
(53, 'Project Deliverable Edit', 'project_deliverable_edit', 'Allows users to edit Project Deliverable'),
(54, 'Project Deliverable Delete', 'project_deliverable_delete', 'Allows users to delete Project Deliverable'),
(55, 'Project Deliverable Show', 'project_deliverable_show', 'Allows users to view Project Deliverable'),
(56, 'Project Deliverable List', 'project_deliverable_index', 'Allows users to list Project Deliverable'),
(57, 'Project Milestone Create', 'project_milestone_create', 'Allows users to create Project Milestone'),
(58, 'Project Milestone Edit', 'project_milestone_edit', 'Allows users to edit Project Milestone'),
(59, 'Project Milestone Delete', 'project_milestone_delete', 'Allows users to delete Project Milestone'),
(60, 'Project Milestone Show', 'project_milestone_show', 'Allows users to view Project Milestone'),
(61, 'Project Milestone List', 'project_milestone_index', 'Allows users to list Project Milestone'),
(62, 'Project Activity Create', 'project_activity_create', 'Allows users to create Project Activity'),
(63, 'Project Activity Edit', 'project_activity_edit', 'Allows users to edit Project Activity'),
(64, 'Project Activity Delete', 'project_activity_delete', 'Allows users to delete Project Activity'),
(65, 'Project Activity Show', 'project_activity_show', 'Allows users to view Project Activity'),
(66, 'Project Activity List', 'project_activity_index', 'Allows users to list Project Activity'),
(67, 'Activity Files Create', 'activity_files_create', 'Allows users to create Activity Files'),
(68, 'Activity Files Edit', 'activity_files_edit', 'Allows users to edit Activity Files'),
(69, 'Activity Files Delete', 'activity_files_delete', 'Allows users to delete Activity Files'),
(70, 'Activity Files Show', 'activity_files_show', 'Allows users to view Activity Files'),
(71, 'Activity Files List', 'activity_files_index', 'Allows users to list Activity Files'),
(72, 'Activity Progress Create', 'activity_progress_create', 'Allows users to create Activity Progress'),
(73, 'Activity Progress Edit', 'activity_progress_edit', 'Allows users to edit Activity Progress'),
(74, 'Activity Progress Delete', 'activity_progress_delete', 'Allows users to delete Activity Progress'),
(75, 'Activity Progress Show', 'activity_progress_show', 'Allows users to view Activity Progress'),
(76, 'Activity Progress List', 'activity_progress_index', 'Allows users to list Activity Progress'),
(77, 'Activity Verification Create', 'activity_verification_create', 'Allows users to create Activity Verification'),
(78, 'Activity Verification Edit', 'activity_verification_edit', 'Allows users to edit Activity Verification'),
(79, 'Activity Verification Delete', 'activity_verification_delete', 'Allows users to delete Activity Verification'),
(80, 'Activity Verification Show', 'activity_verification_show', 'Allows users to view Activity Verification'),
(81, 'Activity Verification List', 'activity_verification_index', 'Allows users to list Activity Verification'),
(82, 'Activity Chat Create', 'activity_chat_create', 'Allows users to create Activity Chat'),
(83, 'Activity Chat Edit', 'activity_chat_edit', 'Allows users to edit Activity Chat'),
(84, 'Activity Chat Delete', 'activity_chat_delete', 'Allows users to delete Activity Chat'),
(85, 'Activity Chat Show', 'activity_chat_show', 'Allows users to view Activity Chat'),
(86, 'Activity Chat List', 'activity_chat_index', 'Allows users to list Activity Chat'),
(87, 'Project Member History Create', 'project_member_history_create', 'Allows users to create Project Member History'),
(88, 'Project Member History Edit', 'project_member_history_edit', 'Allows users to edit Project Member History'),
(89, 'Project Member History Delete', 'project_member_history_delete', 'Allows users to delete Project Member History'),
(90, 'Project Member History Show', 'project_member_history_show', 'Allows users to view Project Member History'),
(91, 'Project Member History List', 'project_member_history_index', 'Allows users to list Project Member History'),
(92, 'Activity User Create', 'activity_user_create', 'Allows users to create Activity User'),
(93, 'Activity User Edit', 'activity_user_edit', 'Allows users to edit Activity User'),
(94, 'Activity User Delete', 'activity_user_delete', 'Allows users to delete Activity User'),
(95, 'Activity User Show', 'activity_user_show', 'Allows users to view Activity User'),
(96, 'Activity User List', 'activity_user_index', 'Allows users to list Activity User'),
(97, 'Project Collaboration Topic Create', 'project_collaboration_topic_create', 'Allows users to create Project Collaboration Topic'),
(98, 'Project Collaboration Topic Edit', 'project_collaboration_topic_edit', 'Allows users to edit Project Collaboration Topic'),
(99, 'Project Collaboration Topic Delete', 'project_collaboration_topic_delete', 'Allows users to delete Project Collaboration Topic'),
(100, 'Project Collaboration Topic Show', 'project_collaboration_topic_show', 'Allows users to view Project Collaboration Topic'),
(101, 'Project Collaboration Topic List', 'project_collaboration_topic_index', 'Allows users to list Project Collaboration Topic'),
(102, 'Organization Unit Create', 'organization_unit_create', 'Allows users to create Organization Unit'),
(103, 'Organization Unit Edit', 'organization_unit_edit', 'Allows users to edit Organization Unit'),
(104, 'Organization Unit Delete', 'organization_unit_delete', 'Allows users to delete Organization Unit'),
(105, 'Organization Unit Show', 'organization_unit_show', 'Allows users to view Organization Unit'),
(106, 'Organization Unit List', 'organization_unit_index', 'Allows users to list Organization Unit'),
(107, 'Stakeholder Create', 'stakeholder_create', 'Allows users to create Stakeholder'),
(108, 'Stakeholder Edit', 'stakeholder_edit', 'Allows users to edit Stakeholder'),
(109, 'Stakeholder Delete', 'stakeholder_delete', 'Allows users to delete Stakeholder'),
(110, 'Stakeholder Show', 'stakeholder_show', 'Allows users to view Stakeholder'),
(111, 'Stakeholder List', 'stakeholder_index', 'Allows users to list Stakeholder'),
(112, 'Organization Create', 'organization_create', 'Allows users to create Organization'),
(113, 'Organization Edit', 'organization_edit', 'Allows users to edit Organization'),
(114, 'Organization Delete', 'organization_delete', 'Allows users to delete Organization'),
(115, 'Organization Show', 'organization_show', 'Allows users to view Organization'),
(116, 'Organization List', 'organization_index', 'Allows users to list Organization'),
(117, 'Email Template Create', 'email_template_create', 'Allows users to create Email Template'),
(118, 'Email Template Edit', 'email_template_edit', 'Allows users to edit Email Template'),
(119, 'Email Template Delete', 'email_template_delete', 'Allows users to delete Email Template'),
(120, 'Email Template Show', 'email_template_show', 'Allows users to view Email Template'),
(121, 'Email Template List', 'email_template_index', 'Allows users to list Email Template'),
(122, 'Project Structure Create', 'project_structure_create', 'Allows users to create Project Structure'),
(123, 'Project Structure Edit', 'project_structure_edit', 'Allows users to edit Project Structure'),
(124, 'Project Structure Delete', 'project_structure_delete', 'Allows users to delete Project Structure'),
(125, 'Project Structure Show', 'project_structure_show', 'Allows users to view Project Structure'),
(126, 'Project Structure List', 'project_structure_index', 'Allows users to list Project Structure'),
(127, 'User Group Create', 'user_group_create', 'Allows users to create User Group'),
(128, 'User Group Edit', 'user_group_edit', 'Allows users to edit User Group'),
(129, 'User Group Delete', 'user_group_delete', 'Allows users to delete User Group'),
(130, 'User Group Show', 'user_group_show', 'Allows users to view User Group'),
(131, 'User Group List', 'user_group_index', 'Allows users to list User Group'),
(132, 'User Group Permission', 'user_group_permission', 'Manage user group\'s permission'),
(133, 'User Group User', 'user_group_user', 'Manage users in a user group'),
(134, 'Permission Edit', 'permission_edit', 'Allows users to edit Permission'),
(135, 'Permission Delete', 'permission_delete', 'Allows users to delete Permission'),
(136, 'Permission Show', 'permission_show', 'Allows users to view Permission'),
(137, 'Permission List', 'permission_index', 'Allows users to list Permission'),
(138, 'Currency Index', 'currency_index', 'Allows user to list currency'),
(139, 'Project Category List', 'project_category_index', 'Allows user to list project category'),
(140, 'Resource Type List', 'resource_type_index', 'Allows user to list resource types'),
(141, 'Sponsor List', 'sponsor_index', 'Allows user to list sponsors'),
(142, 'Organization List', 'organization_index', 'Allows user to list organizations'),
(143, 'Organization Unit List', 'organization_unit_index', 'Allows user to list organization unit'),
(145, 'Manage Project Plan', 'manage_project_plan', 'Allows user to approve or reject project plan'),
(146, 'Goal Create', 'goal_create', 'Allows users to create Goal'),
(147, 'Goal Edit', 'goal_edit', 'Allows users to edit Goal'),
(148, 'Goal Delete', 'goal_delete', 'Allows users to delete Goal'),
(149, 'Goal Show', 'goal_show', 'Allows users to view Goal'),
(150, 'Goal List', 'goal_index', 'Allows users to list Goal'),
(151, 'Objective Create', 'objective_create', 'Allows users to create Objective'),
(152, 'Objective Edit', 'objective_edit', 'Allows users to edit Objective'),
(153, 'Objective Delete', 'objective_delete', 'Allows users to delete Objective'),
(154, 'Objective Show', 'objective_show', 'Allows users to view Objective'),
(155, 'Objective List', 'objective_index', 'Allows users to list Objective'),
(156, 'Report Create', 'report_create', 'Allows users to create Report'),
(157, 'Report Edit', 'report_edit', 'Allows users to edit Report'),
(158, 'Report Delete', 'report_delete', 'Allows users to delete Report'),
(159, 'Report Show', 'report_show', 'Allows users to view Report'),
(160, 'Report List', 'report_index', 'Allows users to list Report');

-- --------------------------------------------------------

--
-- Table structure for table `plan_modification_request`
--

CREATE TABLE `plan_modification_request` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `approved_by_id` int DEFAULT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `status` int NOT NULL,
  `approved_at` datetime DEFAULT NULL,
  `approver_comment` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plan_modification_request`
--

INSERT INTO `plan_modification_request` (`id`, `project_id`, `created_by_id`, `approved_by_id`, `comment`, `created_at`, `status`, `approved_at`, `approver_comment`) VALUES
(1, 9, 1, 1, 'hey man i forgot to include the data we talked about on last week so would you be able to give it back to me again?', '2021-08-27 10:20:21', 3, '2021-08-27 11:36:29', 'Sorry but this aint a good reason. Better luck next time.sd');

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `id` int NOT NULL,
  `currency_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `stakeholders` longtext COLLATE utf8mb4_unicode_ci,
  `amount` double NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `objective` longtext COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL,
  `program_manager_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`id`, `currency_id`, `name`, `description`, `stakeholders`, `amount`, `start_date`, `end_date`, `objective`, `status`, `program_manager_id`) VALUES
(1, 2, 'Digital Transformation Program', NULL, 'MinT, EtherNET', 200000000, '2021-07-01', '2026-07-01', NULL, 1, 1),
(2, 1, 'DTP', NULL, NULL, 1321, '2021-08-10', '2021-08-19', NULL, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` int NOT NULL,
  `project_manager_id` int NOT NULL,
  `currency_id` int NOT NULL,
  `program_id` int DEFAULT NULL,
  `created_by_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `amount` double NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `outcome` longtext COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL,
  `created_at` datetime NOT NULL,
  `baseline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_id` int DEFAULT NULL,
  `planned_value` int NOT NULL,
  `objective_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `project_manager_id`, `currency_id`, `program_id`, `created_by_id`, `name`, `description`, `amount`, `start_date`, `end_date`, `outcome`, `status`, `created_at`, `baseline`, `unit_id`, `planned_value`, `objective_id`) VALUES
(1, 1, 1, 1, 1, 'City Portals', NULL, 2000000, '2021-01-01', '2022-01-01', NULL, 4, '2021-07-26 11:44:01', NULL, 2, 0, 1),
(2, 3, 1, 1, 1, 'STI project', NULL, 1234567890, '2021-01-01', '2023-01-01', 'sdf', 1, '2021-07-27 15:11:50', NULL, 4, 0, 2),
(9, 1, 1, NULL, 1, 'DIgital Literacy', NULL, 5785, '2021-08-24', '2021-08-30', NULL, 2, '2021-08-22 07:26:52', NULL, 3, 2, 3),
(10, 1, 1, NULL, 1, 'Project Followup', NULL, 0, '2021-08-02', '2021-08-11', NULL, 4, '2021-08-23 10:25:09', NULL, 4, 1, 7),
(11, 1, 1, NULL, 1, 'Main Project', NULL, 0, '2021-08-24', '2024-04-30', NULL, 1, '2021-08-24 16:15:19', NULL, 2, 1, 19),
(12, 1, 1, NULL, 1, 'test', NULL, 20, '2021-08-25', '2021-08-31', NULL, 2, '2021-08-27 15:47:58', '0', 2, 3, 16);

-- --------------------------------------------------------

--
-- Table structure for table `project_activity`
--

CREATE TABLE `project_activity` (
  `id` int NOT NULL,
  `milestone_id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL,
  `display_order` int NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `weight` double DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `project_id` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_activity`
--

INSERT INTO `project_activity` (`id`, `milestone_id`, `created_by_id`, `title`, `description`, `status`, `display_order`, `is_active`, `weight`, `created_at`, `project_id`, `parent_id`, `start_date`, `end_date`) VALUES
(5, 1, 1, 'sfdf', '<p>sd<br></p>', 1, 1, 1, NULL, '2021-08-18 14:23:05', 1, NULL, '2021-08-19', '2021-08-19'),
(7, 1, 1, 'sdfsd', '<p>sd<br></p>', 1, 1, 1, NULL, '2021-08-20 09:13:26', 1, NULL, '2021-08-21', '2021-08-31'),
(9, 1, 1, 'sd', NULL, 1, 1, 1, NULL, '2021-08-22 13:54:58', 9, NULL, '2021-08-17', '2022-01-20'),
(10, 1, 1, 'create initial data', NULL, 1, 1, 1, NULL, '2021-08-23 10:26:56', 10, NULL, '2021-08-04', '2021-08-11'),
(11, 1, 1, 'additioanl activity', NULL, 1, 2, 1, NULL, '2021-08-23 10:31:38', 10, NULL, '2021-08-18', '2021-08-27'),
(12, 7, 1, 'Initiate Project', NULL, 1, 1, 1, NULL, '2021-08-25 09:33:54', 11, NULL, '2021-09-25', '2021-09-28'),
(13, 7, 1, 'Concept Note', NULL, 1, 2, 1, NULL, '2021-08-25 09:37:09', 11, NULL, '2021-09-29', '2021-10-13'),
(14, 7, 1, 'Pre Assesment', NULL, 1, 3, 1, NULL, '2021-08-25 09:39:29', 11, NULL, '2021-10-14', '2021-10-18'),
(15, 7, 1, 'Board Review', NULL, 1, 4, 1, NULL, '2021-08-25 09:43:47', 11, NULL, '2021-10-19', '2021-10-25'),
(16, 10, 1, 'Define Requirement', NULL, 1, 5, 1, 50, '2021-08-25 09:45:17', 11, NULL, '2021-10-11', '2021-10-19'),
(17, 10, 1, 'Review Requirement', NULL, 1, 6, 1, 25, '2021-08-25 09:46:15', 11, NULL, '2021-10-20', '2021-10-30'),
(18, 10, 1, 'Approve Requirement', NULL, 1, 7, 1, 25, '2021-08-25 09:47:24', 11, NULL, '2021-11-01', '2021-11-10'),
(19, 11, 1, 'Generate Design', NULL, 1, 8, 1, 40, '2021-08-25 10:02:38', 11, NULL, '2021-12-01', '2021-12-03'),
(20, 11, 1, 'Iterate Design', NULL, 1, 9, 1, 30, '2021-08-25 10:04:11', 11, NULL, '2021-12-06', '2021-12-17'),
(21, 11, 1, 'Finalize Design', NULL, 1, 10, 1, 20, '2021-08-25 10:07:43', 11, NULL, '2021-12-20', '2021-12-25'),
(22, 11, 1, 'Approve Design', NULL, 1, 11, 1, 10, '2021-08-25 10:08:51', 11, NULL, '2021-12-27', '2021-12-31'),
(23, 12, 1, 'Build a System', NULL, 1, 12, 1, 50, '2021-08-25 10:13:43', 11, NULL, '2022-02-01', '2022-02-15'),
(24, 12, 1, 'Unit Testing', NULL, 1, 13, 1, 10, '2021-08-25 10:30:09', 11, NULL, '2022-02-17', '2022-02-20'),
(25, 12, 1, 'Integration Testing', NULL, 1, 14, 1, 20, '2021-08-25 10:31:10', 11, NULL, '2022-02-21', '2022-02-23'),
(26, 12, 1, 'Validation', NULL, 1, 15, 1, 10, '2021-08-25 10:31:58', 11, NULL, '2022-02-24', '2022-02-26'),
(27, 12, 1, 'Verification', NULL, 1, 16, 1, 10, '2021-08-25 10:32:57', 11, NULL, '2022-02-27', '2022-03-01'),
(28, 13, 1, 'End User Training', NULL, 1, 17, 1, 60, '2021-08-25 10:34:27', 11, NULL, '2022-03-01', '2022-03-20'),
(29, 13, 1, 'Deployement', NULL, 1, 18, 1, 15, '2021-08-25 10:35:50', 11, NULL, '2022-03-21', '2022-03-25'),
(30, 13, 1, 'Final Handover', NULL, 1, 19, 1, 25, '2021-08-25 10:36:37', 11, NULL, '2022-03-28', '2022-03-31'),
(31, 1, 1, 'another it is wow', NULL, 1, 1, 1, NULL, '2021-08-22 13:54:58', 9, NULL, '2021-08-17', '2022-03-17'),
(32, 1, 1, 'amsalu is really', NULL, 1, 1, 1, NULL, '2021-08-22 13:54:58', 9, NULL, '2021-08-17', '2022-06-09'),
(33, 5, 1, 'sdf', NULL, 1, 3, 1, 10, '2021-08-26 12:08:51', 9, NULL, '2021-08-15', '2021-08-20'),
(34, 15, 1, 'Approve Design', NULL, 1, 1, 1, NULL, '2021-08-27 16:01:27', 12, NULL, '2021-08-25', '2021-08-26');

-- --------------------------------------------------------

--
-- Table structure for table `project_category`
--

CREATE TABLE `project_category` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_category`
--

INSERT INTO `project_category` (`id`, `name`, `description`) VALUES
(1, 'Project Mangement', 'for project management category');

-- --------------------------------------------------------

--
-- Table structure for table `project_collaboration_topic`
--

CREATE TABLE `project_collaboration_topic` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_collaboration_topic`
--

INSERT INTO `project_collaboration_topic` (`id`, `project_id`, `created_by_id`, `title`, `description`, `created_at`) VALUES
(2, 1, 1, 'database design', '<p>designing of the database related things<br></p>', '2021-08-03 08:13:07'),
(3, 10, 1, 'khj', '<p>kljkl;<br></p>', '2021-08-27 16:21:00');

-- --------------------------------------------------------

--
-- Table structure for table `project_deliverable`
--

CREATE TABLE `project_deliverable` (
  `id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `delivery_date` date DEFAULT NULL,
  `percentage` double NOT NULL,
  `planned_delivery_date` date NOT NULL,
  `verify_deliverable` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `project_id` int NOT NULL,
  `milestone_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_deliverable`
--

INSERT INTO `project_deliverable` (`id`, `created_by_id`, `title`, `description`, `delivery_date`, `percentage`, `planned_delivery_date`, `verify_deliverable`, `created_at`, `project_id`, `milestone_id`) VALUES
(7, 1, 'sd', '<p>sd<br></p>', NULL, 20, '2021-08-26', 0, '2021-08-22 13:54:21', 9, 1),
(8, 1, 'Documents', NULL, NULL, 10, '2021-08-17', 0, '2021-08-23 10:26:30', 10, 1),
(9, 1, 'Concept Note', NULL, NULL, 20, '2021-09-26', 0, '2021-08-24 16:50:16', 11, 7),
(10, 1, 'Assesment Result', NULL, NULL, 30, '2021-10-11', 0, '2021-08-24 16:55:18', 11, 7),
(11, 1, 'Project Document', NULL, NULL, 50, '2021-10-06', 0, '2021-08-24 16:56:30', 11, 7),
(15, 1, 'd1', NULL, NULL, 20, '2021-08-25', 0, '2021-08-27 15:58:02', 12, 14);

-- --------------------------------------------------------

--
-- Table structure for table `project_deliverable_status`
--

CREATE TABLE `project_deliverable_status` (
  `id` int NOT NULL,
  `deliverable_id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `type` int NOT NULL,
  `status` int NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_members`
--

CREATE TABLE `project_members` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `role_id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `status` int NOT NULL,
  `is_working_on_task` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `project_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_members`
--

INSERT INTO `project_members` (`id`, `user_id`, `role_id`, `created_by_id`, `status`, `is_working_on_task`, `created_at`, `project_id`) VALUES
(8, 1, 3, 1, 1, 0, '2021-08-02 14:27:50', 1),
(9, 3, 3, 1, 1, 1, '2021-08-04 10:54:36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `project_member_history`
--

CREATE TABLE `project_member_history` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_milestone`
--

CREATE TABLE `project_milestone` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `last_revision` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `activities_equal_weight` tinyint(1) NOT NULL,
  `weight` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_milestone`
--

INSERT INTO `project_milestone` (`id`, `project_id`, `created_by_id`, `name`, `description`, `last_revision`, `created_at`, `activities_equal_weight`, `weight`, `start_date`, `end_date`) VALUES
(1, 1, 1, 'new', NULL, NULL, '2021-08-07 11:27:38', 1, 0, '2021-08-01', '2021-08-10'),
(5, 9, 1, 'capetown', NULL, NULL, '2021-08-22 13:54:05', 0, 10, '2021-08-11', '2021-08-20'),
(6, 10, 1, 'initial', NULL, NULL, '2021-08-23 10:26:10', 0, 10, '2021-02-21', '2021-08-31'),
(7, 11, 1, 'Project approval', NULL, NULL, '2021-08-24 16:19:35', 1, 10, '2021-09-25', '2021-10-25'),
(10, 11, 1, 'Requirements Review', NULL, NULL, '2021-08-24 16:34:13', 0, 30, '2021-10-10', '2021-11-11'),
(11, 11, 1, 'Design Approval', NULL, NULL, '2021-08-24 16:39:08', 0, 10, '2021-12-01', '2022-01-01'),
(12, 11, 1, 'Implementation', NULL, NULL, '2021-08-24 16:47:01', 0, 30, '2022-02-01', '2022-03-01'),
(13, 11, 1, 'Handover', NULL, NULL, '2021-08-24 16:47:41', 0, 20, '2022-03-01', '2022-04-01'),
(14, 12, 1, 'm1', NULL, NULL, '2021-08-27 15:50:28', 1, 25, '2021-08-25', '2021-08-27'),
(15, 12, 1, 'm2', NULL, NULL, '2021-08-27 15:53:58', 0, 50, '2021-08-25', '2021-08-29');

-- --------------------------------------------------------

--
-- Table structure for table `project_milestone_status`
--

CREATE TABLE `project_milestone_status` (
  `id` int NOT NULL,
  `milestone_id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_organization`
--

CREATE TABLE `project_organization` (
  `project_id` int NOT NULL,
  `organization_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_organization`
--

INSERT INTO `project_organization` (`project_id`, `organization_id`) VALUES
(12, 1),
(12, 2);

-- --------------------------------------------------------

--
-- Table structure for table `project_plan_comment`
--

CREATE TABLE `project_plan_comment` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `entity` int NOT NULL,
  `data` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_id` int NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_plan_comment`
--

INSERT INTO `project_plan_comment` (`id`, `project_id`, `created_by_id`, `entity`, `data`, `data_id`, `comment`, `created_at`) VALUES
(1, 9, 1, 3, 'sd', 9, 'this is biased title get rid of it\r\n', '2021-08-26 10:39:23'),
(2, 12, 1, 3, 'Approve Design', 34, 'not enough days for this activity\r\n', '2021-08-27 16:07:15');

-- --------------------------------------------------------

--
-- Table structure for table `project_plan_revision`
--

CREATE TABLE `project_plan_revision` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `revision_id` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revision_details` longtext COLLATE utf8mb4_unicode_ci,
  `created_by_id` int NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_plan_revision`
--

INSERT INTO `project_plan_revision` (`id`, `project_id`, `revision_id`, `revision_details`, `created_by_id`, `created_at`) VALUES
(4, 1, '1.2', 'updated the data', 1, '2021-08-19 09:13:58'),
(5, 1, '1.2', 'astekakelkut', 1, '2021-08-19 13:58:46'),
(7, 1, '1.2', 'nmm', 1, '2021-08-19 15:22:53'),
(8, 1, '10', 'jbljh', 1, '2021-08-19 15:44:57'),
(10, 9, '1', '', 1, '2021-08-22 13:55:21'),
(11, 10, '1.0', '', 1, '2021-08-23 10:28:04'),
(12, 10, '1.1', 'detail', 1, '2021-08-23 10:32:32'),
(13, 12, '1.1', '', 1, '2021-08-27 16:05:30');

-- --------------------------------------------------------

--
-- Table structure for table `project_plan_status`
--

CREATE TABLE `project_plan_status` (
  `id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `project_id` int NOT NULL,
  `decision` int NOT NULL,
  `justification` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_plan_status`
--

INSERT INTO `project_plan_status` (`id`, `created_by_id`, `project_id`, `decision`, `justification`, `created_at`) VALUES
(1, 1, 1, 1, 'It needs more work', '2021-08-19 13:52:10'),
(2, 1, 1, 3, '', '2021-08-19 14:00:38'),
(3, 1, 1, 4, NULL, '2021-08-19 14:30:23'),
(4, 1, 1, 1, 'hjgjhg', '2021-08-19 15:43:59'),
(5, 1, 1, 3, '', '2021-08-19 15:46:23'),
(6, 1, 1, 4, NULL, '2021-08-19 15:51:51'),
(8, 1, 10, 1, 'not enough data', '2021-08-23 10:30:59'),
(9, 1, 10, 4, '', '2021-08-23 10:33:08');

-- --------------------------------------------------------

--
-- Table structure for table `project_resource`
--

CREATE TABLE `project_resource` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `resource_type_id` int NOT NULL,
  `uploaded_by_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL,
  `is_public` tinyint(1) DEFAULT NULL,
  `is_pinned` tinyint(1) DEFAULT NULL,
  `uploaded_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_resource`
--

INSERT INTO `project_resource` (`id`, `project_id`, `resource_type_id`, `uploaded_by_id`, `title`, `description`, `file`, `status`, `is_public`, `is_pinned`, `uploaded_at`) VALUES
(1, 1, 1, 1, 'Weekly report template', 'this is a weekly report template', '60ffa7c3363ef.pdf', 1, 0, 0, '2021-07-27 09:26:15'),
(2, 1, 2, 1, 'Concept Note', 'some description', '60ffa9f7b75ca.pdf', 1, 1, 1, '2021-07-27 09:38:47');

-- --------------------------------------------------------

--
-- Table structure for table `project_sponsor`
--

CREATE TABLE `project_sponsor` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `sponsor_id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_structure`
--

CREATE TABLE `project_structure` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `reports_to_id` int DEFAULT NULL,
  `created_by_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL,
  `one_person_only` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_structure`
--

INSERT INTO `project_structure` (`id`, `project_id`, `reports_to_id`, `created_by_id`, `name`, `description`, `created_at`, `one_person_only`) VALUES
(1, 1, NULL, 1, 'Testing', NULL, '2021-08-11 11:27:39', 0),
(2, 1, 1, 1, 'Integrator', NULL, '2021-08-11 11:40:23', 0),
(3, 1, 2, 1, 'Training', NULL, '2021-08-11 11:40:46', 0),
(4, 10, 6, 1, 'Development Team Leader', NULL, '2021-08-23 10:51:06', 0),
(5, 10, 6, 1, 'Networking Team Leader', NULL, '2021-08-23 10:51:27', 0),
(6, 10, NULL, 1, 'Project Manager', NULL, '2021-08-23 10:51:38', 0),
(7, 10, 4, 1, 'Development Team Members', NULL, '2021-08-23 10:53:15', 0),
(8, 10, 5, 1, 'Networking Team Members', NULL, '2021-08-23 10:53:43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `project_structure_email_template`
--

CREATE TABLE `project_structure_email_template` (
  `project_structure_id` int NOT NULL,
  `email_template_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_version`
--

CREATE TABLE `project_version` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `released_date` date NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_version`
--

INSERT INTO `project_version` (`id`, `project_id`, `created_by_id`, `version`, `description`, `released_date`, `created_at`) VALUES
(1, 1, 1, '1.0', 'this is an alpha release', '2021-07-30', '2021-07-26 14:19:51'),
(2, 1, 1, '1.1', 'this is a beta release', '2021-07-30', '2021-07-26 14:22:51'),
(3, 1, 1, '1.2', 'this is a minor release', '2021-07-31', '2021-07-26 15:22:51');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `query` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`id`, `created_by_id`, `name`, `description`, `query`, `is_active`, `created_at`) VALUES
(1, 1, 'All Porjects', 'list of all projects', 'select * from projects;', 1, '2021-08-28 07:55:33');

-- --------------------------------------------------------

--
-- Table structure for table `resource_type`
--

CREATE TABLE `resource_type` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `resource_type`
--

INSERT INTO `resource_type` (`id`, `name`, `description`) VALUES
(1, 'Reporting template', 'used for <b>reporting template</b>'),
(2, 'Uncategorized', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sponsor`
--

CREATE TABLE `sponsor` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sponsor`
--

INSERT INTO `sponsor` (`id`, `name`, `contact_person`, `email`, `phone`) VALUES
(1, 'Ministry of Innovation and Technology', 'Ferid Bedru', 'ferid.bedru@mint.gov.et', '0929033032');

-- --------------------------------------------------------

--
-- Table structure for table `stakeholder`
--

CREATE TABLE `stakeholder` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `project_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stakeholder`
--

INSERT INTO `stakeholder` (`id`, `name`, `description`, `project_id`) VALUES
(2, 'mint', 'mint', 1),
(3, 'ministry of finance', 'mofed', 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `created_by_id` int DEFAULT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `status` int NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirm_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` int NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `created_by_id`, `username`, `email`, `roles`, `password`, `created_at`, `status`, `is_active`, `last_login`, `confirm_token`, `photo`, `phone`, `position`, `full_name`) VALUES
(1, NULL, 'ferid', 'ferid.bedru@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$6UaugI/QUOzmDr1a2XfYfOwN1wW7bXnUJ.lz8ukeMun7Li6rZg85y', '2021-06-11 14:36:36', 1, 0, '2021-08-28 07:47:42', NULL, NULL, 0, '', 'Ferid Bedru'),
(3, NULL, 'amslau', 'amsalu.tadesse@mint.gov.et', '[\"ROLE_ADMIN\"]', '$2y$13$6UaugI/QUOzmDr1a2XfYfOwN1wW7bXnUJ.lz8ukeMun7Li6rZg85y', '2021-06-11 14:36:36', 1, 0, '2021-08-03 08:06:11', NULL, NULL, 0, '', 'Amsalu Tadesse'),
(4, NULL, 'third', 'ferid.bedru@ju.edu.et', '[\"ROLE_ADMIN\"]', '$2y$13$6UaugI/QUOzmDr1a2XfYfOwN1wW7bXnUJ.lz8ukeMun7Li6rZg85y', '2021-06-11 14:36:36', 1, 0, '2021-08-03 08:06:11', NULL, NULL, 0, '', 'Someone Else');

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `id` int NOT NULL,
  `registered_by_id` int NOT NULL,
  `updated_by_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`id`, `registered_by_id`, `updated_by_id`, `name`, `description`, `created_at`, `updated_at`, `is_active`) VALUES
(1, 1, 1, 'Super Admin', 'A group with highest privilleges', '2021-07-25 14:05:37', '2021-08-28 07:47:24', 1),
(2, 1, 1, 'Project Manager', NULL, '2021-08-04 11:42:54', '2021-08-04 11:44:33', 1),
(3, 1, NULL, 'Team Members', NULL, '2021-08-04 11:43:12', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_group_permission`
--

CREATE TABLE `user_group_permission` (
  `user_group_id` int NOT NULL,
  `permission_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_group_permission`
--

INSERT INTO `user_group_permission` (`user_group_id`, `permission_id`) VALUES
(1, 1),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 25),
(1, 26),
(1, 27),
(1, 28),
(1, 29),
(1, 30),
(1, 31),
(1, 32),
(1, 33),
(1, 34),
(1, 35),
(1, 36),
(1, 37),
(1, 38),
(1, 39),
(1, 40),
(1, 41),
(1, 47),
(1, 48),
(1, 49),
(1, 50),
(1, 51),
(1, 52),
(1, 53),
(1, 54),
(1, 55),
(1, 56),
(1, 57),
(1, 58),
(1, 59),
(1, 60),
(1, 61),
(1, 62),
(1, 63),
(1, 64),
(1, 65),
(1, 66),
(1, 67),
(1, 68),
(1, 69),
(1, 70),
(1, 71),
(1, 72),
(1, 73),
(1, 74),
(1, 75),
(1, 76),
(1, 77),
(1, 78),
(1, 79),
(1, 80),
(1, 81),
(1, 82),
(1, 83),
(1, 84),
(1, 85),
(1, 86),
(1, 87),
(1, 88),
(1, 89),
(1, 90),
(1, 91),
(1, 92),
(1, 93),
(1, 94),
(1, 95),
(1, 96),
(1, 97),
(1, 98),
(1, 99),
(1, 100),
(1, 101),
(1, 102),
(1, 103),
(1, 104),
(1, 105),
(1, 106),
(1, 107),
(1, 108),
(1, 109),
(1, 110),
(1, 111),
(1, 112),
(1, 113),
(1, 114),
(1, 115),
(1, 116),
(1, 117),
(1, 118),
(1, 119),
(1, 120),
(1, 121),
(1, 122),
(1, 123),
(1, 124),
(1, 125),
(1, 126),
(1, 127),
(1, 128),
(1, 129),
(1, 130),
(1, 131),
(1, 132),
(1, 133),
(1, 134),
(1, 135),
(1, 136),
(1, 137),
(1, 138),
(1, 139),
(1, 140),
(1, 141),
(1, 142),
(1, 143),
(1, 145),
(1, 146),
(1, 147),
(1, 148),
(1, 149),
(1, 150),
(1, 151),
(1, 152),
(1, 153),
(1, 154),
(1, 155),
(1, 156),
(1, 157),
(1, 158),
(1, 159),
(1, 160);

-- --------------------------------------------------------

--
-- Table structure for table `user_user_group`
--

CREATE TABLE `user_user_group` (
  `user_id` int NOT NULL,
  `user_group_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_user_group`
--

INSERT INTO `user_user_group` (`user_id`, `user_group_id`) VALUES
(1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_chat`
--
ALTER TABLE `activity_chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_665929385A6D2235` (`posted_by_id`),
  ADD KEY `IDX_665929381F55203D` (`topic_id`);

--
-- Indexes for table `activity_files`
--
ALTER TABLE `activity_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_183776EA81C06096` (`activity_id`),
  ADD KEY `IDX_183776EAA2B28FE8` (`uploaded_by_id`);

--
-- Indexes for table `activity_progress`
--
ALTER TABLE `activity_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_820B424881C06096` (`activity_id`),
  ADD KEY `IDX_820B4248B03A8386` (`created_by_id`);

--
-- Indexes for table `activity_user`
--
ALTER TABLE `activity_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8E570DDB81C06096` (`activity_id`),
  ADD KEY `IDX_8E570DDBA76ED395` (`user_id`),
  ADD KEY `IDX_8E570DDB6E6F1246` (`assigned_by_id`);

--
-- Indexes for table `activity_verification`
--
ALTER TABLE `activity_verification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5349074AA73CA575` (`activity_user_id`),
  ADD KEY `IDX_5349074AB03A8386` (`created_by_id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `email_template`
--
ALTER TABLE `email_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goal`
--
ALTER TABLE `goal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8F3F68C5A76ED395` (`user_id`);

--
-- Indexes for table `objective`
--
ALTER TABLE `objective`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B996F101667D1AFE` (`goal_id`);

--
-- Indexes for table `organization`
--
ALTER TABLE `organization`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_unit`
--
ALTER TABLE `organization_unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E5B232CE32C8A3DE` (`organization_id`),
  ADD KEY `IDX_E5B232CEF41A619E` (`head_id`),
  ADD KEY `IDX_E5B232CE9BE3208E` (`reports_to_id`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plan_modification_request`
--
ALTER TABLE `plan_modification_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9FA33756166D1F9C` (`project_id`),
  ADD KEY `IDX_9FA33756B03A8386` (`created_by_id`),
  ADD KEY `IDX_9FA337562D234F6A` (`approved_by_id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_92ED778438248176` (`currency_id`),
  ADD KEY `IDX_92ED77846E04C9D7` (`program_manager_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2FB3D0EE60984F51` (`project_manager_id`),
  ADD KEY `IDX_2FB3D0EE38248176` (`currency_id`),
  ADD KEY `IDX_2FB3D0EE3EB8070A` (`program_id`),
  ADD KEY `IDX_2FB3D0EEB03A8386` (`created_by_id`),
  ADD KEY `IDX_2FB3D0EEF8BD700D` (`unit_id`),
  ADD KEY `IDX_2FB3D0EE73484933` (`objective_id`);

--
-- Indexes for table `project_activity`
--
ALTER TABLE `project_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_913A82814B3E2EDA` (`milestone_id`),
  ADD KEY `IDX_913A8281B03A8386` (`created_by_id`),
  ADD KEY `IDX_913A8281166D1F9C` (`project_id`),
  ADD KEY `IDX_913A8281727ACA70` (`parent_id`);

--
-- Indexes for table `project_category`
--
ALTER TABLE `project_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_collaboration_topic`
--
ALTER TABLE `project_collaboration_topic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DCBF5303166D1F9C` (`project_id`),
  ADD KEY `IDX_DCBF5303B03A8386` (`created_by_id`);

--
-- Indexes for table `project_deliverable`
--
ALTER TABLE `project_deliverable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_69B253BAB03A8386` (`created_by_id`),
  ADD KEY `IDX_69B253BA166D1F9C` (`project_id`),
  ADD KEY `IDX_69B253BA4B3E2EDA` (`milestone_id`);

--
-- Indexes for table `project_deliverable_status`
--
ALTER TABLE `project_deliverable_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A0050BD5F3C6560A` (`deliverable_id`),
  ADD KEY `IDX_A0050BD5B03A8386` (`created_by_id`);

--
-- Indexes for table `project_members`
--
ALTER TABLE `project_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D3BEDE9AA76ED395` (`user_id`),
  ADD KEY `IDX_D3BEDE9AD60322AC` (`role_id`),
  ADD KEY `IDX_D3BEDE9AB03A8386` (`created_by_id`),
  ADD KEY `IDX_D3BEDE9A166D1F9C` (`project_id`);

--
-- Indexes for table `project_member_history`
--
ALTER TABLE `project_member_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_71F9F37D166D1F9C` (`project_id`),
  ADD KEY `IDX_71F9F37DA76ED395` (`user_id`),
  ADD KEY `IDX_71F9F37DB03A8386` (`created_by_id`);

--
-- Indexes for table `project_milestone`
--
ALTER TABLE `project_milestone`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5E90C655166D1F9C` (`project_id`),
  ADD KEY `IDX_5E90C655B03A8386` (`created_by_id`);

--
-- Indexes for table `project_milestone_status`
--
ALTER TABLE `project_milestone_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CEBBE6A74B3E2EDA` (`milestone_id`),
  ADD KEY `IDX_CEBBE6A7B03A8386` (`created_by_id`);

--
-- Indexes for table `project_organization`
--
ALTER TABLE `project_organization`
  ADD PRIMARY KEY (`project_id`,`organization_id`),
  ADD KEY `IDX_EB49871F166D1F9C` (`project_id`),
  ADD KEY `IDX_EB49871F32C8A3DE` (`organization_id`);

--
-- Indexes for table `project_plan_comment`
--
ALTER TABLE `project_plan_comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6CFE07166D1F9C` (`project_id`),
  ADD KEY `IDX_6CFE07B03A8386` (`created_by_id`);

--
-- Indexes for table `project_plan_revision`
--
ALTER TABLE `project_plan_revision`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B797B5B0166D1F9C` (`project_id`),
  ADD KEY `IDX_B797B5B0B03A8386` (`created_by_id`);

--
-- Indexes for table `project_plan_status`
--
ALTER TABLE `project_plan_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7978A95B03A8386` (`created_by_id`),
  ADD KEY `IDX_7978A95166D1F9C` (`project_id`);

--
-- Indexes for table `project_resource`
--
ALTER TABLE `project_resource`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_81DF7FCD166D1F9C` (`project_id`),
  ADD KEY `IDX_81DF7FCD98EC6B7B` (`resource_type_id`),
  ADD KEY `IDX_81DF7FCDA2B28FE8` (`uploaded_by_id`);

--
-- Indexes for table `project_sponsor`
--
ALTER TABLE `project_sponsor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1792C5B1166D1F9C` (`project_id`),
  ADD KEY `IDX_1792C5B112F7FB51` (`sponsor_id`),
  ADD KEY `IDX_1792C5B1B03A8386` (`created_by_id`);

--
-- Indexes for table `project_structure`
--
ALTER TABLE `project_structure`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7E3D723D166D1F9C` (`project_id`),
  ADD KEY `IDX_7E3D723D9BE3208E` (`reports_to_id`),
  ADD KEY `IDX_7E3D723DB03A8386` (`created_by_id`);

--
-- Indexes for table `project_structure_email_template`
--
ALTER TABLE `project_structure_email_template`
  ADD PRIMARY KEY (`project_structure_id`,`email_template_id`),
  ADD KEY `IDX_F6F926CBF93E4E8` (`project_structure_id`),
  ADD KEY `IDX_F6F926CB131A730F` (`email_template_id`);

--
-- Indexes for table `project_version`
--
ALTER TABLE `project_version`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2902DFA6166D1F9C` (`project_id`),
  ADD KEY `IDX_2902DFA6B03A8386` (`created_by_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C42F7784B03A8386` (`created_by_id`);

--
-- Indexes for table `resource_type`
--
ALTER TABLE `resource_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sponsor`
--
ALTER TABLE `sponsor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stakeholder`
--
ALTER TABLE `stakeholder`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8B9823AA166D1F9C` (`project_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD KEY `IDX_8D93D649B03A8386` (`created_by_id`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8F02BF9D27E92E18` (`registered_by_id`),
  ADD KEY `IDX_8F02BF9D896DBBDE` (`updated_by_id`);

--
-- Indexes for table `user_group_permission`
--
ALTER TABLE `user_group_permission`
  ADD PRIMARY KEY (`user_group_id`,`permission_id`),
  ADD KEY `IDX_4A91B1C51ED93D47` (`user_group_id`),
  ADD KEY `IDX_4A91B1C5FED90CCA` (`permission_id`);

--
-- Indexes for table `user_user_group`
--
ALTER TABLE `user_user_group`
  ADD PRIMARY KEY (`user_id`,`user_group_id`),
  ADD KEY `IDX_28657971A76ED395` (`user_id`),
  ADD KEY `IDX_286579711ED93D47` (`user_group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_chat`
--
ALTER TABLE `activity_chat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `activity_files`
--
ALTER TABLE `activity_files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `activity_progress`
--
ALTER TABLE `activity_progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `activity_user`
--
ALTER TABLE `activity_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `activity_verification`
--
ALTER TABLE `activity_verification`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `email_template`
--
ALTER TABLE `email_template`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `goal`
--
ALTER TABLE `goal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `objective`
--
ALTER TABLE `objective`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `organization`
--
ALTER TABLE `organization`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `organization_unit`
--
ALTER TABLE `organization_unit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `plan_modification_request`
--
ALTER TABLE `plan_modification_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `project_activity`
--
ALTER TABLE `project_activity`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `project_category`
--
ALTER TABLE `project_category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `project_collaboration_topic`
--
ALTER TABLE `project_collaboration_topic`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `project_deliverable`
--
ALTER TABLE `project_deliverable`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `project_deliverable_status`
--
ALTER TABLE `project_deliverable_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_members`
--
ALTER TABLE `project_members`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `project_member_history`
--
ALTER TABLE `project_member_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_milestone`
--
ALTER TABLE `project_milestone`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `project_milestone_status`
--
ALTER TABLE `project_milestone_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_plan_comment`
--
ALTER TABLE `project_plan_comment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `project_plan_revision`
--
ALTER TABLE `project_plan_revision`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `project_plan_status`
--
ALTER TABLE `project_plan_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `project_resource`
--
ALTER TABLE `project_resource`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `project_sponsor`
--
ALTER TABLE `project_sponsor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_structure`
--
ALTER TABLE `project_structure`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `project_version`
--
ALTER TABLE `project_version`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `resource_type`
--
ALTER TABLE `resource_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sponsor`
--
ALTER TABLE `sponsor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stakeholder`
--
ALTER TABLE `stakeholder`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_chat`
--
ALTER TABLE `activity_chat`
  ADD CONSTRAINT `FK_665929381F55203D` FOREIGN KEY (`topic_id`) REFERENCES `project_collaboration_topic` (`id`),
  ADD CONSTRAINT `FK_665929385A6D2235` FOREIGN KEY (`posted_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `activity_files`
--
ALTER TABLE `activity_files`
  ADD CONSTRAINT `FK_183776EA81C06096` FOREIGN KEY (`activity_id`) REFERENCES `project_activity` (`id`),
  ADD CONSTRAINT `FK_183776EAA2B28FE8` FOREIGN KEY (`uploaded_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `activity_progress`
--
ALTER TABLE `activity_progress`
  ADD CONSTRAINT `FK_820B424881C06096` FOREIGN KEY (`activity_id`) REFERENCES `project_activity` (`id`),
  ADD CONSTRAINT `FK_820B4248B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `activity_user`
--
ALTER TABLE `activity_user`
  ADD CONSTRAINT `FK_8E570DDB6E6F1246` FOREIGN KEY (`assigned_by_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_8E570DDB81C06096` FOREIGN KEY (`activity_id`) REFERENCES `project_activity` (`id`),
  ADD CONSTRAINT `FK_8E570DDBA76ED395` FOREIGN KEY (`user_id`) REFERENCES `project_members` (`id`);

--
-- Constraints for table `activity_verification`
--
ALTER TABLE `activity_verification`
  ADD CONSTRAINT `FK_5349074AA73CA575` FOREIGN KEY (`activity_user_id`) REFERENCES `activity_user` (`id`),
  ADD CONSTRAINT `FK_5349074AB03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `FK_8F3F68C5A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `objective`
--
ALTER TABLE `objective`
  ADD CONSTRAINT `FK_B996F101667D1AFE` FOREIGN KEY (`goal_id`) REFERENCES `goal` (`id`);

--
-- Constraints for table `organization_unit`
--
ALTER TABLE `organization_unit`
  ADD CONSTRAINT `FK_E5B232CE32C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organization` (`id`),
  ADD CONSTRAINT `FK_E5B232CE9BE3208E` FOREIGN KEY (`reports_to_id`) REFERENCES `organization_unit` (`id`),
  ADD CONSTRAINT `FK_E5B232CEF41A619E` FOREIGN KEY (`head_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `plan_modification_request`
--
ALTER TABLE `plan_modification_request`
  ADD CONSTRAINT `FK_9FA33756166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_9FA337562D234F6A` FOREIGN KEY (`approved_by_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_9FA33756B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `program`
--
ALTER TABLE `program`
  ADD CONSTRAINT `FK_92ED778438248176` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`),
  ADD CONSTRAINT `FK_92ED77846E04C9D7` FOREIGN KEY (`program_manager_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `FK_2FB3D0EE38248176` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`),
  ADD CONSTRAINT `FK_2FB3D0EE3EB8070A` FOREIGN KEY (`program_id`) REFERENCES `program` (`id`),
  ADD CONSTRAINT `FK_2FB3D0EE60984F51` FOREIGN KEY (`project_manager_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_2FB3D0EE73484933` FOREIGN KEY (`objective_id`) REFERENCES `objective` (`id`),
  ADD CONSTRAINT `FK_2FB3D0EEB03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_2FB3D0EEF8BD700D` FOREIGN KEY (`unit_id`) REFERENCES `organization_unit` (`id`);

--
-- Constraints for table `project_activity`
--
ALTER TABLE `project_activity`
  ADD CONSTRAINT `FK_913A8281166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_913A82814B3E2EDA` FOREIGN KEY (`milestone_id`) REFERENCES `project_milestone` (`id`),
  ADD CONSTRAINT `FK_913A8281727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `project_activity` (`id`),
  ADD CONSTRAINT `FK_913A8281B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `project_collaboration_topic`
--
ALTER TABLE `project_collaboration_topic`
  ADD CONSTRAINT `FK_DCBF5303166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_DCBF5303B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `project_deliverable`
--
ALTER TABLE `project_deliverable`
  ADD CONSTRAINT `FK_69B253BA166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_69B253BA4B3E2EDA` FOREIGN KEY (`milestone_id`) REFERENCES `project_milestone` (`id`),
  ADD CONSTRAINT `FK_69B253BAB03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `project_deliverable_status`
--
ALTER TABLE `project_deliverable_status`
  ADD CONSTRAINT `FK_A0050BD5B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_A0050BD5F3C6560A` FOREIGN KEY (`deliverable_id`) REFERENCES `project_deliverable` (`id`);

--
-- Constraints for table `project_members`
--
ALTER TABLE `project_members`
  ADD CONSTRAINT `FK_D3BEDE9A166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_D3BEDE9AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_D3BEDE9AB03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_D3BEDE9AD60322AC` FOREIGN KEY (`role_id`) REFERENCES `project_structure` (`id`);

--
-- Constraints for table `project_member_history`
--
ALTER TABLE `project_member_history`
  ADD CONSTRAINT `FK_71F9F37D166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_71F9F37DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_71F9F37DB03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `project_milestone`
--
ALTER TABLE `project_milestone`
  ADD CONSTRAINT `FK_5E90C655166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_5E90C655B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `project_milestone_status`
--
ALTER TABLE `project_milestone_status`
  ADD CONSTRAINT `FK_CEBBE6A74B3E2EDA` FOREIGN KEY (`milestone_id`) REFERENCES `project_milestone` (`id`),
  ADD CONSTRAINT `FK_CEBBE6A7B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `project_organization`
--
ALTER TABLE `project_organization`
  ADD CONSTRAINT `FK_EB49871F166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_EB49871F32C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organization` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_plan_comment`
--
ALTER TABLE `project_plan_comment`
  ADD CONSTRAINT `FK_6CFE07166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_6CFE07B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `project_plan_revision`
--
ALTER TABLE `project_plan_revision`
  ADD CONSTRAINT `FK_B797B5B0166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_B797B5B0B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `project_plan_status`
--
ALTER TABLE `project_plan_status`
  ADD CONSTRAINT `FK_7978A95166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_7978A95B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `project_resource`
--
ALTER TABLE `project_resource`
  ADD CONSTRAINT `FK_81DF7FCD166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_81DF7FCD98EC6B7B` FOREIGN KEY (`resource_type_id`) REFERENCES `resource_type` (`id`),
  ADD CONSTRAINT `FK_81DF7FCDA2B28FE8` FOREIGN KEY (`uploaded_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `project_sponsor`
--
ALTER TABLE `project_sponsor`
  ADD CONSTRAINT `FK_1792C5B112F7FB51` FOREIGN KEY (`sponsor_id`) REFERENCES `sponsor` (`id`),
  ADD CONSTRAINT `FK_1792C5B1166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_1792C5B1B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `project_structure`
--
ALTER TABLE `project_structure`
  ADD CONSTRAINT `FK_7E3D723D166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_7E3D723D9BE3208E` FOREIGN KEY (`reports_to_id`) REFERENCES `project_structure` (`id`),
  ADD CONSTRAINT `FK_7E3D723DB03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `project_structure_email_template`
--
ALTER TABLE `project_structure_email_template`
  ADD CONSTRAINT `FK_F6F926CB131A730F` FOREIGN KEY (`email_template_id`) REFERENCES `email_template` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_F6F926CBF93E4E8` FOREIGN KEY (`project_structure_id`) REFERENCES `project_structure` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_version`
--
ALTER TABLE `project_version`
  ADD CONSTRAINT `FK_2902DFA6166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_2902DFA6B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `FK_C42F7784B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `stakeholder`
--
ALTER TABLE `stakeholder`
  ADD CONSTRAINT `FK_8B9823AA166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_group`
--
ALTER TABLE `user_group`
  ADD CONSTRAINT `FK_8F02BF9D27E92E18` FOREIGN KEY (`registered_by_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_8F02BF9D896DBBDE` FOREIGN KEY (`updated_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_group_permission`
--
ALTER TABLE `user_group_permission`
  ADD CONSTRAINT `FK_4A91B1C51ED93D47` FOREIGN KEY (`user_group_id`) REFERENCES `user_group` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_4A91B1C5FED90CCA` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_user_group`
--
ALTER TABLE `user_user_group`
  ADD CONSTRAINT `FK_286579711ED93D47` FOREIGN KEY (`user_group_id`) REFERENCES `user_group` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_28657971A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
