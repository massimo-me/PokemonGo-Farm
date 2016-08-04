
#PokemonGo Farm

Powerfull web interface to manage your bot ⚡️

![screencapture-pokemongo-farm-local-config-edit-config_helllo-massimo-me-json-1469801819390](https://cloud.githubusercontent.com/assets/5167596/17251191/f5b93ac2-55a7-11e6-9cf6-dd83c21c1ce0.png)

![screencapture-pokemongo-farm-local-config-list-1470313702697](https://cloud.githubusercontent.com/assets/5167596/17401927/bb4fd438-5a50-11e6-9a23-4413f2113b1c.png)

![screencapture-pokemongo-farm-local-bot-show-config_hello-massimo-me-json-1470313684709](https://cloud.githubusercontent.com/assets/5167596/17401928/bb512f0e-5a50-11e6-899d-4bbbeafcb223.png)

#@Wip

- [x] Build config.json
- [x] Config list
- [X] Delete config
- [X] Edit config
- [X] Run PokemonGo-Bot
- [ ] View activity

##Install 

Clone

`git clone --recursive -b master https://github.com/ChiarilloMassimo/PokemonGo-Farm.git`

----
PHP Dependencies

Download Composer

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === 'e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

Install dependencies

`composer.phar install`

---

Web Dependencies

Install Bower

`npm install -g bower`

Install dependencies

`bower install`

---

Copy and past your `parameters.json`

`cp app/parameters.json.dist app/parameters.json`

##Run ⚡️
`php -S 127.0.0.1:8080 -t web`