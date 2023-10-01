<?php
/** @noinspection PhpUnhandledExceptionInspection */

/**
 * Checks all no-content urls is available.
 */

namespace Repo;

$badUrls = [];
$goodUrls = [];

$api = new WporgApiClient();

foreach ($api->getAllVersions() as $ver) {
	foreach (RepoTypes::cases() as $repoType) {
		$packVer = new PackageVersion($repoType, $ver);
		$url = $packVer->url();

		match ($api->isUrlAvailable($url)) {
			true => $goodUrls[] = $url,
			false => $badUrls[] = $url,
		};
	}
}

echo "Bad Urls:\n";
/** @noinspection ForgottenDebugOutputInspection */
print_r($badUrls);

echo "Good Urls:\n";
/** @noinspection ForgottenDebugOutputInspection */
print_r($goodUrls);


if ($badUrls) {
	exit(1);
}
