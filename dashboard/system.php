<?php  

	require_once "../dbconfig.php";

	if (!$user->isLoggedIn()) {  
        header("location:/mading"); //Redirect ke halaman login  
    }  

    $getDataMessage   = $user->getDataMessage();

    header('Content-Type: json/application');

    $tampungNamaUser 	= [];
    $tampungPesan 		= [];

    foreach ($getDataMessage as $key) {
    	array_push($tampungNamaUser, $key['nama_user']);
    	array_push($tampungPesan, $key['isi_pesan']);
    }

    $data = [];

    $data['from'] = $tampungNamaUser;
    $data['isi_pesan'] = $tampungPesan;
	
	echo json_encode($data);exit;

?>

	<?php foreach ($getDataMessage as $data): ?>
        <li class="show_data" data-toggle="modal" data-target="#modal-default" data-from="<?= $data['nama_user']; ?>"
          data-judul="<?= $data['judul_pesan']; ?>" data-isi="<?= $data['isi_pesan']; ?>" data-nama="<?= $data['nama_user']; ?>"><!-- start message -->
            <a href="#">
              <div class="pull-left">
                <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
              </div>
              <h4>
                <p style="font-size:10px;"> From : <?= $data['nama_user']; ?> </p>
                <p style="font-size:10px;"> Via<span style="margin-left: 11px;"></span>: Admin </p>
              </h4>
              <h4> <?= $data['isi_pesan']; ?> </h4>
            </a>
        </li>
  	<?php endforeach; ?>
