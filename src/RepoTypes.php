<?php

namespace Repo;

enum RepoTypes: string
{
	case full = 'repo';
	case newBundled = 'repo/new-bundled';
	case noContent = 'repo/no-content';

	public function filePath(): string
	{
		return ROOT_DIR . "/$this->value/packages.json";
	}
}
