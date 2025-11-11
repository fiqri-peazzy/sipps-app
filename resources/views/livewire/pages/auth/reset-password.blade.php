<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset($this->only('email', 'password', 'password_confirmation', 'token'), function ($user) {
            $user
                ->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])
                ->save();

            event(new PasswordReset($user));
        });

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <a href="{{ url('/') }}" class="d-flex justify-content-center">
        <img src="{{ asset('backend/assets/images/logo-dark.svg') }}" alt="logo" class="img-fluid brand-logo" />
    </a>

    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="auth-header">
                <h2 class="text-secondary mt-5"><b>Reset Password</b></h2>
                <p class="f-16 mt-2">Enter your new password</p>
            </div>
        </div>
    </div>

    <form wire:submit="resetPassword">
        <div class="form-floating mb-3">
            <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror"
                id="floatingEmail" placeholder="Email address" required autofocus autocomplete="username" />
            <label for="floatingEmail">Email address</label>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-floating mb-3">
            <input wire:model="password" type="password" class="form-control @error('password') is-invalid @enderror"
                id="floatingPassword" placeholder="New Password" required autocomplete="new-password" />
            <label for="floatingPassword">New Password</label>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-floating mb-3">
            <input wire:model="password_confirmation" type="password" class="form-control" id="floatingPasswordConfirm"
                placeholder="Confirm Password" required autocomplete="new-password" />
            <label for="floatingPasswordConfirm">Confirm Password</label>
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-secondary">Reset Password</button>
        </div>
    </form>
</div>
