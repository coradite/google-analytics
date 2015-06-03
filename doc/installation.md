# Installation

To install the Yii Google Analytics package, you will need [Composer](http://getcomposer.org). It's a PHP 5.3+
dependency manager which allows you to declare the dependent libraries your project needs and it will install &
autoload them for you.

## Set up Composer

Composer comes with a simple phar file. To easily access it from anywhere on your system, you can execute:

```
$ curl -s https://getcomposer.org/installer | php
$ sudo mv composer.phar /usr/local/bin/composer
```

## Define dependencies

Create a ``composer.json`` file at the root directory of your project and simply require the
``coradite/yii-google-analytics`` package:

```
{
    "require": {
        "coradite/yii-google-analytics": "*"
    }
}
```

## Install dependencies

Now, you have define your dependencies, you can install them:

```
$ composer install
```

Composer will automatically download your dependencies & create an autoload file in the ``vendor`` directory.

## Get your credentials

As you have read in the README, the library allows you to request the google analytics service without user interaction.
In order to make it possible, you need to create a Google Service Account. Here, the explanation:

 * Create a [Google App](http://code.google.com/apis/console).
 * Enable the Google Analytics service.
 * Create a service account on [Google App](http://code.google.com/apis/console) (Tab "API Access", choose
   "Create client ID" and then "Service account").
 * You should have received the `client_id` and `profile_id` in a email from Google but if you don't, then:
   * Check the "API Access" tab of your [Google App](http://code.google.com/apis/console) to get your client_id (use
     "Email Adress")
   * Check the [Google Analytics](http://www.google.com/analytics) admin panel (Sign in -> Admin -> Profile column ->
     Settings -> View ID) for the profile_id (don't forget to prefix the view ID by ga:)
 * Download the private key and put it somewhere on your server (for instance, you can put it in `app/bin/`).

At the end, you should have:

 * `client_id`: an email address which should look like `XXXXXXXXXXXX@developer.gserviceaccount.com`.
 * `profile_id`: a view ID which should look like `ga:XXXXXXXX`.
 * `private_key`: a PKCS12 certificate file


## Yii Configuration

Now you just have to add the following array to your config file:

``` php
    components => [
    
        'gapi'=>[
          'class'=>'Coradite\GoogleAnalytics\Connection',
          'clientEmail' => 'YOUR_CLIENT_ID',
          'privateKeyFile' => '/PATH_TO_YOUR/private_key.p12',
          'defaultProfileId'=> 'YOUR_PROFILE_ID', //optional. Can be set at time of query
        ],
        
    ]
```

You can call the component what ever you like instead of `gapi`.
