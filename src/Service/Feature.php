<?php

	namespace BrokenTitan\LaunchDarkly\Service;

	use Illuminate\Database\Eloquent\Model;
	use LaunchDarkly\{LDClient, LDUser, LDUserBuilder};

	class Feature {
		private LDClient $client;
		private LDUser $user;

		public function __construct(LDClient $client, ?Model $user = null) {
			if (!empty($user)) {
				$this->setUser($user);
			} else {
				$this->setAnonymousUser();
			}

			$this->client = $client;
		}

		public function setUser(Model $user) {
			$this->user = (new LDUserBuilder($user->getKey()))
				->email($user->email ?? null)
				->firstName($user->firstname ?? null)
				->lastName($user->lastname ?? null)
				->build();
		}

		public function setAnonymousUser() {
			$key = "anonymous";
            $this->user = (new LDUserBuilder($key))
            	->anonymous(true)
            	->build();
		}

		public function variation(string $flag) {
			return $this->client->variation($flag, $this->user);
		}

		public function flag(string $flag) : bool {
			return $this->bool($flag);
		}

		public function bool(string $flag) : bool {
			return filter_var($this->variation($flag), FILTER_VALIDATE_BOOLEAN);
		}

		public function string(string $flag) : string {
			return (string)$this->variation($flag);
		}

		public function number(string $flag) : float {
			return floatval($this->variation($flag));
		}

		public function json(string $flag) : array {
			return (array)$this->variation($flag);
		}
	}
