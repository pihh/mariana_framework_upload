<?php namespace Mariana\Framework\Upload\Audio;
/**
 * Created by PhpStorm.
 * User: pihh
 * Date: 26/03/2016
 * Time: 15:54
 */

use Mariana\Framework\Upload\mUpload;

class mAudioUpload extends mUpload{

    /*
    |---------------------------
    | Variables & Configuration
    |---------------------------
    */

    protected $allowed_file_extensions = array (
        'mp3'	=> TRUE,
        'wav'	=> TRUE,
    );

    protected $allowed_file_types = array (

    );

    public function __construct(){
        parent::__construct();

    }

    /*
    |---------------------------
    | Audio Manipulation Functions
    |---------------------------
    */
}