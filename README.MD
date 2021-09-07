#### PHP Batch Processing

**Update dependencies**:

* PHP
  - `composer update`

* Node
  - `npm install`

* Docker
  - `docker container run -d --name redis -p 6379:6379 redis`

**Commit Pattern**:

To run a new commit, exec only:

`git commit`

**Exec process**:

* Workers
  - `php src/workers/WorkerSearchCoupon.php`
  - `php src/workers/WorkerSaveCoupon.php`

* Main instance
  - `php index.php`
