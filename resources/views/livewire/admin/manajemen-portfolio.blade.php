<div>
    <x-slot name="header">
        <div class="page-header-title">
            <h2 class="mb-0">Manajemen Portfolio</h2>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Daftar Portfolio Sablon</h5>
                        </div>
                        <div class="col-auto">
                            <button wire:click="create" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Tambah Portfolio
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input wire:model.live="search" type="text" class="form-control"
                                placeholder="Cari portfolio (judul, client, atau metode)...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Gambar</th>
                                    <th>Judul / Client</th>
                                    <th>Spesifikasi</th>
                                    <th>Metode</th>
                                    <th>Unggulan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($portfolios as $index => $portfolio)
                                    <tr>
                                        <td>{{ $portfolios->firstItem() + $index }}</td>
                                        <td>
                                            @if ($portfolio->image)
                                                <img src="{{ Storage::url($portfolio->image) }}" alt="Portfolio"
                                                    class="img-thumbnail"
                                                    style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-light text-center"
                                                    style="width: 60px; height: 60px; line-height: 60px;">
                                                    <i class="ti ti-camera fs-4 text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $portfolio->title }}</strong><br>
                                            <small class="text-muted">{{ $portfolio->client_name ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <small>
                                                Bahan: {{ $portfolio->material ?? '-' }}<br>
                                                Size: {{ $portfolio->size ?? '-' }}
                                            </small>
                                        </td>
                                        <td><span class="badge bg-secondary">{{ $portfolio->method ?? '-' }}</span></td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input wire:click="toggleFeatured({{ $portfolio->id }})"
                                                    class="form-check-input" type="checkbox"
                                                    {{ $portfolio->is_featured ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input wire:click="toggleStatus({{ $portfolio->id }})"
                                                    class="form-check-input" type="checkbox"
                                                    {{ $portfolio->is_active ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <button wire:click="edit({{ $portfolio->id }})"
                                                class="btn btn-sm btn-warning">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button type="button" onclick="confirmDelete({{ $portfolio->id }})"
                                                class="btn btn-sm btn-danger">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">Tidak ada data portfolio</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $portfolios->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Modal -->
    <div wire:ignore.self class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEdit ? 'Edit' : 'Tambah' }} Portfolio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label font-weight-bold">Judul Portfolio <span
                                        class="text-danger">*</span></label>
                                <input wire:model="title" type="text"
                                    class="form-control @error('title') is-invalid @enderror"
                                    placeholder="Contoh: Sablon Kaos Panitia Event X">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Nama Client</label>
                                <input wire:model="client_name" type="text" class="form-control"
                                    placeholder="Contoh: PT. Maju Jaya">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Bahan Kaos</label>
                                <input wire:model="material" type="text" class="form-control"
                                    placeholder="Contoh: Combed 30s">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Ukuran (Area Sablon)</label>
                                <input wire:model="size" type="text" class="form-control"
                                    placeholder="Contoh: A3 / Full Print">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Metode / Jenis Sablon</label>
                                <input wire:model="method" type="text" class="form-control"
                                    placeholder="Contoh: Plastisol / DTF">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label font-weight-bold">Deskripsi Singkat</label>
                                <textarea wire:model="description" class="form-control" rows="3"
                                    placeholder="Ceritakan sedikit tentang proyek ini..."></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Foto Produk Portfolio</label>
                                <input wire:model="image" type="file"
                                    class="form-control @error('image') is-invalid @enderror">
                                <small class="text-muted">Maks. 2MB (Jpeg, Png)</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="mt-3">
                                    <div wire:loading wire:target="image">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        </div>
                                        <small>Uploading...</small>
                                    </div>
                                    @if ($image)
                                        <p class="mb-1 small font-weight-bold">Preview:</p>
                                        <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail"
                                            style="max-height: 150px;">
                                    @elseif($existing_image)
                                        <p class="mb-1 small font-weight-bold">File Sekarang:</p>
                                        <img src="{{ Storage::url($existing_image) }}" class="img-thumbnail"
                                            style="max-height: 150px;">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Setelan Tambahan</label>
                                    <div class="form-check form-switch mb-2">
                                        <input wire:model="is_featured" class="form-check-input" type="checkbox"
                                            id="featuredSwitch">
                                        <label class="form-check-label" for="featuredSwitch">Tampilkan sebagai
                                            Unggulan</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input wire:model="is_active" class="form-check-input" type="checkbox"
                                            id="activeSwitch" checked>
                                        <label class="form-check-label" for="activeSwitch">Tampilkan di Publik</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button wire:click="{{ $isEdit ? 'update' : 'store' }}" type="button" class="btn btn-primary"
                        wire:loading.attr="disabled">
                        <span wire:loading wire:target="{{ $isEdit ? 'update' : 'store' }}"
                            class="spinner-border spinner-border-sm me-1"></span>
                        {{ $isEdit ? 'Update Portfolio' : 'Simpan Portfolio' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDelete(id) {
                Swal.fire({
                    title: 'Hapus Portfolio?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete(id);
                    }
                })
            }

            window.addEventListener('show-form-modal', event => {
                $('#formModal').modal('show');
            });

            window.addEventListener('hide-form-modal', event => {
                $('#formModal').modal('hide');
            });

            window.addEventListener('show-toast', event => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: event.detail[0].message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    @endpush
</div>
