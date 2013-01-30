<?php

$_SERVER['REQUEST_METHOD'] = 'POST';

require_once('../framework/classes/input.php');
require_once('../framework/classes/validation.php');


class Validation_Test extends PHPUnit_Framework_TestCase
{

	protected $val;

	protected function setUp()
	{
		$this->val = new Validation();
		$this->val->register('test');
	}


	public function test_equals()
	{
		$string = 'abcd';
		$_POST['test'] = $string;

		// strings match
		$this->assertTrue($this->val->rule('equals', $string)->validate());
		$this->assertFalse($this->val->rule('equals', 'blah')->validate());
	}


	public function test_match_regex()
	{
		$_POST['test'] = 'match agai9nst me!';
		$pattern = '/agai[0-9]nst/';

		$this->assertTrue($this->val->rule('match_regex', $pattern)->validate());
	}


	public function test_min_length()
	{
		$string = 'this is 26 characters long';
		$_POST['test'] = $string;

		$this->assertTrue($this->val->rule('min_length', 0)->validate());
		$this->assertTrue($this->val->rule('min_length', strlen($string))->validate());
		$this->assertFalse($this->val->rule('min_length', 30)->validate());
	}


	public function test_max_length()
	{
		$string = 'this is 26 characters long';
		$_POST['test'] = $string;

		$this->assertTrue($this->val->rule('max_length', strlen($string))->validate());
		$this->assertTrue($this->val->rule('max_length', 50)->validate());
		$this->assertFalse($this->val->rule('max_length', 0)->validate());
	}


	public function test_exact_length()
	{
		$string = 'this is 26 characters long';
		$_POST['test'] = $string;

		$this->assertTrue($this->val->rule('exact_length', strlen($string))->validate());
	}


	public function test_at_least()
	{
		$val = 55;
		$_POST['test'] = $val;

		$this->assertTrue($this->val->rule('at_least', $val - 1)->validate());
		$this->assertTrue($this->val->rule('at_least', $val)->validate());
		$this->assertFalse($this->val->rule('at_least', $val + 1)->validate());
	}


	public function test_at_most()
	{
		$val = 55;
		$_POST['test'] = $val;

		$this->assertTrue($this->val->rule('at_most', $val + 1)->validate());
		$this->assertTrue($this->val->rule('at_most', $val)->validate());
		$this->assertFalse($this->val->rule('at_most', $val - 1)->validate());
	}


	public function test_numerical()
	{
		$_POST['test'] = 55;

		$this->assertTrue($this->val->rule('numerical')->validate());

		$_POST['test'] = 'abcd';

		$this->assertFalse($this->val->validate());
	}


	public function test_integer()
	{
		$_POST['test'] = 55;
		$this->assertTrue($this->val->rule('integer')->validate());

		$_POST['test'] = 55.5;
		$this->assertFalse($this->val->validate());

		$_POST['test'] = 'abcd';
		$this->assertFalse($this->val->validate());
	}


	public function test_float()
	{
		$_POST['test'] = 55.5;
		$this->assertTrue($this->val->rule('float')->validate());

		$_POST['test'] = 0;
		$this->assertFalse($this->val->validate());

		$_POST['test'] = 'abcd';
		$this->assertFalse($this->val->validate());
	}


	public function test_alphabetical()
	{
		$_POST['test'] = 'allgoodhere';
		$this->assertTrue($this->val->rule('alphabetical')->validate());

		$_POST['test'] = 'notgood!';
		$this->assertFalse($this->val->validate());

		$_POST['test'] = 'not good';
		$this->assertFalse($this->val->validate());
	}


	public function test_alphanumeric()
	{
		$_POST['test'] = 'abc123';
		$this->assertTrue($this->val->rule('alphanumeric')->validate());

		$_POST['test'] = 'yxz987!';
		$this->assertFalse($this->val->validate());

		$_POST['test'] = 'not good';
		$this->assertFalse($this->val->validate());
	}


	public function test_valid_ip()
	{
		$_POST['test'] = '192.168.0.123';
		$this->assertTrue($this->val->rule('valid_ip')->validate());

		$_POST['test'] = '123.234.123.234';
		$this->assertTrue($this->val->validate());

		$_POST['test'] = '0.0.0.256';
		$this->assertFalse($this->val->validate());
	}


	public function test_valid_uri()
	{
		$_POST['test'] = 'http://www.google.com';
		$this->assertTrue($this->val->rule('valid_uri')->validate());

		$_POST['test'] = 'http://www.google.com/';
		$this->assertTrue($this->val->validate());

		$_POST['test'] = 'http://www.google.com/something';
		$this->assertTrue($this->val->validate());

		$_POST['test'] = '/relative/';
		$this->assertFalse($this->val->validate());

		$_POST['test'] = 'http://www.';
		$this->assertFalse($this->val->validate());

		$_POST['test'] = 'just a string';
		$this->assertFalse($this->val->validate());
	}


	public function test_valid_email()
	{
		$_POST['test'] = 'valid@email.com';
		$this->assertTrue($this->val->rule('valid_email')->validate());

		$_POST['test'] = 'valid+yup@email.com';
		$this->assertTrue($this->val->validate());

		// yup the RFC allows some ridiculous stuff
		$_POST['test'] = 'valid+yup.really?@email.com';
		$this->assertTrue($this->val->validate());

		$_POST['test'] = '++++++@email.com';
		$this->assertTrue($this->val->validate());

		$_POST['test'] = 'invalid@email';
		$this->assertFalse($this->val->validate());

		$_POST['test'] = '++++++email.com';
		$this->assertFalse($this->val->validate());

		$_POST['test'] = '++++++.com';
		$this->assertFalse($this->val->validate());
	}


	public function test_valid_emails()
	{
		$_POST['test'] = 'valid@email.com, valid+yup@email.com, valid+yup.really?@email.com';
		$this->assertTrue($this->val->rule('valid_emails')->validate());

		$_POST['test'] = 'valid@email.com
		valid+yup@email.com
		valid+yup.really?@email.com';
		$this->assertTrue($this->val->rule('valid_emails')->validate());
	}


	public function test_valid_base64()
	{
		$_POST['test'] = 'dGhpcyBpcyBhIHRlc3Qgc3RyaW5n';
		$this->assertTrue($this->val->rule('valid_base64')->validate());

		$_POST['test'] = 'dGhpcyBpcyBhIHRlc3Qgc3RyaW5'; // stripped of last char
		$this->assertFalse($this->val->validate());

		$_POST['test'] = '';
		$this->assertFalse($this->val->validate());
	}

}