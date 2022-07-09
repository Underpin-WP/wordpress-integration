<?php

namespace Underpin\WordPress\Enums;


enum Database_Type: string {

	case numeric = 'NUMERIC';
	case binary = 'BINARY';
	case char = 'CHAR';
	case date = 'DATE';
	case datetime = 'DATETIME';
	case decimal = 'DECIMAL';
	case signed = 'SIGNED';
	case time = 'TIME';
	case unsigned = 'UNSIGNED';

}