<?php

/*
	Rules:
		-required (value is required)
		-required_if (value is required if other value is present)
		-equals (value ==)
		-match_regex
		-min_length
		-max_length
		-exact_length
		-min_val
		-max_val
		-is_numeric
		-is_boolean
		-valid_ip
		-valid_uri
		-valid_email
		-valid_emails

	Syntax:
		$validation->register('fieldname', 'Readable Name')
			->rule('required')
			->rule('min_length', 5)
			->rule('max_length', 20)
			...
*/


class Validation
{

}