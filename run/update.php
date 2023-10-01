<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace Repo;

$api = new WporgApiClient();
$allVersions = $api->getAllVersions();

foreach (RepoTypes::cases() as $repoType) {
	$repo = new RepoGenerator($allVersions, $repoType);
	$repo->generate()->saveToFile();
}

