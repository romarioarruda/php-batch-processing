## Batch processing using php and queues in RabbitMQ

<br>

**Update dependencies**:

* **PHP**
  
  ```
  composer update
  ```

* **Node**
  ```
  npm install
  ```

* **RabbitMQ**
  ```
  docker container run --rm -d --hostname my-rabbit --name rabbitmq -p 8080:15672 -p 5672:5672 rabbitmq:management-alpin
  ```

<br>

**Commit Pattern**:

*To run a new commit, execute only:*

```
git commit
```

<br>

**Run application**:

* **RabbitMQ Management**
  ```
  http://localhost:8080/#/
  ```

* **Workers**
  ```
  php src/workers/WorkerSearchCoupon.php
  ```
  
  ```
  php src/workers/WorkerSaveCoupon.php
  ```

* **Service**
  ```
  php index.php
  ```

<br>

#### Useful links

* [Docker Documentation](https://docs.docker.com/)
* [RabbitMQ Documentation](https://www.rabbitmq.com/getstarted.html)
* [Commitizen Documentation](https://github.com/commitizen/cz-cli)
* [Husky Documentation](https://typicode.github.io/husky/#/)
