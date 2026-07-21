<?php

namespace Tests\Feature\Auth;

use App\Models\Auth\Otp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private string $otpCode;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('oldpassword'),
        ]);
    }

    public function test_user_can_request_password_reset_otp(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/v1/forgot-password', [
            'email' => $this->user->email,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'OTP sent successfully.',
        ]);

        $this->assertDatabaseHas('otps', [
            'user_id' => $this->user->id,
            'type' => 'password_reset',
        ]);
    }

    public function test_user_can_verify_password_reset_otp(): void
    {
        $otp = Otp::create([
            'user_id' => $this->user->id,
            'otp' => '123456',
            'type' => 'password_reset',
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->postJson('/api/v1/verify-otp', [
            'email' => $this->user->email,
            'otp' => '123456',
            'type' => 'password_reset',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Password reset verified successfully.',
        ]);

        $this->assertNotNull($otp->fresh()->used_at);
    }

    public function test_verify_otp_fails_with_invalid_otp(): void
    {
        Otp::create([
            'user_id' => $this->user->id,
            'otp' => '123456',
            'type' => 'password_reset',
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->postJson('/api/v1/verify-otp', [
            'email' => $this->user->email,
            'otp' => '999999',
            'type' => 'password_reset',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'The OTP is invalid or has expired.',
        ]);
    }

    public function test_user_can_reset_password_with_verified_otp(): void
    {
        $otp = Otp::create([
            'user_id' => $this->user->id,
            'otp' => '123456',
            'type' => 'password_reset',
            'expires_at' => now()->addMinutes(10),
            'used_at' => now(),
        ]);

        $response = $this->postJson('/api/v1/reset-password', [
            'email' => $this->user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Password reset successfully.',
        ]);

        $this->assertTrue(Hash::check('newpassword', $this->user->fresh()->password));
    }

    public function test_reset_password_fails_without_verified_otp(): void
    {
        $response = $this->postJson('/api/v1/reset-password', [
            'email' => $this->user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Please verify your OTP first.',
        ]);
    }

    public function test_reset_password_fails_with_expired_otp_verification(): void
    {
        Otp::create([
            'user_id' => $this->user->id,
            'otp' => '123456',
            'type' => 'password_reset',
            'expires_at' => now()->subMinutes(5),
            'used_at' => now()->subMinutes(15),
        ]);

        $response = $this->postJson('/api/v1/reset-password', [
            'email' => $this->user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Please verify your OTP first.',
        ]);
    }

    public function test_forgot_password_fails_with_invalid_email(): void
    {
        $response = $this->postJson('/api/v1/forgot-password', [
            'email' => 'not-an-email',
        ]);

        $response->assertStatus(422);
    }

    public function test_forgot_password_fails_with_nonexistent_email(): void
    {
        $response = $this->postJson('/api/v1/forgot-password', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_verify_otp_fails_with_invalid_email(): void
    {
        $response = $this->postJson('/api/v1/verify-otp', [
            'email' => 'nonexistent@example.com',
            'otp' => '123456',
            'type' => 'password_reset',
        ]);

        $response->assertStatus(422);
    }

    public function test_reset_password_fails_with_unconfirmed_password(): void
    {
        $otp = Otp::create([
            'user_id' => $this->user->id,
            'otp' => '123456',
            'type' => 'password_reset',
            'expires_at' => now()->addMinutes(10),
            'used_at' => now(),
        ]);

        $response = $this->postJson('/api/v1/reset-password', [
            'email' => $this->user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_full_password_reset_flow(): void
    {
        Notification::fake();

        $this->postJson('/api/v1/forgot-password', [
            'email' => $this->user->email,
        ])->assertStatus(200);

        $otpRecord = Otp::where('user_id', $this->user->id)
            ->byType('password_reset')
            ->unused()
            ->first();

        $this->assertNotNull($otpRecord);

        $this->postJson('/api/v1/verify-otp', [
            'email' => $this->user->email,
            'otp' => $otpRecord->otp,
            'type' => 'password_reset',
        ])->assertStatus(200);

        $response = $this->postJson('/api/v1/reset-password', [
            'email' => $this->user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Password reset successfully.',
        ]);

        $this->assertTrue(Hash::check('newpassword', $this->user->fresh()->password));
    }
}
