<?php

	namespace BrokenTitan\LaunchDarkly\Facades;

	use BrokenTitan\LaunchDarkly\Service\Feature as FeatureService;
	use Illuminate\Support\Facades\Facade;

	class Feature extends Facade {
	    protected static function getFacadeAccessor() {
	        return FeatureService::class;
	    }
	}