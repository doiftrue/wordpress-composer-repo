<?php

namespace WPRepo;

class WPVersionUrl
{
	public const DEV_MASTER_URL = 'https://github.com/WordPress/WordPress/archive/refs/heads/master.zip';

	public const DW_URL_PATT = 'https://downloads.wordpress.org/release/wordpress-{VERSION}.zip';

	public const NO_CONTENT_DW_URL_PATT = 'https://downloads.wordpress.org/release/wordpress-{VERSION}-no-content.zip';

	public string $url;

	public string $noContentUrl;

	public string $version;

	public function __construct(array $data)
	{
		$this->version = $data['version'];
		$this->url = str_replace('{VERSION}', $data['version'], self::DW_URL_PATT);
		$this->noContentUrl = $data['hasNoContent'] ? str_replace('{VERSION}', $data['version'], self::NO_CONTENT_DW_URL_PATT) : '';
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getNoContentUrl()
	{
//		if(version_compare($this->version,
		return $this->noContentUrl;
	}

	public function getArciveType()
	{
		preg_match('~\.([^.]+)$~', $this->url, $match);
		return $match[1] ?? 'zip';
	}
}
