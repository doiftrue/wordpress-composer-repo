<?php

namespace Repo;

enum RepoTypes: string
{
	case full = 'repo';
	case noContent = 'repo/no-content';
}
