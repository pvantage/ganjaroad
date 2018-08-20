<?php namespace Oneweb\Extensions;

use Cartalyst\Sentinel\Checkpoints\CheckpointInterface;
use Cartalyst\Sentinel\Checkpoints\AuthenticatedCheckpoint;
use Cartalyst\Sentinel\Users\UserInterface;

use Oneweb\Extensions\NotPublishedException;
use App\Helpers\Template;
use Lang;

class PublishedCheckpoint implements CheckpointInterface
{
	use AuthenticatedCheckpoint;

	public function __construct()
	{
	}

	public function login(UserInterface $user)
	{
		return $this->checkPublished($user);
	}

	public function check(UserInterface $user)
	{
		return $this->checkPublished($user);
	}

	protected function checkPublished(UserInterface $user)
	{
		$published = $user->published;
        //$require_approval = (bool)Template::getSetting('user_approval');

        if (/*$require_approval && */!$published) {
			$exception = new NotPublishedException(Lang::get('auth.not_approved'));

			$exception->setUser($user);

			throw $exception;
		}
	}
}
