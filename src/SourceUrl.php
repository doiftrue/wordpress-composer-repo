<?php

namespace Repo;

class SourceUrl
{
	public const DEV_MASTER_URL = 'https://github.com/WordPress/WordPress/archive/refs/heads/master.zip';

	public const FULL_URL_PATT = 'https://downloads.wordpress.org/release/wordpress-{VERSION}.zip';

	public const NO_CONTENT_URL_PATT = 'https://downloads.wordpress.org/release/wordpress-{VERSION}-no-content.zip';

	public function __construct(public ?string $version = null)
	{
		if (!$this->version) {
			$this->version = 'dev-master';
		}
	}

	public function url(): string
	{
		if ('dev-master' === $this->version) {
			return self::DEV_MASTER_URL;
		}

		return str_replace('{VERSION}', $this->version, self::FULL_URL_PATT);
	}

	public function noContentUrl(): string
	{
		return str_replace('{VERSION}', $this->version, self::NO_CONTENT_URL_PATT);
	}

	public function archiveType(): string
	{
		$url = explode('?', $this->url())[0];
		preg_match('~\.([^.]+)$~', $url, $match);

		return $match[1] ?? 'zip';
	}
}
