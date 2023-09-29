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
	$item = new RepoItemGenerator(RepoTypes::noContent, $ver);
	$url = $item->url();

	match ($api->isUrlAvailable($url)) {
		true => $goodUrls[] = $url,
		false => $badUrls[] = $url,
	};
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
