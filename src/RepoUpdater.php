<?php

namespace Repo;

class RepoUpdater
{
	public const FILE_NAME = 'packages.json';

	public function __construct(
		private readonly string $repoDir,
		private readonly string $jsonData,
	) {
		if (!is_writable($this->repoDir)) {
			throw new \RuntimeException(
				sprintf('Repo dir is not exists or is not writeable: %s', $this->repoDir)
			);
		}
	}

	public function saveToFile(): bool
	{
		return (bool)file_put_contents("$this->repoDir/" . self::FILE_NAME, $this->jsonData);
	}
}
