<?php

/**
 * OstiumDate 
 * Pustaka format dan perhitungan tanggal untuk Bahasa Indonesia 
 * Pustaka ini diambil dari project OstiumCMS milik Adnan Zaki, web developer Wolestech
 *
 * @package		Application
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Adnan Zaki
 * @link		https://wolestech.com
 * @version     2.0.0
 */

require_once 'helper/Calculation.php';

/**
 * Format dan perhitungan tanggal PHP untuk Bahasa Indonesia
 */
class OstiumDate extends Calculation
{
    /**
     * Pemanggil fungsi getdate()
     *
     * @var array
     */
    protected $date;

    /**
     * Nama-nama hari dalam bahasa Indonesia
     *
     * @var array
     */
    protected $dayName = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    /**
     * Nama-nama bulan dalam bahasa Indonesia
     *
     * @var array
     */
    protected $monthName = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    /**
     * Memanggil fungsi getdate()
     *
     * @return array
     */
    public function __construct()
    {
        $this->date = getdate();
    }

    /**
     * Mengambil nama hari ini
     *
     * @return string
     */
    protected function getDayName()
    {
        $day = $this->date['wday'];

        return $this->dayName[$day];
    }

    /**
     * Mengambil nama bulan
     *
     * @param mixed $mon
     * @return string
     */
    public function getMonthName($mon = '')
    {
        if (empty($mon)) {
            $mon = $this->date['mon'];
        }

        return $this->monthName[$mon];
    }

    // --------------------------- DATE SETTER ----------------------------------------

    /**
     * Set tanggal dengan tampilan lengkap
     * Misalnya: Sabtu, 1 Oktober 2016
     * Format: fullDate(1, 10, 2016)
     * Jika argumen kosong akan menampilkan tanggal hari ini
     *
     * @param mixed $date
     * @param mixed $month
     * @param mixed $year
     * @param boolean $useDay whether to include day name or not
     * @return string
     */
    public function fullDate($date = '', $month = '', $year = '', $useDay = true)
    {
        if (empty($date) && empty($month) && empty($year)) {
            $day = $this->getDayName();
            $date = $this->date['mday'];
            $month = $this->getMonthName();
            $year = $this->date['year'];
        } else {
            if (! $this->dateValidation($date, $month, $year)) {
                $hint = $date . "-" . $month . "-" . $year;
                return $this->error('date', $hint);
            } else {
                $date = intval($date);
                $month = intval($month);
                $day = $this->setDay($date, $month, $year);
                $month = $this->getMonthName($month);
            }
        }

        $day = $useDay ? $day . ', ' : '';

        return $day . $date . " " . $month . " " . $year;
    }

    /**
     * Set tanggal dengan tampilan ringkas
     * Misalnya: 26-12-2016
     * Format: shortDate(26, 12, 2016, '-')
     * Jika argumen kosong akan menampilkan tanggal hari ini,
     * dengan pemisah tanggal default adalah tanda strip (-)
     *
     * @param mixed $date
     * @param mixed $month
     * @param mixed $year
     * @param string $separator
     * @return string
     */
    public function shortDate($date = '', $month = '', $year = '', $separator = '-')
    {
        if (empty($date) && empty($month) && empty($year)) {
            $mon = $this->date['mon'];
            $mon < 10 ? $mon = '0' . $mon : $mon = $mon;

            $monthDay = $this->date['mday'];
            $monthDay < 10 ? $monthDay = '0' . $monthDay : $monthDay = $monthDay;

            return $monthDay . $separator . $mon . $separator . $this->date['year'];
        } else {
            if (! $this->dateValidation($date, $month, $year)) {
                $hint = $date . "-" . $month . "-" . $year;
                return $this->error('date', $hint);
            } else {
                $date   = intval($date);
                $month  = intval($month);
                $date < 10 ? $date = 0 . $date : $date = $date;
                $month < 10 ? $month = 0 . $month : $month = $month;
            }

            return $date . $separator . $month . $separator . $year;
        }
    }

    /**
     * Method ini memiliki fungsi yang sama dengan $this->format(),
     * hanya saja method ini menerima input $date sesuai dengan
     * standar tanggal PHP, sehingga pengguna tidak perlu
     * menjalankan fungsi reverse() terlebih dahulu agar tanggal
     * yang dihasilkan PHP dapat diformat oleh OstiumDate.
     * 
     * Contoh penggunaan: $date = '2023-12-26';
     * $this->create($date, 'd-M-Y');
     * akan menampilkan hasil: 26 Desember 2023
     * 
     * @param string $date tanggal yang akan di format dalam standar PHP, contoh: '2023-12-26'
     * @param string $pattern pola format tanggal, contoh: 'd-M-Y'
     * @param string $separator karakter yang digunakan untuk memisahkan tanggal, contoh: ' '
     * 
     * @return string
     */
    public function create(string $date = '', string $pattern = 'd-MM-y', string $separator = ' '): string
    {
        if (empty($date)) {
            $day   = $this->date['mday'] ?? date('j');
            $month = $this->date['mon']  ?? date('n');
            $year  = $this->date['year'] ?? date('Y');
        } else {
            $parts = explode('-', $date);
            if (count($parts) !== 3) {
                return $this->error('format', $date);
            }

            [$year, $month, $day] = array_map('intval', $parts);
        }

        return $this->createFormattedDate($day, $month, $year, $pattern, $separator);
    }

