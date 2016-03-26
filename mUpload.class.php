<?php namespace Mariana\Framework\Upload;
/**
 * Created by PhpStorm.
 * User: pihh
 * Date: 26/03/2016
 * Time: 15:46
 * Features:
 *  Check if it's dir
 *  Check if dir it's writable
 *  Allow for auto-rename option on overwrite
 *  Parse arguments
 *  Get Extension and Filename
 *  Description of Upload Errors
 *  Debug report as array
 *  Set upload variables
 *  Fully Commented
 */

abstract class mUpload{


    private $set_writable_dir = false;
    private $file_status = array();
    private $error_count = 0;
    private $valid_file_count = 0;
    private $moved_file_count = 0;
    private $corrupt_files = array();
    private $files_size = 0;

    protected $allowed_size = 512000;
    protected $extension_check = true;
    protected $type_check = false;

    protected $allowed_file_extensions = array();
    protected $allowed_file_types = array();

    public function __construct(){}

    private function _check_allowed_extensions($file_extension){
        return (in_array($file_extension,$this->allowed_file_extensions))?
            true:
            false;
    }

    private function _check_allowed_types($file_type){
        return (in_array($file_type,$this->allowed_file_types))?
            true:
            false;
    }

    private function _check_if_dir($upload_directory){
        return ( is_dir( $upload_directory ) ) ?
            true:
            false;
    }

    private function _create_dir($upload_directory){
        mkdir($upload_directory, 0777,true);
        $this->set_writable_dir = true;
    }

    private function _check_if_writable($upload_directory){
        if( !is_writable( $upload_directory ) ) {
            $this->_set_writable($upload_directory);
        }
        return true;
    }

    private function _set_writable($upload_directory){
        chmod($upload_directory, 0777,true);
        $this->set_writable_dir = true;
    }

    private function _set_unwritable($upload_directory){
        chmod($upload_directory, 0744);
        $this->set_writable_dir = true;
    }

    private function _parse_arg($var = '', $separator = ''){
        return preg_replace('|[^a-zA-Z0-9_]|',$separator,$var);
    }

    private function _get_ext_and_name($file_name){
        $file_ext = explode('.',$file_name);
        $file_ext = end($file_ext);
        $file_name = str_replace('.'.$file_ext,'',$file_name);
        return array(strtolower($file_name),strtolower($file_ext));
    }

    private function _rename($file_ext){
        return str_replace('.','_',time().'_'.uniqid('',true).'_'.rand(0,2500)).'.'.$file_ext;
    }

    /*
    |---------------------------
    | Processes the files so the output is the following array
    | array(
    |   0 => array(
    |       'name' => 'string',
    |       'original_name' => 'string',
    |       'tmp_name' => 'string',
    |       'size' =>  'integer',
    |       'error'=>  'integer'
    |   )
    | );
    |
    |---------------------------
    */
    private function _process_files($files){
        // File name
        // File tmp
        // File size

        $processed_files = array();

        // check if for instance file['name'] is array or string
        if(is_array($files['name'])){
            $loopsize = sizeof($files['name']);
            $loopindex = 0;

            // Get total file size
            foreach($files['size'] as $size){
                $this->files_size += $size;
            }

            // loop
            while($loopindex < $loopsize){

                // If file exists
                if($files['size'][$loopindex] > 0) {
                    // Get file extension
                    $file_ext = $this->_get_ext_and_name($files['name'][$loopindex])[1];

                    // File processing
                    if($this->type_check){
                        $files['error'][$loopindex] = $this->_check_allowed_types($files['type'][$loopindex]);
                    }
                    if($this->extension_check){
                        $files['error'][$loopindex] = $this->_check_allowed_extensions($file_ext);
                    }

                    // Merge
                    $processed_files[$loopindex] = array(
                        'original_name' => $files['name'][$loopindex],
                        'name' => $this->_rename($file_ext),
                        'tmp_name' => $files['tmp_name'][$loopindex],
                        'size' => $files['size'][$loopindex],
                        'error' => $files['error'][$loopindex],
                        'type' => $files['type'][$loopindex]
                    );
                }

                $loopindex++;
            }

        }else{

            $this->files_size = $files['size'];
            $files['original_name'] = $files['name'];
            $files['name'] = $this->_rename($this->_get_ext_and_name($files['name'])[1]);
            $processed_files[0] = $files;
        }

        return $processed_files;
    }

    private function _error_debug($error_code){
        switch ( $error_code ) {
            case UPLOAD_ERR_OK:
                $error = "OK";
                break;
            case UPLOAD_ERR_INI_SIZE:
                $error = "The total uploaded file size ( '. $this->files_size. ') exceeds the upload_max_filesize directive (".ini_get("upload_max_filesize").") in php.ini.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $error = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $error = "The uploaded file was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $error = "No file was uploaded.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $error = "Missing a temporary folder.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $error = "Failed to write file to disk.";
                break;
            case UPLOAD_ERR_EXTENSION:
                $error = "A PHP extension stopped the file upload.";
                break;
            case 10:
                $error = "The file size exceeds the maximum allowed on this class configuration";
                break;
            case 11:
                $error = "This file type is not allowed in this class configuration";
                break;
            default:
                $error = "Unknown Error.";
        }
        return strtoupper($error);
    }

    public function _debug(){
        return array(
            'file_count' => $this->valid_file_count,
            'moved_file_count' => $this->moved_file_count,
            'error_count' => $this->error_count,
            'corrupt_file_list' => $this->corrupt_files,
            'file_report' => $this->file_status
        );
    }

    public function upload($files = array(),$destination){

        // trim destination
        $destination = trim($destination,'/');

        // parse files
        $files = $this->_process_files($files);

        // update valid file count
        $this->valid_file_count = sizeof($files);

        if($this->valid_file_count > 0) {

            // Destination check
            if(!$this->_check_if_dir($destination)){
                $this->_create_dir($destination);
            }

            // Writable check
            if(!$this->_check_if_writable($destination)){
                $this-$this->_set_writable($destination);
            }

            // Start the loop
            $foreachindex = 0;
            foreach ($files as $file) {
                $moved = 'false';
                //$new_destination = $destination . '/' . $file['name'];
                $new_destination = $destination . '/'.$file['name'];
                // Case has error doesn't move file
                if ($file['error'] < 1) {
                    if (move_uploaded_file($file['tmp_name'], $new_destination)) {
                        chmod($new_destination, 0777);
                        $moved = 'true';
                        $this->moved_file_count++;
                    }
                }

                // Debug info
                $this->file_status[$foreachindex] = array(
                    'destination'   =>  $new_destination,
                    'orginal_file_name' => $file['original_name'],
                    'uploaded_file_name' => $file['name'],
                    'temp_file_name' => $file['tmp_name'],
                    'size' => $file['size'],
                    'error' => $this->_error_debug($file['error']),
                    'moved' => $moved
                );

                // Set Report Info
                if ($file['error'] > 0) {
                    $this->error_count++;
                    $this->corrupt_files = $this->file_status[$foreachindex];
                }

                // Update index
                ++$foreachindex;
            }

            // set unwritable
            if ($this->set_writable_dir) {
                $this->_set_unwritable($destination);
            }
        }

        return $this->_debug();
    }
}