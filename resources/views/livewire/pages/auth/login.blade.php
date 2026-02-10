<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        if (Auth::user()->isAdmin()) {
            $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
            return;
        }

        $this->redirectIntended(default: route('customer.dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <a href="{{ url('/') }}" style="text-decoration:none;" class="d-flex flex-column align-items-center mb-4">
        <img src="{{ asset('backend/assets/images/sipps.png') }}" alt="SIPPS Logo" class="img-fluid mb-2"
            style="max-height: 80px;" />
        <h2 class="text-secondary"><b>SIPPS Login</b></h2>
    </a>

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

        <div class="form-floating mb-3" x-data="{ show: false }">
            <input wire:model="form.password" :type="show ? 'text' : 'password'"
                class="form-control @error('form.password') is-invalid @enderror" id="floatingInput1"
                placeholder="Password" required autocomplete="current-password" />
            <label for="floatingInput1">Password</label>
            <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-secondary text-decoration-none py-0" 
                @click="show = !show" style="z-index: 10;">
                <i :class="show ? 'ti ti-eye-off' : 'ti ti-eye'" style="font-size: 1.25rem;"></i>
            </button>
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

    <div class="saprator mt-3">
        <span>or</span>
    </div>

    <div class="d-grid">
        <a href="{{ route('auth.google') }}" class="btn mt-2 bg-light-primary bg-light text-muted">
            <img src="{{ asset('backend/assets/images/authentication/google-icon.svg') }}" alt="google" />
            Sign In With Google
        </a>
    </div>



    <hr />
    <div class="text-center">
        <h5>Don't have an account? <a href="{{ route('register') }}" class="text-primary" wire:navigate>Sign Up</a></h5>
    </div>
</div>