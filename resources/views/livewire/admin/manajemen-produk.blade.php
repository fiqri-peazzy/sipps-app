<div>
    <x-slot name="header">
        <div class="page-header-title">
            <h2 class="mb-0">Manajemen Produk</h2>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Daftar Produk Sablon</h5>
                        </div>
                        <div class="col-auto">
                            <button wire:click="create" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Tambah Produk
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input wire:model.live="search" type="text" class="form-control"
                                placeholder="Cari produk...">
                        </div>
                        <div class="col-md-3">
                            <select wire:model.live="filterJenis" class="form-select">
                                <option value="">Semua Jenis Sablon</option>
                                @foreach ($jenisSablons as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select wire:model.live="filterTipe" class="form-select">
                                <option value="">Semua Tipe Layanan</option>
                                <option value="regular">Regular</option>
                                <option value="express">Express</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Sablon</th>
                                    <th>Ukuran</th>
                                    <th>Tipe Layanan</>
                                    <th>Harga</th>
                                    <th>Estimasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produks as $index => $produk)
                                    <tr>
                                        <td>{{ $produks->firstItem() + $index }}</td>
                                        <td>{{ $produk->jenisSablon->nama }}</td>
                                        <td><span class="badge bg-secondary">{{ $produk->ukuran->nama }}</span></td>
                                        <td>
                                            @if ($produk->tipe_layanan === 'express')
                                                <span class="badge bg-danger">Express</span>
                                            @else
                                                <span class="badge bg-success">Regular</span>
                                            @endif
                                        </td>
                                        <td>{{ $produk->formatted_harga }}</td>
                                        <td>{{ $produk->estimasi_hari }} hari</td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input wire:click="toggleStatus({{ $produk->id }})"
                                                    class="form-check-input" type="checkbox"
                                                    {{ $produk->is_active ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <button wire:click="edit({{ $produk->id }})"
                                                class="btn btn-sm btn-warning">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button type="button"
                                                wire:click="$dispatch('confirm-delete', { id: {{ $produk->id }} })"
                                                class="btn btn-sm btn-danger">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data produk</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $produks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEdit ? 'Edit' : 'Tambah' }} Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Sablon</label>
                                <select wire:model="jenis_sablon_id"
                                    class="form-select @error('jenis_sablon_id') is-invalid @enderror">
                                    <option value="">Pilih Jenis Sablon</option>
                                    @foreach ($jenisSablons as $jenis)
                                        <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                                    @endforeach
                                </select>
                                @error('jenis_sablon_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ukuran</label>
                                <select wire:model="ukuran_id"
                                    class="form-select @error('ukuran_id') is-invalid @enderror">
                                    <option value="">Pilih Ukuran</option>
                                    @foreach ($ukurans as $ukuran)
                                        <option value="{{ $ukuran->id }}">{{ $ukuran->nama }}</option>
                                    @endforeach
                                </select>
                                @error('ukuran_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipe Layanan</label>
                                <select wire:model="tipe_layanan"
                                    class="form-select @error('tipe_layanan') is-invalid @enderror">
                                    <option value="regular">Regular</option>
                                    <option value="express">Express</option>
                                </select>
                                @error('tipe_layanan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga (Rp)</label>
                                <input wire:model="harga" type="number"
                                    class="form-control @error('harga') is-invalid @enderror"
                                    placeholder="Masukkan harga">
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Estimasi Waktu (Jam)</label>
                                <input wire:model="estimasi_waktu" type="number"
                                    class="form-control @error('estimasi_waktu') is-invalid @enderror"
                                    placeholder="Contoh: 24 untuk 1 hari">
                                @error('estimasi_waktu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input wire:model="is_active" class="form-check-input" type="checkbox"
                                        id="statusSwitch">
                                    <label class="form-check-label" for="statusSwitch">Aktif</label>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea wire:model="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3"
                                    placeholder="Masukkan deskripsi produk"></textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button wire:click="{{ $isEdit ? 'update' : 'store' }}" type="button" class="btn btn-primary">
                        {{ $isEdit ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('confirm-delete', (data) => {
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Data ini akan dihapus permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('deleteConfirmed', {
                            id: data.id
                        });
                    }
                });
            });
        });
        $(function() {


            // show modal
            window.addEventListener('show-form-modal', function() {
                var $el = $('#formModal');
                if ($el.length) {
                    var modalEl = $el[0];
                    var myModal = new bootstrap.Modal(modalEl);
                    myModal.show();
                }
            });

            // hide modal
            window.addEventListener('hide-form-modal', function() {
                var $el = $('#formModal');
                if ($el.length) {
                    var modalEl = $el[0];
                    var myModal = bootstrap.Modal.getInstance(modalEl);
                    if (myModal) myModal.hide();
                }
            });

            // toast via SweetAlert2 (using jQuery available in layout)
            window.addEventListener('show-toast', function(e) {
                console.log(e.detail[0]);
                var message = (e && e.detail[0] && e.detail[0].message) ? e.detail[0].message : '';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        // theme: 'dark',
                        position: 'top-end',
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    // fallback
                    alert(message);
                }
            });

            // optional: re-init after Livewire DOM updates
            Livewire.hook('message.processed', function() {
                // re-init plugins if needed
            });
        });
    </script>
@endpush
