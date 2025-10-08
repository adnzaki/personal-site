<?php
/*
Plugin Name: Post View Counter
Description: Menampilkan jumlah views pada setiap post yang diurutkan berdasarkan jumlah views terbanyak
Version: 1.0.0
Author: Adnan Zaki
*/

if (!defined('ABSPATH')) exit;

/**
 * Register admin menu
 */
add_action('admin_menu', function () {
    add_menu_page(
        'Post View Counter',
        'Post View Counter',
        'manage_options',
        'post-view-counter',
        'pvc_admin_page',
        'dashicons-calculator',
        20
    );
});

/**
 * Admin page
 */
function pvc_admin_page()
{
    echo '<div class="wrap">';
    echo '<h1>Post View Counter</h1>';
    pvc_render_views();
    echo '</div>';
}

/**
 * Popularity category mapping
 * Rules:
 * - < 25  => Sepi
 * - < 50  => Ramai
 * - < 75  => Populer
 * - > 100 => Booming
 * - 75–100 => Populer (untuk menutup celah)
 */
function pvc_get_popularity($views)
{
    $v = (int) $views;
    if ($v > 100) {
        return ['label' => 'Booming', 'class' => 'boom'];
    } elseif ($v >= 75) {
        return ['label' => 'Populer', 'class' => 'pop'];
    } elseif ($v >= 50) {
        return ['label' => 'Ramai', 'class' => 'busy'];
    } elseif ($v >= 25) {
        return ['label' => 'Ramai', 'class' => 'busy']; // 25–49 sesuai aturan "Ramai"
    } else {
        return ['label' => 'Sepi', 'class' => 'quiet'];
    }
}

/**
 * Render data + responsive UI + pagination
 */
