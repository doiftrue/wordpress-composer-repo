<?php

namespace WPRepo;

use GuzzleHttp\Exception\GuzzleException;

class ApiWorker
{
	public const LAST_VERS_API_URL = 'https://api.wordpress.org/core/version-check/1.7/';

	public const ALL_VERS_API_URL = 'http://api.wordpress.org/core/stable-check/1.0/';

	private HttpClient $http;

	public function __construct()
	{
		$this->http = new HttpClient();
	}

	/**
	 * @return array ASC sorted WP versions.
	 * @throws GuzzleException
	 */
	public function getBranchLastVersions(): array
	{
		$actualVers = $this->http->get(self::LAST_VERS_API_URL)->offers;

		$lastBranchVers = [];
		foreach ($actualVers as $actualVer) {
			$lastBranchVers[] = $actualVer->version;
		}

		return $lastBranchVers;
	}

	/**
	 * @return array ASC sorted WP versions.
	 * @throws GuzzleException
	 */
	public function getAllVersions(): array
	{
		$allVers = (array)$this->http->get(self::ALL_VERS_API_URL);
		$allVers = array_keys($allVers);
		$allVers = array_filter($allVers, static fn($ver) => version_compare($ver, '4.1.0', '>='));

		return $allVers;
	}

}
