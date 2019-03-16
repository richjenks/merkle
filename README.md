# Merkle Tree

Generates the Merkle Root for any data

# Usage

Install with [Composer](https://getcomposer.org) via `composer require richjenks/merkle` then:

```php
require 'vendor/autoload.php';
use RichJenks\Merkle\Merkle;

$data = [
	'8c14f0db3df150123e6f3dbbf30f8b955a8249b62ac1d1ff16284aefa3d06d87',
	'fff2525b8931402dd09222c50775608f75787bd2b87e56995a7bdd30f79702c4',
	'6359f0868171b1d194cbee1af2f16ea598ae8fad666d9b012c8ed2b79a236ec4',
	'e9a66845e05d5abc0ad04ec80f774a7e585c6e8db975962d069a522137b80c1d',
];

$root = Merkle::root($data);
// 6657A9252AACD5C0B2940996ECFF952228C3067CC38D4885EFB5A4AC4247E9F3

if ($merkle::verify($root, $data)) {
	// Data hasn't been tampered with
} else {
	// Naughty naughty...
}
```

Inputs don't have to be hexadecimal representations of double-SHA256 outputs, they are simply the most common use-case.

# Endianness

This library assumes that its inputs will be in big-endian format, i.e. the same as would be visible on a blockchain explorer. If you want to use this library for anything bitcoin-related then please be aware that you'll need to account for endianness when creating transaction hashes. See https://en.bitcoin.it/wiki/Block_hashing_algorithm#Endianess for details.

If you're using this library for anything else then you can safely ignore this section.

## Tests

Tests can be run with the `composer test` command.
