<?php

	namespace BrokenTitan\LaunchDarkly\Service;

	use Illuminate\Auth\Authenticatable;
	use LaunchDarkly\{LDClient, LDUser, LDUserBuilder};

	class Feature {
		private LDClient $client;
		private LDUser $user;

		public function __construct(LDClient $client) {
			if (auth()->check()) {
				$user = auth()->user();
				$user = (new LDUserBuilder($user->getKey()))
					->email($user->email ?? null)
					->firstName($user->firstname ?? null)
					->lastName($user->lastname ?? null)
					->build();
            } else {
            	$key = "anonymous";
                $user = (new LDUserBuilder($key))
                	->anonymous(true)
                	->build();
            }

            $this->client = $client;
            $this->user = $user;
		}

		public function flag(string $flag) : bool {
			return $this->client->variation($flag, $this->user);
		}
	}