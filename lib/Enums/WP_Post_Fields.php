<?php

namespace Underpin_WordPress\Enums;


enum WP_Post_Fields: string {

	case id = 'ID';
	case author = 'post_author';
	case date = 'post_date';
	case date_gmt = 'post_date_gmt';
	case content = 'post_content';
	case title = 'post_title';
	case excerpt = 'post_excerpt';
	case status = 'post_status';
	case comment_status = 'comment_status';
	case ping_status = 'ping_status';
	case password = 'post_password';
	case name = 'post_name';
	case to_ping = 'to_ping';
	case pinged = 'pinged';
	case modified = 'post_modified';
	case modified_gmt = 'post_modified_gmt';
	case content_filtered = 'post_content_filtered';
	case parent = 'post_parent';
	case guid = 'guid';
	case menu_order = 'menu_order';
	case type = 'post_type';
	case mime_type = 'post_mime_type';
	case comment_count = 'comment_count';

}