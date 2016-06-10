<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
        <link rel="stylesheet" type="text/css" href="semantic/semantic.min.css">
        
    </head>
    <body>
        <div class="ui secondary menu grid stackable" style="padding:1em;">
          <div class="active item">Dashboard</div>
          <a class="item">Meetings</a>
          <a class="item">Team</a>
          <div class="right menu">
            <a class="item">Profile</a>
          </div>
        </div>
         <router-view></router-view>
         <script type="text/javascript" src="/js/main.js"></script>
    </body>
</html>
