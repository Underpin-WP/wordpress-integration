<?php

namespace Underpin\WordPress\Enums;


enum Query_Return_Fields: string {

	case objects = '';
	case ids = 'ids';
	case id_parent = 'id=>parent';

}