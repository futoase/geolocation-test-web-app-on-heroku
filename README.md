Geolocation test web app on heroku
----------------------------------

How to setup
------------

```
> heroku create --buildpack https://github.com/heroku/heroku-buildpack-php
> git remote add heroku ${URL}
> heroku config:add TZ="Asia/Tokyo”
> heroku addons:add cleardb
> git push heroku master
```

Author
------
Keiji Matsuzaki <futoase@gmail.com>

LICENSE
-------

MIT.
