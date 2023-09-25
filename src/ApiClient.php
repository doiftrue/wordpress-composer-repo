<?php

namespace Repo;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient
{
	private ?ClientInterface $client;

	public function __construct()
	{
		$this->client = new Client();
	}

	/**
	 * @throws GuzzleException
	 * @throws \JsonException
	 * @throws \RuntimeException
	 */
	public function get(string $url): object
	{
		$res = $this->client->request('GET', $url);

		if (200 !== $res->getStatusCode()) {
			throw new \RuntimeException(
				sprintf('Bad status code: %s.', $res->getStatusCode())
			);
		}

		return json_decode($res->getBody(), false, 512, JSON_THROW_ON_ERROR);
	}
}
