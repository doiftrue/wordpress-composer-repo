<?php

namespace Repo;

require_once __DIR__ . '/vendor/autoload.php';

try {
	$api = new ApiWorker();
	$allVersions = $api->getAllVersions();

	foreach (RepoTypes::cases() as $repoType) {
		$repo = new RepoDataGenerator($allVersions, $repoType);
		$repoJson = $repo->generate()->toJson();

		$updater = new RepoUpdater($repoType);
		$updater->saveToFile($repoJson);
	}
} catch (\Throwable $ex) {
	$trace = $ex->getTraceAsString();
	echo "{$ex->getMessage()} File: {$ex->getFile()}:{$ex->getLine()}. Trace: $trace\n";

	exit(1);
}
