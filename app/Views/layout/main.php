<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Bit & Bait - Merangkai logika dan rasa dalam tiap baris kode">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="keywords" content="Bit & Bait, coding, teknologi, blog, puisi, artikel, filsafat, kehidupan, nalar, naluri, pengembangan web, CodeIgniter 4, WordPress REST API, WpAdapter, plugin custom, dokumentasi teknis, storytelling digital, editorial teknologi, performa website, integrasi API, kampung-style coding, branding filosofis, poetry display, absensi sekolah, Actudent, SSPaging, Sync API, Vue, Quasar, Pinia, teknologi pendidikan, web modular, PWA Indonesia">
    <meta property="og:title" content="<?= $og_meta['title'] ?>">
    <meta property="og:description" content="<?= $og_meta['description'] ?>">
    <meta property="og:image" content="<?= $og_meta['image'] ?>">
    <meta property="og:url" content="<?= $og_meta['url'] ?>" />
    <!-- Title -->
    <title><?= $title ?></title>

    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('img/core-img/favicon.ico') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('img/core-img/apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('img/core-img/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('img/core-img/favicon-16x16.png') ?>">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Core Stylesheet -->
    <link rel="stylesheet" href="<?= base_url('css/site.css') ?>">

    <!-- Style CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts untuk tipografi modern -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#0f172a',
                            primary: '#1e3a8a',
                            accent: '#3b82f6',
                            muted: '#64748b',
                            bg: '#f8fafc'
                        }
                    }
                }
            }
        }
    </script>
    <?php if (isset($isSinglePost) && $isSinglePost): ?>
        <link rel='stylesheet' id='kevinbatdorf-code-block-pro-style-css' href='<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/style-index.css?ver=1.13.0' media='all' />
        <link rel='stylesheet' id='kevinbatdorf-code-block-pro-style-css' href='<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/index.css' />
        <style>
            @font-face {
                font-family: Code-Pro-Fira-Code;
                src: url("<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/fonts/Code-Pro-Fira-Code.bf5ea52f.woff2") format('woff2');
            }

            @font-face {
                font-family: Code-Pro-JetBrains-Mono;
                src: url("<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/fonts/Code-Pro-JetBrains-Mono.1e66c47a.woff2") format('woff2');
            }

            @font-face {
                font-family: Code-Pro-JetBrains-Mono-NL;
                src: url("<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/fonts/Code-Pro-JetBrains-Mono-NL.432a7b10.ttf") format('truetype');
            }

            @font-face {
                font-family: Code-Pro-Comic-Mono;
                src: url("<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/fonts/Code-Pro-Comic-Mono.53a3d0c4.ttf") format('truetype');
            }

            @font-face {
                font-family: Code-Pro-Roboto-Mono;
                src: url("<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/fonts/Code-Pro-Roboto-Mono.94ffabb1.ttf") format('truetype');
            }

            @font-face {
                font-family: Code-Pro-Fantasque-Sans-Mono;
                src: url("<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/fonts/Code-Pro-Fantasque-Sans-Mono.6c511db2.woff2") format('woff2');
            }

            @font-face {
                font-family: Code-Pro-Cozette;
                src: url("<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/fonts/Code-Pro-Cozette.6a7d67c5.woff2") format('woff2');
            }

            @font-face {
                font-family: Code-Pro-Deja-Vu-Mono;
                src: url("<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/fonts/Code-Pro-Deja-Vu-Mono.7ba86537.ttf") format('truetype');
            }

            @font-face {
                font-family: Code-Pro-Monaspace-Argon;
                src: url("<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/fonts/Code-Pro-Monaspace-Argon.102d94d0.woff") format('woff');
            }

            @font-face {
                font-family: Code-Pro-Monaspace-Krypton;
                src: url("<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/fonts/Code-Pro-Monaspace-Krypton.44966aaa.woff") format('woff');
            }

            @font-face {
                font-family: Code-Pro-Monaspace-Neon;
                src: url("<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/fonts/Code-Pro-Monaspace-Neon.257144c3.woff") format('woff');
            }

            @font-face {
                font-family: Code-Pro-Monaspace-Radon;
                src: url("<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/fonts/Code-Pro-Monaspace-Radon.9f755736.woff") format('woff');
            }

            @font-face {
                font-family: Code-Pro-Monaspace-Xenon;
                src: url("<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/fonts/Code-Pro-Monaspace-Xenon.bad9f4b9.woff") format('woff');
            }

            @font-face {
                font-family: Code-Pro-Geist-Mono;
                src: url("<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/fonts/Code-Pro-Geist-Mono.8f5e9544.woff2") format('woff2');
            }

            @font-face {
                font-family: Code-Pro-Hack;
                src: url("<?= env('wordpress_url_2') ?>wp-content/plugins/code-block-pro/build/fonts/Code-Pro-Hack.fe74d490.woff2") format('woff2');
            }
        </style>

    <?php endif; ?>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

</head>

<body class="antialiased selection:bg-blue-500 selection:text-white">
    <?php if (session()->getFlashdata('success')): ?>
        <?= view('layout/toast', ['message' => session()->getFlashdata('success'), 'color' => 'success']) ?>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <?= view('layout/toast', ['message' => session()->getFlashdata('error'), 'color' => 'danger']) ?>
    <?php endif; ?>

    <!-- Preloader -->
    <!-- <div id="preloader">
        <div class="preload-content">
            <div id="original-load"></div>
        </div>
    </div> -->

    <?= view('layout/header') ?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <?= $content ?>
    </main>


    <?= view('layout/footer') ?>
    <?= view('layout/scripts') ?>
</body>