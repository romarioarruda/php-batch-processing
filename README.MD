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


**App exec**:

* RabbitMQ Management
  - `http://localhost:8080/#/`

* Workers
  - `php src/workers/WorkerSearchCoupon.php`
  - `php src/workers/WorkerSaveCoupon.php`

* Main instance
  - `php index.php`


#### Useful links

* [Docker Documentation](https://docs.docker.com/)
* [RabbitMQ Documentation](https://www.rabbitmq.com/getstarted.html)
* [Commitizen Documentation](https://github.com/commitizen/cz-cli)
* [Husky Documentation](https://typicode.github.io/husky/#/)