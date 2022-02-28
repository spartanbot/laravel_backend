<!DOCTYPE html>
<html>
  <head>
    <title>Welcome Email</title>
  </head>
  <body>
    <h2>Welcome to the site</h2>
    <br/>
    Please click on the below link to verify your email account
    <br/>
    <a href="{{url('reset-password',$detail['token'])}}" class="btn btn-primary">Reset Your Password</a>
  </body>
</html>