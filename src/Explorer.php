<?php



namespace Solenoid\Text;



use \Solenoid\System\Directory;
use \Solenoid\System\File;
use \Solenoid\System\EOL;
use \Solenoid\System\Resource;



class Explorer
{
    private string $folder_path;



    # Returns [self]
    public function __construct (string $folder_path)
    {
        // (Getting the value)
        $this->folder_path = $folder_path;        
    }

    # Returns [Explorer]
    public static function create (string $folder_path)
    {
        // Returning the value
        return new Explorer( $folder_path );
    }



    # Returns [void] | Throws [Exception]
    public function walk (callable $handle_entry, Regex $line_content, ?Regex $file_name = null)
    {
        // (Getting the value)
        $resources = array_filter
        (
            Directory::select( $this->folder_path )->resolve()->list(),
            function ($resource) use ($file_name)
            {
                if ( !Resource::select( $resource )->is_file() )
                {// (Resource is not a file)
                    // Returning the value
                    return false;
                }



                // (Getting the value)
                $file_basename = basename( $resource );

                if ( $file_name && preg_match( $file_name, $file_basename ) === 0 )
                {// (Regex does not match the text)
                    // Returning the value
                    return false;
                }



                // Returning the value
                return true;
            }
        )
        ;

        foreach ($resources as $file_path)
        {// Processing each entry
            // (Getting the value)
            $file = File::select( $file_path );



            // (Reading the content)
            $content = $file->read();

            if ( $content === false )
            {// (Unable to read the file content)
                // (Setting the value)
                $message = "Unable to read the file content";

                // Throwing an exception
                throw new \Exception($message);

                // Returning the value
                return;
            }



            // (Getting the value)
            $eol = EOL::detect( $content );

            if ( $eol === false )
            {// (Unable to detect the EOL)
                // Continuing the iteration
                continue;
            }



            # debug
            #echo "$file_path -> $eol->type\n";



            // (Processing each file line)
            $file->walk
            (
                function ($line, $index, $matches) use ($handle_entry, $file_path, &$break)
                {
                    // (Calling the function)
                    $break = $handle_entry( ExplorerResult::create( $file_path, $index, $line, $matches ) ) === false;

                    if ( $break )
                    {// Value is true
                        // Returning the value
                        return false;
                    }
                },
                $eol,
                $line_content->pattern
            )
            ;



            if ( $break )
            {// Value is true
                // Returning the value
                return false;
            }
        }
    }
}



?>