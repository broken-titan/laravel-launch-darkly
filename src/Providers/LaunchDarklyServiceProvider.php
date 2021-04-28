<?php

    namespace BrokenTitan\LaunchDarkly\Providers;

    use BrokenTitan\LaunchDarkly\Facades\Feature;
    use Illuminate\Support\Facades\Blade;
    use Illuminate\Support\ServiceProvider;
    use LaunchDarkly\Integrations\Guzzle;
    use LaunchDarkly\LDClient;

    class LaunchDarklyServiceProvider extends ServiceProvider {
        public function register() {
            $this->mergeConfigFrom(config_path("services.php"), "launchDarkly");

            $this->app->singleton(LDClient::class, function() {
                return new LDClient(config("services.launchDarkly.key"), ["event_publisher" => Guzzle::eventPublisher()]);
            });

            Blade::if("feature", function(string $flag) {
                return Feature::flag($flag);
            });
        }
    }
