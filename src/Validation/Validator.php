<?php

namespace App\Validation;
use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
	/**
	 * @var array
	 */
	protected array $errors = [];

	public function validate(array $data, array $rules): Validator
	{
		foreach ($rules as $field => $rule) {
			try {
				$rule->setName(ucfirst($field))->assert($data[$field]);
			} catch (NestedValidationException $ex) {
				$this->errors[$field] = $ex->getMessage();
			}
		}

		return $this;
	}

	/**
	 * @return bool
	 */
	public function failed(): bool
	{
		return !empty($this->errors);
	}
	/**
	 * @return array
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}
}