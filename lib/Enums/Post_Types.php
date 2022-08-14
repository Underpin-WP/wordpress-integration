<?php

namespace Underpin\WordPress\Enums;

enum Post_Types {
	case post;
	case page;
	case attachment;
	case revision;
	case navigation_menu;
	case wp_template;
	case wp_template_part;
}