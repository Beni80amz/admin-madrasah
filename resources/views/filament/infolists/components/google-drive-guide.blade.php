<div class="google-drive-guide">
    <style>
        .google-drive-guide .guide-wrapper {
            border-radius: 1.5rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
            background: rgba(249, 250, 251, 0.5);
            padding: 2rem;
        }

        .dark .google-drive-guide .guide-wrapper {
            border-color: rgba(255, 255, 255, 0.1);
            background: rgba(15, 23, 42, 0.4);
        }

        .google-drive-guide .guide-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            font-weight: 900;
            text-transform: uppercase;
            color: #111827;
        }

        .dark .google-drive-guide .guide-title {
            color: white;
        }

        .google-drive-guide .timeline {
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .google-drive-guide .step {
            display: flex;
            gap: 1rem;
            position: relative;
        }

        .google-drive-guide .line {
            position: absolute;
            left: 0.875rem;
            top: 2rem;
            bottom: -1rem;
            width: 2px;
            background: #E5E7EB;
        }

        .dark .google-drive-guide .line {
            background: #334155;
        }

        .google-drive-guide .step-num {
            position: relative;
            display: flex;
            height: 1.75rem;
            width: 1.75rem;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            background: white;
            border: 1px solid #E5E7EB;
            font-size: 0.75rem;
            font-weight: 900;
            color: #10B981;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .dark .google-drive-guide .step-num {
            background: #1e293b;
            border-color: #334155;
        }

        .google-drive-guide .step-content {
            padding-top: 0.25rem;
            font-size: 0.75rem;
            line-height: 1.5;
            color: #4B5563;
        }

        .dark .google-drive-guide .step-content {
            color: #94A3B8;
        }
    </style>

    <div class="guide-wrapper">
        <div class="guide-title">
            <div
                style="height: 2rem; width: 2rem; display: flex; align-items: center; justify-content: center; border-radius: 0.5rem; background: rgba(16, 185, 129, 0.1);">
                <x-heroicon-o-light-bulb style="width: 18px; height: 18px; color: #10B981;" />
            </div>
            Prosedur Unggah
        </div>

        <div class="timeline">
            <div class="step">
                <div class="line"></div>
                <div class="step-num">01</div>
                <div class="step-content">
                    Buka menu <strong style="color: #111827;">Berkas Administrasi</strong> untuk mulai mengunggah
                    Dokumen.
                </div>
            </div>
            <div class="step">
                <div class="line"></div>
                <div class="step-num">02</div>
                <div class="step-content">
                    Pilih <strong style="color: #111827;">Kategori & Sub-Kategori</strong> yang sesuai dengan jenis
                    berkas Anda.
                </div>
            </div>
            <div class="step">
                <div class="step-num" style="background: #10B981; border: none; color: white;">
                    <x-heroicon-m-check style="width: 12px; height: 12px;" />
                </div>
                <div class="step-content">
                    Selesai! Berkas tersimpan di Drive dan menunggu <strong>Verifikasi Kepala Sekolah</strong>.
                </div>
            </div>
        </div>
    </div>
</div>