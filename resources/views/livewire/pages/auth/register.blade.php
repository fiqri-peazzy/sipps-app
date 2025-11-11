<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <a href="{{ url('/') }}" class="d-flex justify-content-center">
        <img src="{{ asset('backend/assets/images/logo-dark.svg') }}" alt="logo" class="img-fluid brand-logo" />
    </a>

    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="auth-header">
                <h2 class="text-secondary mt-5"><b>Create Account</b></h2>
                <p class="f-16 mt-2">Enter your details to get started</p>
            </div>
        </div>
    </div>

    <div class="d-grid">
        <a href="{{ route('auth.google') }}" class="btn mt-2 bg-light-primary bg-light text-muted">
            <img src="{{ asset('backend/assets/images/authentication/google-icon.svg') }}" alt="google" />
            Sign Up With Google
        </a>
    </div>

    <div class="saprator mt-3">
        <span>or</span>
    </div>

    <h5 class="my-4 d-flex justify-content-center">Sign up with Email address</h5>

    <form wire:submit="register">
        <div class="form-floating mb-3">
            <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror"
                id="floatingName" placeholder="Full Name" required autofocus autocomplete="name" />
            <label for="floatingName">Full Name</label>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-floating mb-3">
            <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror"
                id="floatingEmail" placeholder="Email address" required autocomplete="username" />
            <label for="floatingEmail">Email address</label>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-floating mb-3">
            <input wire:model="password" type="password" class="form-control @error('password') is-invalid @enderror"
                id="floatingPassword" placeholder="Password" required autocomplete="new-password" />
            <label for="floatingPassword">Password</label>
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
            <button type="submit" class="btn btn-secondary">Sign Up</button>
        </div>
    </form>

    <hr />
    <div class="text-center">
        <h5>Already have an account? <a href="{{ route('login') }}" class="text-primary" wire:navigate>Sign In</a></h5>
    </div>
</div>
