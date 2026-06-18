/**
 * public/js/sweetalert.js
 * ─────────────────────────────────────────────────────────────
 * Helper EduPanel untuk SweetAlert2
 * Include di layout: <script src="{{ asset('js/sweetalert.js') }}"></script>
 * ─────────────────────────────────────────────────────────────
 */

window.EP = window.EP || {};

// ── Config default ────────────────────────────────────────────
const _swalBase = {
    customClass: {
        popup:         'swal-edupanel',
        confirmButton: 'ep-btn-primary',
        cancelButton:  'ep-btn-secondary',
        denyButton:    'ep-btn-danger',
    },
    buttonsStyling: false,
};

const _toastBase = {
    ..._swalBase,
    toast:              true,
    position:           'top-end',
    showConfirmButton:  false,
    timer:              4000,
    timerProgressBar:   true,
    didOpen(toast) {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    },
};

// ── Toast helpers ─────────────────────────────────────────────

/** EP.toast.success('Data berhasil disimpan!') */
EP.toast = {
    success(msg) {
        return Swal.fire({ ..._toastBase, icon: 'success', title: msg, iconColor: '#10B981' });
    },
    error(msg) {
        return Swal.fire({ ..._toastBase, icon: 'error',   title: msg, iconColor: '#EF4444' });
    },
    warning(msg) {
        return Swal.fire({ ..._toastBase, icon: 'warning', title: msg, iconColor: '#F59E0B' });
    },
    info(msg) {
        return Swal.fire({ ..._toastBase, icon: 'info',    title: msg, iconColor: '#6366F1' });
    },
};

// ── Confirm dialog helpers ─────────────────────────────────────

/**
 * EP.confirm.delete('Yakin hapus kursus ini?', () => { form.submit() })
 */
EP.confirm = {
    /**
     * Dialog konfirmasi hapus standar.
     * @param {string}   text      - teks konfirmasi
     * @param {Function} onConfirm - callback jika ya
     */
    delete(text = 'Data yang dihapus tidak dapat dikembalikan.', onConfirm) {
        return Swal.fire({
            ..._swalBase,
            title:              'Hapus Data?',
            text,
            icon:               'warning',
            iconColor:          '#EF4444',
            showCancelButton:   true,
            confirmButtonText:  'Ya, Hapus',
            cancelButtonText:   'Batal',
            reverseButtons:     true,
            focusCancel:        true,
        }).then(result => {
            if (result.isConfirmed && typeof onConfirm === 'function') {
                onConfirm();
            }
        });
    },

    /**
     * Dialog konfirmasi generik.
     * @param {Object} options - { title, text, confirmText, icon, iconColor, onConfirm }
     */
    custom({ title, text, confirmText = 'Ya', icon = 'question', iconColor = '#6366F1', onConfirm } = {}) {
        return Swal.fire({
            ..._swalBase,
            title,
            text,
            icon,
            iconColor,
            showCancelButton:  true,
            confirmButtonText: confirmText,
            cancelButtonText:  'Batal',
            reverseButtons:    true,
        }).then(result => {
            if (result.isConfirmed && typeof onConfirm === 'function') {
                onConfirm();
            }
        });
    },

    /**
     * Dialog konfirmasi + input teks.
     * @param {Object} options - { title, inputLabel, placeholder, onConfirm }
     */
    withInput({ title = 'Masukkan alasan', inputLabel = 'Alasan', placeholder = '', onConfirm } = {}) {
        return Swal.fire({
            ..._swalBase,
            title,
            input:              'textarea',
            inputLabel,
            inputPlaceholder:   placeholder,
            showCancelButton:   true,
            confirmButtonText:  'Kirim',
            cancelButtonText:   'Batal',
            inputValidator(value) {
                if (!value) return 'Kolom ini wajib diisi!';
            },
        }).then(result => {
            if (result.isConfirmed && typeof onConfirm === 'function') {
                onConfirm(result.value);
            }
        });
    },
};

// ── Loading helper ─────────────────────────────────────────────

/**
 * EP.loading.show('Menyimpan data...')
 * EP.loading.hide()
 */
EP.loading = {
    show(msg = 'Memproses...') {
        Swal.fire({
            ..._swalBase,
            title:             msg,
            allowOutsideClick: false,
            showConfirmButton:  false,
            didOpen() { Swal.showLoading(); },
        });
    },
    hide() {
        Swal.close();
    },
};

// ── Form delete helper ─────────────────────────────────────────

/**
 * Tambahkan ke tombol delete:
 *   <button onclick="EP.deleteForm(this)" data-form-id="delete-form-1">Hapus</button>
 *   <form id="delete-form-1" action="..." method="POST">@csrf @method('DELETE')</form>
 */
EP.deleteForm = function(btn, text = 'Data yang dihapus tidak dapat dikembalikan.') {
    const formId = btn.dataset.formId;
    EP.confirm.delete(text, () => {
        document.getElementById(formId)?.submit();
    });
};