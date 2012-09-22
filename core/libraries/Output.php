<?php

class Output
{
    protected static $_bufferLevel;
    protected static $_output = '';

    public static function initialize()
    {
        self::$_bufferLevel = ob_get_level();
    }

    public static function outputBuffer($output)
    {
        self::$_output = $output;
        return self::$_output;
    }

    public static function closeBuffers($flush = TRUE)
	{
		if (ob_get_level() >= self::$_bufferLevel)
		{
			// Set the close function
			$close = ($flush === TRUE) ? 'ob_end_flush' : 'ob_end_clean';

			while (ob_get_level() > self::$_bufferLevel)
			{
				// Flush or clean the buffer
				$close();
			}

			// Store the output buffer
			ob_end_clean();
		}
	}

    public static function shutdown()
    {
        // Close output buffers
		self::closeBuffers(TRUE);
        
        // Render the final output
        self::render();
    }

    public static function render()
    {
        echo self::$_output;
    }
}