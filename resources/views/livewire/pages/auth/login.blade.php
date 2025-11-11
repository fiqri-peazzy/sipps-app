<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <a href="{{ url('/') }}" class="d-flex justify-content-center">
        <img src="{{ asset('backend/assets/images/logo-dark.svg') }}" alt="logo" class="img-fluid brand-logo" />
    </a>

    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="auth-header">
                <h2 class="text-secondary mt-5"><b>Hi, Welcome Back</b></h2>
                <p class="f-16 mt-2">Enter your credentials to continue</p>
            </div>
        </div>
    </div>

    <div class="d-grid">
        <a href="{{ route('auth.google') }}" class="btn mt-2 bg-light-primary bg-light text-muted">
            <img src="{{ asset('backend/assets/images/authentication/google-icon.svg') }}" alt="google" />
            Sign In With Google
        </a>
    </div>

    <div class="saprator mt-3">
        <span>or</span>
    </div>

    <h5 class="my-4 d-flex justify-content-center">Sign in with Email address</h5>

    <form wire:submit="login">
        <div class="form-floating mb-3">
            <input wire:model="form.email" type="email" class="form-control @error('form.email') is-invalid @enderror"
                id="floatingInput" placeholder="Email address" required autofocus autocomplete="username" />
            <label for="floatingInput">Email address</label>
            @error('form.email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-floating mb-3">
            <input wire:model="form.password" type="password"
                class="form-control @error('form.password') is-invalid @enderror" id="floatingInput1"
                placeholder="Password" required autocomplete="current-password" />
            <label for="floatingInput1">Password</label>
            @error('form.password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex mt-1 justify-content-between">
            <div class="form-check">
                <input wire:model="form.remember" class="form-check-input input-primary" type="checkbox"
                    id="customCheckc1" />
                <label class="form-check-label text-muted" for="customCheckc1">Remember me</label>
            </div>
            <a href="{{ route('password.request') }}" class="text-secondary" wire:navigate>Forgot Password?</a>
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-secondary">Sign In</button>
        </div>
    </form>

    <hr />
    <div class="text-center">
        <h5>Don't have an account? <a href="{{ route('register') }}" class="text-primary" wire:navigate>Sign Up</a></h5>
    </div>
</div>
