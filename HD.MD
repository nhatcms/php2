## Doctrine DBAL

**1. Khái niệm**

**Doctrine DBAL (Database Abstraction Layer)** là một **lớp trừu tượng cơ sở dữ liệu** trong PHP, giúp bạn làm việc với cơ sở dữ liệu một cách dễ dàng và linh hoạt hơn.

Nó cung cấp các công cụ để thực hiện truy vấn SQL, quản lý kết nối, và xử lý dữ liệu mà không cần viết quá nhiều mã SQL thuần.

**Tại sao nên dùng**

- **Dễ bảo trì và mở rộng:** Việc tách biệt logic xử lý cơ sở dữ liệu ra khỏi mã nguồn chính giúp ứng dụng dễ dàng bảo trì và mở rộng.
- **Chuyển đổi hệ quản trị cơ sở dữ liệu dễ dàng:** Chỉ cần thay đổi cấu hình mà không cần sửa đổi nhiều mã nguồn khi chuyển từ MySQL sang PostgreSQL hoặc các hệ quản trị khác.

- **Bảo mật tốt hơn:** Các câu truy vấn sử dụng Prepared Statements giúp bảo vệ ứng dụng khỏi SQL Injection.

**2. Cách cài đặt**

```
composer require doctrine/dbal
```

**3. Cách dùng cơ bản**
Xây dựng lớp Database trả lại kết nối **\$connection** và lệnh truy vấn **\$queryBuilder**

```php
//Database.php

namespace App\Common;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;

class Database
{
    private static $connection = null;
    private static $queryBuilder = null;

    public static function getConnection()
    {
        if (self::$connection === null) {
            $connectionParams = [
                'dbname'   => 'vuejs',
                'user'     => 'root',
                'password' => '',
                'host'     => '127.0.0.1',
                'driver'   => 'pdo_mysql',
                'port'     => '3307',
            ];

            try {
                self::$connection = DriverManager::getConnection($connectionParams);
                self::$queryBuilder = self::$connection->createQueryBuilder();
            } catch (Exception $e) {
                die('Lỗi kết nối: ' . $e->getMessage());
            }
        }

        return self::$connection;
    }

    public static function getQueryBuilder()
    {
        return self::$queryBuilder;
    }
}

```

Sử dụng Qurey Builder trong Model để truy vấn dữ liệu

```php

namespace App\Models;

use App\Common\Database;

class UserModel
{
    private $queryBuilder;
    private $connection;


    public function __construct()
    {
        $this->connection = Database::getConnection();
        $this->queryBuilder = Database::getQueryBuilder();
    }

    public function getAllUsers()
    {
        // Select * from users
        $stmt = $this->queryBuilder->select('*')
            ->from('users');

        return $stmt->fetchAllAssociative();
    }
}

```

```php
// Select * from users where id = '5';
public function getAllUsers()
{
    $id = '5';
    $stmt = $this->queryBuilder->select('*')
        ->from('users')
        ->where('id = :id')
        ->setParameter('id', $id);

    return $stmt->fetchAllAssociative();
}
```

```php
// Select * from users where role = 'user' and name like '%Văn%';
public function getAllUsers()
{
    $role = 'user';
    $nameSearch = 'Văn';
    $stmt = $this->queryBuilder->select('*')
        ->from('users')
        ->where('role = :role')
        ->setParameter('role', $role)
        ->andWhere('name LIKE :name')
        ->setParameter('name', '%' . $nameSearch . '%');

    return $stmt->fetchAllAssociative();
}
```

```php
// select * from users where id < 5 or id > 10
public function getAllUsers()
{
    $idMin = '5';
    $idMax = '10';

    $stmt = $this->queryBuilder->select('*')
        ->from('users')
        ->where('id < :idMin')
        ->setParameter('idMin', $idMin)
        ->orWhere('id > :idMax')
        ->setParameter('idMax', $idMax);

    return $stmt->fetchAllAssociative();
}
```

```php
// SELECT products.*, category.name AS category_name
// FROM products AS p
// JOIN category AS c ON p.category_id = c.id;
public function getAllUsers()
{
    $stmt = $this->queryBuilder
        ->select('p.*', 'c.name')
        ->from('products', 'p')
        ->join('p', 'category', 'c', 'p.category_id = c.id');

    return $stmt->fetchAllAssociative();
}
```

**Insert**

```php
// INSERT INTO `users`(`name`, `email`, `password`) VALUES ('a', 'b', 'c')
public function getAllUsers()
{
    $name = "Nguyễn Văn B";
    $email = "babc@gmail.com";
    $password = "123456";
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $this->queryBuilder->insert('users')
        ->values([
            'name' => ':name',
            'email' => ':email',
            'password' => ':password',
        ])
        ->setParameters([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
        ]);

    $this->connection->executeStatement($stmt->getSQL(), $stmt->getParameters());
}
```

**Update**

```php
// UPDATE `users` SET `name`='a',`email`='b',`password`='c' WHERE id = '13'

public function getAllUsers()
{
    $id = "13";
    $name = "Nguyễn Văn C";
    $email = "qwert@gmail.com";
    $password = "654321";
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $this->queryBuilder->update('users')
        ->where('id = :id')
        ->set('name', ':name')
        ->set('email', ':email')
        ->set('password', ':password')
        ->setParameters([
            'id'    => $id,
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
        ]);

    $this->connection->executeStatement($stmt->getSQL(), $stmt->getParameters());
}
```

**Xóa**

```php
// DELETE FROM `users` WHERE id = '14'
public function getAllUsers()
{
    $id = 14;

    $stmt = $this->queryBuilder->delete('users')
        ->where('id = :id')
        ->setParameter('id', $id);

    $this->connection->executeStatement($stmt->getSQL(), $stmt->getParameters());
}
```
