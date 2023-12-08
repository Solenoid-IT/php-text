<?php



namespace Solenoid\Text;



class ExplorerResult
{
    public string  $file_path;

    public int     $line_index;
    public string  $line_content;

    public ?array  $matches;



    # Returns [self]
    public function __construct (string $file_path, int $line_index, string $line_content, ?array $matches)
    {
        // (Getting the value)
        $this->file_path    = $file_path;

        $this->line_index   = $line_index;
        $this->line_content = $line_content;

        $this->matches      = $matches;
    }

    # Returns [ExplorerResult]
    public static function create (string $file_path, int $line_index, string $line_content, ?array $matches)
    {
        // Returning the value
        return new ExplorerResult( $file_path, $line_index, $line_content, $matches );
    }



    # Returns [assoc]
    public function to_array ()
    {
        // Returning the value
        return get_object_vars( $this );
    }

    # Returns [string]
    public function to_string ()
    {
        // (Getting the values)
        $start_position = $this->matches[0][1];
        $end_position   = $start_position + strlen( $this->matches[0][0] ) - 1;

        $php_context    = php_sapi_name() === 'cli' ? 'cli' : 'http';



        // (Getting the value)
        $line_content = $this->line_content;

        if ( $php_context === 'cli' )
        {// Match OK
            // (Getting the value)
            $line_content = substr( $line_content, 0, $start_position ) . Text::create( $this->matches[0][0] )->mark( 'info' ) . substr( $line_content, $end_position + 1 );
        }



        // Returning the value
        return
            <<<EOD
            File
                $this->file_path on line $this->line_index
            Content
                `$line_content` ( $start_position - $end_position )
            
            EOD
        ;
    }



    # Returns [string]
    public function __toString ()
    {
        // Returning the value
        return $this->to_string();
    }
}



?>