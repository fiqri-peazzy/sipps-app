<div>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Pengaturan Sistem</a></li>
                        <li class="breadcrumb-item active">Bobot Prioritas DPS</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Manajemen Bobot Prioritas (DPS)</h2>
                        <button wire:click="openCreateModal" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Tambah Konfigurasi
                        </button>
                    </div>
                    <p class="text-muted mt-2">Atur porsi bobot untuk setiap parameter dalam rumus <i>Dynamic Priority
                            Scheduling</i>.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-12">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5>Daftar Konfigurasi Bobot</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Konfigurasi</th>
                                    <th class="text-center">$w<sub>u</sub>$ (Urgensi)</th>
                                    <th class="text-center">$w<sub>c</sub>$ (Kompleks)</th>
                                    <th class="text-center">$w<sub>w</sub>$ (Tunggu)</th>
                                    <th class="text-center">$w<sub>q</sub>$ (Jumlah)</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($weights as $item)
                                    <tr class="{{ $item->is_active ? 'table-warning shadow-sm' : '' }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($item->is_active)
                                                    <span class="badge bg-primary me-2"><i
                                                            class="ti ti-check"></i></span>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $item->name }}</div>
                                                    <small
                                                        class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-light-danger text-danger fw-bold border border-danger">
                                                {{ number_format($item->weight_urgency * 100, 0) }}%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light-info text-info fw-bold border border-info">
                                                {{ number_format($item->weight_complexity * 100, 0) }}%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-light-warning text-warning fw-bold border border-warning">
                                                {{ number_format($item->weight_waiting_time * 100, 0) }}%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-light-success text-success fw-bold border border-success">
                                                {{ number_format($item->weight_quantity * 100, 0) }}%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if ($item->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if (!$item->is_active)
                                                <button wire:click="activate({{ $item->id }})"
                                                    class="btn btn-sm btn-outline-success" title="Aktifkan">
                                                    <i class="ti ti-circle-check"></i>
                                                </button>
                                            @endif
                                            <button wire:click="edit({{ $item->id }})"
                                                class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            @if (!$item->is_active)
                                                <button wire:click="delete({{ $item->id }})"
                                                    onclick="return confirm('Hapus konfigurasi ini?')"
                                                    class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">Belum ada konfigurasi bobot.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formula Guide Card -->
        <div class="col-md-12 mt-4">
            <div class="card bg-dark text-white overflow-hidden">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="text-white mb-3">Pedoman Persamaan Skor Prioritas</h4>
                            <p class="mb-4 opacity-75">
                                $SkorPrioritas<sub>i</sub> = (U<sub>i</sub> \times w<sub>u</sub>) + (C<sub>i</sub>
                                \times w<sub>c</sub>) + (W<sub>i</sub> \times w<sub>w</sub>) + (Q<sub>i</sub> \times
                                w<sub>q</sub>)$
                            </p>
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <div class="border-start border-danger border-4 ps-3">
                                        <small class="d-block opacity-50">Bobot Urgensi</small>
                                        <span class="fw-bold">$w<sub>u</sub>$</span>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="border-start border-info border-4 ps-3">
                                        <small class="d-block opacity-50">Bobot Kompleksitas</small>
                                        <span class="fw-bold">$w<sub>c</sub>$</span>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="border-start border-warning border-4 ps-3">
                                        <small class="d-block opacity-50">Bobot Waktu Tunggu</small>
                                        <span class="fw-bold">$w<sub>w</sub>$</span>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="border-start border-success border-4 ps-3">
                                        <small class="d-block opacity-50">Bobot Jumlah</small>
                                        <span class="fw-bold">$w<sub>q</sub>$</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <div class="bg-white rounded-circle p-4 d-inline-block shadow-lg">
                                <i class="ti ti-settings-automation text-primary" style="font-size: 64px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade @if ($showModal) show @endif" tabindex="-1"
        style="@if ($showModal) display: block; background: rgba(0,0,0,0.5); @else display: none; @endif"
        aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white">
                        {{ $isEditing ? 'Edit Konfigurasi' : 'Tambah Konfigurasi Baru' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white"
                        wire:click="$set('showModal', false)"></button>
                </div>
                <div class="modal-body p-4">
                    <form wire:submit.prevent="save">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Nama Konfigurasi</label>
                                <input type="text" wire:model="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Contoh: Fokus Deadline, Default, dll">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mt-4">
                                <h6 class="mb-3 border-bottom pb-2"><i class="ti ti-calculator me-2"></i> Pengaturan
                                    Bobot (Input dalam desimal, misal: 0.40)</h6>
                                <div class="alert alert-info py-2 small">
                                    <i class="ti ti-info-circle me-1"></i> Total jumlah keempat bobot harus
                                    <strong>tepat 1.00</strong>.
                                </div>
                                @error('weight_sum')
                                    <div class="alert alert-danger py-2 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-danger fw-bold">$w<sub>u</sub>$ Bobot Urgensi
                                    (Deadline)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" max="1"
                                        wire:model.blur="weight_urgency"
                                        class="form-control @error('weight_urgency') is-invalid @enderror">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('weight_urgency')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-info fw-bold">$w<sub>c</sub>$ Bobot Kompleksitas
                                    Desain</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" max="1"
                                        wire:model.blur="weight_complexity"
                                        class="form-control @error('weight_complexity') is-invalid @enderror">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('weight_complexity')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-warning fw-bold">$w<sub>w</sub>$ Bobot Waktu
                                    Tunggu</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" max="1"
                                        wire:model.blur="weight_waiting_time"
                                        class="form-control @error('weight_waiting_time') is-invalid @enderror">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('weight_waiting_time')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-success fw-bold">$w<sub>q</sub>$ Bobot Jumlah
                                    Pesanan</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" max="1"
                                        wire:model.blur="weight_quantity"
                                        class="form-control @error('weight_quantity') is-invalid @enderror">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('weight_quantity')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mt-3">
                                <div class="p-3 bg-light rounded d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Total Bobot Saat Ini:</span>
                                    @php
                                        $total =
                                            (float) $weight_urgency +
                                            (float) $weight_complexity +
                                            (float) $weight_waiting_time +
                                            (float) $weight_quantity;
                                    @endphp
                                    <span
                                        class="badge @if (abs($total - 1.0) < 0.001) bg-success @else bg-danger @endif fs-6">
                                        {{ number_format($total, 2) }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <label class="form-label">Catatan / Deskripsi</label>
                                <textarea wire:model="description" class="form-control" rows="3" placeholder="Opsional..."></textarea>
                            </div>
                        </div>

                        <div class="modal-footer px-0 pb-0 mt-4">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('showModal', false)">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Konfigurasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .bg-light-danger {
                background-color: rgba(220, 53, 69, 0.1);
            }

            .bg-light-info {
                background-color: rgba(13, 202, 240, 0.1);
            }

            .bg-light-warning {
                background-color: rgba(255, 193, 7, 0.1);
            }

            .bg-light-success {
                background-color: rgba(25, 135, 84, 0.1);
            }
        </style>
    @endpush
</div>
