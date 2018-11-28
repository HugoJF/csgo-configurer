<?php

namespace Tests\Feature;

use App\Classes\CompoundVariableTranslator;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompoundVariableTranslatorTest extends TestCase
{
	/**
	 * Test basic variable translation
	 */
	public function testVariablesCanBeTranslated() {

		$data = [
			'a' => 'A',
			'b' => '{%a%}',
		];

		$cvt = new CompoundVariableTranslator($data);

		$cvt->translate();

		$this->assertEquals(['a' => 'A', 'b' => 'A'], $data);

	}

	/**
	 * Tests array traversing and referencing value that needs translation
	 */
	public function testVariablesCanBeTranslatedWithComplexPath() {

		$data = [
			'a' => '{%b.c%}',
			'b' => [
				'c' => 'C',
				'd' => '{%a%}',
			],
		];

		$cvt = new CompoundVariableTranslator($data);

		$cvt->setModeReplaceEmpty();

		$cvt->translate();

		$this->assertEquals(['a' => 'C', 'b' => ['c' => 'C', 'd' => 'C']], $data);
	}

	/**
	 * Test path loop can be detected
	 */
	public function testLoopCanBeDetected() {
		$data = [
			'a' => '{%b%}',
			'b' => '{%a%}',
		];
		$cvt = new CompoundVariableTranslator($data);

		$this->expectException('Exception');

		$cvt->translate();
	}

	/**
	 * An invalid path will raise exception
	 */
	public function testModeExceptionRaisesException() {
		$data = [
			'a' => 'A',
			'b' => '{%c%}',
		];

		$cvt = new CompoundVariableTranslator($data);

		$cvt->setModeException();

		$this->expectException('Exception');

		$cvt->translate();
	}

	/**
	 * An invalid path will be replaced by empty value
	 */
	public function testModeReplaceEmptyReplacesIncorrectPath() {
		$data = [
			'a' => 'A',
			'b' => '{%c%}',
		];

		$cvt = new CompoundVariableTranslator($data);

		$cvt->setModeReplaceEmpty();

		$cvt->translate();

		$this->assertEquals(['a' => 'A', 'b' => ''], $data);
	}

	/**
	 * An invalid path will not be replaced
	 */
	public function testModeNoReplaceDoesNotReplacePath() {
		$data = [
			'a' => 'A',
			'b' => '{%c%}',
		];

		$cvt = new CompoundVariableTranslator($data);

		$cvt->setModeNoReplace();

		$cvt->translate();

		$this->assertEquals(['a' => 'A', 'b' => '{%c%}'], $data);
	}
}
