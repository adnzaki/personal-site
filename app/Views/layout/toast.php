<?php
// Mapping warna Bootstrap ke Tailwind
$bg_color = ($color === 'success') ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white';
?>

<div id="toastContainerKomentar" class="fixed top-6 left-1/2 -translate-x-1/2 z-[9999] w-11/12 md:w-8/12 lg:w-7/12 pointer-events-none transition-all duration-300">

    <div id="notifKomentar" class="pointer-events-auto flex items-center justify-between w-full p-4 rounded-xl shadow-xl border border-white/10 opacity-100 transition-opacity duration-500 <?= $bg_color ?>" role="alert" aria-live="assertive" aria-atomic="true">

        <div class="text-sm font-medium flex-1 pr-4 leading-relaxed">
            <?= $message ?>
        </div>

        <button type="button" onclick="closeToast()" class="inline-flex shrink-0 justify-center items-center h-6 w-6 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors focus:outline-none" aria-label="Close">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

    </div>
</div>

<script>
    // Fungsi untuk menutup toast dengan efek transisi halus (fade out)
    function closeToast() {
        const toastCard = document.getElementById('notifKomentar');
        const toastContainer = document.getElementById('toastContainerKomentar');

        if (toastCard) {
            // Ubah opacity jadi 0 dulu untuk efek fade out
            toastCard.classList.remove('opacity-100');
            toastCard.classList.add('opacity-0');

            // Hapus elemen dari DOM setelah animasi fade out selesai (500ms)
            setTimeout(() => {
                if (toastContainer) toastContainer.remove();
            }, 500);
        }
    }

    // Set timeout otomatis 10 detik (10000ms)
    setTimeout(closeToast, 10000);
</script>