<?php

namespace Repo;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;

class WporgApiClient
{
	public const LAST_VERS_API_URL = 'https://api.wordpress.org/core/version-check/1.7/';

	public const ALL_VERS_API_URL = 'http://api.wordpress.org/core/stable-check/1.0/';

	public const LOWEST_VERSION = '4.0.0';

	private array $versionsData;

	public function __construct(
		private readonly ?GuzzleClientInterface $http = new GuzzleClient()
	) {
	}

	/**
	 * @return string[] WP versions.
	 * @throws \Throwable
	 */
	public function getAllVersions(): array
	{
		$allVers = (array)$this->httpGet(self::ALL_VERS_API_URL);
		$allVers = array_keys($allVers);
		$allVers = array_filter($allVers, static fn($ver) => version_compare($ver, self::LOWEST_VERSION, '>='));

		return $allVers;
	}

	/**
	 * @throws \Throwable
	 */
	public function getVersionsData(): array
	{
		if (empty($this->versionsData)) {
			$this->versionsData = $this->httpGet(self::LAST_VERS_API_URL)->offers;
		}

		return $this->versionsData;
	}

	public function isUrlAvailable(string $url): bool
	{
		try {
			$resp = $this->http->head($url);
		} catch (\Throwable $ex) {
			return false;
		}

		return 200 === $resp->getStatusCode();
	}

	/**
	 * @throws \Throwable
	 */
	private function httpGet(string $url): object
	{
		$res = $this->http->get($url);

		if (200 !== $res->getStatusCode()) {
			throw new \RuntimeException(
				sprintf('Bad status code: %s.', $res->getStatusCode())
			);
		}

		return json_decode($res->getBody(), false, 512, JSON_THROW_ON_ERROR);
	}
}
