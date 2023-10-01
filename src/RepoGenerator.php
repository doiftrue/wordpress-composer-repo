<?php

namespace Repo;

class RepoGenerator
{
	public const PACKAGE_NAME = 'wordpress/wordpress';
	public const PACKAGE_TYPE = 'wordpress-dropin';

	/**
	 * @var array Whole repository data.
	 */
	private array $repo;

	public function __construct(
		private array $wpVersions,
		private readonly RepoTypes $repoType,
	) {
	}

	public function generate(): self
	{
		$this->repo = [
			'packages' => [
				self::PACKAGE_NAME => [
					...$this->collectVersions()
				]
			],
		];

		return $this;
	}

	/**
	 * @throws \Exception
	 */
	public function saveToFile(): void
	{
		$filePath = $this->repoType->filePath();

		if (!is_writable($filePath)) {
			throw new \RuntimeException("Repo file is not writeable or exists: $filePath");
		}

		$saved = file_put_contents($filePath, $this->toJson());

		if (!$saved) {
			throw new \RuntimeException(
				"ERROR: Something went wrong: the data not saved to file: $filePath."
			);
		}
	}

	public function getRepo(): array
	{
		return $this->repo;
	}

	/**
	 * @throws \JsonException
	 */
	public function toJson(): string
	{
		return json_encode($this->repo, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	}

	private function collectVersions(): array
	{
		$repoVersions = [];
		$wpVersions = array_unique($this->wpVersions);
		$wpVersions = $this->sortWpVers($wpVersions);

		$this->addDevMaserVersion($repoVersions);

		foreach ($wpVersions as $ver) {
			$packVer = new PackageVersion($this->repoType, $ver);

			$repoVersions[$packVer->version] = $packVer->getData();
		}

		return $repoVersions;
	}

	private function addDevMaserVersion(&$repoVersions): void
	{
		$packVer = new PackageVersion($this->repoType, 'dev-master');

		// Some packages has no `dev-master` version.
		if ($packVer->url()) {
			$repoVersions[$packVer->version] = $packVer->getData();
		}
	}

	private function sortWpVers(array $wpVers): array
	{
		usort($wpVers, static fn($a, $b) => version_compare($a, $b, '<') ? 1 : -1);

		return $wpVers;
	}

}
