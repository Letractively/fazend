# Usage example #

Use it in any place where you want to validate your input:

```
validate()
    ->true($a > 10, 'Input should be bigger than 10!') // FaZend validator
    ->regex($b, '/^\w+$/', 'Only letters please') // Zend validator
    ->alnum($d, 'Alpha-numeric only') // Zend validator
    ->type($c, 'string', 'Only string values please');
```

When validation fails the exception `FaZend_Validator_Failure` will be thrown.

You can use FaZend validators, Zend validators or your own validators (from `application/validators`).

# Validators in forms #

Inside your form `.ini` file do like this (here we use standard validator):

```
elements.email.options.validators.email1.validator = "EmailAddress"
elements.email.options.validators.email1.options.messages.emailAddressInvalid = "Invalid email address"
elements.email.options.validators.email1.options.messages.emailAddressInvalidHostname = "Invalid email address"
elements.email.options.validators.email2.validator = "ValidateEmail"
elements.email.options.validators.email2.options.messages.duplicateEmail = "Such email is already registered"
```

Or you can create your own validator, here is the example (file `application/validators/ValidateEmail.php`):

```
class Validator_ValidateEmail extends Zend_Validate_Abstract {  
	
	const DUPLICATE_EMAIL = 'duplicateEmail';  

	protected $_messageTemplates = array(  
		self::DUPLICATE_EMAIL => 'It is duplicate!'
	);

	public function isValid($value, $context = null) {  
            if ($value) {
                $this->_error(self::INVALID);
                return false;
            }
            return true;
	}
}
```