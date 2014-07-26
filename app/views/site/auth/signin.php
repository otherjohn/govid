<?php include('header.php');?>

    <div class="container">
      <div class="col-xs-12 topformpad">
        <div class="col-xs-4"></div>
        <div class="col-xs-4 apptitle">Please sign in to authorize Health.gov</div>
        <div class="col-xs-4"></div>
      </div>
      <div class="col-xs-4"></div>
            <form class="form col-xs-4" action="user.php?user" method="post" id="registrationForm">
                      <div class="form-group">                          
                          <div class="col-xs-12 formpad">
                              <input type="text" class="form-control" name="username" id="first_name" placeholder="username or email" title="enter your username.">
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-xs-12 formpad">
                              <input type="password" class="form-control" name="password" id="password" placeholder="password" title="enter your password.">
                          </div>
                      </div>
                      <div class="form-group">
                           <div class="col-xs-12 formpad">
                                <br>
                                <button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
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
