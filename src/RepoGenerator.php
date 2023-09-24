<?php

namespace WPRepo;

class RepoGenerator
{
	private string $cacheDir;

	public function __construct(
		private array $actualVers,
		private array $allVers,
	) {
	}

	public function setCacheDir(string $dir)
	{
		$this->cacheDir = $dir;
	}

	public function generateJson(): string
	{
		$repo = [
			'packages' => [
				'wordpress/wordpress' => [
					'dev-master' => [
						'name' => 'wordpress/wordpress',
						'version' => 'dev-master',
						'dist' => [
							'url' => WPVersionUrl::DEV_MASTER_URL,
							'type' => 'zip'
						]
					],
					...$this->getItems()
				]
			]
		];

		return json_encode($repo, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
	}

	private function getItems(): array
	{
		$items = [];
		foreach ($this->allVers as $ver) {
			$WPVersion = new WPVersionUrl([
				'version' => $ver,
				'hasNoContent' => false,
				'url' => '',
				'archiveType' => 'zip',
			]);

			[$major, $minor] = explode('.', $ver);

			$items["$major.$minor.x"] = [
				'name' => 'wordpress/wordpress',
				'version' => $ver,
				'dist' => [
					'url' => $WPVersion->url,
					'type' => 'zip'
				]
			];
		}

		return $items;
	}

}
