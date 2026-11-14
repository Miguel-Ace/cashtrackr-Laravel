<?php

use App\Models\User;
use App\Notifications\verifyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

test('shows the registration screen', function () {
    $response = $this->get(route('register'));

    $response->assertOk();

    $response->assertStatus(200);

    $response->assertSee('Crear Cuenta');

    $response->assertSee('Registrarme');

    $response->assertSeeInOrder([
        'Crear Cuenta',
        'Registrarme'
    ]);
});

test('registers a new user as unverified and dispatches the registered event', function() {
    Event::fake();

    $response = $this->post(route('register.store'), [
        'name' => 'Miguel Angel',
        'email' => 'correo@correo.com',
        'password' => 'password',
        'password_confirmation' => 'password'
    ]);

    $response->assertRedirect(route('verification.notice'));

    $user = User::where('email','correo@correo.com')->first();

    expect($user)->not->toBeNull();
    expect($user->name)->toBe('Miguel Angel');
    expect($user->email)->toBe('correo@correo.com');
    expect($user->hasVerifiedEmail())->toBeFalse();

    Event::assertDispatched(Registered::class);
});

test('should validate required fileds when the request body is empty', function() {
    $response = $this->post(route('register.store'), []);

    $response->assertSessionHasErrors([
        'name',
        'email',
        'password'
    ]);

    $response->assertSessionHasErrors([
        'name' => 'El Nombre es Obligatorio',
        'email' => 'El E-mail es Obligatorio',
        'password' => 'La Contraseña es Obligatoria'
    ]);
});

test('prevents duplicate email addresses',function() {
    User::factory()->create([
        'email' => 'correo@correo.com'
    ]);

    $response = $this->post(route('register.store'), [
        'name' => 'Miguel Angel',
        'email' => 'correo@correo.com',
        'password' => 'password',
        'password_confirmation' => 'password'
    ]);

    $response->assertRedirect();
   
    $response->assertSessionHasErrors([
        'email' => 'Este correo ya está registrado'
    ]);
});

test('sends the verification email notification after registration', function() {
    Notification::fake();

    $response = $this->post(route('register.store'), [
        'name' => 'Miguel Angel',
        'email' => 'correo@correo.com',
        'password' => 'password',
        'password_confirmation' => 'password'
    ]);

    $user = User::where('email','correo@correo.com')->first();

    Notification::assertSentTo($user, verifyEmail::class);
});

test('verifies the user email from a signed verification link', function() {
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    $response->assertRedirect(route('dashboard'));

    expect($user->hasVerifiedEmail())->toBeTrue();
});

test('does not allow an unverified user to access the dashboard', function() {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertRedirect(route('verification.notice'));
});

test('allows a unverified user to access the dashboard', function() {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
});