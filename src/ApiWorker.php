<?php

namespace Repo;

use GuzzleHttp\Exception\GuzzleException;

class ApiWorker
{
	public const LAST_VERS_API_URL = 'https://api.wordpress.org/core/version-check/1.7/';

	public const ALL_VERS_API_URL = 'http://api.wordpress.org/core/stable-check/1.0/';

	public const LOWEST_VERSION = '3.3.0';

	private ApiClient $api;

	private array $versionsData;

	public function __construct()
	{
		$this->api = new ApiClient();
	}

	/**
	 * @throws \Throwable
	 */
	public function getVersionsData(): array
	{
		if (empty($this->versionsData)) {
			$this->versionsData = $this->api->get(self::LAST_VERS_API_URL)->offers;
		}

		return $this->versionsData;
	}

	/**
	 * @return string[] WP versions.
	 * @throws GuzzleException
	 * @throws \JsonException
	 */
	public function getAllVersions(): array
	{
		$allVers = (array)$this->api->get(self::ALL_VERS_API_URL);
		$allVers = array_keys($allVers);
		$allVers = array_filter($allVers, static fn($ver) => version_compare($ver, self::LOWEST_VERSION, '>='));

		return $allVers;
	}

}
