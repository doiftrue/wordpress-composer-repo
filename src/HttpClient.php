<?php

namespace WPRepo;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class HttpClient
{
	private ?ClientInterface $client;

	public function __construct()
	{
		$this->client = new Client();
	}

	/**
	 * @throws GuzzleException
	 */
	public function get(string $url): object
	{
		$res = $this->client->request('GET', $url);

		if (200 !== $res->getStatusCode()) {
			$msg = sprintf('Bad status code: %s.', $res->getStatusCode());
			throw new \RuntimeException($msg);
		}

		return json_decode($res->getBody());
	}
}
