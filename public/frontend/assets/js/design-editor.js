/**
 * Design Editor dengan Fabric.js
 * Full jQuery Implementation
 */
(function ($) {
    'use strict';

    const DesignEditor = {
        canvases: {
            front: null,
            back: null,
            left_sleeve: null,
            right_sleeve: null
        },
        currentArea: 'front',
        itemIndex: null,
        ukuranKaos: 'M',
        warnaKaos: 'putih',
        colors: {
            putih: '#FFFFFF',
            hitam: '#000000',
            merah: '#EF4444',
            biru: '#3B82F6',
            hijau: '#10B981',
            kuning: '#F59E0B',
            navy: '#1e3a8a',
            abu: '#6b7280'
        },
        csrfToken: $('meta[name="csrf-token"]').attr('content'),

        init: function (itemIndex, existingConfig) {
            console.log('===== INIT START =====');
            console.log('ItemIndex received:', itemIndex);
            console.log('ExistingConfig:', existingConfig);

            if (typeof fabric === 'undefined') {
                console.error('Fabric.js not loaded!');
                alert('Error: Fabric.js belum ter-load. Refresh halaman.');
                return false;
            }

            this.itemIndex = itemIndex;
            this.initCanvases();

            if (existingConfig) {
                this.loadExistingDesign(existingConfig);
            }

            this.bindEvents();
            console.log('Design Editor initialized for item:', this.itemIndex);
            return true;
        },

        initCanvases: function () {
            const areas = ['front', 'back', 'left_sleeve', 'right_sleeve'];

            areas.forEach(area => {
                const canvasId = 'canvas-' + area;
                const canvasEl = document.getElementById(canvasId);

                if (!canvasEl) {
                    console.error('Canvas element not found:', canvasId);
                    return;
                }

                try {
                    const canvas = new fabric.Canvas(canvasId, {
                        selection: true,
                        preserveObjectStacking: true
                    });

                    canvas.setBackgroundColor('rgba(0,0,0,0)', canvas.renderAll.bind(canvas));
                    this.canvases[area] = canvas;
                    console.log('Canvas initialized:', area);
                } catch (error) {
                    console.error('Error initializing canvas ' + area + ':', error);
                }
            });
        },

        bindEvents: function () {
            const self = this;

            $('.area-btn').off('click').on('click', function () {
                const area = $(this).data('area');
                self.switchArea(area);
            });

            $('.color-option').off('click').on('click', function () {
                const color = $(this).data('color');
                self.changeColor(color);
            });

            $('#ukuran-kaos').off('change').on('change', function () {
                self.ukuranKaos = $(this).val();
            });

            $('#btn-upload').off('click').on('click', function () {
                self.uploadImage();
            });

            $('#btn-add-text').off('click').on('click', function () {
                self.addText();
            });

            $('#btn-delete').off('click').on('click', function () { self.deleteSelected(); });
            $('#btn-bring-front').off('click').on('click', function () { self.bringToFront(); });
            $('#btn-send-back').off('click').on('click', function () { self.sendToBack(); });
            $('#btn-reset').off('click').on('click', function () { self.resetCanvas(); });
        },

        switchArea: function (area) {
            this.currentArea = area;
            $('.area-btn').removeClass('active');
            $('.area-btn[data-area="' + area + '"]').addClass('active');
            $('.canvas-area').hide();
            $('.canvas-area[data-area="' + area + '"]').show();
            this.updateSummary();
        },

        changeColor: function (colorName) {
            this.warnaKaos = colorName;
            $('.color-option').removeClass('active').find('i').remove();
            const $selected = $('.color-option[data-color="' + colorName + '"]');
            $selected.addClass('active');

            const iconColor = colorName === 'putih' ? '#000' : '#fff';
            $selected.append('<i class="lni lni-checkmark" style="color: ' + iconColor + ';"></i>');

            this.updateTemplateImages();
            $('.placeholder-bg').css('background', this.colors[colorName]);
            const textColor = colorName === 'putih' ? '#64748b' : '#fff';
            $('.placeholder-bg span').css('color', textColor);
        },

        updateTemplateImages: function () {
            const self = this;
            const baseUrl = '/frontend/assets/img/kaos-templates/';

            $('.kaos-template').each(function () {
                const area = $(this).data('area');
                const newSrc = baseUrl + self.warnaKaos + '-' + area + '.png';
                $(this).attr('src', newSrc);
            });
        },

        uploadImage: function () {
            const self = this;
            const fileInput = document.getElementById('upload-image');
            const file = fileInput.files[0];

            if (!file) {
                self.showAlert('Pilih file gambar terlebih dahulu', 'warning');
                return;
            }

            if (!file.type.match('image.*')) {
                self.showAlert('File harus berupa gambar', 'danger');
                return;
            }

            if (file.size > 10 * 1024 * 1024) {
                self.showAlert('Ukuran file maksimal 10MB', 'danger');
                return;
            }

            $('#btn-upload').prop('disabled', true).html('<i class="lni lni-spinner-arrow spinning"></i> Uploading...');

            const formData = new FormData();
            formData.append('image', file);
            formData.append('area', self.currentArea);

            $.ajax({
                url: '/customer/design-editor/upload-image',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': self.csrfToken
                },
                success: function (response) {
                    if (response.success) {
                        // PERBAIKAN: Gunakan temp_path untuk sementara
                        const metadata = {
                            original_path: response.temp_path, // Sekarang pakai temp_path
                            original_name: response.original_name,
                            file_size: response.file_size,
                            extension: response.extension
                        };

                        self.addImageToCanvas(response.url, self.currentArea, metadata);
                        self.showAlert('Gambar berhasil ditambahkan', 'success');
                        fileInput.value = '';
                    } else {
                        self.showAlert(response.message || 'Gagal upload gambar', 'danger');
                    }
                },
                error: function (xhr) {
                    const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat upload';
                    self.showAlert(message, 'danger');
                },
                complete: function () {
                    $('#btn-upload').prop('disabled', false).html('<i class="lni lni-cloud-upload"></i> Upload & Tambahkan');
                }
            });
        },

        addImageToCanvas: function (imageUrl, area, metadata) {
            const canvas = this.canvases[area];
            if (!canvas) {
                console.error('Canvas not found for area:', area);
                return;
            }

            const self = this;
            const fullUrl = imageUrl.startsWith('http')
                ? imageUrl
                : window.location.origin + imageUrl;

            // PERBAIKAN: Set default metadata dengan data lengkap
            metadata = metadata || {
                original_path: '',
                original_name: '',
                file_size: 0,
                extension: ''
            };

            fabric.Image.fromURL(fullUrl, function (img) {
                if (!img || !img.width) {
                    console.error('Failed to load image');
                    self.showAlert('Gagal memuat gambar ke canvas', 'danger');
                    return;
                }

                const scale = Math.min(300 / img.width, 300 / img.height);
                img.scale(scale);

                // PERBAIKAN: Set semua custom properties dengan benar
                img.set({
                    left: canvas.width / 2 - (img.width * scale) / 2,
                    top: canvas.height / 2 - (img.height * scale) / 2,
                    angle: 0,
                    originalFilePath: metadata.original_path,
                    originalFileName: metadata.original_name,
                    originalFileSize: metadata.file_size,
                    originalExtension: metadata.extension
                });

                canvas.add(img);
                canvas.setActiveObject(img);
                canvas.renderAll();
                self.updateSummary();

                console.log('Image added with metadata:', {
                    path: metadata.original_path,
                    name: metadata.original_name
                });
            }, {
                crossOrigin: 'anonymous'
            });
        },


        addText: function () {
            const text = $('#text-input').val().trim();

            if (!text) {
                this.showAlert('Masukkan teks terlebih dahulu', 'warning');
                return;
            }

            const canvas = this.canvases[this.currentArea];
            if (!canvas) {
                console.error('Canvas not found for current area:', this.currentArea);
                return;
            }

            const fabricText = new fabric.Text(text, {
                left: canvas.width / 2 - 100,
                top: canvas.height / 2 - 25,
                fontSize: 48,
                fontFamily: 'Arial',
                fill: '#000000',
                fontWeight: 'bold'
            });

            canvas.add(fabricText);
            canvas.setActiveObject(fabricText);
            canvas.renderAll();

            this.showAlert('Teks berhasil ditambahkan', 'success');
            this.updateSummary();
        },

        deleteSelected: function () {
            const canvas = this.canvases[this.currentArea];
            if (!canvas) return;

            const activeObject = canvas.getActiveObject();
            if (activeObject) {
                canvas.remove(activeObject);
                canvas.renderAll();
                this.updateSummary();
            } else {
                this.showAlert('Pilih objek terlebih dahulu', 'warning');
            }
        },

        bringToFront: function () {
            const canvas = this.canvases[this.currentArea];
            if (!canvas) return;

            const activeObject = canvas.getActiveObject();
            if (activeObject) {
                canvas.bringToFront(activeObject);
                canvas.renderAll();
            }
        },

        sendToBack: function () {
            const canvas = this.canvases[this.currentArea];
            if (!canvas) return;

            const activeObject = canvas.getActiveObject();
            if (activeObject) {
                canvas.sendToBack(activeObject);
                canvas.renderAll();
            }
        },

        resetCanvas: function () {
            if (confirm('Yakin ingin reset semua desain di area ini?')) {
                const canvas = this.canvases[this.currentArea];
                if (!canvas) return;

                canvas.clear();
                canvas.renderAll();
                this.updateSummary();
                this.showAlert('Canvas berhasil direset', 'info');
            }
        },

        updateSummary: function () {
            const areas = {
                front: 'Depan',
                back: 'Belakang',
                left_sleeve: 'Lengan Kiri',
                right_sleeve: 'Lengan Kanan'
            };

            const self = this;
            Object.keys(areas).forEach(function (area) {
                const canvas = self.canvases[area];
                if (!canvas) return;

                const hasObjects = canvas.getObjects().length > 0;
                const $summaryItem = $('.summary-item[data-summary-area="' + area + '"]');

                if (hasObjects) {
                    $summaryItem.html('<strong>' + areas[area] + ':</strong> <span class="badge bg-success"><i class="lni lni-checkmark"></i> Ada Desain</span>');
                } else {
                    $summaryItem.html('<strong>' + areas[area] + ':</strong> <span class="badge bg-secondary">Belum ada desain</span>');
                }
            });
        },

        getDesignConfig: function () {
            const canvasData = {};
            const fileMetadata = {};
            const self = this;

            Object.keys(this.canvases).forEach(function (area) {
                const canvas = self.canvases[area];
                if (canvas) {
                    // Simpan canvas JSON dengan custom properties
                    canvasData[area] = JSON.stringify(canvas.toJSON([
                        'originalFilePath',
                        'originalFileName',
                        'originalFileSize',
                        'originalExtension'
                    ]));

                    // Extract file metadata dari objects
                    const objects = canvas.getObjects();
                    fileMetadata[area] = [];

                    objects.forEach(function (obj, index) {
                        if (obj.type === 'image') {
                            // PERBAIKAN: Pastikan property ada sebelum push
                            const imgMetadata = {
                                type: 'image',
                                original_path: obj.originalFilePath || obj.get('originalFilePath') || '',
                                original_name: obj.originalFileName || obj.get('originalFileName') || 'unknown.jpg',
                                file_size: obj.originalFileSize || obj.get('originalFileSize') || 0,
                                extension: obj.originalExtension || obj.get('originalExtension') || 'jpg',
                                position: {
                                    left: Math.round(obj.left || 0),
                                    top: Math.round(obj.top || 0),
                                    scaleX: obj.scaleX || 1,
                                    scaleY: obj.scaleY || 1,
                                    angle: obj.angle || 0
                                }
                            };

                            // VALIDASI: Hanya push jika ada original_path
                            if (imgMetadata.original_path) {
                                fileMetadata[area].push(imgMetadata);
                                console.log('Metadata extracted for ' + area + ' #' + index + ':', imgMetadata);
                            } else {
                                console.warn('Image object missing originalFilePath:', obj);
                            }
                        } else if (obj.type === 'text') {
                            fileMetadata[area].push({
                                type: 'text',
                                text: obj.text || '',
                                fontFamily: obj.fontFamily || 'Arial',
                                fontSize: obj.fontSize || 16,
                                fill: obj.fill || '#000000',
                                position: {
                                    left: Math.round(obj.left || 0),
                                    top: Math.round(obj.top || 0),
                                    angle: obj.angle || 0
                                }
                            });
                        }
                    });
                }
            });

            const config = {
                ukuran_kaos: this.ukuranKaos,
                warna_kaos: this.warnaKaos,
                canvas_data: canvasData,
                file_metadata: fileMetadata,
                has_design: {
                    front: this.canvases.front?.getObjects().length > 0 || false,
                    back: this.canvases.back?.getObjects().length > 0 || false,
                    left_sleeve: this.canvases.left_sleeve?.getObjects().length > 0 || false,
                    right_sleeve: this.canvases.right_sleeve?.getObjects().length > 0 || false
                }
            };

            console.log('Final design config:', config);
            return config;
        },


        loadExistingDesign: function (config) {
            if (!config) return;

            this.ukuranKaos = config.ukuran_kaos || 'M';
            this.warnaKaos = config.warna_kaos || 'putih';

            $('#ukuran-kaos').val(this.ukuranKaos);
            this.changeColor(this.warnaKaos);

            if (config.canvas_data) {
                const self = this;
                Object.keys(config.canvas_data).forEach(function (area) {
                    const canvasJson = config.canvas_data[area];
                    const canvas = self.canvases[area];

                    if (canvasJson && canvas) {
                        try {
                            canvas.loadFromJSON(canvasJson, function () {
                                canvas.renderAll();
                            });
                        } catch (e) {
                            console.error('Error loading canvas ' + area + ':', e);
                        }
                    }
                });
            }

            setTimeout(function () {
                DesignEditor.updateSummary();
            }, 500);
        },

        showAlert: function (message, type) {
            type = type || 'info';
            const $alert = $('#design-alert');
            $alert.removeClass('alert-success alert-danger alert-warning alert-info');
            $alert.addClass('alert-' + type + ' show');
            $('#design-alert-message').text(message);
            $alert.fadeIn();

            setTimeout(function () {
                $alert.fadeOut();
            }, 3000);
        }
    };

    window.DesignEditor = DesignEditor;

})(jQuery);

const style = document.createElement('style');
style.textContent = '.spinning { animation: spin 1s linear infinite; } @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }';
document.head.appendChild(style);