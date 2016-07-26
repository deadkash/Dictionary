Dictionary
===========

"Dictionary" test application. Test your knowledge of words.

Installation
-------------

Clone the repository and enter the folder

    git clone https://github.com/deadkash/Dictionary.git
    
Run composer installation
    
    composer install
     
Create your database if it doesn`t exists

    php app/console doctrine:database:create --if-not-exists
    
Run DB schema update
    
    php app/console doctrine:schema:update --force
    
Load test data in the DB
    
    php app/console doctrine:fixtures:load
    
Install vendors assets with the Bower
    
    bower install
    
Run server
    
    php app/console server:start