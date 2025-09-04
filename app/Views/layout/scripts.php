<script>
    document.addEventListener("DOMContentLoaded", function() {
        const nav = document.querySelector(".classy-navbar");
        const navHeight = nav.offsetHeight;
        const stickyOffset = 100; // Jarak gulir sebelum menu menjadi "sticky"

        window.addEventListener("scroll", function() {
            if (window.pageYOffset > stickyOffset) {
                nav.classList.add("sticky-nav", "scrolled");
                document.body.style.paddingTop = navHeight + "px"; // Mencegah halaman "melompat"
            } else {
                nav.classList.remove("sticky-nav", "scrolled");
                document.body.style.paddingTop = 0;
            }
        });
    });
</script>

<!-- jQuery (Necessary for All JavaScript Plugins) -->
<script src="<?= base_url('js/jquery/jquery-2.2.4.min.js') ?>"></script>
<!-- Popper js -->
<script src="<?= base_url('js/popper.min.js') ?>"></script>
<!-- Bootstrap js -->
<!-- <script src="<? //= base_url('js/bootstrap.min.js') 
                    ?>"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="<?= base_url('js/prism.js') ?>"></script>
<!-- Plugins js -->
<script src="<?= base_url('js/plugins.js') ?>"></script>
<!-- Active js -->
<script src="<?= base_url('js/active.js') ?>"></script>
<script src="<?= base_url('js/site.js') ?>"></script>