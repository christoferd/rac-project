<?php

namespace App\Library;

class Phone
{
    /**
     * Format the number to be a full phone number with country-code, beginning with "+" and following with digits only.
     * Side Note: Check first character for "+", when found, the string is not manipulated, just striped of other characters.
     * e.g. "+61-419-571-596" will result in "+61419571596"
     *
     * @param string $phoneNumber
     * @return string
     */
    static function fullPhoneNumber(string $phoneNumber)
    {
        // digits only
        $digitsOnly = preg_replace('/[^0-9]/', '', $phoneNumber);
        // remove 0 prefix
        $digitsOnly = ltrim($digitsOnly, '0');

        // Check for "+"
        if(isset($phoneNumber[0]) && $phoneNumber[0] === '+') {
            // assume country code is already present
            return '+'.$digitsOnly;
        }

        // prefix +{country-code}
        return '+'.config('rental.phone_number.country_code').$digitsOnly;
    }

    /**
     * Very basic validation:
     * - First character is '+'
     * - String is at least 10 chars long
     *
     * @todo Update to accept a country code and properly validate.
     */
    static function isValidPhoneNumber(string $phoneNumber): bool
    {
        if(empty($phoneNumber)) {
            return false;
        }

        if($phoneNumber[0] !== '+') {
            return false;
        }

        return (strlen($phoneNumber) >= 10);
    }
}
