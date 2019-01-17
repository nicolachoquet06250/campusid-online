<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 06/12/2018
 * Time: 21:28
 */

namespace loggers;


use Dframe\custom\Logger\traits\log;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class mail {
	use log;
	private $to, $from_email, $from_name, $object, $charset, $mailer;
	protected                             $messages = [];
	private $default_charset = 'UTF-8';
	private $is_log = true;

	/**
	 * mail constructor.
	 *
	 * @param array $options
	 * @throws Exception
	 */
	public function __construct(array $options) {
		$this->to = is_array($options['to']) ? $options['to'] : explode(',', $options['to']);
		$this->charset = isset($options['charset']) ? $options['charset'] : $this->default_charset;
		$this->from_email = 'nicolachoquet06250@gmail.com';
		$this->from_name = 'Nicolas Choquet';
		$this->object = 'Logs du '.$this->get_date();
		$this->mailer    = new PHPMailer;

		$this->set_mailer_infos();
	}

	/**
	 * @throws Exception
	 */
	private function set_mailer_infos() {
		$this->mailer->isSMTP();
		$this->mailer->Host       = 'smtp.gmail.com';
		$this->mailer->Port       = 587;
		$this->mailer->SMTPSecure = 'tls';
		$this->mailer->SMTPAuth   = true;
		$this->mailer->Username   = $this->from_email;
		$this->mailer->Password   = '12042107NicolasChoquet2669!';
		$this->mailer->setFrom($this->from_email, $this->from_name);
		$this->mailer->CharSet = $this->charset;
		foreach ($this->to as $to) $this->mailer->addAddress($to);
		$this->mailer->Subject = $this->object;
	}

	/**
	 * @param $msg
	 * @param array $params
	 * @throws Exception
	 */
	public function log($msg, $params = []) {
		if(!empty($params)) {
			foreach ($params as $param => $value) {
				$this->$param = $value;
			}
			$this->set_mailer_infos();
		}
		$this->messages[] = $msg;
	}

	/**
	 * @throws Exception
	 * @throws \Exception
	 */
	public function send() {
		$messages = '';
		foreach ($this->messages as $msg) {
			$messages .= ($this->is_log ? '<b>'.$this->get_date_text_format().'</b>'.$this->get_complete_domain().'=> ' : '').$msg.'<br>';
		}
		$this->mailer->msgHTML($messages);
		if (!$this->mailer->send()) throw new \Exception('Mailer Error: '.$this->mailer->ErrorInfo."\n");
	}
}