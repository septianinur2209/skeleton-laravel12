<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\User;

use Mockery;

class AuthControllerTest extends TestCase
{
    use WithoutMiddleware;

    public $master_admin_login;
    public $mockUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->master_admin_login = new User($this->userMockData());
        $this->mockUser = Mockery::mock(User::class);
        $this->app->instance(User::class, $this->mockUser);
    }

    private function userMockData(): array
    {
        return [
            "id" => 1,
            "name" => "admin",
            "email" => "admin@admin.com",
            "password" => bcrypt('password'),
            "created_at" => null,
            "updated_at" => "2021-03-23 09:02:49",
        ];
    }

    public function testIsLogin()
    {
        $response = $this->actingAs($this->master_admin_login);
        $response = auth()->check();

        $this->assertEquals(true, $response);
    }
}
