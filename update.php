<?php

namespace WPRepo;

require_once __DIR__ . '/vendor/autoload.php';

try {
	$api = new ApiWorker();

	$repo = new RepoDataGenerator($api->getBranchLastVersions());
	$repo->generate();

	$updater = new RepoUpdater(repoDir: __DIR__, jsonData: $repo->toJson());
	$saved = $updater->saveToFile();

	if ($saved) {
		throw new \RuntimeException('ERROR: Something went wrong: the data not saved to file.');
	}
} catch (\Throwable $ex) {
	$trace = $ex->getTraceAsString();
	echo "{$ex->getMessage()} File: {$ex->getFile()}:{$ex->getLine()}. Trace: $trace\n";

	exit(1);
}
