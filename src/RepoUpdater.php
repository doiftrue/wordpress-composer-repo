<?php

namespace Repo;

class RepoUpdater
{
	public const FILE_NAME = 'packages.json';

	public readonly string $saveFilePath;

	public function __construct(
		private readonly RepoTypes $repoType,
	) {
		$this->setSaveFilePath();
	}

	public function setSaveFilePath(): void
	{
		$this->saveFilePath = sprintf('%s/%s/%s', ROOT_DIR, $this->repoType->value, self::FILE_NAME);

		if (!is_writable($this->saveFilePath)) {
			throw new \RuntimeException(
				sprintf('Repo file is not exists or is not writeable: %s', $this->saveFilePath)
			);
		}
	}

	/**
	 * @throws \RuntimeException
	 */
	public function saveToFile(string $json): void
	{
		$saved = file_put_contents($this->saveFilePath, $json);

		if (!$saved) {
			throw new \RuntimeException(
				"ERROR: Something went wrong: the data not saved to file: $this->saveFilePath."
			);
		}
	}
}
