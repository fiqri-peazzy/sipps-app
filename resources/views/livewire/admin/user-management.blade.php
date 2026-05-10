<div>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manajemen User</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Manajemen Users</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="ti ti-search text-muted"></i>
                                </span>
                                <input type="text" wire:model.live="search" class="form-control border-start-0"
                                    placeholder="Cari nama atau email...">
                            </div>
                        </div>
                        <div class="col-md-8 text-end">
                            <!-- Optional: Button for adding user if needed -->
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show border-0 rounded-0" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-0" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">User</th>
                                    <th>Role</th>
                                    <th>No. HP</th>
                                    <th>Terdaftar Pada</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                                                    @if ($user->avatar)
                                                        <img src="{{ asset('storage/' . $user->avatar) }}"
                                                            alt="avatar" class="rounded-circle"
                                                            style="width: 32px; height: 32px; object-fit: cover;">
                                                    @else
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm w-auto"
                                                wire:change="changeRole({{ $user->id }}, $event.target.value)">
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>Owner</option>
                                                <option value="keuangan" {{ $user->role == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                                                <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
                                            </select>
                                        </td>
                                        <td>{{ $user->phone ?? '-' }}</td>
                                        <td>{{ $user->created_at->format('d M Y') }}</td>
                                        <td class="text-end pe-4">
                                            @if ($user->id !== auth()->id())
                                                <button wire:click="deleteUser({{ $user->id }})"
                                                    wire:confirm="Apakah Anda yakin ingin menghapus user ini?"
                                                    class="btn btn-sm btn-icon btn-light-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            @else
                                                <span class="badge bg-light-secondary text-secondary">Akun Anda</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="ti ti-users text-muted mb-2" style="font-size: 3rem;"></i>
                                            <p class="text-muted mb-0">Tidak ada user ditemukan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($users->hasPages())
                    <div class="card-footer bg-white py-3">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
