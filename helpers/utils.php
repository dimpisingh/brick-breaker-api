<?

class utils {
    public static function implode_array_keys ($glue, $array) {
        $array_of_keys = [];
        foreach ($array as $key => $value) {
            $array_of_keys[] = $key;
        }
        return implode($glue, $array_of_keys);
    }

    public static function generate_password ($pwd, $salt='') {
		$hash = hash_hmac('sha256', md5($pwd), $salt);
		return $hash;
    }
    
    public static function get_country_flag_url ($country_code, $size = 32) {
        return 'https://www.countryflags.io/' . $country_code . '/flat/' . $size . '.png';
    }
}