# war

A random based war simulation.

## Installation

create `.env.dev.local` file, override the `DATABASE_URL` parameter  
Run `composer dump-env dev`  
Run `compser install`  
Run `php bin/console doctrine:database:create`  
Run `php bin/console doctrine:migrations:migrate`
