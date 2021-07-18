### Vtex masterdata coupon validate

**Run a coupon validation in an entity from vtex masterdata**:

```
Search a coupon and if not exists save it on file cupons/chosen_name_file.txt

$ php run.php
```

**Run a search for duplicate coupons on a chosen file**:

```
Run a validation on the chosen file and create a new file without possible duplication

$ php duplicateRemove.php
```

**Insert data in an entity from vtex masterdata**:

```
Save the coupons from chosen file in an entity

$ php saveCoupon.php
```