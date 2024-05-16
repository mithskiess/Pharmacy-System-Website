<h2>Ubah Produk</h2>
<?php
$id_produk = $_GET["id"];
$ambil=$koneksi->query("SELECT * FROM produk WHERE id_produk='$_GET[id]'");
$pecah= $ambil->fetch_assoc();

//echo "<pre>";
//rint_r($pecah);
//echo "</pre>";
?>

<?php
$datakategori = array();

$ambil = $koneksi->query("SELECT * FROM kategori");
while($tiap = $ambil->fetch_assoc())
{
	$datakategori[] = $tiap;
}

//echo "<pre>";
//print_r($datakategori);
//echo "</pre>";
?>

<form method="post" enctype="multipart/form-data">
	<div class="form-group">
		<label>Kategori</label>
		<select class="form-control" name="id_kategori">
			<option value="">Pilih Kategori</option>
			<?php foreach ($datakategori as $key => $value): ?>

			<option value="<?php echo $value["id_kategori"] ?>" <?php if($pecah["id_kategori"]==$value["id_kategori"]){ echo "selected"; } ?>><?php echo $value["nama_kategori"] ?>
				
			</option>
			<?php endforeach ?>
		</select>
	</div>
	<div class="form-group">
		<label>Nama Produk</label>
		<input type="text" name="nama" class="form-control" value="<?php echo $pecah['nama_produk']; ?>">
	</div>
	<div class="form-group">
		<label>Harga Rp</label>
		<input type="numbert" class="form-control" name="harga" value="<?php echo $pecah['harga']; ?>">
	</div>
	<div class="form-group">
		<label>Merek</label>
		<input type="text" class="form-control" name="merek" value="<?php echo $pecah['merek_produk']; ?>">
	</div>
	<div class="form-group">
		<label>Stok</label>
		<input type="numbert" class="form-control" name="stok" value="<?php echo $pecah['stok_produk']; ?>">
	</div>
	<div class="form-group">
		<img src="foto_produk/<?php echo $pecah['foto_produk'] ?>" width="200">
	</div>
	<div class="form-group">
		<label>Ganti Foto</label>
		<input type="file" name="foto" class="form-control">
	</div>
	<div class="form-group">
		<label>Deskripsi</label>
		<textarea name="deskripsi" class="form-control" rows="20">
			<?php echo $pecah['deskripsi_produk']; ?>
		</textarea>

	</div>
	<button class="btn btn-primary" name="ubah">Ubah</button>
</form>

<?php
if (isset($_POST['ubah']))
{
	$namafoto=$_FILES['foto']['name'];
	$lokasifoto = $_FILES['foto']['tmp_name'];
	// jk foto dirubah
	if (!empty($lokasifoto))
	{
		move_uploaded_file($lokasifoto,"foto_produk/$namafoto");

		$koneksi->query("UPDATE produk SET nama_produk='$_POST[nama]',harga='$_POST[harga]',merek_produk='$_POST[merek]',stok_produk='$_POST[stok]',id_kategori='$_POST[id_kategori]',foto_produk='$namafoto',deskripsi_produk='$_POST[deskripsi]' WHERE id_produk='$_GET[id]'");
	}
	else
	{
		$koneksi->query("UPDATE produk SET nama_produk='$_POST[nama]',harga='$_POST[harga]',merek_produk='$_POST[merek]',stok_produk='$_POST[stok]',deskripsi_produk='$_POST[deskripsi]',id_kategori='$_POST[id_kategori]' WHERE id_produk='$_GET[id]'");
	}
	echo "<script>alert('data produk telah diubah');</script>";
	echo "<script>location='index.php?halaman=produk';</script>";
}
?> 