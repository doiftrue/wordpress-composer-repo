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
		private array $versionsArray,
		private readonly RepoTypes $repoType,
	) {
		$this->versionsArray = $this->sortVers($this->versionsArray);
	}

	public function generate(): self
	{
		$this->repo = [
			'packages' => [
				self::PACKAGE_NAME => [
					...$this->collectItems()
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

	private function collectItems(): array
	{
		$items = [];

		$this->add_dev_maser_version($items);

		foreach ($this->versionsArray as $ver) {
			$item = new RepoItemGenerator($this->repoType, $ver);

			$items[$item->version] = $item->generateItem();
		}

		return $items;
	}

	private function add_dev_maser_version(&$items): void
	{
		// NOTE: RepoTypes::noContent has no `dev-master` version.
		if ($this->repoType === RepoTypes::noContent) {
			return;
		}

		$item = new RepoItemGenerator($this->repoType, 'dev-master');
		$items = [
			$item->version => $item->generateItem()
		];
	}

	private function sortVers(array $vers): array
	{
		$vers = array_unique($vers);

		usort($vers, static fn($a, $b) => version_compare($a, $b, '<') ? 1 : -1);

		return $vers;
	}

}
