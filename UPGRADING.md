# Upgrading

## From v1 to v2

The `startFrom` parameter was added, so you may need to change the order of your passed arguments or use named arguments:

```php
// Instead of doing this:
Transaction::ofLast7Days(DateRange::INCLUSIVE);
// Do this:
Transaction::ofLast7Days(customRange: DateRange::INCLUSIVE);
```
