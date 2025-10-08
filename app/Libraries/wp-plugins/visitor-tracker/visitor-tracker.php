<?php
/*
Plugin Name: Site Visitors Tracker
Description: Plugin khusus website Bit & Bait untuk menampilkan kunjungan website
Version:     1.0.1 
Author:      Adnan Zaki
*/

if (! defined('ABSPATH')) exit;

class Visitor_Tracker_Modern
{
    private $table;
    private $per_page = 25;

    public function __construct()
    {
        global $wpdb;
        $this->table = 'site_visitors';
        add_action('admin_menu', [$this, 'add_admin_menu']);
    }

    public function add_admin_menu()
    {
        add_menu_page(
            'Site Visitors',
            'Site Visitors',
            'manage_options',
            'site-visitors-tracker',
            [$this, 'render_admin_page'],
            'dashicons-chart-area',
            25
        );
    }

    public function render_admin_page()
    {
        global $wpdb;

        $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $offset       = ($current_page - 1) * $this->per_page;

        $total_items = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$this->table}");
        $total_pages = max(1, ceil($total_items / $this->per_page));

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT id, ip_address, user_agent, visited_url, referrer, created_at
                 FROM {$this->table}
                 ORDER BY created_at DESC
                 LIMIT %d OFFSET %d",
                $this->per_page,
                $offset
            ),
            ARRAY_A
        );

        $start = $total_items ? ($offset + 1) : 0;
        $end   = min($offset + $this->per_page, $total_items);

        echo '<div class="wrap"><h1>Bit & Bait - Site Visitors</h1>';

        // CSS modern & responsif
        echo '<style>
            :root {
                --vt-bg: #ffffff;
                --vt-surface: #f8fafc;
                --vt-border: #e5e7eb;
                --vt-muted: #6b7280;
                --vt-ink: #0f172a;
                --vt-primary: #0073aa;
                --vt-shadow: 0 2px 6px rgba(0,0,0,0.08);
                --vt-radius: 12px;
            }
            .vt-panel { margin-top:20px; background:var(--vt-bg); border:1px solid var(--vt-border); border-radius:var(--vt-radius); box-shadow:var(--vt-shadow); overflow:hidden; }
            .vt-header { padding:14px 16px; border-bottom:1px solid var(--vt-border); background:var(--vt-surface); display:flex; flex-wrap:wrap; gap:8px; justify-content:space-between; align-items:center; }
            .vt-info { font-size:13px; color:var(--vt-muted); }

            /* Desktop table */
            .vt-table-wrap { display:none; }
            @media(min-width:768px){ .vt-table-wrap{display:block;} }
            table.vt-table { width:100%; border-collapse:collapse; font-size:14px; }
            table.vt-table th, table.vt-table td { padding:12px 14px; border-bottom:1px solid var(--vt-border); text-align:left; }
            table.vt-table th { background:#eef2f7; font-weight:600; color:#111827; }
            table.vt-table tbody tr:hover { background:#f9fafb; }
            table.vt-table td a { color:var(--vt-primary); text-decoration:none; }
            table.vt-table td a:hover { text-decoration:underline; }
            .vt-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; font-size:12px; color:#334155; }

            /* Mobile cards */
            .vt-cards { display:grid; grid-template-columns:1fr; gap:12px; padding:12px; }
            @media(min-width:768px){ .vt-cards{display:none;} }
            .vt-card { background:var(--vt-bg); border:1px solid var(--vt-border); border-radius:var(--vt-radius); box-shadow:var(--vt-shadow); padding:14px; }
            .vt-title { font-size:15px; font-weight:600; margin-bottom:6px; color:var(--vt-ink); }
            .vt-meta { font-size:13px; color:var(--vt-muted); margin-bottom:6px; word-break:break-word; }
            .vt-meta strong { color:#111827; }
            .vt-link { color:var(--vt-primary); text-decoration:none; }
            .vt-link:hover { text-decoration:underline; }

            /* Footer + Pagination (sticky di mobile agar tidak ketendang) */
            .vt-footer { padding:12px 16px; border-top:1px solid var(--vt-border); background:var(--vt-bg); display:flex; flex-wrap:wrap; gap:10px; align-items:center; justify-content:space-between; position:sticky; bottom:0; z-index:5; }
            .vt-page-info { font-size:13px; color:var(--vt-muted); }
            .vt-pagination { display:flex; flex-wrap:wrap; gap:4px; align-items:center; justify-content:flex-end; }
            .vt-pagination a, .vt-pagination span {
                display:inline-flex; align-items:center; justify-content:center;
                min-width:36px; height:34px; padding:0 10px;
                border:1px solid var(--vt-border); border-radius:10px;
                background:var(--vt-surface); color:#1f2937; text-decoration:none; font-size:13px;
            }
            .vt-pagination a:hover { background:#e5edf5; }
            .vt-pagination .is-current { background:var(--vt-primary); border-color:var(--vt-primary); color:#fff; font-weight:600; }
            .vt-pagination .is-disabled { opacity:0.5; pointer-events:none; }
        </style>';

        // Header info
        $info_text = sprintf(
            'Menampilkan %s–%s dari %s baris · Halaman %s dari %s',
            number_format_i18n($start),
            number_format_i18n($end),
            number_format_i18n($total_items),
            number_format_i18n($current_page),
            number_format_i18n($total_pages)
        );

        echo '<div class="vt-panel">';
        echo '<div class="vt-header"><div class="vt-info">' . esc_html($info_text) . '</div></div>';

        // Desktop table
        echo '<div class="vt-table-wrap"><table class="vt-table">';
        echo '<thead><tr>
                <th>ID</th>
                <th>IP Address</th>
                <th>User Agent</th>
                <th>Visited URL</th>
                <th>Referrer</th>
                <th>Waktu</th>
              </tr></thead><tbody>';

        if (!empty($results)) {
            foreach ($results as $row) {
                echo '<tr>';
                echo '<td class="vt-mono">' . esc_html($row['id']) . '</td>';
                echo '<td class="vt-mono">' . esc_html($row['ip_address']) . '</td>';
                echo '<td>' . esc_html(wp_trim_words($row['user_agent'], 12, '…')) . '</td>';
                echo '<td><a class="vt-link" href="' . esc_url($row['visited_url']) . '" target="_blank" rel="noopener noreferrer">' . esc_html($row['visited_url']) . '</a></td>';
                echo '<td><a class="vt-link" href="' . esc_url($row['referrer']) . '" target="_blank" rel="noopener noreferrer">' . esc_html($row['referrer']) . '</a></td>';
                echo '<td class="vt-mono">' . ($row['created_at'] ? esc_html($row['created_at']) : '<em>—</em>') . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="6">Belum ada data pengunjung.</td></tr>';
        }
        echo '</tbody></table></div>';

        // Mobile cards (tanpa scroll horizontal)
        echo '<div class="vt-cards">';
        if (!empty($results)) {
            foreach ($results as $row) {
                echo '<div class="vt-card">';
                echo '<div class="vt-title">Visitor #' . esc_html($row['id']) . '</div>';
                echo '<div class="vt-meta"><strong>IP:</strong> <span class="vt-mono">' . esc_html($row['ip_address']) . '</span></div>';
                echo '<div class="vt-meta"><strong>Agent:</strong> ' . esc_html(wp_trim_words($row['user_agent'], 16, '…')) . '</div>';
                echo '<div class="vt-meta"><strong>Visited:</strong> <a class="vt-link" href="' . esc_url($row['visited_url']) . '" target="_blank" rel="noopener noreferrer">' . esc_html($row['visited_url']) . '</a></div>';
                echo '<div class="vt-meta"><strong>Referrer:</strong> <a class="vt-link" href="' . esc_url($row['referrer']) . '" target="_blank" rel="noopener noreferrer">' . esc_html($row['referrer']) . '</a></div>';
                echo '<div class="vt-meta"><strong>Waktu:</strong> ' . ($row['created_at'] ? esc_html($row['created_at']) : '<em>—</em>') . '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="vt-card"><div class="vt-title">Belum ada data pengunjung.</div></div>';
        }
        echo '</div>'; // end vt-cards

        // Footer + Pagination (sticky di mobile)
        echo '<div class="vt-footer">';
        echo '<div class="vt-page-info">' . esc_html($info_text) . '</div>';
        echo '<div class="vt-pagination">';

        $base = admin_url('admin.php?page=site-visitors-tracker');

        $first_url = esc_url(add_query_arg('paged', 1, $base));
        $prev_url  = esc_url(add_query_arg('paged', max(1, $current_page - 1), $base));
        $next_url  = esc_url(add_query_arg('paged', min($total_pages, $current_page + 1), $base));
        $last_url  = esc_url(add_query_arg('paged', $total_pages, $base));

        // First & Prev
        echo '<a class="' . ($current_page <= 1 ? 'is-disabled' : '') . '" href="' . $first_url . '">&laquo;</a>';
        echo '<a class="' . ($current_page <= 1 ? 'is-disabled' : '') . '" href="' . $prev_url . '">&lsaquo;</a>';

        // Window numbered pages
        $window = 2;
        $start_page = max(1, $current_page - $window);
        $end_page   = min($total_pages, $current_page + $window);

        if ($start_page > 1) {
            $p1 = esc_url(add_query_arg('paged', 1, $base));
            echo '<a href="' . $p1 . '">1</a>';
            if ($start_page > 2) {
                echo '<span>…</span>';
            }
        }

        for ($i = $start_page; $i <= $end_page; $i++) {
            $url = esc_url(add_query_arg('paged', $i, $base));
            $cls = ($i === $current_page) ? 'is-current' : '';
            echo '<a class="' . $cls . '" href="' . $url . '">' . number_format_i18n($i) . '</a>';
        }

        if ($end_page < $total_pages) {
            if ($end_page < $total_pages - 1) {
                echo '<span>…</span>';
            }
            $plast = esc_url(add_query_arg('paged', $total_pages, $base));
            echo '<a href="' . $plast . '">' . number_format_i18n($total_pages) . '</a>';
        }

        // Next & Last
        echo '<a class="' . ($current_page >= $total_pages ? 'is-disabled' : '') . '" href="' . $next_url . '">&rsaquo;</a>';
        echo '<a class="' . ($current_page >= $total_pages ? 'is-disabled' : '') . '" href="' . $last_url . '">&raquo;</a>';

        echo '</div>'; // vt-pagination
        echo '</div>'; // vt-footer

        echo '</div>'; // vt-panel
        echo '</div>'; // wrap
    }
}

new Visitor_Tracker_Modern();
