<?php

namespace Repo;

class RepoItemGenerator
{
	public const DEV_MASTER_URL = 'https://github.com/WordPress/WordPress/archive/refs/heads/master.zip';

	public const FULL_URL_PATT = 'https://downloads.wordpress.org/release/wordpress-{VERSION}.zip';

	public const NO_CONTENT_URL_PATT = 'https://downloads.wordpress.org/release/wordpress-{VERSION}-no-content.zip';

	public function __construct(
		private readonly RepoTypes $repoType,
		public readonly string $version = 'dev-master',
	) {
	}

	public function generateItem(): array
	{
		$url = ($this->repoType === RepoTypes::noContent) ? $this->noContentUrl() : $this->url();

		return [
			'name' => RepoDataGenerator::PACKAGE_NAME,
			'type' => RepoDataGenerator::PACKAGE_TYPE,
			'version' => $this->version,
			'dist' => [
				'url' => $url,
				'type' => $this->archiveType(),
			]
		];
	}

	private function isDevMasterVersion(): bool
	{
		return 'dev-master' === $this->version;
	}

	private function url(): string
	{
		if ($this->isDevMasterVersion()) {
			return self::DEV_MASTER_URL;
		}

		return str_replace('{VERSION}', $this->version, self::FULL_URL_PATT);
	}

	private function noContentUrl(): string
	{
		if ($this->isDevMasterVersion()) {
			return ''; // `no-content` has no `dev-master` URL
		}

		return str_replace('{VERSION}', $this->version, self::NO_CONTENT_URL_PATT);
	}

	private function archiveType(): string
	{
		$url = explode('?', $this->url())[0];
		preg_match('~\.([^.]+)$~', $url, $match);

		return $match[1] ?? 'zip';
	}
}
