<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="lni lni-reload"></i> Ajukan Return Barang</h5>
                </div>
                <div class="card-body">
                    <!-- Info Order -->
                    <div class="alert alert-info">
                        <strong>Order: {{ $order->order_number }}</strong><br>
                        <small>Tanggal Order: {{ $order->created_at->format('d M Y') }}</small><br>
                        <small>Batas Waktu Return: {{ $order->completed_at->addDays(7)->format('d M Y') }}</small>
                    </div>

                    <form wire:submit.prevent="submit">
                        <!-- Pilih Item -->
                        <div class="mb-4">
                            <label class="form-label">Pilih Item yang Ingin Di-return <span
                                    class="text-danger">*</span></label>
                            @foreach ($order->items as $item)
                                <div
                                    class="form-check mb-2 p-3 border rounded {{ $selectedItemId == $item->id ? 'bg-light' : '' }}">
                                    <input class="form-check-input" type="radio" wire:model.live="selectedItemId"
                                        value="{{ $item->id }}" id="item-{{ $item->id }}">
                                    <label class="form-check-label w-100" for="item-{{ $item->id }}">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>{{ $item->produk->jenisSablon->nama }}</strong><br>
                                                <small class="text-muted">
                                                    {{ $item->produk->ukuran->nama }} |
                                                    Qty: {{ $item->quantity }}
                                                    @if ($item->ukuran_kaos)
                                                        | Ukuran: {{ $item->ukuran_kaos }}
                                                    @endif
                                                </small>
                                            </div>
                                            <div>
                                                <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                            @error('selectedItemId')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Alasan Return -->
                        <div class="mb-4">
                            <label class="form-label">Alasan Return <span class="text-danger">*</span></label>
                            <select wire:model.live="reason" class="form-select @error('reason') is-invalid @enderror">
                                <option value="">Pilih Alasan</option>
                                <option value="wrong_size">Ukuran Salah</option>
                                <option value="wrong_color">Warna Tidak Sesuai</option>
                                <option value="print_quality">Kualitas Cetakan Buruk</option>
                                <option value="damage">Produk Rusak/Cacat</option>
                                <option value="not_as_described">Tidak Sesuai Deskripsi</option>
                                <option value="other">Lainnya</option>
                            </select>
                            @error('reason')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Detail Alasan -->
                        <div class="mb-4">
                            <label class="form-label">Jelaskan Detail Masalahnya <span
                                    class="text-danger">*</span></label>
                            <textarea wire:model="reasonDetail" class="form-control @error('reasonDetail') is-invalid @enderror" rows="4"
                                placeholder="Contoh: Cetakan buram di bagian depan, warna merah menjadi pink, dll"></textarea>
                            @error('reasonDetail')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Upload Foto Bukti -->
                        <div class="mb-4">
                            <label class="form-label">Upload Foto Bukti <span class="text-danger">*</span></label>
                            <p class="text-muted small">Upload minimal 1 foto, maksimal 5 foto. Format: JPG, PNG. Maks
                                2MB per foto.</p>

                            <input type="file" wire:model="evidencePhotos"
                                class="form-control @error('evidencePhotos.*') is-invalid @enderror" multiple
                                accept="image/jpeg,image/png,image/jpg">

                            @error('evidencePhotos.*')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror

                            <!-- Preview Uploaded Photos -->
                            @if (count($evidencePhotos) > 0)
                                <div class="row mt-3">
                                    @foreach ($evidencePhotos as $index => $photo)
                                        <div class="col-md-3 mb-2">
                                            <div class="position-relative">
                                                <img src="{{ $photo->temporaryUrl() }}" class="img-thumbnail"
                                                    style="width: 100%; height: 150px; object-fit: cover;">
                                                <button type="button" wire:click="removePhoto({{ $index }})"
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1">
                                                    <i class="lni lni-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div wire:loading wire:target="evidencePhotos" class="mt-2">
                                <small class="text-primary"><i class="lni lni-spinner-arrow spinning"></i>
                                    Uploading...</small>
                            </div>
                        </div>

                        <!-- Info Penting -->
                        <div class="alert alert-warning">
                            <h6><i class="lni lni-information"></i> Informasi Penting:</h6>
                            <ul class="mb-0">
                                <li>Pastikan foto bukti jelas dan menunjukkan masalah dengan produk</li>
                                <li>Return hanya berlaku maksimal 7 hari setelah order selesai</li>
                                <li>Barang akan diganti dengan produk baru (tidak ada refund uang)</li>
                                <li>Proses review oleh admin maksimal 2x24 jam</li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-secondary">
                                <i class="lni lni-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-warning" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="submit">
                                    <i class="lni lni-checkmark"></i> Ajukan Return
                                </span>
                                <span wire:loading wire:target="submit">
                                    <i class="lni lni-spinner-arrow spinning"></i> Memproses...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
