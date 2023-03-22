<?php

	return [
		"launchDarkly" => [
			"key" => env("LAUNCHDARKLY_API_KEY", ""),
			"options" => ["offline" => env("LAUNCHDARKLY_OFFLINE", false)]
		]
	];
