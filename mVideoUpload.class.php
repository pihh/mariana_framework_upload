<?php namespace Mariana\Framework\Upload\Video;
/**
 * Created by PhpStorm.
 * User: pihh
 * Date: 26/03/2016
 * Time: 15:56
 */

use Mariana\Framework\Upload\mUpload;

class mVideoUpload extends mUpload{

    /*
    |---------------------------
    | Variables & Configuration
    |---------------------------
    */

    protected $allowed_file_extensions = array (
        'gif'	=> TRUE,
        'png'	=> TRUE,
        'jpg'	=> TRUE,
        'jpeg'	=> TRUE,
    );

    protected $allowed_file_types = array (
        'gif'	=> TRUE,
        'png'	=> TRUE,
        'jpg'	=> TRUE,
        'jpeg'	=> TRUE,
    );

    public function __construct(){
        parent::__construct();

    }

    /*
    |---------------------------
    | Video Manipulation Functions
    |---------------------------
    */
}