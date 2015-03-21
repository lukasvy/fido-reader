<?php namespace Fido\Core\Validation;


trait ValidationTrait {

	private $errors  = [];
	private $invalid = false;

	public function getErrors() {
		return $errors;
	}

	/**
	 * Retrieves model parameters and returns them in array 
	 * (this is based on fillable property)
	 *
	 * @return array
	 */
	private function getModelParams () {
		if (!$this->fillable) {
			throw new \Exception('Cannot find fillable propery of this object');
		}
		$params = [];
		foreach ($this->fillable as $key => $value) {
			$params[$value] = $this[$value];
		}
		return $params;
	}

	public function validate ($input = null) {
		if (!$input) {
			$input = $this->getModelParams();
		}
		if (!$this->rules) {
			throw new ValidationException('Cannot find rules, please add rules for calidation into model');
		}

		$validator = \Validator::make($input, $this->rules);
		if ($validator->fails()) {
			$this->errors = $validator->messages();
			$this->invalid = true;
		} else {
			$this->invalid = false;
			$this->errors  = [];
		}
		return $this;
	}

	public function save (array $options = []) {
		$this->validate();
		if (!$this->invalid) {
			parent::save();
		} else {
			throw new ValidationException($this->errors);
		}
	}
}