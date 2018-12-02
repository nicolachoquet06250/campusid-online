<?php

/**
 * Project Name
 * Copyright (c) Firstname Lastname
 *
 * @license http://yourLicenceUrl/ (Licence Name)
 */

namespace Model;

use Dframe\Database\Database;

/**
 * Here is a description of what this file is for.
 *
 * @author First Name <adres@email>
 */

class ExampleModel extends Model
{
	public function example()
    {
		/** @var Database $db */
    	$db = $this->loader->get_service_database();
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
