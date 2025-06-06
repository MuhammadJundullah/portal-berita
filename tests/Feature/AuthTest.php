<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(RefreshDatabase::class);

test('user can register and gets authenticated automatically', function () {
    // Data registrasi
    $userData = [
        'name' => 'john_doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];

    // Kirim request POST
    $response = $this->post('/register', $userData);

    // Assertions
    $response->assertRedirect(route('home')); // Ganti 'home' dengan route tujuan setelah registrasi

    // Cek user terbuat di database
    $this->assertDatabaseHas('users', [
        'name' => 'john_doe',
        'email' => 'john@example.com'
    ]);

    // Cek password di-hash
    $user = User::firstWhere('email', 'john@example.com');
    $this->assertTrue(Hash::check('password123', $user->password));

    // Cek user terautentikasi
    $this->assertAuthenticatedAs($user);
});

test('registration fails with invalid data', function () {
    // Data tidak valid (password tidak match)
    $response = $this->post('/register', [
        'name' => 'john_doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'different_password'
    ]);

    $response->assertRedirect()
        ->assertSessionHasErrors('password'); 

    $this->assertDatabaseCount('users', 0); 
    $this->assertGuest();
});



