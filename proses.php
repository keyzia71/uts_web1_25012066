<?php
session_start();

require_once __DIR__ . '/data/menu.php';

function aman($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

function rupiahProses($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

$pesan = '';
$status = 'error';

$kodeInput = '';
$jumlahInput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kodeInput = isset($_POST['kode_menu'])
        ? trim($_POST['kode_menu'])
        : '';

    $jumlahInput = isset($_POST['jumlah'])
        ? trim($_POST['jumlah'])
        : '';

    $menuDipilih = null;

    // Periksa apakah kode menu terdaftar.
    foreach ($menu as $item) {
        if ($item['kode'] === $kodeInput) {
            $menuDipilih = $item;
            break;
        }
    }

    // Validasi kode menu.
    if ($kodeInput === '') {
        $pesan = 'Kode menu tidak boleh kosong.';
    } elseif ($menuDipilih === null) {
        $pesan = 'Kode menu tidak terdaftar.';
    }

    // Validasi jumlah pembelian.
    elseif ($jumlahInput === '') {
        $pesan = 'Jumlah pembelian tidak boleh kosong.';
    } else {
        $jumlahValid = filter_var(
            $jumlahInput,
            FILTER_VALIDATE_INT
        );

        if ($jumlahValid === false) {
            $pesan = 'Jumlah harus berupa bilangan bulat.';
        } elseif ($jumlahValid < 1 || $jumlahValid > 10) {
            $pesan = 'Jumlah pembelian harus antara 1 sampai 10.';
        }
    }

    // Hanya input valid yang boleh mengubah keranjang.
    if ($pesan === '') {
        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }

        $kode = $menuDipilih['kode'];

        // Harga diambil dari array menu di server.
        $harga = $menuDipilih['harga'];

        if (isset($_SESSION['keranjang'][$kode])) {
            $pesan = 'Menu ini sudah ada di keranjang. Penggabungan jumlah akan disempurnakan pada tahap berikutnya.';
            $status = 'error';
        } else {
            $_SESSION['keranjang'][$kode] = [
                'kode' => $kode,
                'nama' => $menuDipilih['nama'],
                'harga' => $harga,
                'jumlah' => $jumlahValid
            ];

            $pesan = 'Pesanan berhasil ditambahkan ke keranjang.';
            $status = 'success';
        }
    }
} else {
    $pesan = 'Silakan gunakan form pemesanan.';
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="panel">
    <h2>Hasil Pemesanan</h2>

    <div class="alert <?php echo $status === 'success' ? 'alert-success' : 'alert-error'; ?>">
        <?php echo aman($pesan); ?>
    </div>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') { ?>
        <h3>Data yang Dikirim</h3>

        <p>
            Kode menu:
            <strong><?php echo aman($kodeInput); ?></strong>
        </p>

        <p>
            Jumlah pembelian:
            <strong><?php echo aman($jumlahInput); ?></strong>
        </p>

        <?php if ($status === 'success' && isset($menuDipilih)) { ?>
            <p>
                Nama menu:
                <strong><?php echo aman($menuDipilih['nama']); ?></strong>
            </p>

            <p>
                Harga satuan:
                <strong><?php echo rupiahProses($menuDipilih['harga']); ?></strong>
            </p>

            <p>
                Subtotal:
                <strong><?php echo rupiahProses($menuDipilih['harga'] * $jumlahValid); ?></strong>
            </p>
        <?php } ?>
    <?php } ?>

    <p>
        <a href="index.php" class="btn">Kembali ke Daftar Menu</a>
    </p>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>