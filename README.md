# M2 Customer Login History Module

This module has been developed for Magento 2.3.4

The purpose of this module is to save the information of each user login in a custom database table.

This information will be displayed on the user dashboard who will have the possibility of deleting some or all of the records.

The module provides a console command that allows deleting all the records of a specific user:
bin/magento customerlogin:deletebyuser userID

or allows deleting the records of all users
bin/magento customerlogin:deletebyuser all

To prevent the log table growing infinitely, a cron-triggered routine has been included that allows you to delete old records. The Admin can establish the period of time that the records must be kept in the database from the module configuration options. It is also possible to cancel this option.

## Installation

The extension must be installed via `composer`. To proceed, run these commands in your terminal:

```
composer require orangecat/customerloginhistory
php bin/magento module:enable Orangecat_CustomerLoginHistory
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

## Note
This module depends on Orangecat Geoip, if you install this module manually, install the GeoIP module first

## Screenshot
![ScreenShot](https://github.com/olivertar/m2_login_history/blob/master/screen-shot/user_dashboard.png)
<br/><br/>
![ScreenShot](https://github.com/olivertar/m2_login_history/blob/master/screen-shot/log_history.png)
<br/><br/>
![ScreenShot](https://github.com/olivertar/m2_login_history/blob/master/screen-shot/loginhistory_system.png)
