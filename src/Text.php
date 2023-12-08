<?php



namespace Solenoid\Text;



class Text
{
    const ENCODINGS =
    [
        'UTF-8',
        'ASCII',
        'Windows-1252',
        'ISO-8859-15',
        'ISO-8859-1',
        'ISO-8859-6',
        'CP1256'
    ]
    ;



    public string $value;



    # Returns [self]
    public function __construct (string $value)
    {
        // (Getting the value)
        $this->value = $value;
    }

    # Returns [Text]
    public static function create (string $value)
    {
        // Returning the value
        return new Text( $value );
    }



    # Returns [Text]
    public function insert (string $value, array $positions)
    {
        // (Getting the value)
        $list = str_split( $this->value );



        // (Setting the value)
        $v = '';

        for ($i = 0; $i < count($list); $i++)
        {// Iterating each index
            if ( in_array( $i, $positions ) )
            {// Match OK
                // (Appending the value)
                $v .= $value . $list[$i];
            }
            else
            {// Match failed
                // (Appending the value)
                $v .= $list[$i];
            }
        }



        // (Getting the values)
        return Text::create( $v );
    }



    # Returns [Text]
    public function unwrap ()
    {
        // Returning the value
        return Text::create( trim( $this->value, " \t\n\r\0\x0B\"'`" ) );
    }



    # Returns [Text|false] | Throws [Exception]
    public function transform (string $type)
    {
        switch ($type)
        {
            case 'upper_camel_case':
                // (Getting the value)
                $value = implode( ' ', array_map( function ($word) { return ucfirst( $word ); }, preg_split( '/\s+/', strtolower( trim( $this->value ) ) ) ) );
            break;

            default:
                // (Setting the value)
                $message = "Type '$type' is not a valid option";

                // Throwing an exception
                throw new \Exception($message);

                // Returning the value
                return false;
        }



        // Returning the value
        return Text::create( $value );
    }

    # Returns [Text]
    public function normalize (string $input_encoding = 'UTF-8')
    {
        // (Getting the value)
        $v = $this->value;
        $v = iconv( $input_encoding, 'ASCII//TRANSLIT//IGNORE', $v );
        $v = preg_replace( '/[^\w\s]/', '', $v );



        // Returning the value
        return Text::create( $v );
    }



    # Returns [array<assoc>]
    public function debug_encodings (array $encodings = self::ENCODINGS)
    {
        // (Setting the value)
        $list = [];

        foreach ($encodings as $a)
        {// Processing each entry
            foreach ($encodings as $b)
            {// Processing each entry
                // (Appending the value)
                $list[] =
                [
                    'from'  => $a,
                    'to'    => $b,
                    
                    'value' => iconv( $a, $b, $this->value )
                ]
                ;
            }
        }



        // Returning the value
        return $list;
    }



    # Returns [string|false] | Throws [Exception]
    public function mark (string $type, ?int $start_pos = null, ?int $end_pos = null)
    {
        // (Setting the value)
        $type_id = null;

        switch ($type)
        {
            case 'error':
                // (Setting the value)
                $type_id = '31';
            break;
            case 'warning':
                // (Setting the value)
                $type_id = '33';
            break;
            case 'success':
                // (Setting the value)
                $type_id = '32';
            break;
            case 'info':
                // (Setting the value)
                $type_id = '36';
            break;

            default:
                // (Setting the value)
                $message = "Type '$type' is not a valid option";

                // Throwing an exception
                throw new \Exception($message);

                // Returning the value
                return false;
        }



        // (Counting the length)
        $text_length = strlen( $this->value );

        $start_pos = $start_pos === null ? 0 : $start_pos;
        $end_pos   = $end_pos === null ? strlen( $this->value ) - 1 : $end_pos;



        // (Setting the value)
        $formatted_text = '';

        for ($i = 0; $i < $text_length; $i++)
        {// Iterating each index
            // (Getting the value)
            $char = $this->value[$i];



            if ($i >= $start_pos && $i <= $end_pos)
            {// Match OK
                // (Appending the value)
                $formatted_text .= "\033[" . $type_id . "m$char\033[0m";
            }
            else
            {// Match failed
                // (Appending the value)
                $formatted_text .= $char;
            }
        }



        // Returning the value
        return $formatted_text;
    }



    # Returns [string]
    public function __toString ()
    {
        // Returning the value
        return $this->value;
    }
}



?>