    /**
     * Format tanggal khusus dengan pilihan format d, D, Dd, m, M, Mm, Y
     * Contoh: 'd' = 26, 'D' = Sen, 26, 'DD' = Senin, 26
     *         'm' = 12, 'M' = Des, MM = Desember, y atau Y = 2016
     * Contoh eksekusi: format('D-M-Y', '1-9-2016', '-')
     * akan menampilkan hasil: Kam, 1-Sep-2016
     * => argumen ke-3 akan menghasilkan spasi jika dikosongkan
     *
     * @param string $pattern
     * @param string $date
     * @param string $separator
     * 
     * @return string
     * @deprecated
     */
    public function format($pattern, $date, $separator = " ")
    {
        $date   = explode("-", $date);
        $day    = intval($date[0]);
        $month  = intval($date[1]);
        $year   = $date[2];

        return $this->createFormattedDate($day, $month, $year, $pattern, $separator);
    }

    private function createFormattedDate($day, $month, $year, $pattern, $separator): string
    {
        if (! $this->dateValidation($day, $month, $year)) {
            return $this->error('date', "$day-$month-$year");
        }

        $parts = explode('-', $pattern);
        if (count($parts) !== 3) {
            return $this->error('format', $pattern);
        }

        [$dayFmt, $monthFmt, $yearFmt] = $parts;
        $output = [];

        // 🗓️ Format Hari
        $output[] = $this->formatDay($dayFmt, $day, $month, $year);
        if ($output[0] === false) {
            return $this->error('format', $pattern);
        }

        // 📅 Format Bulan
        $output[] = $this->formatMonth($monthFmt, $month);
        if ($output[1] === false) {
            return $this->error('format', $pattern);
        }

        // 📆 Format Tahun
        $output[] = $this->formatYear($yearFmt, $year);
        if ($output[2] === false) {
            return $this->error('format', $pattern);
        }

        return implode($separator, $output);
    }

    /**
     * Format tanggal berdasarkan input $format.
     * 
     * d  = 26
     * D  = Sen, 26
     * DD = Senin, 26
     * 
     * @param string $format
     * @param int $day
     * @param int $month
     * @param int $year
     * 
     * @return string|false
     */
    private function formatDay(string $format, int $day, int $month, int $year): string|false
    {
        return match ($format) {
            'd'  => str_pad($day, 2, '0', STR_PAD_LEFT),
            'D'  => substr($this->setDay($day, $month, $year), 0, 3) . ", $day",
            'DD' => $this->setDay($day, $month, $year) . ", $day",
            default => false,
        };
    }

    /**
     * Format bulan berdasarkan input $format.
     * 
     * m  = 12
     * M  = Des
     * MM = Desember
     * 
     * @param string $format
     * @param int $month
     * 
     * @return string|false
     */
    private function formatMonth(string $format, int $month): string|false
    {
        return match ($format) {
            'm'  => str_pad($month, 2, '0', STR_PAD_LEFT),
            'M'  => substr($this->getMonthName($month), 0, 3),
            'MM' => $this->getMonthName($month),
            default => false,
        };
    }

    /**
     * Format tahun berdasarkan input $format.
     * 
     * y  = 4 digit tahun (2023)
     * Y  = 4 digit tahun (2023)
     * 
     * @param string $format
     * @param int $year
     * 
     * @return string|false
     */
    private function formatYear(string $format, int $year): string|false
    {
        return in_array($format, ['y', 'Y']) ? (string) $year : false;
    }

    /**
     * Fungsi yang digunakan untuk mengambil jumlah hari dalam bulan
     * yang diinput untuk divalidasi oleh fungsi dateValidation()
     * Fungsi ini dibuat public agar bisa digunakan dengan mudah oleh user
     * jika ingin mengetahui total hari dalam sebulan
     *
     * @param int $month
     * @param int $year
     * 
     * @return int
     */
    public function daysInMonth($month, $year)
    {
        $totalDays = [
            1 => 31,
            2 => $this->daysOfFebruary($year),
            3 => 31,
            4 => 30,
            5 => 31,
            6 => 30,
            7 => 31,
            8 => 31,
            9 => 30,
            10 => 31,
            11 => 30,
            12 => 31
        ];

        return $totalDays[$month];
    }

    /**
     * Fungsi ini berperan dalam menampilkan nama hari
     * berdasarkan input dari user.
     *
     * @param int $date
     * @param int $month
     * @param int $year
     * @return string
     */
    protected function setDay($date, $month, $year)
    {
        $day = date("l", mktime(0, 0, 0, $month, $date, $year));
        switch ($day) {
            case 'Sunday':
                $day = $this->dayName[0];
                break;
            case 'Monday':
                $day = $this->dayName[1];
                break;
            case 'Tuesday':
                $day = $this->dayName[2];
                break;
            case 'Wednesday':
                $day = $this->dayName[3];
                break;
            case 'Thursday':
                $day = $this->dayName[4];
                break;
            case 'Friday':
                $day = $this->dayName[5];
                break;
            case 'Saturday':
                $day = $this->dayName[6];
                break;
            default:
                'Not a date';
                break;
        }

        return $day;
    }

    /**
     * Validasi input tanggal dan bulan
     *
     * @param int $date
     * @param int $month
     * @param int $year
     * @return bool
     */
    protected function dateValidation($date, $month, $year)
    {
        if (
            $month > 12 || $month < 1
            || $date > $this->daysInMonth($month, $year)
            || $date < 1
        ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Fungsi yang digunakan untuk mengetahui jumlah hari di bulan Februari
     * apakah berjumlah 28 hari atau 29 hari jika pada tahun kabisat
     *
     * @param int $year
     * @return int
     */
    protected function daysOfFebruary($year)
    {
        return ($year % 4 === 0) ? 29 : 28;
    }
}
