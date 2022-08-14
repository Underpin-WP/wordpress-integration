<?php

namespace Underpin\WordPress\Enums;


enum Capabilities {

	case switch_themes;
	case edit_themes;
	case edit_theme_options;
	case install_themes;
	case activate_plugins;
	case edit_plugins;
	case install_plugins;
	case edit_users;
	case edit_files;
	case manage_options;
	case moderate_comments;
	case manage_categories;
	case manage_links;
	case upload_files;
	case import;
	case unfiltered_html;
	case edit_posts;
	case edit_others_posts;
	case edit_published_posts;
	case publish_posts;
	case edit_pages;
	case read;
	case publish_pages;
	case edit_others_pages;
	case edit_published_pages;
	case delete_pages;
	case delete_others_pages;
	case delete_published_pages;
	case delete_posts;
	case delete_others_posts;
	case delete_published_posts;
	case delete_private_posts;
	case edit_private_posts;
	case read_private_posts;
	case delete_private_pages;
	case edit_private_pages;
	case read_private_pages;
	case delete_users;
	case create_users;
	case unfiltered_upload;
	case edit_dashboard;
	case customize;
	case delete_site;
	case update_plugins;
	case delete_plugins;
	case update_themes;
	case update_core;
	case list_users;
	case remove_users;
	case add_users;
	case promote_users;
	case delete_themes;
	case export;
	case edit_comment;
	case create_sites;
	case delete_sites;
	case manage_network;
	case manage_sites;
	case manage_network_users;
	case manage_network_themes;
	case manage_network_options;
	case manage_network_plugins;
	case upload_plugins;
	case upload_themes;
	case upgrade_network;
	case setup_network;

}