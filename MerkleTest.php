<?php
declare(strict_types=1);
namespace RichJenks\Merkle;

use PHPUnit\Framework\TestCase;

final class MerkleTest extends TestCase
{
	private $tests;

	public function setUp(): void
	{
		$this->tests = require './MerkleTestData.php';
	}

	public function testMerkle(): void
	{
		foreach ($this->tests as $root => $data)
		{
			$this->assertEquals($root, Merkle::root($data));
		}
	}

	public function testVerify(): void
	{
		foreach ($this->tests as $root => $data)
		{
			$this->assertTrue(Merkle::verify($root, $data));
			$this->assertFalse(Merkle::verify(hash('sha256', $root), $data));
		}
	}
}