function pvc_render_views()
{
    global $wpdb;

    $table_views = $wpdb->prefix . 'post_views';
    $table_posts = $wpdb->prefix . 'posts';

    $per_page     = 25;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset       = ($current_page - 1) * $per_page;

    // Total baris
    $total = (int) $wpdb->get_var("
        SELECT COUNT(*)
        FROM {$table_views} pv
        INNER JOIN {$table_posts} p ON pv.post_id = p.ID
        WHERE p.post_status = 'publish'
    ");

    // Data halaman saat ini
    $results = $wpdb->get_results($wpdb->prepare("
        SELECT 
            pv.id          AS pv_id,
            pv.views       AS viewers,
            p.ID           AS post_id,
            p.post_title   AS judul,
            DATE(p.post_date) AS tanggal
        FROM {$table_views} pv
        INNER JOIN {$table_posts} p ON pv.post_id = p.ID
        WHERE p.post_status = 'publish'
        ORDER BY pv.views DESC, p.post_date DESC
        LIMIT %d OFFSET %d
    ", $per_page, $offset));

    $start       = $total ? ($offset + 1) : 0;
    $end         = min($offset + $per_page, $total);
    $total_pages = max(1, (int) ceil($total / $per_page));

    // Styles: modern, mobile-first, pagination stabil
    echo '<style>
        :root {
            --pvc-bg: #ffffff;
            --pvc-surface: #f8fafc;
            --pvc-border: #e5e7eb;
            --pvc-muted: #6b7280;
            --pvc-ink: #0f172a;
            --pvc-primary: #0073aa;
            --pvc-primary-dark: #005a87;
            --pvc-success: #065f46;
            --pvc-warning: #92400e;
            --pvc-danger: #991b1b;
            --pvc-info: #3730a3;
            --pvc-shadow: 0 2px 6px rgba(0,0,0,0.08);
            --pvc-radius: 12px;
        }
        .pvc-panel {
            margin-top: 20px;
            background: var(--pvc-bg);
            border: 1px solid var(--pvc-border);
            border-radius: var(--pvc-radius);
            box-shadow: var(--pvc-shadow);
            overflow: hidden;
        }
        .pvc-header {
            padding: 14px 16px;
            border-bottom: 1px solid var(--pvc-border);
            background: var(--pvc-surface);
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            justify-content: space-between;
        }
        .pvc-info {
            font-size: 13px;
            color: var(--pvc-muted);
        }
        .pvc-actions {
            display: flex;
            gap: 8px;
        }
        .pvc-btn {
            padding: 6px 10px;
            border: 1px solid var(--pvc-border);
            border-radius: 8px;
            background: #f1f5f9;
            color: #1f2937;
            text-decoration: none;
            font-size: 12px;
        }
        .pvc-btn:hover { background: #e2e8f0; }

        /* Desktop Table */
        .pvc-table-wrap { display: none; }
        @media (min-width: 768px) { .pvc-table-wrap { display: block; } }
        table.pvc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        table.pvc-table thead th {
            background: #eef2f7;
            color: #111827;
            font-weight: 600;
            padding: 12px 14px;
            border-bottom: 1px solid var(--pvc-border);
            text-align: left;
        }
        table.pvc-table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--pvc-border);
            color: var(--pvc-ink);
        }
        table.pvc-table tbody tr:hover { background: #f9fafb; }
        .pvc-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid var(--pvc-border);
            background: #fff;
        }
        .pvc-badge .dot {
            width: 8px; height: 8px; border-radius: 50%;
        }
        .pvc-badge.quiet  .dot { background: #fecaca; }  /* sepi */
        .pvc-badge.busy   .dot { background: #fde68a; }  /* ramai */
        .pvc-badge.pop    .dot { background: #bbf7d0; }  /* populer */
        .pvc-badge.boom   .dot { background: #c7d2fe; }  /* booming */
        .pvc-badge.quiet  { color: var(--pvc-danger); }
        .pvc-badge.busy   { color: var(--pvc-warning); }
        .pvc-badge.pop    { color: var(--pvc-success); }
        .pvc-badge.boom   { color: var(--pvc-info); }

        /* Mobile Cards (no horizontal scroll) */
        .pvc-cards { display: grid; grid-template-columns: 1fr; gap: 12px; padding: 12px; }
        @media (min-width: 768px) { .pvc-cards { display: none; } }
        .pvc-card {
            background: var(--pvc-bg);
            border: 1px solid var(--pvc-border);
            border-radius: var(--pvc-radius);
            box-shadow: var(--pvc-shadow);
            padding: 14px;
        }
        .pvc-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--pvc-ink);
            margin-bottom: 6px;
            line-height: 1.4;
        }
        .pvc-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 16px;
            font-size: 13px;
            color: var(--pvc-muted);
            margin-bottom: 8px;
        }
        .pvc-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid var(--pvc-border);
            background: #fff;
        }
        .pvc-chip .dot { width: 8px; height: 8px; border-radius: 50%; }
        .pvc-chip.quiet .dot { background: #fecaca; }
        .pvc-chip.busy  .dot { background: #fde68a; }
        .pvc-chip.pop   .dot { background: #bbf7d0; }
        .pvc-chip.boom  .dot { background: #c7d2fe; }
        .pvc-chip.quiet { color: var(--pvc-danger); }
        .pvc-chip.busy  { color: var(--pvc-warning); }
        .pvc-chip.pop   { color: var(--pvc-success); }
        .pvc-chip.boom  { color: var(--pvc-info); }

        /* Footer + Pagination (sticky on mobile to avoid layout miss) */
        .pvc-footer {
            padding: 12px 16px;
            border-top: 1px solid var(--pvc-border);
            background: var(--pvc-bg);
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            bottom: 0;
            z-index: 5;
        }
        .pvc-page-info {
            font-size: 13px;
            color: var(--pvc-muted);
        }
        .pvc-pagination {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            align-items: center;
            justify-content: flex-end;
        }
        .pvc-pagination a,
        .pvc-pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 34px;
            padding: 0 10px;
            border: 1px solid var(--pvc-border);
            border-radius: 10px;
            background: var(--pvc-surface);
            color: #1f2937;
            text-decoration: none;
            font-size: 13px;
        }
        .pvc-pagination a:hover { background: #e5edf5; }
        .pvc-pagination .is-current {
            background: var(--pvc-primary);
            border-color: var(--pvc-primary);
            color: #fff;
            font-weight: 600;
        }
        .pvc-pagination .is-disabled {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>';

    // Header info
    $info_text = sprintf(
        'Menampilkan %s–%s dari %s baris · Halaman %s dari %s',
        number_format_i18n($start),
        number_format_i18n($end),
        number_format_i18n($total),
        number_format_i18n($current_page),
        number_format_i18n($total_pages)
    );

    echo '<div class="pvc-panel">';
    echo '<div class="pvc-header">';
    echo '<div class="pvc-info">' . esc_html($info_text) . '</div>';
    echo '<div class="pvc-actions">';
    echo '</div>';
    echo '</div>';

    // Desktop: table
    echo '<div class="pvc-table-wrap">';
    echo '<table class="pvc-table">';
    echo '<thead><tr>
            <th>No</th>
            <th>Judul Post</th>
            <th>Tanggal</th>
            <th>Viewers</th>
            <th>Kategori</th>
        </tr></thead><tbody>';

    if (!empty($results)) {
        $row_no = $offset + 1;
        foreach ($results as $row) {
            $pop = pvc_get_popularity($row->viewers);
            echo '<tr>';
            echo '<td>' . number_format_i18n($row_no) . '</td>';
            echo '<td>' . esc_html($row->judul) . '</td>';
            echo '<td>' . esc_html($row->tanggal) . '</td>';
            echo '<td>' . number_format_i18n((int)$row->viewers) . '</td>';
            echo '<td><span class="pvc-badge ' . esc_attr($pop['class']) . '"><span class="dot"></span>' . esc_html($pop['label']) . '</span></td>';
            echo '</tr>';
            $row_no++;
        }
    } else {
        echo '<tr><td colspan="5">Tidak ada data.</td></tr>';
    }
    echo '</tbody></table>';
    echo '</div>'; // end table

    // Mobile: cards (no horizontal scroll; pagination sticky)
    echo '<div class="pvc-cards">';
    if (!empty($results)) {
        $row_no = $offset + 1;
        foreach ($results as $row) {
            $pop = pvc_get_popularity($row->viewers);
            echo '<div class="pvc-card">';
            echo '<div class="pvc-title">' . esc_html($row->judul) . '</div>';
            echo '<div class="pvc-meta"><span>No ' . number_format_i18n($row_no) . '</span><span>Tanggal: ' . esc_html($row->tanggal) . '</span></div>';
            echo '<div class="pvc-meta"><span>Views: ' . number_format_i18n((int)$row->viewers) . '</span></div>';
            echo '<span class="pvc-chip ' . esc_attr($pop['class']) . '"><span class="dot"></span>' . esc_html($pop['label']) . '</span>';
            echo '</div>';
            $row_no++;
        }
    } else {
        echo '<div class="pvc-card"><div class="pvc-title">Tidak ada data.</div><div class="pvc-meta">Silakan cek tabel wp_post_views.</div></div>';
    }
    echo '</div>'; // end cards

    // Pagination footer (sticky on mobile)
    echo '<div class="pvc-footer">';
    echo '<div class="pvc-page-info">' . esc_html($info_text) . '</div>';
    echo '<div class="pvc-pagination">';

    $base = admin_url('admin.php?page=post-view-counter');

    $first_url = esc_url(add_query_arg('paged', 1, $base));
    $prev_url  = esc_url(add_query_arg('paged', max(1, $current_page - 1), $base));
    $next_url  = esc_url(add_query_arg('paged', min($total_pages, $current_page + 1), $base));
    $last_url  = esc_url(add_query_arg('paged', $total_pages, $base));

    echo '<a class="' . ($current_page <= 1 ? 'is-disabled' : '') . '" href="' . $first_url . '">&laquo;</a>';
    echo '<a class="' . ($current_page <= 1 ? 'is-disabled' : '') . '" href="' . $prev_url . '">&lsaquo;</a>';

    $window = 2; // numbered pages window around current
    $start_page = max(1, $current_page - $window);
    $end_page   = min($total_pages, $current_page + $window);

    if ($start_page > 1) {
        $p1 = esc_url(add_query_arg('paged', 1, $base));
        echo '<a href="' . $p1 . '">1</a>';
        if ($start_page > 2) echo '<span>…</span>';
    }

    for ($i = $start_page; $i <= $end_page; $i++) {
        $url = esc_url(add_query_arg('paged', $i, $base));
        $cls = ($i === $current_page) ? 'is-current' : '';
        echo '<a class="' . $cls . '" href="' . $url . '">' . number_format_i18n($i) . '</a>';
    }

    if ($end_page < $total_pages) {
        if ($end_page < $total_pages - 1) echo '<span>…</span>';
        $plast = esc_url(add_query_arg('paged', $total_pages, $base));
        echo '<a href="' . $plast . '">' . number_format_i18n($total_pages) . '</a>';
    }

    echo '<a class="' . ($current_page >= $total_pages ? 'is-disabled' : '') . '" href="' . $next_url . '">&rsaquo;</a>';
    echo '<a class="' . ($current_page >= $total_pages ? 'is-disabled' : '') . '" href="' . $last_url . '">&raquo;</a>';
    echo '</div>'; // pvc-pagination
    echo '</div>'; // pvc-footer

    echo '</div>'; // pvc-panel
}
