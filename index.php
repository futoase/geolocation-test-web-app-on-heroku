<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8"/>
    <title>Geolocation test</title>
    <script type="text/javascript" src="//code.jquery.com/jquery-2.1.0.min.js"></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src=//maps.googleapis.com/maps/api/js?sensor=false&v=3.14"></script>
    <script type="text/javascript" src="gmaps.min.js"></script>
    <script type="text/javascript" src="geolocation.js"></script>
  </head>

  <body>
  <nav class="navbar navbar-default" role="navigation">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Geolocation test</a>
    </div>
  </nav>

  <div class="container">
    <button id="geo-confirm" type="button" class="btn btn-primary">現在位置の情報を投稿する</button> 
  </div>

  <div class="container">
    <h1>住所</h1>
    <p id="message">取得中...</p>
  </div>

  <div class="container">
    <h1>地図</h1>
    <div id="map" class="img-thumbnail" style="width: 720px; height: 480px;"></div> 
  </div>

  <div class="container">
    <h1>Log</h1>
    <table id="geo-list" class="table">
      <thead>
        <tr>
          <th>緯度</th>
          <th>経度</th>
          <th>住所</th>
          <th>作成時刻</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
  </body>

</html>
