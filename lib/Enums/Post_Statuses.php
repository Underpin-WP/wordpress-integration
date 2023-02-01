<?php

namespace Underpin\WordPress\Enums;


enum Post_Statuses {

	case publish;
	case future;
	case draft;
	case pending;
	case private;

}