<?php namespace Mariana\Framework\Upload\Document;


/**
 * Created by PhpStorm.
 * User: pihh
 * Date: 26/03/2016
 * Time: 15:52
 */

use Mariana\Framework\Upload\mUpload;

class mDocumentUpload extends mUpload{

    /*
    |---------------------------
    | Variables & Configuration
    |---------------------------
    */

    protected $allowed_file_extensions = array (
        'txt'	=> TRUE,
        'pdf'	=> TRUE,
        'doc' 	=> TRUE,
        'xls'	=> TRUE,
        'ppt'	=> TRUE,
    );

    protected $allowed_file_types = array (

    );

    public function __construct(){
        parent::__construct();

    }

    /*
    |---------------------------
    | Document Manipulation Functions
    |---------------------------
    */
}