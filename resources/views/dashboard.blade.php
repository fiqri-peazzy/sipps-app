<x-app-layout>
    <x-slot name="header">
        <div class="page-header-title">
            <h5 class="m-b-10">Dashboard</h5>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Welcome to Dashboard</h5>
                </div>
                <div class="card-body">
                    <p>You're logged in as <strong>{{ Auth::user()->name }}</strong></p>
                    <p>Email: {{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
