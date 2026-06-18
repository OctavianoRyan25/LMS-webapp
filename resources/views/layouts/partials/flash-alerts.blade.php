{{-- layouts/partials/flash-alerts.blade.php --}}

{{--
| Auto-trigger SweetAlert2 dari Laravel session flash.
|
| Cara pakai di Controller:
|   return redirect()->back()->with('success', 'Data berhasil disimpan!');
|   return redirect()->back()->with('error', 'Terjadi kesalahan.');
|   return redirect()->back()->with('warning', 'Perhatian, data duplikat!');
|   return redirect()->back()->with('info', 'Sesi Anda akan berakhir.');
--}}

@if(session()->hasAny(['success', 'error', 'warning', 'info']))
<script>
document.addEventListener('DOMContentLoaded', function () {

    const toastConfig = {
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    };

    @if(session('success'))
    Swal.fire({
        ...toastConfig,
        icon: 'success',
        title: @json(session('success')),
        iconColor: '#10B981',
        customClass: { popup: 'swal-edupanel' }
    });
    @endif

    @if(session('error'))
    Swal.fire({
        ...toastConfig,
        icon: 'error',
        title: @json(session('error')),
        iconColor: '#EF4444',
        customClass: { popup: 'swal-edupanel' }
    });
    @endif

    @if(session('warning'))
    Swal.fire({
        ...toastConfig,
        icon: 'warning',
        title: @json(session('warning')),
        iconColor: '#F59E0B',
        customClass: { popup: 'swal-edupanel' }
    });
    @endif

    @if(session('info'))
    Swal.fire({
        ...toastConfig,
        icon: 'info',
        title: @json(session('info')),
        iconColor: '#6366F1',
        customClass: { popup: 'swal-edupanel' }
    });
    @endif

});
</script>
@endif