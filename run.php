<?php
/**
 * Usage:
 *     $ php run.php update
 *     $ php run.php check
 */

define('ROOT_DIR', __DIR__);

require_once ROOT_DIR . '/vendor/autoload.php';

$action = $argv[1] ?? null;

try {
	match ($action) {
		'check' => require ROOT_DIR . '/run/check.php',
		'update' => require ROOT_DIR . '/run/update.php',
		default => throw new \RuntimeException('Set action. One of: `check`, `update`.')
	};
} catch (\Throwable $ex) {
	echo "{$ex->getMessage()} File: {$ex->getFile()}:{$ex->getLine()}. Trace: {$ex->getTraceAsString()}\n";

	exit(1);
}
