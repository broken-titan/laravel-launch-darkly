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
			return $this->variation($flag);
		}
	}
