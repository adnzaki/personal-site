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

    <!-- Style CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/prism.css') ?>">

</head>

<body>
    <?php if (session()->getFlashdata('success')): ?>
        <?= view('layout/toast', ['message' => session()->getFlashdata('success'), 'color' => 'success']) ?>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <?= view('layout/toast', ['message' => session()->getFlashdata('error'), 'color' => 'danger']) ?>
    <?php endif; ?>

    <!-- Preloader -->
    <div id="preloader">
        <div class="preload-content">
            <div id="original-load"></div>
        </div>
    </div>

    <?= view('layout/header') ?>

    <?= $content ?>

    <?= view('layout/footer') ?>
    <?= view('layout/scripts') ?>
</body>