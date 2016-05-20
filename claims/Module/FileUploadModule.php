<?php

namespace PP\Module;

use Slim\Http\UploadedFile;

/**
 * handle of fileUploadModule from Slim3
 *
 */
class FileUploadModule {

    /**
     * @var Slim\Http\UploadedFile
     */
    public $file;

    public $validation = array();

    private $validationFunction = array();

    /**
     * @var bool
     */
    public $hasValidationError = false;

    public $hasValidationMsg = array();

    //put your code here
    public function __construct( UploadedFile $file ) {
        $this->file = $file;
    }

    /**
     * array('image/png', 'image/gif')
     *
     * @param array $array
     */
    public function setAllowMimetype($array){
        $this->validation['mimetype'] = $array;
        $this->validationFunction[] = 'validationMimetype';
    }

    /**
     * file is no larger than 5M (use "B", "K", M", or "G")
     *
     * @param string $size
     */
    public function setAllowFilesize($size){
        $this->validation['filesize'] = $this->humanReadableToBytes($size);
        $this->validationFunction[] = 'validationFilesize';
    }

    public function getError(){
        return $this->file->getError();
    }

    public function getClientFilename(){
        return $this->file->getClientFilename();
    }

    public function moveTo($path) {
        $this->file->moveTo($path);
    }

    public function isValid(){
        if  ( $this->file->getError() !== UPLOAD_ERR_OK ) {
            return false;
        }

        foreach ( $this->validationFunction as $function ){
            $this->$function();
        }
        return !$this->hasValidationError;
    }

    private function validationMimetype(){
        if (in_array($this->file->getClientMediaType(), $this->validation['mimetype']) === false) {
            $this->hasValidationError = true;
            $this->hasValidationMsg[] = 'Mimetype error:' . $this->file->getClientMediaType() . ' allow ' . print_r($this->validation['mimetype'],1);
        }
    }

    private function validationFilesize(){
        if ( $this->file->getSize() > $this->validation['filesize'] ) {
            $this->hasValidationError = true;
            $this->hasValidationMsg[] = 'Filesize error:' . $this->file->getSize() . ' > ' . $this->validation['filesize'];
        }
    }

    /**
     * Convert human readable file size (e.g. "10K" or "3M") into bytes
     *
     * @param  string $input
     * @return int
     */
    public static function humanReadableToBytes($input)
    {
        $number = (int)$input;
        $units = array(
            'b' => 1,
            'k' => 1024,
            'm' => 1048576,
            'g' => 1073741824
        );
        $unit = strtolower(substr($input, -1));
        if (isset($units[$unit])) {
            $number = $number * $units[$unit];
        }
        return $number;
    }

}
