<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace Repo;

$api = new WporgApiClient();
$allVersions = $api->getAllVersions();

foreach (RepoTypes::cases() as $repoType) {
	$repo = new RepoDataGenerator($allVersions, $repoType);
	$repoJson = $repo->generate()->toJson();

	$updater = new RepoUpdater($repoType);
	$updater->saveToFile($repoJson);
}

