<?php

	namespace BrokenTitan\LaunchDarkly\Listeners;

	use BrokenTitan\LaunchDarkly\Facades\Feature;
	use Illuminate\Auth\Events\{Authenticated, Login, Logout};

	class AuthEventSubscriber {
		public function handleUserAuth($event) {
			Feature::setUser($event->user);
		}

		public function handleUserLogout($event) {
			Feature::setAnonymousUser();
		}

		public function subscribe($events) {
			$events->listen(Authenticated::class, [self::class, "handleUserAuth"]);
			$events->listen(Login::class, [self::class, "handleUserAuth"]);
			$events->listen(Logout::class, [self::class, "handleUserLogout"]);
		}
	}