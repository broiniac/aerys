<?php

namespace Aerys\Http\BodyWriters;

use Aerys\Http\ResourceException;

class String extends BodyWriter {
    
    private $destination;
    private $body;
    private $contentLength;
    
    private $totalBytesWritten = 0;
    
    function __construct($destination, $body, $contentLength) {
        $this->destination = $destination;
        $this->body = $body;
        $this->contentLength = $contentLength;
    }
    
    function write() {
        $bytesWritten = @fwrite($this->destination, $this->body, $this->granularity);
        
        $this->totalBytesWritten += $bytesWritten;
        
        if ($bytesWritten && ($this->totalBytesWritten == $this->contentLength)) {
            return TRUE;
        } elseif ($bytesWritten) {
            $this->body = substr($this->body, $bytesWritten);
            return FALSE;
        } elseif (!is_resource($this->destination)) {
            throw new ResourceException(
                'Failed writing to destination stream resource'
            );
        }
    }
}
