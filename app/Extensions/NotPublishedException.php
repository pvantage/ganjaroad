<?php namespace Oneweb\Extensions;

use Cartalyst\Sentinel\Users\UserInterface;
use RuntimeException;

class NotPublishedException extends RuntimeException
{
	protected $user;

	public function getUser()
	{
		return $this->user;
	}

	public function setUser(UserInterface $user)
	{
		$this->user = $user;
	}
}
