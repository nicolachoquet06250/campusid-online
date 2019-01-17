<?php

namespace modules\home\mvc\models;


use Dframe\Database\Database;
use Model\Model;

class Home extends Model {
	public function example() {
		/** @var Database $db */
		$db    = $this->loader->get_service_database();
		$users = $db->select('account', '*', [
			'id_account' => 1
		])->results();

		return $this->methodResult(
			true,
			[
				'data' => $users
			]
		);
	}
}