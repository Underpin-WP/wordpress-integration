<?php

namespace Underpin_WordPress\Builders;

use Underpin\Helpers\Array_Helper;
use Underpin_WordPress\Abstracts\Builder;
use Underpin_WordPress\Enums\Compare;
use Underpin_WordPress\Enums\Database_Operator;
use Underpin_WordPress\Enums\Date_Query_Columns;
use Underpin_WordPress\Interfaces\Clause_Query_Parameter;
use UnitEnum;

class Date_Query_Parameter extends Builder implements Clause_Query_Parameter {

	private function set_date( string $key, ?int $year = null, ?int $month = null, ?int $day = null, ?int $hour = null, ?int $minute = null ): static {
		if ( ! isset( $this->args[ $key ] ) ) $this->args[ $key ] = [];

		// Set everything but the key.
		foreach ( Array_Helper::after( get_defined_vars(), 1 ) as $date => $var ) {
			if ( $var ) $this->args[ $key ][ $date ] = $var;
		}

		return $this;
	}

	public function set_before( ?int $year = null, ?int $month = null, ?int $day = null, ?int $hour = null, ?int $minute = null ): static {
		return $this->set_date( 'before', ...func_get_args() );
	}

	public function set_after( ?int $year = null, ?int $month = null, ?int $day = null, ?int $hour = null, ?int $minute = null ): static {
		return $this->set_date( 'after', ...func_get_args() );
	}

	public function set_column( Date_Query_Columns|UnitEnum $column ): static {
		return $this->set_string( 'column', $column->name );
	}

	public function set_compare( Compare|Database_Operator $compare ): static {
		return $this->set_string( 'compare', $compare->value );
	}

	public function set_inclusive( bool $inclusive ): static {
		return $this->set_bool( 'inclusive', $inclusive );
	}

	public function set_years( int ...$years ): static {
		return $this->set_varidic( 'year', 'year', $years );
	}

	public function set_months( int ...$months ): static {
		return $this->set_varidic( 'month', 'month', $months );
	}

	public function set_weeks( int ...$weeks ): static {
		return $this->set_varidic( 'week', 'week', $weeks );
	}

	public function set_hours( int ...$hours ): static {
		return $this->set_varidic( 'hour', 'hour', $hours );
	}

	public function set_minutes( int ...$minutes ): static {
		return $this->set_varidic( 'minute', 'minute', $minutes );
	}

	public function set_seconds( int ...$seconds ): static {
		return $this->set_varidic( 'second', 'second', $seconds );
	}

	public function set_days_of_year( int ...$days ): static {
		return $this->set_varidic( 'dayofyear', 'dayofyear', $days );
	}

	public function set_days_of_week( int ...$days ): static {
		return $this->set_varidic( 'dayofweek', 'dayofweek', $days );
	}

	public function set_days_of_week_iso( int ...$days ): static {
		return $this->set_varidic( 'dayofweek_iso', 'dayofweek_iso', $days );
	}

	public function set_days( int ...$days ): static {
		return $this->set_varidic( 'day', 'day', $days );
	}

}