<?php
session_start();
include 'koneksi.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>login pelanggan</title>
	<link rel="stylesheet" href="admin/bs-binary-admin/assets/css/bootstrap.css">
	<?php include 'latar.php'; ?>
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Silahkan Login Ke Akun Anda</h3>
				</div>
				<div class="panel-body">
					<form method="post">
						<div class="form-group">
							<label>Email</label>
							<input type="email" class="form-control" name="email" >
						</div>
						<div class="form-group">
							<label>Password</label>
							<input type="password" class="form-control" name="password">
						</div>
						<button class="btn btn-primary" name="submit">Submit</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
// jk ada tombol submit(tombol submit ditekan)
if (isset($_POST["submit"]))
{

	$email = $_POST["email"];
	$password = $_POST["password"];
	// lakukan kuery ngecek akun di tabel pelanggan di db
	$ambil = $koneksi->query("SELECT * FROM pelanggan
		WHERE email='$email' AND password='$password'");

	// ngitung akun yg terambil
	$akunyangcocok = $ambil->num_rows;

	// jika 1 akun yang cocok, maka diloginkan
	if($akunyangcocok==1)
	{
		//anda sukses login
		//mendapatkan akun dalam bentuk array
		$akun = $ambil->fetch_assoc();
		// simpan di session pelanggan
		$_SESSION["pelanggan"] = $akun;
		echo "<script>alert('Login Sukses');</script>";
		echo "<script>location='index.php';</script>";
	}
	else
	{
		// anda gagal login
		echo "<script>alert('Maaf email atau password yang anda masukkan salah');</script>";
		echo "<script>location='login.php';</script>";
	}
}

?>

