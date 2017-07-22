<?php


namespace Modules\DB;

use Modules\DB\DataBase as DB;

class Factory
{
	public function __construct(DB $db)
	{
		return $db;
	}
}