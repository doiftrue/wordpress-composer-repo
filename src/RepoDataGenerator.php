<?php

namespace Repo;

class RepoDataGenerator
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
		$this->wpVersions = $this->sortVers($this->wpVersions);
	}

	public function generate(): self
	{
		$this->repo = [
			'packages' => [
				self::PACKAGE_NAME => [
					...$this->collectPackages()
				]
			],
		];

		return $this;
	}

	public function getRepoData(): array
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

	private function collectPackages(): array
	{
		$packages = [];

		$this->addDevMaserVersion($packages);

		foreach ($this->wpVersions as $ver) {
			$package = new RepoPackage($this->repoType, $ver);

			$packages[$package->version] = $package->getData();
		}

		return $packages;
	}

	private function addDevMaserVersion(&$packages): void
	{
		$package = new RepoPackage($this->repoType, 'dev-master');

		// Some packages has no `dev-master` version.
		if ($package->url()) {
			$packages[$package->version] = $package->getData();
		}
	}

	private function sortVers(array $vers): array
	{
		$vers = array_unique($vers);

		usort($vers, static fn($a, $b) => version_compare($a, $b, '<') ? 1 : -1);

		return $vers;
	}

}
