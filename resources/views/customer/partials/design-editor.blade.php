<div class="design-editor-container" id="designEditorContainer" data-item-index="{{ $itemIndex }}">
    <div class="row">
        <!-- Left: Canvas Preview -->
        <div class="col-lg-8">
            <div class="canvas-container">
                <div class="canvas-header">
                    <h5>Preview Desain</h5>
                    <div class="canvas-controls">
                        <!-- Area Selector -->
                        <div class="btn-group area-selector" role="group">
                            <button type="button" class="btn btn-outline-primary area-btn active" data-area="front">
                                Depan
                            </button>
                            <button type="button" class="btn btn-outline-primary area-btn" data-area="back">
                                Belakang
                            </button>
                            <button type="button" class="btn btn-outline-primary area-btn" data-area="left_sleeve">
                                Lengan Kiri
                            </button>
                            <button type="button" class="btn btn-outline-primary area-btn" data-area="right_sleeve">
                                Lengan Kanan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Canvas Preview Area -->
                <div class="canvas-preview-wrapper">
                    <!-- Front -->
                    <div class="canvas-area" data-area="front" style="display: block;">
                        <img src="{{ asset('frontend/assets/img/kaos-templates/putih-front.png') }}" alt="Kaos Depan"
                            class="kaos-template" data-area="front" onerror="this.style.display='none'">
                        <canvas id="canvas-front" width="1200" height="600"></canvas>
                    </div>

                    <!-- Back -->
                    <div class="canvas-area" data-area="back" style="display: none;">
                        <img src="{{ asset('frontend/assets/img/kaos-templates/putih-back.png') }}" alt="Kaos Belakang"
                            class="kaos-template" data-area="back" onerror="this.style.display='none'">
                        <canvas id="canvas-back" width="1200" height="600"></canvas>
                    </div>

                    <!-- Left Sleeve -->
                    <div class="canvas-area canvas-area-placeholder" data-area="left_sleeve" style="display: none;">
                        <div class="placeholder-bg" style="background: #FFFFFF;">
                            <span style="color: #64748b;">Area Lengan Kiri</span>
                        </div>
                        <canvas id="canvas-left_sleeve" width="1200" height="600"></canvas>
                    </div>

                    <!-- Right Sleeve -->
                    <div class="canvas-area canvas-area-placeholder" data-area="right_sleeve" style="display: none;">
                        <div class="placeholder-bg" style="background: #FFFFFF;">
                            <span style="color: #64748b;">Area Lengan Kanan</span>
                        </div>
                        <canvas id="canvas-right_sleeve" height="600" style="1200"></canvas>
                    </div>
                </div>

                <!-- Canvas Tools -->
                <div class="canvas-tools mt-3">
                    <div class="btn-toolbar" role="toolbar">
                        <div class="btn-group me-2" role="group">
                            <button type="button" class="btn btn-outline-secondary" id="btn-delete">
                                <i class="lni lni-trash-can"></i> Hapus
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="btn-bring-front">
                                <i class="lni lni-layers"></i> Ke Depan
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="btn-send-back">
                                <i class="lni lni-layers"></i> Ke Belakang
                            </button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-danger" id="btn-reset">
                                <i class="lni lni-reload"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Design Controls -->
        <div class="col-lg-4">
            <!-- Alert Messages -->
            <div id="design-alert" class="alert alert-dismissible fade" role="alert" style="display: none;">
                <span id="design-alert-message"></span>
                <button type="button" class="btn-close" onclick="$('#design-alert').fadeOut()"></button>
            </div>

            <!-- Kaos Settings -->
            <div class="design-control-card mb-3">
                <h6><i class="lni lni-shirt"></i> Pengaturan Kaos</h6>
                <div class="mb-3">
                    <label class="form-label">Ukuran Kaos</label>
                    <select class="form-control" id="ukuran-kaos">
                        <option value="S">S</option>
                        <option value="M" selected>M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                        <option value="XXXL">XXXL</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Warna Kaos</label>
                    <div class="color-picker-grid">
                        <div class="color-option active" data-color="putih"
                            style="background-color: #FFFFFF; border: 2px solid #ddd;" title="Putih">
                            <i class="lni lni-checkmark" style="color: #000;"></i>
                        </div>
                        <div class="color-option" data-color="hitam" style="background-color: #000000;"
                            title="Hitam"></div>

                        <div class="color-option" data-color="merah" style="background-color: #EF4444;"
                            title="Merah"></div>
                        <div class="color-option" data-color="biru" style="background-color: #3B82F6;"
                            title="Biru"></div>
                        <div class="color-option" data-color="hijau" style="background-color: #10B981;"
                            title="Hijau"></div>
                        <div class="color-option" data-color="kuning" style="background-color: #F59E0B;"
                            title="Kuning"></div>
                        <div class="color-option" data-color="navy" style="background-color: #1e3a8a;"
                            title="Navy"></div>
                        {{-- <div class="color-option" data-color="abu" style="background-color: #6b7280;"
                            title="Abu-abu"></div> --}}
                    </div>
                </div>
            </div>

            <!-- Upload Design -->
            <div class="design-control-card mb-3">
                <h6><i class="lni lni-upload"></i> Upload Desain</h6>
                <div class="mb-3">
                    <label class="form-label">Pilih File Gambar</label>
                    <input type="file" class="form-control" id="upload-image" accept="image/*">
                    <small class="text-muted">Max 10MB (JPG, PNG, GIF)</small>
                </div>
                <button type="button" class="btn btn-primary w-100" id="btn-upload">
                    <i class="lni lni-cloud-upload"></i> Upload & Tambahkan
                </button>
            </div>

            <!-- Add Text -->
            <div class="design-control-card mb-3">
                <h6><i class="lni lni-text-format"></i> Tambah Teks</h6>
                <div class="mb-3">
                    <label class="form-label">Teks</label>
                    <input type="text" class="form-control" id="text-input" placeholder="Contoh: RONALDO 7">
                </div>
                <button type="button" class="btn btn-success w-100" id="btn-add-text">
                    <i class="lni lni-plus"></i> Tambah Teks
                </button>
            </div>

            <!-- Design Summary -->
            <div class="design-control-card">
                <h6><i class="lni lni-checkmark-circle"></i> Ringkasan Desain</h6>
                <div class="design-summary">
                    <div class="summary-item" data-summary-area="front">
                        <strong>Depan:</strong>
                        <span class="badge bg-secondary">Belum ada desain</span>
                    </div>
                    <div class="summary-item" data-summary-area="back">
                        <strong>Belakang:</strong>
                        <span class="badge bg-secondary">Belum ada desain</span>
                    </div>
                    <div class="summary-item" data-summary-area="left_sleeve">
                        <strong>Lengan Kiri:</strong>
                        <span class="badge bg-secondary">Belum ada desain</span>
                    </div>
                    <div class="summary-item" data-summary-area="right_sleeve">
                        <strong>Lengan Kanan:</strong>
                        <span class="badge bg-secondary">Belum ada desain</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .design-editor-container {
        padding: 20px;
    }

    .canvas-container {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .canvas-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f1f5f9;
    }

    .canvas-header h5 {
        margin: 0;
        color: #1e293b;
        font-weight: 700;
    }

    .canvas-preview-wrapper {
        position: relative;
        background: #f8fafc;
        min-height: 600px;
        border-radius: 15px;
        overflow: hidden;
    }

    .canvas-area {
        position: relative;
        width: 100%;
        height: 600px;
    }

    .kaos-template {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        z-index: 1;
        pointer-events: none;
    }

    .canvas-area canvas {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 2;
    }

    .placeholder-bg {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        height: 400px;
        border: 3px dashed #94a3b8;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        pointer-events: none;
    }

    .placeholder-bg span {
        font-weight: 600;
        font-size: 14px;
    }

    .canvas-tools {
        padding-top: 15px;
        border-top: 2px solid #f1f5f9;
    }

    .design-control-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .design-control-card h6 {
        color: #1e293b;
        font-weight: 700;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .color-picker-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    .color-option {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .color-option:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .color-option.active {
        border: 3px solid #3b82f6 !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
    }

    .color-option i {
        font-size: 20px;
        font-weight: bold;
        display: none;
    }

    .color-option.active i {
        display: block;
    }

    .design-summary {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .summary-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        background: #f8fafc;
        border-radius: 8px;
    }
</style>
