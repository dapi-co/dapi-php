<?php

class BeneficiaryType
{
    public const LOCAL = 'local';
    public const SAME = 'same';
    public const INTL = 'intl';
}
class DapiHelper
{
    public static function generateRandomString($length = 5)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
    
        return $randomString;
    }
    public static function truncateString($string, $limit)
    {
        if(strlen($string) <= $limit){
            return $string;
        }
        $randomSuffix = self::generateRandomString(5);
        return  substr($string, 0, $limit - 5). $randomSuffix;

    }
    protected static function truncateKeyValue($keyValidatorProps, $key, $beneficiaryArray){
        if (
            (array_key_exists('required', $keyValidatorProps) && $keyValidatorProps['required'] && array_key_exists($key, $beneficiaryArray)) ||
            (array_key_exists('optional', $keyValidatorProps) && $keyValidatorProps['optional'] && array_key_exists($key, $beneficiaryArray)) ||
            array_key_exists($key, $beneficiaryArray)
            
        ){
            if (!array_key_exists($key, $beneficiaryArray) || !$beneficiaryArray[$key]){
                throw new Exception( $key.' is missing');
            }
            
            if(array_key_exists('length', $keyValidatorProps) && $keyValidatorProps['length'] > 0 &&
                $keyValidatorProps['length'] < strlen($beneficiaryArray[$key])
            ){
                $beneficiaryArray[$key] = self::truncateString($beneficiaryArray[$key], $keyValidatorProps['length']);
            }
            
            if(array_key_exists('allowedCharacters', $keyValidatorProps)){
                preg_match('/'.$keyValidatorProps['allowedCharacters'].'/', $beneficiaryArray[$key], $matches);
                if(count($matches) == 0){
                    // This should only happen if you send a string that contains only special characters
                    throw new Exception('Unable to truncate string value to invalid parameter - '. $key);
                }else{
                    $beneficiaryArray[$key] = $matches[0];
                }
            }
        }

        return $beneficiaryArray[$key];
    }
    protected static function validateKeyValue($keyValidatorProps, $key, $beneficiaryArray){
        if (
            (array_key_exists('required', $keyValidatorProps) && $keyValidatorProps['required']) ||
            (array_key_exists('optional', $keyValidatorProps) && $keyValidatorProps['optional'] && array_key_exists($key, $beneficiaryArray))
            
        ){
            if (!array_key_exists($key, $beneficiaryArray) || !$beneficiaryArray[$key]){
                throw new Exception( $key.' is missing');
            }
            
            if(
                array_key_exists('length', $keyValidatorProps) && 
                $keyValidatorProps['length'] > 0 &&
                $keyValidatorProps['length'] < strlen($beneficiaryArray[$key])
            ){
                throw new Exception($key. ' is too long. Maximum length: '.$keyValidatorProps['length'] .'. But got length: '.strlen($beneficiaryArray[$key]));
            }
            
            if(array_key_exists('allowedCharacters', $keyValidatorProps)){
                if(
                    preg_match('/'.$keyValidatorProps['allowedCharacters'].'/', $beneficiaryArray[$key], $matches) !== 1 || 
                    count($matches) > 0 && $matches[0] !== $beneficiaryArray[$key]
                ){
                    throw new Exception( $key.' should not contain special characters');
                }
            }
        }

        return $beneficiaryArray[$key];
    }
    public static function truncateCreateBeneficiary($validator, $beneficiary)
    {
        $type = array_key_exists('type', $beneficiary) ? $beneficiary['type'] : null;

        if(!$type){
            throw new Exception('type is missing');
        } else if ($type !== BeneficiaryType::LOCAL && type !== BeneficiaryType::SAME) {
            throw new Exception('type has to be local or same');
        }
        $validatorProps = array_key_exists('createBeneficiary', $validator) && array_key_exists($type, $validator['createBeneficiary']) ? $validator['createBeneficiary'][$type] : [];
        
        foreach ($validatorProps as $key => $prop) {
            if($key == 'address' && array_key_exists($key, $beneficiary)){
                foreach ($prop as $propKey => $propKeyValue) {
                    if($propKey != 'length'){
                        $beneficiary[$key][$propKey] =  self::truncateKeyValue($validatorProps[$key][$propKey], $propKey, $beneficiary[$key]);
                    }
                }
            }else {
                $beneficiary[$key] =  self::truncateKeyValue($validatorProps[$key], $key, $beneficiary);
            }
        }
      
        return $beneficiary;
    }
    public static function validateCreateBeneficiary($validator, $beneficiary)
    {
        $type = array_key_exists('type', $beneficiary) ? $beneficiary['type'] : null;

        if(!$type){
            throw new Exception('type is missing');
        } else if ($type !== BeneficiaryType::LOCAL && type !== BeneficiaryType::SAME) {
            throw new Exception('type has to be local or same');
        }

        $validatorProps = array_key_exists('createBeneficiary', $validator) && array_key_exists($type, $validator['createBeneficiary']) ? $validator['createBeneficiary'][$type] : [];
        foreach ($validatorProps as $key => $prop) {
            if($key == 'address' && array_key_exists($key, $beneficiary)){
                foreach ($prop as $propKey => $propKeyValue) {
                    if($propKey != 'length'){
                        $beneficiary[$key][$propKey] =  self::validateKeyValue($validatorProps[$key][$propKey], $propKey, $beneficiary[$key]);
                    }
                }
            }else {
                $beneficiary[$key] =  self::validateKeyValue($validatorProps[$key], $key, $beneficiary);
            }
        }
        return $beneficiary;
    }
}

