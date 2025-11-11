<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink($this->only('email'));

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <a href="{{ url('/') }}" class="d-flex justify-content-center">
        <img src="{{ asset('backend/assets/images/logo-dark.svg') }}" alt="logo" class="img-fluid brand-logo" />
    </a>

    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="auth-header">
                <h2 class="text-secondary mt-5"><b>Forgot Password?</b></h2>
                <p class="f-16 mt-2">Enter your email to reset your password</p>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="sendPasswordResetLink">
        <div class="form-floating mb-3">
            <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror"
                id="floatingInput" placeholder="Email address" required autofocus />
            <label for="floatingInput">Email address</label>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-secondary">Send Reset Link</button>
        </div>
    </form>

    <hr />
    <div class="text-center">
        <h5>Remember your password? <a href="{{ route('login') }}" class="text-primary" wire:navigate>Back to Login</a>
        </h5>
    </div>
</div>
