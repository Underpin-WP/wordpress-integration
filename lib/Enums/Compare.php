<?php

namespace Underpin_WordPress\Enums;


enum Compare: string {

	case equals = '=';
	case not_equals = '!=';
	case greater_than = '>';
	case greater_than_equal_to = '>=';
	case less_than = '<';
	case less_than_equal_to = '<=';

}