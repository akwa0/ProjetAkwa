 rm migrations/V*
        symfony console d:d:drop --force
        symfony console d:d:create 
        symfony console make:migration
        symfony console doctrine:migrations:migrate --no-interaction
        symfony console doctrine:fixtures:load