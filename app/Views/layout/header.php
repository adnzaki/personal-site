<!-- ##### Header Area Start ##### -->
<header class="header-area">




    <!-- Nav Area -->
    <div class="original-nav-area" id="stickyNav">
        <div class="classy-nav-container breakpoint-off">
            <div class="container">
                <!-- Classy Menu -->
                <nav class="classy-navbar justify-content-between">

                    <!-- Subscribe btn -->
                    <div class="subscribe-btn">
                        <!-- <a href="#" class="btn subscribe-btn" data-toggle="modal" data-target="#subsModal">Subscribe</a> -->
                        <h1 class="text-hidden">Bit & Bait - Penulis kode dan puisi</h1>
                        <h2 class="text-hidden">Merangkai logika dan rasa dalam tiap baris kode</h2>
                        <a href="<?= base_url() ?>" style="background: none;">
                            <img class="logo-bit-bait logo-scrolled" src="<?= base_url('img/core-img/small-logo.png') ?>" alt="">
                        </a>
                    </div>

                    <!-- Navbar Toggler -->
                    <div class="classy-navbar-toggler">
                        <span class="navbarToggler"><span></span><span></span><span></span></span>
                    </div>

                    <!-- Menu -->
                    <div class="classy-menu" id="originalNav">
                        <!-- close btn -->
                        <div class="classycloseIcon">
                            <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                        </div>

                        <!-- Nav Start -->
                        <?= view('layout/nav') ?>
                        <!-- Nav End -->
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- ##### Header Area End ##### -->