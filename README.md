# The Slotted Counter / Sharding Counters Pattern

PHP Doctrine/Symfony implementation of the slotted counter / sharding counters pattern as described by Sam Lambert [https://planetscale.com/blog/the-slotted-counter-pattern](https://planetscale.com/blog/the-slotted-counter-pattern).

## Usage Example

*$record_type* (int) - The type of counter.

*$record_id* (int) - Identifies what we are counting.

``$this->slottedCounters->incrementByTypeAndId($record_type, $record_id);``

For example, say we want a hit counter for page requests, we could use $record_type = 200 for a success counter and $record_type = 403 for a forbidden counter. The $record_id in this case would be the page identifier.

## License

This is free software....

Paul Hempshall - [https://www.paulhempshall.com](https://www.paulhempshall.com)
