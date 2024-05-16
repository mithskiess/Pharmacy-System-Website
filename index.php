<?php 
session_start(); 
//koneksi ke database
include 'koneksi.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Apotek Farma</title>
	<link rel="stylesheet" href="admin/bs-binary-admin/assets/css/bootstrap.css">
	<?php include 'latar.php'; ?>
</head>
<body>

<?php include 'menu.php'; ?>
<!-- konten -->
<section class="konten">
	<div class="container">
		<h1>APOTEK FARMA</h1>
	
		<div class="row">
			
			<?php $ambil = $koneksi->query("SELECT * FROM produk") ?>
			<?php while($perproduk = $ambil->fetch_assoc()){ ?>
			
			<div class="col-md-3">
				<div class="thumbnail">
					<img src="admin/bs-binary-admin/assets/foto_produk/<?php echo $perproduk['foto_produk'] ?>" alt="">
					<div class="caption">
						<h3><?php echo $perproduk['nama_produk']; ?></h3>
						<h5>Rp. <?php echo number_format($perproduk['harga']); ?></h5>
						<a href="beli.php?id=<?php echo $perproduk['id_produk']; ?>" class="btn btn-primary">Beli</a>
						<a href="detail.php?id=<?php echo $perproduk["id_produk"]; ?>" class="btn btn-default">Detail</a>
				</div>
			</div>
		</div>

	<?php } ?>


	</div>
</section>

</body>
</html>