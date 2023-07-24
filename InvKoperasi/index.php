<?php
// Koneksi Database
$server="localhost";
$user="root";
$password="";
$database="dbkoperasi2023";

// Buat Koneksi
$koneksi = mysqli_connect($server, $user, $password, $database) or die(mysqli_error($koneksi));

// Kode Otomatis
$q = mysqli_query($koneksi, "SELECT kode FROM tbarang order by kode desc limit 1");
$datax = mysqli_fetch_array($q);
if ($datax){
  $no_terakhir = substr ($datax['kode'], -3);
  $no = $no_terakhir + 1;
  
  if ($no > 0 and $no <10){
    $kode = "00".$no;
  }else if($no > 0 and $no <100){
    $kode = "0".$no;
  }else if($no >100){
    $kode = $no;
  }else{
    $kode = "001";
  }
}

$tahun = date('Y');
$vkode = "INV-" . $tahun . $kode;
// INV-2023-001


// Jika tombol simpan diklik
if(isset($_POST['bsimpan'])){


    // apakah data akan diedit atau disimpan baru
    if(isset($_GET['hal']) == "edit"){
      // data akan di edit  
    $edit = mysqli_query($koneksi, "UPDATE tbarang SET
                                        kode = '$_POST[tkode]',
                                        nama = '$_POST[tnama]',
                                        asal = '$_POST[tasal]',
                                        jumlah = '$_POST[tjumlah]',
                                        satuan = '$_POST[tsatuan]',
                                        tanggal_diterima = '$_POST[ttanggal_diterima]'
                                    WHERE id_barang = '$_GET[id]'
                        ");
    // jika data berhasil diupdate
    if($edit){
      echo "<script>
      alert('Simpan Data Berhasil!');
      document.location='index.php'      
      </script>";
    }
    else{
      echo "<script>
      alert('Simpan Data Gagal!');
      document.location='index.php'      
      </script>";
    }
    }else{
   //data akan disimpan baru
   $simpan = mysqli_query($koneksi, " INSERT INTO tbarang (id_barang, kode, nama, asal, jumlah, satuan, tanggal_diterima)
   VALUE ( '$_POST[NULL]', 
           '$_POST[tkode]', 
           '$_POST[tnama]', 
           '$_POST[tasal]', 
           '$_POST[tjumlah]', 
           '$_POST[tsatuan]', 
           '$_POST[ttanggal_diterima]')
");
        //  Uji Data = Sukses
        if($simpan){
        echo "<script>
        alert('Simpan Data Berhasil!');
        document.location='index.php'      
        </script>";
        }
        else{
        echo "<script>
        alert('Simpan Data Gagal!');
        document.location='index.php'      
        </script>";
        }
    }
    
  }


// deklrasi variabel untuk menampung data yang akan diedit

$vnama="";
$vasal="";
$vjumlah="";
$vsatuan="";
$vtanggal_diterima="";


// Pengujian Tombol Edit / Hapus diklik

if(isset($_GET['hal'])){

  // Pengujian Edit Data
  if($_GET['hal'] == "edit"){
  
    //tampilan data yang akan diedit
    $tampil = mysqli_query($koneksi, "SELECT * FROM tbarang WHERE id_barang = '$_GET[id]' ");
    $data = mysqli_fetch_array($tampil);
    if($data){
      // Jika data ditemukan, maka akan ditampung kedalam Variabel
      $vkode = $data['kode'];
      $vnama = $data['nama'];
      $vasal = $data['asal'];
      $vjumlah = $data['jumlah'];
      $vsatuan = $data['satuan'];
      $vtanggal_diterima = $data['tanggal_diterima'];
    }
  }else if($_GET['hal'] == "hapus"){
    // Persiapan hapus data
    $hapus = mysqli_query($koneksi, "DELETE FROM tbarang WHERE id_barang = '$_GET[id]' ");
    if($hapus){
      echo "<script>
      alert('Hapus Data Berhasil!');
      document.location='index.php'      
      </script>";
      }
      else{
      echo "<script>
      alert('Hapus Data Gagal!');
      document.location='index.php'      
      </script>";
      }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CRUD Inventaris Koperasi</title>
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
      crossorigin="anonymous"
    />
  </head>

  <body>
    <!-- Start Container -->
    <div class="container">
      <div class="row text-center">
        <div class="col-md-8 mx-auto ">
        <h4 class="text-center mt-1 mb-0">Data Inventaris</h4>
        <h3
          class="text-center mt-1"
          style="font-weight: 500; text-transform: uppercase"
        >
          Koperasi SMA Tenjolaya
        </h3>
      </div>
      </div>
      <!-- Awal Row -->

      <div class="row">
        <div class="col-md-8 mx-auto my-4">
          <!-- Awal card 1 -->
          <div class="card">
            <h4 class="card-header bg-primary text-light">
              Form Input Data Barang
            </h4>
            <div class="card-body my-3">
              <!-- Awal Form -->
              <form method="POST">
                <div class="mb-3">
                  <label class="form-label">Kode Barang</label>
                  <input
                    type="text"
                    name="tkode"
                    value="<?=$vkode?>"
                    minlength="12"
                    maxlength="12"
                    class="form-control"
                    placeholder="Input Kod. Barang"
                  />
                </div>
                <div class="mb-3">
                  <label class="form-label">Nama Barang</label>
                  <input
                    type="text"
                    name="tnama"
                    value="<?=$vnama?>"
                    class="form-control"
                    placeholder="Masukkan Nama Barang"
                  />
                </div>
                <div class="mb-3">
                  <label class="form-label">Asal Barang</label>

                  <select
                    class="form-select"
                    name="tasal" 
                    >
                    <option value="<?=$vasal?>"><?=$vasal?></option>
                    <option value="Pembelian">Pembelian</option>
                    <option value="Hibah">Hibah</option>
                    <option value="Bantuan">Bantuan</option>
                    <option value="Sumbangan">Sumbangan</option>
                  </select>
                </div>
                <!-- Memilih informasi -->
                <div class="row p-auto">
                  <div class="col-4">
                    <div class="mb-3">
                      <label class="form-label">Jumlah Barang</label>
                      <input
                        type="number"
                        name="tjumlah"
                        value="<?=$vjumlah?>"
                        class="form-control"
                        placeholder="Masukkan Jumlah Barang"
                      />
                    </div>
                  </div>
                  <div class="col">
                    <div class="mb-3">
                      <label class="form-label">Satuan</label>
                      <select
                        class="form-select"
                        name="tsatuan"
                        aria-label="Default select example"
                      >
                        <option value="<?=$vsatuan ?>"><?=$vsatuan ?></option>
                        <option value="Unit">Unit</option>
                        <option value="Kotak">Kotak</option>
                        <option value="Pcs">Pcs</option>
                        <option value="Pack">Pack</option>
                      </select>
                    </div>
                  </div>
                  <div class="col">
                    <div class="mb-3">
                      <label class="form-label">Tanggal Diterima</label>
                      <input
                        type="date"
                        name="ttanggal_diterima"
                        value="<?=$vtanggal_diterima ?>"
                        class="form-control"
                        placeholder="Masukkan Jumlah Barang"
                      />
                    </div>
                  </div>
                </div>
                <div class="text-center">
                  <hr style="color: rgb(0, 34, 169); margin: 1rem" />
                  <button
                    class="btn btn-primary"
                    name="bsimpan"
                    type="submit"
                    style="width: 15%"
                  >
                    Simpan
                  </button>
                  <button
                    class="btn btn-outline-primary"
                    name="bkosongkan"
                    type="reset"
                    style="width: 15%"
                  >
                    Reset
                  </button>
                </div>
              </form>
              <!-- Akhir Form -->
            </div>
            <div class="card-footer bg-secondary"></div>
          </div>
          <!-- Akhir card 1 -->
        </div>
      </div>
      <!-- Akhir Row -->

      <!-- Awal card 2 -->
      <div class="card text-center">
        <h4 class="card-header bg-primary text-light">Data Barang</h4>
        <div class="card-body">
          <div class="col-md-6 mx-auto">
            <form method="POST">
              <div class="input-group mb-3">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Masukan Kata Kunci"
                  name="tcari"
                  value="<?=$_POST['tcari']?>"
                />
                <button class="btn btn-primary" name="bcari" type="submit">
                  Cari
                </button>
                <button
                  class="btn btn-secondary ms-1"
                  type="submit"
                  style="border-radius: 0"
                  name="breset"
                >
                  Reset
                </button>
              </div>
            </form>
          </div>
          <!-- Isi Card Body -->
          <table class="table table-striped table-hover table-bordered">
            <tr>
              <th>No.</th>
              <th>Kode Barang</th>
              <th>Nama Barang</th>
              <th>Asal Barang</th>
              <th>Jumlah Barang</th>
              <th>Tanggal Diterima</th>
              <th>Sunting</th>
            </tr>
            
            <?php 
            // Persiapan menampilkan data
            $nomor = 1;

            // Untuk Melakukan Pencarian Data
          if(isset($_POST['bcari'])){
             // Tampilkan data yang dicari
             $keyword = $_POST['tcari'];
             $q = "SELECT * FROM tbarang WHERE kode like '%$keyword%' or nama like '%$keyword%' order by id_barang desc";
          }else{
            $q = "SELECT * FROM tbarang order by id_barang desc";
          }


            $tampil = mysqli_query($koneksi, $q);
            while ($data = mysqli_fetch_array($tampil)) {
            
            ?> 
            <tr>
              <td><?=$nomor++ ?></td>
              <td><?=$data['kode'] ?></td>
              <td><?=$data['nama'] ?></td>
              <td><?=$data['asal'] ?></td>
              <td><?=$data['jumlah'] ?> <?= $data['satuan']?></td>
              <td><?=$data['tanggal_diterima'] ?></td>
              <td>
                <a href="index.php?hal=edit&id=<?=$data['id_barang'] ?>" class="btn btn-primary"> Edit </a>
                <a href="index.php?hal=hapus&id=<?=$data['id_barang'] ?>" class="btn btn-danger" onclick="return confirm('Apakah anda yakin untuk menghapus data ini?')"> Remove </a>
              </td>
            </tr>

            <?php } ?>

          </table>
          <!-- Akhir Card Body -->
        </div>
        <div class="card-footer bg-light ">
        <button
                    class="btn btn-danger"
                    name="bkosongkan"
                    type="reset"
                    style="width: 80%"
                    href="index.php"
                  >
                    Logout
                  </button>

        </div>
      </div>
      <!-- Akhir card 2 -->
    </div>
    <!-- End Container -->

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
