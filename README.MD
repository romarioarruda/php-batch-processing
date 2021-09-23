#### PHP Batch Processing

**Update dependencies**:

* PHP
  - `composer update`

* Node
  - `npm install`

* RabbitMQ
  - `docker container run --rm -d --hostname my-rabbit --name rabbitmq -p 8080:15672 -p 5672:5672 rabbitmq:management-alpin`

**Commit Pattern**:

To run a new commit, exec only:

`git commit`

**Exec process**:

* RabbitMQ Management
  - `http://localhost:8080/#/`

* Workers
  - `php src/workers/WorkerSearchCoupon.php`
  - `php src/workers/WorkerSaveCoupon.php`

* Main instance
  - `php index.php`
