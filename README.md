# Doctrine Criteria extensions for PHPStan

This extension provides following features:

* Validates entity fields to which criteria is applied.
* Depends on [phpstan-doctrine](https://github.com/phpstan/phpstan-doctrine), and includes its features.

## Usage

```
composer require otobank/doctrine-target-aware-criteria
composer require --dev otobank/phpstan-doctrine-criteria
```

```yaml
includes:
    - vendor/otobank/phpstan-doctrine-criteria/extension.neon
    - vendor/otobank/phpstan-doctrine-criteria/rules.neon
```

## Configuration

```yaml
parameters:
    doctrine:
        objectManagerLoader: bootstrap/phpstan_doctrine_manager.php
```

See: https://github.com/phpstan/phpstan-doctrine

## You must use custom criteria.

`FooCriteria`
```php
namespace App\Criteria;

use App\Entity\Foo;
use Otobank\PHPStan\Doctrine\Criteria;

class FooCriteria extends Criteria
{
    public static function getTargetClass() : string
    {
        return Foo::class;
    }
}
```

Use `FooCriteria`
```php
namespace App\Entity;

use App\Criteria\FooCriteria;

class Bar
{
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Foo", mappedBy="bar")
     */
    private $foos;

    public function getFilteredFoos()
    {
        $criteria = FooCriteria::create();
        $criteria = $criteria
            ->where($criteria->expr()->eq('fieldX', 1)) // Check if fieldX is defined in Foo class
        ;

        return $this->foos->matching($criteria);
    }
}
```

## Author

Toshiyuki Fujita - tfujita@otobank.co.jp - https://github.com/kalibora

## License

Licensed under the MIT License - see the [LICENSE](LICENSE) file for details

----

OTOBANK Inc.
