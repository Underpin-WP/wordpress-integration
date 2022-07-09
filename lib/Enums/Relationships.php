<?php

namespace Underpin\WordPress\Enums;


enum Relationships: string {

	case or = 'OR';
	case and = 'AND';
}