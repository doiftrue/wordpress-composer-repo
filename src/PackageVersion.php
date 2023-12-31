<?php

namespace Repo;

class PackageVersion
{
	public const DEV_MASTER_URL = 'https://github.com/WordPress/WordPress/archive/refs/heads/master.zip';

	public const FULL_URL_PATT = 'https://downloads.wordpress.org/release/wordpress-{VERSION}.zip';

	public const NEW_BUNDLED_URL_PATT = 'https://downloads.wordpress.org/release/wordpress-{VERSION}-new-bundled.zip';

	public const NO_CONTENT_URL_PATT = 'https://downloads.wordpress.org/release/wordpress-{VERSION}-no-content.zip';

	public function __construct(
		private readonly RepoTypes $repoType,
		public readonly string $version = 'dev-master',
	) {
	}

	public function getData(): array
	{
		return [
			'name' => RepoGenerator::PACKAGE_NAME,
			'type' => RepoGenerator::PACKAGE_TYPE,
			'version' => $this->version,
			'dist' => [
				'url' => $this->url(),
				'type' => $this->archiveType(),
			]
		];
	}

	public function url(): string
	{
		return match ($this->repoType) {
			RepoTypes::full => $this->fullUrl(),
			RepoTypes::noContent => $this->noContentUrl(),
			RepoTypes::newBundled => $this->newBundledUrl(),
		};
	}

	public function archiveType(): string
	{
		$url = explode('?', $this->url())[0];
		preg_match('~\.([^.]+)$~', $url, $match);

		return $match[1] ?? 'zip';
	}

	private function fullUrl(): string
	{
		if ($this->isDevMasterVersion()) {
			return self::DEV_MASTER_URL;
		}

		return str_replace('{VERSION}', $this->version, self::FULL_URL_PATT);
	}

	private function newBundledUrl(): string
	{
		if ($this->isDevMasterVersion()) {
			return ''; // `no-content` has no `dev-master` URL
		}

		return str_replace('{VERSION}', $this->version, self::NEW_BUNDLED_URL_PATT);
	}

	private function noContentUrl(): string
	{
		if ($this->isDevMasterVersion()) {
			return ''; // `no-content` has no `dev-master` URL
		}

		return str_replace('{VERSION}', $this->version, self::NO_CONTENT_URL_PATT);
	}

	private function isDevMasterVersion(): bool
	{
		return 'dev-master' === $this->version;
	}
}
