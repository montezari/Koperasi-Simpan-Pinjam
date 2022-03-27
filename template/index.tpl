<style>
.jam {
  font-family: tahoma;
  font-size: 10px;
  letter-spacing:1px;
  border:0px;
  background-color: #fff;
  color: #000;
  padding-top:3px;
  text-align: right;
}
</style>
<script language="javascript">
function dopindahproyek(){
    
  if(confirm('Anda yakin ingin pindah data proyek?')){
    window.location= "?mode=pilihproyek";
  }  
}
</script>
<!-- Placed js at the end of the document so the pages load faster -->
<!--Core js-->
<script src="js/lib/jquery.js"></script>
<script src="assets/jquery-ui/jquery-ui-1.10.1.custom.min.js"></script>
<script src="bs3/js/bootstrap.min.js"></script>
<script src="js/accordion-menu/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/scrollTo/jquery.scrollTo.min.js"></script>
<script src="js/nicescroll/jquery.nicescroll.js" type="text/javascript"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/flot-chart/excanvas.min.js"></script><![endif]-->
<script src="assets/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="assets/skycons/skycons.js"></script>
<script src="assets/jquery.scrollTo/jquery.scrollTo.js"></script>
<script src="assets/cdnjs/jquery.easing.min.js"></script>
<script src="assets/calendar/clndr.js"></script>
<script src="assets/cdnjs/underscore-min.js"></script>
<script src="js/custom-select/jquery.customSelect.min.js" ></script>
<script src="js/validation/js/bootstrapValidator.js" type="text/javascript"></script>
<script src="js/timer.js" ></script>

<body>
<section id="container">
    <!--header start-->
    <header class="header fixed-top clearfix">
        <!--logo start-->
        <div class="brand">
            <a href="index.php" class="logo" style="margin-left:0;">
                <img src="images/logo.png" alt="">
            </a>
            <div class="sidebar-toggle-box">
                <div class="fa fa-bars"></div>
            </div>
        </div>
        <!--logo end-->
		<div class="nav notify-row" id="top_menu">
		  <div style="padding-top:5px; font-size:16px;">Koperasi DutaKSP</div>
		</div>
        <div class="top-nav clearfix">
            <!--search & user info start-->
            <ul class="nav pull-right top-menu">
                <li>
                    <input type="text" class="form-control search" placeholder=" Search">
                </li>
                <!-- user login dropdown start-->
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <img alt="" src="images/no-person.jpg">
                    <span class="username">[var._SESSION.cLoggedName;noerr;]</span></a>
			      <div><form name='jam'><input type='text' class='jam' id='jamDisplay'></form></div>
                    <ul class="dropdown-menu extended logout">
                        <li><a href="?m=admin.chpass"><i class="fa fa-key"></i>Ganti Password</a></li>
                        <li><a href="?m=admin.auth&logout=1"><i class="fa fa-sign-out"></i>Log Out</a></li>
                    </ul>
                </li>
                <!-- user login dropdown end -->
            </ul>
            <!--search & user info end-->
        </div>
    </header>
    <!--header end-->
    <!--sidebar start-->
    <aside>
        <div id="sidebar" class="nav-collapse">
            <!-- sidebar menu start-->
            <ul class="sidebar-menu" id="nav-accordion">
                <li>
                    <a class="active" href="index.html">
                        <i class="fa fa-dashboard"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="sub-menu">
                	[onload_1;block=li;when [var._SESSION.Logged;noerr]=='ADMIN']
                    <a href="javascript:;">
                        <i class="fa fa-users"></i>
                        <span>User Web</span>
                    </a>
                    <ul class="sub">
                        <li><a href="?m=user.user">User List</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-book"></i>
                        <span>Simpanan</span>
                    </a>
                    <ul class="sub">
                        <li><a href="?m=simpan.buku">Buku Simpanan</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-bars"></i>
                        <span>Pinjaman</span>
                    </a>
                    <ul class="sub">
                        <li><a href="?m=pinjam.kartu">Kartu Pinjaman</a></li>
                    </ul>
                </li>
            </ul>
            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
		[var.~content;noerr]
        </section>
    </section>
    <!--main content end-->
</section>
<!--common script init for all pages-->
<script src="js/scripts.js"></script>
<script>StartTheTimer();</script>
<!--script for this page-->
</body>
</html>