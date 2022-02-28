<!DOCTYPE html>
<html>
  <head>
    <title></title>
  </head>
  <body>
    <h2>Welcome to the site </h2>
    <br/>
    Your registered email-id is   , Please click on the below link to verify your email account
    <br/>
    <a href="{{url('user/verify', $detail['token'])}}">Verify Email</a>
  </body>
</html>