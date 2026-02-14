/**
 * Design Editor dengan Fabric.js
 * Full jQuery Implementation
 */
(function ($) {
    "use strict";

    const DesignEditor = {
        canvases: {
            front: null,
            back: null,
            left_sleeve: null,
            right_sleeve: null,
        },
        snapshots: {
            front: null,
            back: null,
            left_sleeve: null,
            right_sleeve: null,
        },
        currentArea: "front",
        itemIndex: null,
        ukuranKaos: "M",
        warnaKaos: "putih",
        colors: {
            putih: "#FFFFFF",
            hitam: "#000000",
            merah: "#EF4444",
            biru: "#3B82F6",
            hijau: "#10B981",
            kuning: "#F59E0B",
            navy: "#1e3a8a",
            abu: "#6b7280",
        },
        csrfToken: $('meta[name="csrf-token"]').attr("content"),

        init: function (itemIndex, existingConfig) {
            console.log("===== INIT START =====");
            console.log("ItemIndex received:", itemIndex);
            console.log("ExistingConfig:", existingConfig);

            if (typeof fabric === "undefined") {
                console.error("Fabric.js not loaded!");
                alert("Error: Fabric.js belum ter-load. Refresh halaman.");
                return false;
            }

            this.itemIndex = itemIndex;

            // Dispose existing canvases if they exist
            Object.keys(this.canvases).forEach((area) => {
                if (this.canvases[area]) {
                    try {
                        this.canvases[area].dispose();
                        this.canvases[area] = null;
                        console.log("Canvas disposed:", area);
                    } catch (e) {
                        console.error("Error disposing canvas:", e);
                    }
                }
            });

            this.initCanvases();

            if (existingConfig) {
                this.loadExistingDesign(existingConfig);
            }

            this.bindEvents();
            console.log("Design Editor initialized for item:", this.itemIndex);
            return true;
        },

        initCanvases: function () {
            const areas = ["front", "back", "left_sleeve", "right_sleeve"];

            areas.forEach((area) => {
                const canvasId = "canvas-" + area;
                const canvasEl = document.getElementById(canvasId);

                if (!canvasEl) {
                    console.error("Canvas element not found:", canvasId);
                    return;
                }

                try {
                    const canvas = new fabric.Canvas(canvasId, {
                        selection: true,
                        preserveObjectStacking: true,
                    });

                    canvas.setBackgroundColor(
                        "rgba(0,0,0,0)",
                        canvas.renderAll.bind(canvas),
                    );
                    this.canvases[area] = canvas;
                    console.log("Canvas initialized:", area);
                } catch (error) {
                    console.error(
                        "Error initializing canvas " + area + ":",
                        error,
                    );
                }
            });
        },

        bindEvents: function () {
            const self = this;

            $(".area-btn")
                .off("click")
                .on("click", function () {
                    const area = $(this).data("area");
                    self.switchArea(area);
                });

            $(".color-option")
                .off("click")
                .on("click", function () {
                    const color = $(this).data("color");
                    self.changeColor(color);
                });

            $("#ukuran-kaos")
                .off("change")
                .on("change", function () {
                    self.ukuranKaos = $(this).val();
                });

            $("#btn-upload")
                .off("click")
                .on("click", function () {
                    self.uploadImage();
                });

            $("#btn-add-text")
                .off("click")
                .on("click", function () {
                    self.addText();
                });

            $("#btn-delete")
                .off("click")
                .on("click", function () {
                    self.deleteSelected();
                });
            $("#btn-bring-front")
                .off("click")
                .on("click", function () {
                    self.bringToFront();
                });
            $("#btn-send-back")
                .off("click")
                .on("click", function () {
                    self.sendToBack();
                });
            $("#btn-reset")
                .off("click")
                .on("click", function () {
                    self.resetCanvas();
                });
        },

        switchArea: function (area) {
            this.currentArea = area;

            // Handle Area Buttons Active State
            $(".area-btn").removeClass("active bg-white text-primary shadow-sm").addClass("text-slate-500 hover:text-slate-700");
            const $activeBtn = $('.area-btn[data-area="' + area + '"]');
            $activeBtn.addClass("active bg-white text-primary shadow-sm").removeClass("text-slate-500 hover:text-slate-700");

            // Handle Canvas Area Visibility
            $(".canvas-area").hide();
            $('.canvas-area[data-area="' + area + '"]').css('display', 'flex');

            this.updateSummary();
        },

        changeColor: function (colorName) {
            this.warnaKaos = colorName;
            $(".color-option").removeClass("active").find("i").remove();
            const $selected = $(
                '.color-option[data-color="' + colorName + '"]',
            );
            $selected.addClass("active");

            const iconColor = colorName === "putih" ? "#000" : "#fff";
            $selected.append(
                '<div class="absolute inset-0 flex items-center justify-center pointer-events-none"><i class="lni lni-checkmark text-xs" style="color: ' +
                iconColor +
                ';"></i></div>',
            );

            this.updateTemplateImages();
            $(".placeholder-bg").css("background", this.colors[colorName]);
            const textColor = colorName === "putih" ? "#64748b" : "#fff";
            $(".placeholder-bg span").css("color", textColor);
        },

        updateTemplateImages: function () {
            const self = this;
            const baseUrl =
                window.DesignEditorConfig && window.DesignEditorConfig.baseUrl
                    ? window.DesignEditorConfig.baseUrl
                    : "/frontend/assets/img/kaos-templates/";

            $(".kaos-template").each(function () {
                const area = $(this).data("area");
                const newSrc = baseUrl + self.warnaKaos + "-" + area + ".png";
                $(this).attr("src", newSrc);
            });
        },

        uploadImage: function () {
            const self = this;
            const fileInput = document.getElementById("upload-image");
            const file = fileInput.files[0];

            if (!file) {
                self.showAlert("Pilih file gambar terlebih dahulu", "warning");
                return;
            }

            if (!file.type.match("image.*")) {
                self.showAlert("File harus berupa gambar", "danger");
                return;
            }

            if (file.size > 10 * 1024 * 1024) {
                self.showAlert("Ukuran file maksimal 10MB", "danger");
                return;
            }

            $("#btn-upload")
                .prop("disabled", true)
                .html(
                    '<i class="lni lni-spinner-arrow spinning"></i> Uploading...',
                );

            const formData = new FormData();
            formData.append("image", file);
            formData.append("area", self.currentArea);

            $.ajax({
                url: "/customer/design-editor/upload-image",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": self.csrfToken,
                },
                success: function (response) {
                    if (response.success) {
                        // PERBAIKAN: Gunakan temp_path untuk sementara
                        const metadata = {
                            original_path: response.temp_path, // Sekarang pakai temp_path
                            original_name: response.original_name,
                            file_size: response.file_size,
                            extension: response.extension,
                        };

                        self.addImageToCanvas(
                            response.url,
                            self.currentArea,
                            metadata,
                        );
                        const imgCount = self.canvases[self.currentArea].getObjects().filter(o => o.type === 'image').length + 1;
                        self.showAlert(
                            "Gambar berhasil ditambahkan (" + imgCount + " Gbr di area ini)",
                            "success",
                        );
                        fileInput.value = "";
                    } else {
                        self.showAlert(
                            response.message || "Gagal upload gambar",
                            "danger",
                        );
                    }
                },
                error: function (xhr) {
                    const message =
                        xhr.responseJSON?.message ||
                        "Terjadi kesalahan saat upload";
                    self.showAlert(message, "danger");
                },
                complete: function () {
                    $("#btn-upload")
                        .prop("disabled", false)
                        .html(
                            '<i class="lni lni-cloud-upload"></i> Upload & Tambahkan',
                        );
                },
            });
        },

        addImageToCanvas: function (imageUrl, area, metadata) {
            const canvas = this.canvases[area];
            if (!canvas) {
                console.error("Canvas not found for area:", area);
                return;
            }

            const self = this;
            const fullUrl = imageUrl.startsWith("http")
                ? imageUrl
                : window.location.origin + imageUrl;

            // PERBAIKAN: Set default metadata dengan data lengkap
            metadata = metadata || {
                original_path: "",
                original_name: "",
                file_size: 0,
                extension: "",
            };

            fabric.Image.fromURL(
                fullUrl,
                function (img) {
                    if (!img || !img.width) {
                        console.error("Failed to load image");
                        self.showAlert(
                            "Gagal memuat gambar ke canvas",
                            "danger",
                        );
                        return;
                    }

                    const scale = Math.min(300 / img.width, 300 / img.height);
                    img.scale(scale);

                    // PERBAIKAN: Set semua custom properties dengan benar
                    img.set({
                        left: canvas.width / 2,
                        top: canvas.height / 2,
                        originX: "center",
                        originY: "center",
                        angle: 0,
                        originalFilePath: metadata.original_path,
                        originalFileName: metadata.original_name,
                        originalFileSize: metadata.file_size,
                        originalExtension: metadata.extension,
                    });

                    canvas.add(img);
                    canvas.setActiveObject(img);
                    canvas.renderAll();
                    self.updateSummary();

                    console.log("Image added with metadata:", {
                        path: metadata.original_path,
                        name: metadata.original_name,
                    });
                },
                {
                    crossOrigin: "anonymous",
                },
            );
        },

        addText: function () {
            const text = $("#text-input").val().trim();

            if (!text) {
                this.showAlert("Masukkan teks terlebih dahulu", "warning");
                return;
            }

            const canvas = this.canvases[this.currentArea];
            if (!canvas) {
                console.error(
                    "Canvas not found for current area:",
                    this.currentArea,
                );
                return;
            }

            const fabricText = new fabric.Text(text, {
                left: canvas.width / 2, // Center of 1200
                top: canvas.height / 2, // Center of 600
                originX: "center",
                originY: "center",
                fontSize: 48,
                fontFamily: "Arial",
                fill: "#000000",
                fontWeight: "bold",
            });

            canvas.add(fabricText);
            canvas.setActiveObject(fabricText);
            canvas.renderAll();

            const textCount = canvas.getObjects().filter(o => o.type === 'text').length;
            this.showAlert("Teks berhasil ditambahkan (" + textCount + " Teks di area ini)", "success");
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
                this.showAlert("Pilih objek terlebih dahulu", "warning");
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
            if (confirm("Yakin ingin reset semua desain di area ini?")) {
                const canvas = this.canvases[this.currentArea];
                if (!canvas) return;

                canvas.clear();
                canvas.renderAll();
                this.updateSummary();
                this.showAlert("Canvas berhasil direset", "info");
            }
        },

        updateSummary: function () {
            const self = this;
            const areas = ["front", "back", "left_sleeve", "right_sleeve"];

            areas.forEach(function (area) {
                const canvas = self.canvases[area];
                if (!canvas) return;

                const objects = canvas.getObjects();
                const count = objects.length;
                const $summaryItem = $('.summary-item[data-summary-area="' + area + '"]');
                const $badge = $summaryItem.find('.status-badge');

                if (count > 0) {
                    const imgCount = objects.filter(o => o.type === 'image').length;
                    const textCount = objects.filter(o => o.type === 'text').length;

                    let summaryText = "";
                    if (imgCount > 0) summaryText += imgCount + " Gbr";
                    if (textCount > 0) summaryText += (summaryText ? ", " : "") + textCount + " Teks";

                    $badge.text(summaryText)
                        .removeClass('text-slate-400 bg-white border-slate-100')
                        .addClass('text-green-600 bg-green-50 border-green-200 font-black');
                } else {
                    $badge.text('Belum ada')
                        .removeClass('text-green-600 bg-green-50 border-green-200 font-black')
                        .addClass('text-slate-400 bg-white border-slate-100');
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
                    canvasData[area] = JSON.stringify(
                        canvas.toJSON([
                            "originalFilePath",
                            "originalFileName",
                            "originalFileSize",
                            "originalExtension",
                        ]),
                    );

                    // Extract file metadata dari objects
                    const objects = canvas.getObjects();
                    fileMetadata[area] = [];

                    objects.forEach(function (obj, index) {
                        if (obj.type === "image") {
                            // PERBAIKAN: Pastikan property ada sebelum push
                            const imgMetadata = {
                                type: "image",
                                original_path:
                                    obj.originalFilePath ||
                                    obj.get("originalFilePath") ||
                                    "",
                                original_name:
                                    obj.originalFileName ||
                                    obj.get("originalFileName") ||
                                    "unknown.jpg",
                                file_size:
                                    obj.originalFileSize ||
                                    obj.get("originalFileSize") ||
                                    0,
                                extension:
                                    obj.originalExtension ||
                                    obj.get("originalExtension") ||
                                    "jpg",
                                position: {
                                    left: Math.round(obj.left || 0),
                                    top: Math.round(obj.top || 0),
                                    scaleX: obj.scaleX || 1,
                                    scaleY: obj.scaleY || 1,
                                    angle: obj.angle || 0,
                                },
                            };

                            // VALIDASI: Hanya push jika ada original_path
                            if (imgMetadata.original_path) {
                                fileMetadata[area].push(imgMetadata);
                                console.log(
                                    "Metadata extracted for " +
                                    area +
                                    " #" +
                                    index +
                                    ":",
                                    imgMetadata,
                                );
                            } else {
                                console.warn(
                                    "Image object missing originalFilePath:",
                                    obj,
                                );
                            }
                        } else if (obj.type === "text") {
                            fileMetadata[area].push({
                                type: "text",
                                text: obj.text || "",
                                fontFamily: obj.fontFamily || "Arial",
                                fontSize: obj.fontSize || 16,
                                fill: obj.fill || "#000000",
                                position: {
                                    left: Math.round(obj.left || 0),
                                    top: Math.round(obj.top || 0),
                                    angle: obj.angle || 0,
                                },
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
                    front:
                        this.canvases.front?.getObjects().length > 0 || false,
                    back: this.canvases.back?.getObjects().length > 0 || false,
                    left_sleeve:
                        this.canvases.left_sleeve?.getObjects().length > 0 ||
                        false,
                    right_sleeve:
                        this.canvases.right_sleeve?.getObjects().length > 0 ||
                        false,
                },
                snapshots: this.snapshots,
            };

            console.log("Final design config:", config);
            return config;
        },

        loadExistingDesign: function (config) {
            if (!config) return;

            this.ukuranKaos = config.ukuran_kaos || "M";
            this.warnaKaos = config.warna_kaos || "putih";

            $("#ukuran-kaos").val(this.ukuranKaos);
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
                            console.error(
                                "Error loading canvas " + area + ":",
                                e,
                            );
                        }
                    }
                });
            }

            setTimeout(function () {
                DesignEditor.updateSummary();
            }, 500);
        },

        showAlert: function (message, type) {
            type = type || "info";
            const $alert = $("#design-alert");

            // Tailwind-based styles
            const styles = {
                success: "bg-green-500 text-white",
                danger: "bg-red-500 text-white",
                warning: "bg-amber-500 text-white",
                info: "bg-primary text-white",
            };

            $alert.removeClass(
                "hidden bg-green-500 bg-red-500 bg-amber-500 bg-primary text-white",
            );
            $alert.addClass(styles[type] || styles.info);
            $("#design-alert-message").text(message);
            $alert.fadeIn().removeClass("hidden");

            setTimeout(function () {
                $alert.fadeOut();
            }, 3000);
        },

        captureAndUploadSnapshot: async function (area) {
            const self = this;
            const $areaEl = $('.canvas-area[data-area="' + area + '"]');

            if ($areaEl.length === 0) {
                console.warn("Area element not found for snapshot:", area);
                return null;
            }

            console.log("Starting snapshot capture for area:", area);

            // Pastikan area terlihat agar html2canvas bisa menangkapnya
            const originalDisplay = $areaEl.css('display');
            $areaEl.css('display', 'flex');

            try {
                // Beri jeda kecil agar browser sempat render area yang baru saja di-show
                await new Promise(resolve => setTimeout(resolve, 100));

                const canvas = await html2canvas($areaEl[0], {
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: null,
                    scale: 1,
                    logging: true // Aktifkan logging html2canvas ke console
                });

                const dataUrl = canvas.toDataURL('image/png');

                if (dataUrl === "data:,") {
                    throw new Error("Canvas is empty/blank after html2canvas");
                }

                // Upload snapshot
                const response = await $.ajax({
                    url: "/customer/design-editor/upload-snapshot",
                    type: "POST",
                    data: {
                        image: dataUrl,
                        area: area
                    },
                    headers: {
                        "X-CSRF-TOKEN": self.csrfToken,
                    }
                });

                if (response.success) {
                    console.log("Snapshot uploaded for " + area + ":", response.temp_path);
                    self.snapshots[area] = response.temp_path;
                    return response.temp_path;
                }
            } catch (error) {
                console.error("Error capturing/uploading snapshot for " + area + ":", error);
            } finally {
                $areaEl.css('display', originalDisplay);
            }
            return null;
        },

        generateAllSnapshots: async function () {
            const self = this;
            const areas = ["front", "back", "left_sleeve", "right_sleeve"];
            const activeAreas = areas.filter(area => self.canvases[area] && self.canvases[area].getObjects().length > 0);

            console.log("Generating snapshots for areas:", activeAreas);

            for (const area of activeAreas) {
                await self.captureAndUploadSnapshot(area);
            }

            console.log("All snapshots finished:", self.snapshots);
            return true;
        }
    };

    window.DesignEditor = DesignEditor;
})(jQuery);

const style = document.createElement("style");
style.textContent =
    ".spinning { animation: spin 1s linear infinite; } @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }";
document.head.appendChild(style);
