<?php

namespace Underpin\WordPress\Enums;


enum Database_Operator: string {

case like = 'LIKE';
case not_like = 'NOT LIKE';
case in = 'IN';
case not_in = 'NOT IN';
case regexp = 'REGEXP';
case not_regexp = 'NOT REGEXP';
case rlike = 'RLIKE';
case exists = 'EXISTS';
case not_exists = 'NOT EXISTS';
case between = 'BETWEEN';
case not_between = 'NOT BETWEEN';

}