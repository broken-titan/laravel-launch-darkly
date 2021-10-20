<?php

	return [
		"launchDarkly" => [
			"key" => env("LAUNCHDARKLY_API_KEY"),
			"offline" => env("LAUNCHDARKLY_OFFLINE", false),
			"options" => []
		]
	];
