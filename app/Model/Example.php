<?php

/**
 * Project Name
 * Copyright (c) Firstname Lastname
 *
 * @license http://yourLicenceUrl/ (Licence Name)
 */

namespace Model;

/**
 * Here is a description of what this file is for.
 *
 * @author First Name <adres@email>
 */

class ExampleModel extends Model
{
	public function example()
    {
		$users = $this->loader->get_db()->select('account', '*', [
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
