<?php
class Gravatar {
	public static function url($email = null, $options = array()) {

		$hash = md5(strtolower(trim($email)));

		$query = null;

		$url = 'https://secure.gravatar.com/avatar/';

		if (!isset($options['d'])) {
			$options['d'] = 'retro';
		}

		if (!empty($options)) {
			$query = '?' . http_build_query($options);
		}

		$path = $url . $hash . '.jpg' . $query;

		return $path;
	}
}
