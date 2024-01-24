<?php  

  if (isset($_POST['isi_title']) && isset($_POST['user_id'])) {
    // echo $_POST['isi_title'];
    $isi_title = $_POST['isi_title'];
    $user_id   = $_POST['user_id'];
    // echo $user_id; 
  } else {
    echo "ga ada";
  }
  
?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Add Announcement Form
    <small>Preview</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Forms</a></li>
    <li class="active">General Elements</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">

    <div class="col-md-5">

      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"> Form Announcement </h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form id="form_announ" method="post" enctype="multipart/form-data">
          <div class="box-body">
            <div class="form-group">
              <label for="exampleInputEmail1"> Title </label>
              <input type="text" class="form-control" name="title_ann" required id="isi_title" placeholder="Title ...">
            </div>
            <div class="form-group buatGambar">
              <div id="imgPreview">
                
              </div>
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1"> Announcement </label>
              <textarea class="form-control" style="height: 120px;" required name="announcement" id="announcement" placeholder="Announcement ..."></textarea>
            </div>
          </div>
          <!-- /.box-body -->

          <div class="box-footer">
            <button type="submit" id="kirimData" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
      <!-- /.box -->

    </div>

  </div>
  <!-- /.row -->
</section>
<!-- /.content -->

<script type="text/javascript">

  // $("#gambar").hide()
  // let getTitle = `<?= $isi_title; ?>`
  // $("#isi_title").val(getTitle)
  

  $(document).ready( function () {
    
    let allTrue   = 0;
    let untukForm = '';
    
    let xhttp = new XMLHttpRequest();
    
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        $(".buatGambar").append(JSON.parse(this.responseText).upload_img)
        $("#gambar").hide()
        const image = document.querySelector("img[id='gambar']"),
        input = document.querySelector(".fileGambar");
        let forImage  = '' 

        input.addEventListener("change", (event) => {
          let filePath          = input.value; 
          let allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;

          if (!allowedExtensions.exec(filePath)) {

            alert("Please upload file having extensions .jpg/.jpeg/.png/.gif only !");
            $("#gambar").hide()
            input.value = '';
            return false;

          } else {

            const inputFile = input.files[0]
            const limit     = 2000;
            const size      = inputFile.size/1024;

            if (size > limit) {
              
              const err = new Error(`File too big : ${(size/1000).toFixed(2)} MB`);
              $("#gambar").hide()
              input.value = '';
              alert(err);

              return false;

            } else {

              alert("Ok ukuran pas");

              // option 1 
              // if (input.files && input.files[0]) {
              //   let reader = new FileReader();
              //   reader.onload = function(e) {
              //     document.getElementById("imgPreview").innerHTML = '<img style = "width: 100%; height: 100%; margin-bottom: 15px;" src=" ' + e.target.result + ' " />';
              //   };
              //   reader.readAsDataURL(input.files[0]);
              // }

              // option 2
              $("#gambar").show()
              image.src = URL.createObjectURL(input.files[0]);
              const files = event.target.files;

              for (const file of files) {
                forImage = file.name;
              }

            }

          }

        })

        $("form#form_announ").on("submit", function(e) {
          
          e.preventDefault()

          let formData = new FormData(this);
          $.ajax({
            url         : "upload.php",
            type        : "POST",
            data        : formData,
            cache       : false,
            processData : false,
            contentType : false,
            success     : function(data) {
              $('#form_announ').trigger("reset");
              $("#gambar").hide()
              Swal.fire({
                title: "Approve",
                icon: "success"
              });
              console.log(JSON.parse(data));
              // console.log(data);
            }
          })

        });

      }
    };

    xhttp.open("GET", "data.php", true);
    xhttp.send();

  });

</script>