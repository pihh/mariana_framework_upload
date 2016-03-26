<?php namespace Mariana\Framework\Upload\Archives;


/**
 * Created by PhpStorm.
 * User: pihh
 * Date: 26/03/2016
 * Time: 15:52
 */

use Mariana\Framework\Upload\mUpload;

class mArchiveUpload extends mUpload{

    /*
    |---------------------------
    | Variables & Configuration
    |---------------------------
    */

    protected $allowed_file_extensions = array (
        'zip'	=> TRUE,
        '7z'	=> TRUE,
    );

    protected $allowed_file_types = array (

    );

    public function __construct(){
        parent::__construct();

    }

    /*
    | Audio Manipulation Functions
    |---------------------------
    */
}