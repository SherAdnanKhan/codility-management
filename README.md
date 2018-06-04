# Setting Up Cordiality Management Project

##After Cloning Project

#### Open in IDE

####Check ".env-example" and rename it to ".env"

####Setup DB CONNECTION 

####Change APP_URL  according to your Virtualhost url

#### env file change
```ssh
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```
#####INTO
```ssh
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465 
MAIL_USERNAME=youremail@gmail.com
MAIL_PASSWORD=yourpassword
MAIL_ENCRYPTION=ssl
```
#### Goto to Config/mail.php and change 

        'address' => env('MAIL_FROM_ADDRESS', 'iamatta2416@gmail.com'),
        'name' => env('MAIL_FROM_NAME', 'Atta Ur Rehman'),
        
   #####TO
   
           'address' => env('MAIL_FROM_ADDRESS', 'youremail@gmail.com'),
           'name' => env('MAIL_FROM_NAME', 'Your Mail Name'),

  
  Step 9 : Then update or install composer
#### Then Open Terminal an go to your folder path and setup Laravel...

```sh
php artisan key:generate
php artisan config:cache
php artisan cache:clear
php artisan migrate
php artisan db:seed

```

#####After these Steps go to your application in browser
https://yourvirtualhost or localhost /

#####Login Credentials 

| Field     | Credentials|
| ------    | ------ |
| Email     |admin@codiliy.co|
| Password  |secret          |

After login Update your profile Upload Admin Avatar
####Now you are setup Codility Managment Application in your System



