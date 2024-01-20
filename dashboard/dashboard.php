

 <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

<!-- Main content -->
    <section class="content konten">

      <div class="row" id="taro_isi_dashboard">


      </div>
     
    </section>
<!-- /.content -->

<script type="text/javascript">
  $(document).ready( function () {

    $("#username").html("Kinis")

    setInterval(function(){
      let xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          let dataNotif = JSON.parse(this.responseText).jumlah_notif
          // console.log(JSON.parse(this.responseText).display_html_approve);
          $("#taro_isi_dashboard").html(JSON.parse(this.responseText).display_html_approve)
          $(".portfolio").click(function(e) {
            let from_nama = $(this).data('from')
            alert(from_nama);
          })
        }
      };

      xhttp.open("GET", "data.php", true);
      xhttp.send();
    }, 1000)

  });
  
</script>