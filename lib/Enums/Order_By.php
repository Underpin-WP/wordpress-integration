<?php

namespace Underpin_WordPress\Enums;


enum Order_By: string {

	case none = 'none';
	case name = 'name';
	case author = 'author';
	case date = 'date';
	case title = 'title';
	case modified = 'modified';
	case menu_order = 'menu_order';
	case parent = 'parent';
	case ID = 'ID';
	case random = 'rand';
	case relevance = 'relevance';
	case comment_count = 'comment_count';
	case meta_value = 'meta_value';
	case meta_value_num = 'meta_value_num';
	case post__in = 'post__in';
	case post_name__in = 'post_name__in';
	case post_parent__in = 'post_parent__in';

}