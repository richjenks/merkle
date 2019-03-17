<?php
declare(strict_types=1);

namespace RichJenks\Merkle;

/**
 * Generates a Merkle Tree from an array of data
 * following the same method used by Bitcoin
 * adapted from https://bitcoin.stackexchange.com/a/43575
 *
 * Usage: `echo Merkle::tree(['one', 'two', 'three', '...']);`
 */
class Merkle
{
	/**
	 * Get the Merkle Root
	 *
	 * @param  array  $data Items to be hashed
	 * @return string The Merkle Root of the data
	 */
	public static function root(array $data): string
	{
		// Support single inputs
		if (count($data) === 1) {
			$data[1] = $data[0];
		}

		// Convert to binary and flip the endian-ness for each datum
		foreach ($data as $key => $datum) {
			$data[$key] = self::bin($datum);
		}

		// Generate binary merkle root
		$bin = self::merkle($data);

		// Flip back and convert to hexadecimal
		$root = self::hex($bin);

		// Return in correct casing
		return strtoupper($root);
	}

	/**
	 * Verifies a Merkle Root against a set of data
	 *
	 * @param  string $root Merkle Root
	 * @param  array  $data Data that supposedly generated the root
	 * @return bool   Whether the root represents the data
	 */
	public static function verify(string $root, array $data): bool
	{
		return (self::root($data) === $root);
	}

	/**
	 * Gets the byte-flipped binary representation of a hexadecimal value
	 *
	 * @param  string $hex Hexadecimal value
	 * @return string Binary representation
	 */
	private static function bin(string $hex): string
	{
		$bin = $hex;
		$bin = hex2bin(trim($bin));
		$bin = self::flip($bin);

		return $bin;
	}

	/**
	 * Gets the hexadecimal value of a byte-flipped binary representation
	 *
	 * @param  string $bin hexadecimal value
	 * @return string Binary representation
	 */
	private static function hex(string $bin): string
	{
		$hex = $bin;
		$hex = self::flip($hex);
		$hex = bin2hex(trim($hex));

		return $hex;
	}

	/**
	 * Flips the endian-ness of a decimal value
	 *
	 * @param  string $decimal Piece of data to be flipped
	 * @return string Endian-flipped version of the decimal
	 */
	private static function flip(string $decimal): string
	{
		return implode('', array_reverse(str_split($decimal, 1)));
	}

	/**
	 * Recursively generates the Merkle Root
	 *
	 * @param  array  $data Items to be hashed
	 * @return string The Merkle Root of the data
	 */
	private static function merkle(array $data): string
	{
		// If there's only one item then it's the merkle root
		if (count($data) === 1) return $data[0];

		// Hash the next row up
		while (count($data) > 0) {

			// First two items, or first item twice
			$one = array_shift($data);
			$two = array_shift($data) ?? $one;

			// Hash the pair
			$row[] = self::hash($one . $two);

		}

		// Recurse...
		return self::merkle($row);
	}

	/**
	 * Double-hashes a string in binary
	 *
	 * @param  string $input
	 * @return string Hash of input
	 */
	private static function hash(string $input): string
	{
		return hash('sha256', hash('sha256', $input, true), true);
	}
}
