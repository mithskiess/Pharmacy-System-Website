<?php
session_start();
include 'koneksi.php';


// jk tidak ada session pelanggan(blm login,).mk dilarikan ke login.php
if (!isset($_SESSION["pelanggan"]))
{
	echo "<script>alert('silahkan login terlebih dahulu');</script>";
	echo "<script>location='login.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>checkout</title>
	<link rel="stylesheet" href="admin/bs-binary-admin/assets/css/bootstrap.css">
	<?php include 'latarrr.php'; ?>
</head>
<body>

<?php include 'menu.php'; ?>

<section class="konten">
	<div class="container">
		<h1>Keranjang Belanja</h1>
		<hr>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>Produk</th>
					<th>Harga</th>
					<th>Jumlah</th>
					<th>Subharga</th>
				</tr>
			</thead>
			<tbody>
				<?php $nomor=1; ?>
				<?php $totalbelanja = 0; ?>
				<?php foreach ($_SESSION["keranjang"] as $id_produk => $jumlah): ?>
					<!-- menampilkan produk yg sedang diperulangkan berdasarkan id_produk -->
					<?php 
					$ambil = $koneksi->query("SELECT * FROM produk
						WHERE id_produk='$id_produk'");
					$pecah = $ambil->fetch_assoc();
					$Subharga = $pecah["harga"]*$jumlah;
					
					?>
				<tr>
					<td><?php echo $nomor; ?></td>
					<td><?php echo $pecah["nama_produk"]; ?></td>
					<td>Rp. <?php echo number_format($pecah["harga"]); ?></td>
					<td><?php echo $jumlah; ?></td>
					<td>Rp. <?php echo number_format($Subharga); ?></td>>
				</tr>
				<?php $nomor++ ?>
				<?php $totalbelanja+=$Subharga; ?>
			<?php endforeach ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="4">Total Belanja</th>
					<th>Rp. <?php echo number_format($totalbelanja) ?></th>
				</tr>
			</tfoot>
		</table>


		<form method="post">
			
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<input typr="text" readonly value="<?php echo $_SESSION["pelanggan"]['nama'] ?>" class="form-control">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<input typr="text" readonly value="<?php echo $_SESSION["pelanggan"]['no_hp'] ?>" class="form-control">
					</div>
				</div>
				<div class="col-md-4">
					<select class="form-control" name="id_ongkir">
						<option value="">Pilih Ongkos Kirim</option>
						<?php
						$ambil = $koneksi->query("SELECT * FROM ongkir");
						while($perongkir = $ambil->fetch_assoc()){
							?>
							<option value="<?php echo $perongkir["id_ongkir"] ?>">
								<?php echo $perongkir['jarak'] ?> -
								Rp. <?php echo number_format($perongkir['biaya']) ?>
							</option>
							<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label>Alamat Lengkap Pengiriman</label>
				<textarea class="form-control" name="alamat pengiriman" placeholder="masukan alamat lengkap pengiriman"></textarea>
			</div>
			<button class="btn btn-primary" name="checkout">Checkout</button>
		</form>

		<?php
		if (isset($_POST["checkout"]))
		{
			$id_pelanggan = $_SESSION["pelanggan"]["id_pelanggan"];
			$id_ongkir = $_POST["id_ongkir"];
			$tanggal_pembelian = date("Y-m-d");
			$alamat_pengiriman = $_POST['alamat_pengiriman'];

			$ambil = $koneksi->query("SELECT * FROM ongkir WHERE id_ongkir='$id_ongkir'");
			$arrayongkir = $ambil->fetch_assoc();
			$nama_kota = $arrayongkir['jarak'];
			$biaya = $arrayongkir['biaya'];

			$total_pembelian = $totalbelanja + $biaya;

			// 1. menyimpan data ke tabel pembelian
			$koneksi->query("INSERT INTO pembelian (id_pelanggan,id_ongkir,tanggal_pembelian,total_pembelian,jarak,biaya,alamat_pengiriman)
				VALUES ('$id_pelanggan','$id_ongkir','$tanggal_pembelian','$total_pembelian','$jarak','$biaya','$alamat_pengiriman') ");

			// mendapatkan id pembelian barusan tadi
			$id_pembelian_barusan = $koneksi->insert_id;

			foreach ($_SESSION["keranjang"] as $id_produk => $jumlah)    
			{
				// mendapatkan data produk berdasarkan id_produk
				$ambil = $koneksi->query("SELECT * FROM produk WHERE id_produk='$id_produk'");
				$perproduk = $ambil->fetch_assoc();

				$nama = $perproduk['nama_produk'];
				$harga = $perproduk['harga'];
				$merek = $perproduk['merek_produk'];

				$submerek = $perproduk['merek_produk']*$jumlah;
				$subharga = $perproduk['harga']*$jumlah;
				$koneksi->query("INSERT INTO pembelian_produk (id_pembelian,id_produk,nama,harga,merek,subharga,jumlah)
					VALUES ('$id_pembelian_barusan','$id_produk','$nama','$harga','$merek','$subharga','$jumlah') ");


				// script mengupdate stok
				$koneksi->query("UPDATE produk SET stok_produk=stok_produk -$jumlah WHERE id_produk='$id_produk'");
			}

			// mengkosongkan keranjang belanja

			unset($_SESSION["keranjang"]);


			// tampilan dialihkan ke halaman nota, nita dari pembelian yang barusan
			echo "<script>alert('pembelian sukses');</script>";
			echo "<script>location='nota.php?id=$id_pembelian_barusan';</script>";
		}
		?>

		
	</div>
</section>


</body>
</html>