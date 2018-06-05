<?php
	function send_data($method, $url, $myvars) {
		if ($method == 'GET') {
			$url .= '?' . $myvars;
		}

		$ch = curl_init($url);

		if ($method == 'POST') {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
		}

		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);

		return $response;
	}
