<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[DatabaseMigrations::class])) {
            $this->runDatabaseMigrations();
        }
        $this->validator = app()->get('validator');
    }

    public function passportAs($user, $scopes = [], $guard = 'api')
    {
        Passport::actingAs($user, $scopes, $guard);
        return $this;
    }

    protected function validate($mockedRequestData, $rules)
    {
        return $this->validator
            ->make($mockedRequestData, $rules)
            ->passes();
    }

    public function getToken($user)
    {
        $logged_in_user = $this->post('api/v1/login', [
            'phone' => $user->phone,
            'password' => PASSWORD,
            'type' => LOGIN_INFO_TYPES['TEACHER'],
        ])->assertSuccessful();
        return  json_decode($logged_in_user->getContent())->data->access_token;

    }
}
