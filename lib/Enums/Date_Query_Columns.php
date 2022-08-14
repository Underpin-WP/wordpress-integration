<?php

namespace Underpin\WordPress\Enums;


enum Date_Query_Columns {

	case post_date;
	case post_date_gmt;
	case post_modified;
	case post_modified_gmt;
	case comment_date;
	case comment_date_gmt;
	case user_registered;
	case registered;
	case last_updated;

}