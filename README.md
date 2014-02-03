Geolocation test web app on heroku
----------------------------------

![screen-shot](https://f.cloud.github.com/assets/72997/2062212/c37c08e8-8c80-11e3-814c-2f87ac0cad8f.png)

How to setup
------------

```
> heroku create --buildpack https://github.com/heroku/heroku-buildpack-php
> git remote add heroku ${URL}
> heroku config:add TZ="Asia/Tokyo"
> heroku addons:add cleardb
> git push heroku master
```

Author
------
Keiji Matsuzaki <futoase@gmail.com>

LICENSE
-------

MIT.
