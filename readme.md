# Thank you

Thanks for purchasing Josh admin theme

We are sure you will Enjoy using it!

# Installation

Git repo installation is a bit different than the original one because here all files copied already and everything is configured properly

infact this will take less time compared to installing from CodeCanyon's files

##### add vendors
we don't push vendors to git as they can grow like anything (a default feature of laravel or any composer related projects)

````composer install````

## run virtual machine
* in / copy ```.env.example``` to ```.env```
* Run ```vagrant up```
* Add the following lines to your machine's ```hosts``` file:

```
192.168.10.10 craigsweed.test
```

* Open it in your browser
```
craigsweed.test
```


#### permissions

````

chmod -R 755 storage

chmod 755 bootstrap/cache

````

If you are on linux/ mac you can run below command to chown it.

````
chown -R www-data /var/www

````

#### develop helper script

Run vagrant 

```
./develop up
```

Stop the vagrant 

```
./develop halt
```

Connect to the box  

```
./develop ssh
```

Restore db dump
```
./develop db-restore
```

Make db dump
```
./develop db-dump
```

Running composer commands in the box
```
./develop composer <command>
```

Running artisan commands in the box
```
./develop artisan <command>
```

Running npm commands in the box
```
./develop npm <command>
```

#### database credentials

open ````.env```` and modify database details with yours

#### add tables to databaes

```` php artisan migrate ````

#### add admin to users table

```` php artisan db:seed --class=AdminSeeder````

#### compile assets

Make sure you have [nodejs](https://nodejs.org) installed in your system

install gulp, bower globally

```npm install -g gulp bower ```

install local packages

```npm install```

get bower components

```gulp bower```

move assets to public

``` gulp ```


# Congratulations
open your website and now it should be fully working :)

***

# Bugs

To report bugs, please click on "Issues" in right menu and check if the bug is reported already
and if it doesn't exists please create new issue by clicking **New issue**

# pull requests

If you would like to improve code :) or fix any bug or add new feature,

please send a pull request and we will be thankful to you for your work! :D
