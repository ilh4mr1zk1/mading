

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
    <div class="container">
      <div class="popup-image">
        <span> &times; </span>
        <img src="../img/65afaee448de8.jpg" alt="">
      </div>
    </div>

<script type="text/javascript">
  $(document).ready( function () {

    $("#username").html("Kinis")

    setInterval(function(){
      let xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          // console.log(JSON.parse(this.responseText).display_html_approve);
          $("#taro_isi_dashboard").html(JSON.parse(this.responseText).display_html_approve)

          document.querySelectorAll('.portfolio img').forEach(image => {
            image.onclick = (e) => {
              e.preventDefault()
              document.querySelector(".popup-image").style.display = 'block';
              document.querySelector(".popup-image img").src = image.getAttribute('src')
            }
          });

          document.querySelector(".popup-image span").onclick = () => {
            document.querySelector('.popup-image').style.display = 'none'
          }

        }
      };

      xhttp.open("GET", "data.php", true);
      xhttp.send();
    }, 1000)

  });
  
</script>