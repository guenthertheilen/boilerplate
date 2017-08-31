<?php

namespace Tests;

use App\Models\User;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Facades\Mail;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp()
    {
        parent::setUp();
        Mail::fake();
    }

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        return RemoteWebDriver::create('http://localhost:9515', DesiredCapabilities::chrome());
    }

    /**
     * Get admin user
     *
     * @return \App\Models\User
     */
    protected function admin()
    {
        return User::where('name', config('scaffold.admin_name'))->first();
    }
}
