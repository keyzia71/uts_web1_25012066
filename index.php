<?php
require_once __DIR__ . '/data/menu.php';

$pencarian = isset($_GET['q'])
    ? trim($_GET['q'])
    : '';

$hasilMenu = [];

foreach ($menu as $item) {
    if (
        $pencarian === '' ||
        stripos($item['nama'], $pencarian) !== false
    ) {
        $hasilMenu[] = $item;
    }
}

function rupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

require_once __DIR__ . '/includes/header.php';
?>

<style>
    .menu-table-wrapper {
        width: 100%;
        overflow-x: auto;
        margin-top: 20px;
        border-radius: 8px;
    }

    .menu-table {
        width: 100%;
        border-collapse: collapse;
        background-color: #ffffff;
    }

    .menu-table th {
        background-color: #2463a6;
        color: white;
        padding: 14px;
        text-align: left;
        white-space: nowrap;
    }

    .menu-table td {
        padding: 13px 14px;
        border-bottom: 1px solid #e2e8f0;
    }

    .menu-table tbody tr:nth-child(even) {
        background-color: #f3f6fa;
    }

    .menu-table tbody tr:hover {
        background-color: #e8f1fc;
    }

    .harga-menu {
        color: #198754;
        font-weight: bold;
        white-space: nowrap;
    }

    .jumlah-menu {
        margin-bottom: 15px;
        color: #64748b;
        font-size: 14px;
    }

    .form-pencarian {
        display: flex;
        flex-wrap: wrap;
        align-items: end;
        gap: 10px;
    }

    .form-pencarian .field {
        flex: 1;
        min-width: 200px;
    }

    .form-pencarian input {
        width: 100%;
        max-width: 100%;
        margin-bottom: 0;
    }

    .pesan-kosong {
        padding: 25px;
        text-align: center;
        background-color: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
    }

    @media (max-width: 600px) {
        .menu-table th,
        .menu-table td {
            padding: 10px;
            font-size: 13px;
        }

        .form-pencarian .field {
            min-width: 100%;
        }
    }
</style>

<section class="intro">
    <h2>Daftar Menu Kantin</h2>
    <p>
        Selamat datang di Pemesanan Kantin Kampus.
        Temukan makanan dan minuman favoritmu.
    </p>
</section>

<section class="panel">
    <h2>Pencarian Menu</h2>

    <form
        action="index.php"
        method="get"
        class="form-pencarian"
    >
        <div class="field">
            <label for="q">Nama Menu</label>

            <input
                type="search"
                id="q"
                name="q"
                placeholder="Contoh: Nasi Goreng"
                value="<?php
                    echo htmlspecialchars(
                        $pencarian,
                        ENT_QUOTES,
                        'UTF-8'
                    );
                ?>"
            >
        </div>

        <button type="submit">Cari Menu</button>
        <a href="index.php" class="btn">Reset</a>
    </form>
</section>

<section class="panel">
    <h2>Menu Tersedia</h2>

    <p class="jumlah-menu">
        Jumlah menu ditemukan: <?php echo count($hasilMenu); ?>
    </p>

    <?php if (count($hasilMenu) > 0) { ?>

        <div class="menu-table-wrapper">
            <table class="menu-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode Menu</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $nomor = 1;

                    foreach ($hasilMenu as $item) {
                    ?>
                        <tr>
                            <td><?php echo $nomor++; ?></td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $item['kode'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $item['nama'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $item['kategori'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td class="harga-menu">
                                <?php echo rupiah($item['harga']); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    <?php } else { ?>

        <div class="pesan-kosong">
            <h3>Menu Tidak Ditemukan</h3>

            <p>
                Maaf, menu yang kamu cari tidak tersedia.
                Silakan coba kata kunci lain.
            </p>

            <a href="index.php" class="btn">
                Lihat Semua Menu
            </a>
        </div>

    <?php } ?>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>