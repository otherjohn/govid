<?php include('header.php');?>

    <div class="container">
      <div class="col-xs-12 topformpad">
        <div class="col-xs-4"></div>
        <div class="col-xs-4 apptitle">Allow Health.gov to access the following information?</div>
        <div class="col-xs-4"></div>
      </div>
      <div class="col-xs-4"></div>
            <form class="form col-xs-4" action="user.php?user" method="post" id="registrationForm">
                      <div class="form-group">                          
                          <div class="col-xs-12 formpad">
                              <input type="checkbox" name="p1" value="1"  /> Permission A<br />
                              <input type="checkbox" name="p2" value="2"  /> Permission B<br />
                              <input type="checkbox" name="p3" value="3"  /> Permission C<br />
                              <input type="checkbox" name="p4" value="4"  /> Permission D<br />
                              <input type="checkbox" name="p5" value="5"  /> Permission E<br />
                          </div>
                      </div>
                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                                <button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Authorize</button>
                                <button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Deny</button>
                            </div>
                      </div>
                </form>
       <div class="col-xs-4"></div>


    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/dist/js/bootstrap.min.js"></script>
  </body>
</html>
