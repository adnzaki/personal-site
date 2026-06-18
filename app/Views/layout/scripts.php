<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');

        menuBtn.addEventListener('click', function() {
            // Toggle kelas hidden pada menu drawer
            mobileMenu.classList.toggle('hidden');

            // Mengubah icon SVG (Hamburger <=> Close 'X') secara dinamis saat diklik
            if (mobileMenu.classList.contains('hidden')) {
                // Balik ke ikon Hamburger lines
                menuIcon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
            } else {
                // Ubah ke ikon 'X' (Close)
                menuIcon.setAttribute('d', 'M6 18L18 6M6 6l12 12');
            }
        });
    });
</script>