<?php

namespace Underpin_WordPress\Enums;


enum Relationships: string {

	case or = 'OR';
	case and = 'AND';
}