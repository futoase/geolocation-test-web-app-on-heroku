// Geolocation test.

var BASE_MAP_URL = "https://maps.google.com/maps/api/geocode/json?sensor=true&latlng=";
var GET_URL = "/api.php?list=1"
,   POST_URL = "/api.php";

var ADMIN_POLITICAL = "administrative_area_level_1"
,   LOCALITY = "locality"
,   SUBLOCALITY = "sublocality_level_1"
,   SUBLOCALITY_2 = "sublocality_level_2";

$(document).ready(function () {

  function getGeoLocationFromDatabase(map) {
    $.get(GET_URL, function (data) {
      for (var i = 0; i < data.length; i++) {
        console.dir(data);
        $('#geo-list > tbody:last').append(
          '<tr>' + 
          '<td>' + data[i].latitude + '</td>' +
          '<td>' + data[i].longitude + '</td>' +
          '<td>' + data[i].address + '</td>' +
          '<td>' + data[i].created_at + '</td>' +
          '</tr>'
        );

        map.addMarker({
          lat: data[i].latitude,
          lng: data[i].longitude
        });

      }
    }, "json");
  };

  function getGeoLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
        var SEARCH_URL = (
          BASE_MAP_URL + 
          position.coords.latitude + 
          "," + 
          position.coords.longitude
        );

        $.get(SEARCH_URL, function(data) {
          var results = data.results;
          var address = {};
          for (var i = 0; i < results.length; i++) {
            var addressComponents = results[i].address_components;
            for (var j = 0; j < addressComponents.length; j++) {
              var component = addressComponents[j];
              if (component.types.indexOf(ADMIN_POLITICAL) != -1) {
                address.political = component.long_name;
              }
              if (component.types.indexOf(LOCALITY) != -1) {
                address.locality = component.long_name;
              }
              if (component.types.indexOf(SUBLOCALITY) != -1) {
                address.sublocality = component.long_name;
              }
              if (component.types.indexOf(SUBLOCALITY_2) != -1) {
                address.sublocality_2 = component.long_name;
              }
            }
          }

          var localAddress = $.map([
            address.political,
            address.locality,
            address.sublocality,
            address.sublocality_2
          ], function (v) {
            if (v !== undefined) { return v; }
          }).join("");

          $.post(POST_URL, {
            vote: "1",
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
            address: localAddress
          }).done(function (data) {

            map = new GMaps({
              div: '#map',
              lat: position.coords.latitude,
              lng: position.coords.longitude
            });

            map.addMarker({
              lat: position.coords.latitude,
              lng: position.coords.longitude
            });

            getGeoLocationFromDatabase(map);
          });

          $("#message").text(localAddress);

        }, "json");
      }, 
      function(err) {
        if (err.code == error.PERMISSION_DENIED) {
          alert("位置情報の取得を許可してください。");
        }
        else if (err.code == error.POSITION_UNAVAILABLE) {
          alert("位置情報が取得できませんでした。");
        }
        else if(err.code == eerror.PERMISSION_DENIED_TIMEOUT) {
          alert("もう一度やり直してみてください。");
        }
      });
    }
    else {
      alert("どうやら利用している端末だと位置情報とれないようです。");
    }
  }

  getGeoLocation();
});
