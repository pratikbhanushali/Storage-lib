# Storage-lib
Storage-lib is a PHP library written over MySQL and MongoDB to provide an abstraction to the developer from the underlying Technology

**Usage**  
Require storage-lib.php in your project.

```code
require_once(__DIR__ . 'storage-lib.php');
```


**Initialize**  
Here, we'll initialize the library for Mongo and MySQL databases
```code 
$storage_mongo = new clsStorage("Mongo");
```

```code 
$storage_sql = new clsStorage("MySQL");
```

**SET**  
This is a function used to insert a new document / row in MongoDB / MySQL. It returns Boolean values TRUE and FALSE

**MongoDB**  
```code
$is_set = $storage_mongo->set("users", $arr);
```


**MySQL**  
```code
$is_set = $storage_sql->set('users', $arr);
```

The function accepts *php array* as the second parameter.  
```code
$arr = [
	"user_id"		=>	101,
	"email"			=>	"abc@xyz.com",
	"mobile"		=>	"9999999999"
];
```