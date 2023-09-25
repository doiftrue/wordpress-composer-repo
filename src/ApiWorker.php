<?php

namespace Repo;

use GuzzleHttp\Exception\GuzzleException;

class ApiWorker
{
	public const LAST_VERS_API_URL = 'https://api.wordpress.org/core/version-check/1.7/';

	public const ALL_VERS_API_URL = 'http://api.wordpress.org/core/stable-check/1.0/';

	public const LOWEST_VERSION = '4.1.0';

	private ApiClient $api;

	public function __construct()
	{
		$this->api = new ApiClient();
	}

	/**
	 * @return string[] WP versions.
	 * @throws GuzzleException
	 */
	public function getLastVersions(): array
	{
		$actualVers = $this->api->get(self::LAST_VERS_API_URL)->offers;

		$lastBranchVers = [];
		foreach ($actualVers as $actualVer) {
			$lastBranchVers[] = $actualVer->version;
		}

		return $lastBranchVers;
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
