<?php

namespace WPRepo;

require_once __DIR__ . '/vendor/autoload.php';

$client = new HttpClient();

$actualVers = $client->get('https://api.wordpress.org/core/version-check/1.7/')->offers;
$allVers = (array)$client->get('http://api.wordpress.org/core/stable-check/1.0/');
$allVers = array_keys($allVers);
$allVers = array_filter($allVers, static fn($ver) => version_compare($ver, '4.5.0', '>='));

$repo = new RepoGenerator($actualVers, $allVers);
$repo->setCacheDir(__DIR__ . '/cache');
$repoJson = $repo->generateJson();

print_r($repoJson);
