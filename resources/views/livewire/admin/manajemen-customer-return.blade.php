<div>
    <!-- Header Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="text-white">Pending Review</h6>
                    <h3 class="text-white mb-0">{{ $stats['pending'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white">Disetujui</h6>
                    <h3 class="text-white mb-0">{{ $stats['approved'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="text-white">Ditolak</h6>
                    <h3 class="text-white mb-0">{{ $stats['rejected'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="text-white">Selesai</h6>
                    <h3 class="text-white mb-0">{{ $stats['completed'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending Review</option>
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                        <option value="completed">Selesai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Alasan Return</label>
                    <select wire:model.live="reasonFilter" class="form-select">
                        <option value="">Semua Alasan</option>
                        <option value="wrong_size">Ukuran Salah</option>
                        <option value="wrong_color">Warna Tidak Sesuai</option>
                        <option value="print_quality">Kualitas Cetakan</option>
                        <option value="damage">Rusak/Cacat</option>
                        <option value="not_as_described">Tidak Sesuai Deskripsi</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                        placeholder="No. Order / Nama Customer">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button wire:click="resetFilters" class="btn btn-secondary w-100">
                        <i class="ti ti-refresh"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Returns -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Order</th>
                            <th>Customer</th>
                            <th>Item</th>
                            <th>Alasan</th>
                            <th>Tanggal Ajuan</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $return)
                            <tr>
                                <td>
                                    <strong>{{ $return->order->order_number }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $return->created_at->format('d M Y') }}</small>
                                </td>
                                <td>
                                    {{ $return->user->name }}
                                    <br>
                                    <small class="text-muted">{{ $return->order->penerima_telepon }}</small>
                                </td>
                                <td>
                                    <strong>{{ $return->orderItem->produk->jenisSablon->nama }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $return->orderItem->produk->ukuran->nama }} |
                                        Qty: {{ $return->orderItem->quantity }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $return->reason_label }}</span>
                                </td>
                                <td>
                                    {{ $return->created_at->format('d M Y, H:i') }}
                                    <br>
                                    <small class="text-muted">{{ $return->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $return->status_color }}">
                                        {{ $return->status_label }}
                                    </span>
                                    @if ($return->reviewed_at)
                                        <br>
                                        <small class="text-muted">
                                            oleh {{ $return->reviewedBy->name }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <button wire:click="showDetail({{ $return->id }})" class="btn btn-sm btn-info"
                                        title="Detail">
                                        <i class="ti ti-eye"></i>
                                    </button>

                                    @if ($return->status === 'pending')
                                        <button wire:click="openApprovalModal({{ $return->id }}, 'approve')"
                                            class="btn btn-sm btn-success" title="Approve">
                                            <i class="ti ti-check"></i>
                                        </button>
                                        <button wire:click="openApprovalModal({{ $return->id }}, 'reject')"
                                            class="btn btn-sm btn-danger" title="Reject">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="ti ti-inbox" style="font-size: 48px; opacity: 0.3;"></i>
                                    <p class="text-muted mt-2">Tidak ada data return</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $returns->links() }}
            </div>
        </div>
    </div>
    <!-- Modal Detail Return -->
    @if ($showDetailModal && $selectedReturn)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Return Request</h5>
                        <button type="button" class="btn-close" wire:click="$set('showDetailModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Info Order -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Informasi Order</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>No. Order:</strong></div>
                                    <div class="col-sm-8">{{ $selectedReturn->order->order_number }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Customer:</strong></div>
                                    <div class="col-sm-8">
                                        {{ $selectedReturn->user->name }}<br>
                                        <small class="text-muted">{{ $selectedReturn->user->email }}</small>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Status Order:</strong></div>
                                    <div class="col-sm-8">
                                        <span class="badge bg-{{ $selectedReturn->order->status_color }}">
                                            {{ $selectedReturn->order->status_label }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Tanggal Completed:</strong></div>
                                    <div class="col-sm-8">
                                        @if ($selectedReturn->order->completed_at)
                                            {{ $selectedReturn->order->completed_at->format('d M Y, H:i') }}
                                            <small
                                                class="text-muted">({{ $selectedReturn->order->completed_at->diffForHumans() }})</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Item -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Item yang Di-return</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Produk:</strong></div>
                                    <div class="col-sm-8">{{ $selectedReturn->orderItem->produk->jenisSablon->nama }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Ukuran:</strong></div>
                                    <div class="col-sm-8">{{ $selectedReturn->orderItem->produk->ukuran->nama }}</div>
                                </div>
                                @if ($selectedReturn->orderItem->ukuran_kaos)
                                    <div class="row mb-2">
                                        <div class="col-sm-4"><strong>Ukuran Kaos:</strong></div>
                                        <div class="col-sm-8">{{ $selectedReturn->orderItem->ukuran_kaos }}</div>
                                    </div>
                                @endif
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Quantity:</strong></div>
                                    <div class="col-sm-8">{{ $selectedReturn->orderItem->quantity }} pcs</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Harga:</strong></div>
                                    <div class="col-sm-8">Rp
                                        {{ number_format($selectedReturn->orderItem->subtotal, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Alasan Return -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Alasan Return</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Kategori:</strong>
                                    <span class="badge bg-secondary ms-2">{{ $selectedReturn->reason_label }}</span>
                                </div>
                                <div class="mb-3">
                                    <strong>Detail Masalah:</strong>
                                    <p class="mb-0 mt-2 p-3 bg-light rounded">{{ $selectedReturn->reason_detail }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Foto -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Bukti Foto</h6>
                            </div>
                            <div class="card-body">
                                @if ($selectedReturn->evidence_photos && count($selectedReturn->evidence_photos) > 0)
                                    <div class="row">
                                        @foreach ($selectedReturn->evidence_photos as $photo)
                                            <div class="col-md-4 mb-3">
                                                <a href="{{ Storage::url($photo) }}" target="_blank">
                                                    <img src="{{ Storage::url($photo) }}" class="img-thumbnail"
                                                        style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;"
                                                        alt="Evidence Photo">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">Tidak ada foto bukti</p>
                                @endif
                            </div>
                        </div>

                        <!-- Status & Review -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Status & Review</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Status:</strong></div>
                                    <div class="col-sm-8">
                                        <span class="badge bg-{{ $selectedReturn->status_color }}">
                                            {{ $selectedReturn->status_label }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Tanggal Ajuan:</strong></div>
                                    <div class="col-sm-8">{{ $selectedReturn->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>

                                @if ($selectedReturn->reviewed_at)
                                    <hr>
                                    <div class="row mb-2">
                                        <div class="col-sm-4"><strong>Direview oleh:</strong></div>
                                        <div class="col-sm-8">{{ $selectedReturn->reviewedBy->name }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4"><strong>Tanggal Review:</strong></div>
                                        <div class="col-sm-8">{{ $selectedReturn->reviewed_at->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                    @if ($selectedReturn->admin_notes)
                                        <div class="row mb-2">
                                            <div class="col-sm-4"><strong>Catatan Admin:</strong></div>
                                            <div class="col-sm-8">
                                                <p class="mb-0 p-3 bg-light rounded">
                                                    {{ $selectedReturn->admin_notes }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @if ($selectedReturn->replacement_order_item_id)
                                    <hr>
                                    <div class="alert alert-success mb-0">
                                        <h6><i class="ti ti-check-circle"></i> Item Pengganti</h6>
                                        <p class="mb-1">
                                            <strong>Order:</strong>
                                            {{ $selectedReturn->replacementItem->order->order_number }}<br>
                                            <strong>Status Produksi:</strong>
                                            <span
                                                class="badge bg-info">{{ ucfirst($selectedReturn->replacementItem->production_status) }}</span>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if ($selectedReturn->status === 'pending')
                            <button type="button"
                                wire:click="openApprovalModal({{ $selectedReturn->id }}, 'approve')"
                                class="btn btn-success">
                                <i class="ti ti-check"></i> Setujui Return
                            </button>
                            <button type="button"
                                wire:click="openApprovalModal({{ $selectedReturn->id }}, 'reject')"
                                class="btn btn-danger">
                                <i class="ti ti-x"></i> Tolak Return
                            </button>
                        @endif
                        <button type="button" class="btn btn-secondary" wire:click="$set('showDetailModal', false)">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Approval -->
    @if ($showApprovalModal && $selectedReturn)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div
                        class="modal-header bg-{{ $approvalAction === 'approve' ? 'success' : 'danger' }} text-white">
                        <h5 class="modal-title">
                            <i class="ti ti-{{ $approvalAction === 'approve' ? 'check' : 'x' }}"></i>
                            {{ $approvalAction === 'approve' ? 'Setujui Return' : 'Tolak Return' }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                            wire:click="$set('showApprovalModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-{{ $approvalAction === 'approve' ? 'success' : 'danger' }}">
                            <strong>Order:</strong> {{ $selectedReturn->order->order_number }}<br>
                            <strong>Item:</strong> {{ $selectedReturn->orderItem->produk->jenisSablon->nama }}<br>
                            <strong>Customer:</strong> {{ $selectedReturn->user->name }}
                        </div>

                        @if ($approvalAction === 'approve')
                            <div class="alert alert-info">
                                <h6><i class="ti ti-info-circle"></i> Proses Approval:</h6>
                                <ul class="mb-0">
                                    <li>Item pengganti akan dibuat otomatis</li>
                                    <li>Masuk ke queue produksi dengan <strong>priority tinggi</strong></li>
                                    <li>Deadline: <strong>7 hari</strong> dari sekarang</li>
                                    <li>Customer akan menerima notifikasi</li>
                                </ul>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <h6><i class="ti ti-alert-triangle"></i> Peringatan:</h6>
                                <p class="mb-0">Return akan ditolak dan order akan kembali ke status
                                    <strong>completed</strong>. Pastikan alasan penolakan jelas.
                                </p>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Catatan Admin
                                {{ $approvalAction === 'reject' ? '(Wajib)' : '(Opsional)' }}</label>
                            <textarea wire:model="adminNotes" class="form-control @error('adminNotes') is-invalid @enderror" rows="3"
                                placeholder="Berikan catatan untuk customer..."></textarea>
                            @error('adminNotes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmApproval" required>
                            <label class="form-check-label" for="confirmApproval">
                                Saya yakin akan {{ $approvalAction === 'approve' ? 'menyetujui' : 'menolak' }} return
                                request ini
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="$set('showApprovalModal', false)">
                            Batal
                        </button>
                        <button type="button" wire:click="processApproval"
                            class="btn btn-{{ $approvalAction === 'approve' ? 'success' : 'danger' }}"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="processApproval">
                                <i class="ti ti-{{ $approvalAction === 'approve' ? 'check' : 'x' }}"></i>
                                {{ $approvalAction === 'approve' ? 'Setujui' : 'Tolak' }}
                            </span>
                            <span wire:loading wire:target="processApproval">
                                <i class="ti ti-loader spinning"></i> Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Overlay -->
    {{-- <div wire:loading.flex
        class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
        style="background: rgba(0,0,0,0.3); z-index: 9999;">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div> --}}

    @push('styles')
        <style>
            .spinning {
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('alert', (data) => {
                    const alertType = data[0].type || 'info';
                    const alertMessage = data[0].message || 'Notification';

                    // Buat toast notification
                    const toastDiv = document.createElement('div');
                    toastDiv.className = `alert alert-${alertType} alert-dismissible fade show position-fixed`;
                    toastDiv.style.cssText = 'top: 20px; right: 20px; z-index: 99999; min-width: 300px;';
                    toastDiv.innerHTML = `
                    <i class="ti ti-${alertType === 'success' ? 'check' : 'info'}-circle"></i> 
                    ${alertMessage}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                    document.body.appendChild(toastDiv);

                    setTimeout(() => {
                        toastDiv.remove();
                    }, 5000);
                });
            });
        </script>
    @endpush
</div>
