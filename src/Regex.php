<?php



namespace Solenoid\Text;



class Regex
{
    public string $pattern;



    # Returns [self]
    public function __construct (string $pattern)
    {
        // (Getting the value)
        $this->pattern = $pattern;
    }

    # Returns [Regex]
    public static function create (string $pattern)
    {
        // Returning the value
        return new Regex( $pattern );
    }



    # Returns [string]
    public function __toString ()
    {
        // Returning the value
        return $this->pattern;
    }
}



?>