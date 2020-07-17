#Enum

```php
use bohyn\Enum\Enum;

// To create new enum, you just have to define constants
class MyEnum extends Enum {

  public const STATUS_NEW = 1;
  public const STATUS_ACTIVE = 2;
  public const STATUS_INACTIVE = 3;
  public const STATUS_DELETED = 4;
}

MyEnum::getValidValues(); // [1, 2, 3, 4]

$myEnum = new MyEnum(5); // throws bohyn\Enum\EnumException
$myEnum = new MyEnum(MyEnum::STATUS_ACTIVE);


// returns enum value
$myEnum->get(); // 1

// comparisons can be by value or by instance
$myEnum->equals(1); // true
$myEnum->equals(2); // false
$myEnum->equals(new MyEnum(MyEnum::STATUS_DELETED)); // false

// Check if enum value is one of values in array
$myEnum->equalsAny([1, 2]); // true
$myEnum->equalsAny([2, 3, 4]); // false

(string)$myEnum; // 1

MyEnum::isValid(1); // true
MyEnum::isValid(5); // false
```

#MultiEnum

```php
use bohyn\Enum\MultiEnum;

class MyMultiEnum extends MultiEnum {

    public const COLOR_BLUE = 1;
    public const COLOR_RED = 2;
    public const COLOR_GREEN = 3;
    public const COLOR_BLACK = 4;
}

$myEnum = new MyMultiEnum([MyMultiEnum::COLOR_BLUE, MyMultiEnum::COLOR_RED]);

$myEnum->get(); // [1, 2]

$myEnum->equals([1, 2]); // true
$myEnum->equals(new MyMultiEnum([MyMultiEnum::COLOR_BLUE, MyMultiEnum::COLOR_RED])); // true
$myEnum->equals([1, 3]); // false
$myEnum->equals([1]); // false

$myEnum->matchAny([1, 2, 3]); // true
$myEnum->matchAny([1]); // true
$myEnum->matchAny([3]); // false

(string)$myEnum; // 1,2

MyMultiEnum::isValid([3, 4]); // true
MyMultiEnum::isValid(2); // true
MyMultiEnum::isValid(5); // false
MyMultiEnum::isValid([4, 5]); // false

```
