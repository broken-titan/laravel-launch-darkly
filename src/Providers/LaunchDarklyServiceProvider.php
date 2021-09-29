<?php

    namespace BrokenTitan\LaunchDarkly\Providers;

    use BrokenTitan\LaunchDarkly\Facades\Feature;
    use BrokenTitan\LaunchDarkly\Service\Feature as FeatureService;
    use BrokenTitan\LaunchDarkly\Listeners\AuthEventSubscriber;
    use Illuminate\Support\Facades\{Blade, Event};
    use Illuminate\Foundation\Support\Providers\EventServiceProvider;
    use LaunchDarkly\Integrations\Guzzle;
    use LaunchDarkly\LDClient;

    class LaunchDarklyServiceProvider extends EventServiceProvider {
        protected $subscribe = [
            AuthEventSubscriber::class
        ];

        public function boot() {
            foreach ($this->subscribe as $subscriber) {
                Event::subscribe($subscriber);
            }
        }

        public function register() {
            $this->mergeConfigFrom(config_path("services.php"), "launchDarkly");

            $this->app->bind(LDClient::class, function() {
                $options = array_merge(["event_publisher" => Guzzle::eventPublisher()], config("services.launchDarkly.options") ?? []);
                return new LDClient(config("services.launchDarkly.key"), $options);
            });

            $this->app->bind(FeatureService::class, function($app) {
                return new FeatureService($app->make(LDClient::class));
            });

            Blade::if("feature", function(string $flag) {
                return Feature::flag($flag);
            });
        }
    }
