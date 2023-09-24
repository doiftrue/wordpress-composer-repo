<?php

namespace WPRepo;

class RepoDataGenerator
{
	public const PACKAGE_NAME = 'wordpress/wordpress';
	public const PACKAGE_TYPE = 'wordpress-dropin';

	/**
	 * @var array Whole repository data.
	 */
	private array $data;

	public function __construct(
		private array $versionsArray,
	) {
		$this->versionsArray = $this->sortVers($this->versionsArray);
	}

	public function generate(): self
	{
		$this->data = [
			'packages' => [
				self::PACKAGE_NAME => [
					...$this->collectItems()
				]
			]
		];

		return $this;
	}

	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * @throws \JsonException
	 */
	public function toJson(): string
	{
		return json_encode($this->data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	}

	private function collectItems(): array
	{
		$sourceUrl = new SourceUrl(version: null);

		$items = [
			$sourceUrl->version => $this->generateItem($sourceUrl)
		];

		foreach ($this->versionsArray as $ver) {
			$sourceUrl = new SourceUrl($ver);
			$packageVer = $this->packageVer($ver);

			// If there are for example 6.3.1 and 6.3 versions the array
			// index for both will be `6.3.x` and 6.3 will leave acactual. So handle this.
			if (isset($items[$packageVer])) {
				continue;
			}

			$items[$packageVer] = $this->generateItem($sourceUrl);
			$items["$packageVer-patch"] = $this->generateItem($sourceUrl, noContent: true);
		}

		return $items;
	}

	private function generateItem(SourceUrl $source, bool $noContent = false): array
	{
		return [
			'name' => self::PACKAGE_NAME,
			'type' => self::PACKAGE_TYPE,
			'version' => $source->version . ($noContent ? '-patch' : ''),
			'dist' => [
				'url' => $noContent ? $source->noContentUrl() : $source->url(),
				'type' => $source->archiveType(),
			]
		];
	}

	private function packageVer(?string $ver): string
	{
		[$major, $minor] = explode('.', $ver);

		return "$major.$minor.x";
	}

	private function sortVers(array $vers): array
	{
		$vers = array_unique($vers);

		usort($vers, static fn($a, $b) => version_compare($a, $b, '<') ? 1 : -1);

		return $vers;
	}

}
