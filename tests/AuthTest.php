<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function testUserCannotLogin()
    {
        $response = $this->post('/api/auth/login', [
            'email' => 'a@a.com',
            'password' => '12345',
        ]);

        $response->assertResponseStatus(401);
    }

    public function testUserCannotRegisterWithoutPassword()
    {
        $response = $this->post('/api/auth/register', [
            'email' => 'a@a.com',
            'password' => '',
            'name' => 'Test'
        ]);

        $response->assertResponseStatus(422);
    }

    public function testUserCanRegister()
    {
        $response = $this->post('/api/auth/register', [
            'email' => 'a@a.com',
            'password' => '12345',
            'name' => 'Test'
        ]);

        $response->assertResponseStatus(200);
    }

    /**
     * @depends testUserCanRegister
     */
    public function testUserCanLoginAfterRegistering()
    {
        $response = $this->post('/api/auth/register', [
            'email' => 'a@a.com',
            'password' => '12345',
            'name' => 'Test'
        ]);

        $response->assertResponseStatus(200);

        $response = $this->post('/api/auth/login', [
            'email' => 'a@a.com',
            'password' => '12345'
        ]);

        $response->assertResponseStatus(200);
    }

    public function testUserCannotLoginWithWrongPassword()
    {
        $response = $this->post('/api/auth/register', [
            'email' => 'a@a.com',
            'password' => '12345',
            'name' => 'Test'
        ]);

        $response->assertResponseStatus(200);

        $response = $this->post('/api/auth/login', [
            'email' => 'a@a.com',
            'password' => '54321'
        ]);

        $response->assertResponseStatus(401);
    }

    public function testUserCannotGetMeWithoutRegistering()
    {
        $response = $this->post('/api/auth/me', []);

        $response->assertResponseStatus(401);
    }

    public function testUserCanGetMe()
    {
        $response = $this->post('/api/auth/register', [
            'email' => 'a@a.com',
            'password' => '12345',
            'name' => 'Test'
        ]);

        $response->assertResponseStatus(200);

        $accessToken = json_decode($response->response->getContent(), true)['access_token'];

        $response = $this->post('/api/auth/me', [], ["authorization" => "Bearer " . $accessToken]);

        $response->assertResponseStatus(200);
    }

    public function testUserFullFlow()
    {
        // Register
        $response = $this->post('/api/auth/register', [
            'email' => 'a@a.com',
            'password' => '12345',
            'name' => 'Test'
        ]);
        $response->assertResponseStatus(200);

        // Login
        $response = $this->post('/api/auth/login', [
            'email' => 'a@a.com',
            'password' => '12345'
        ]);
        $response->assertResponseStatus(200);
        $accessToken = json_decode($response->response->getContent(), true)['access_token'];

        // Get me
        $response = $this->post('/api/auth/me', [], ["authorization" => "Bearer " . $accessToken]);
        $response->assertResponseStatus(200);

        // Refresh token 
        $response = $this->post('/api/auth/refresh', [], ["authorization" => "Bearer " . $accessToken]);
        $response->assertResponseStatus(200);
        $accessToken = json_decode($response->response->getContent(), true)['access_token'];

        // Get me (with the new token)
        $response = $this->post('/api/auth/me', [], ["authorization" => "Bearer " . $accessToken]);
        $response->assertResponseStatus(200);

        // Logout
        $response = $this->post('/api/auth/logout', [], ["authorization" => "Bearer " . $accessToken]);
        $response->assertResponseStatus(200);
    }
}
