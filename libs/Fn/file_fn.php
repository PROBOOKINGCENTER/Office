<?php

class File_Fn extends Fn
{
    public function createName($filename, $id, $secret, $user_id)
    {
        $extension = $this->getExtension($filename);

        $firstName = hexdec( substr(sha1($id), 0, 9) );
        return $firstName.'_'.hexdec( substr(sha1($secret), 0, 9) ).'_'.time().'_'.hexdec( substr(sha1($user_id), 0, 8) ).$extension;
    }
	public function type($type){

        switch ($type) {
            case "pdf": $typename = "application/pdf"; break;
            case "doc": $typename = "application/msword"; break;
            case "docx": $typename = "application/vnd.openxmlformats-officedocument.wordprocessingml.document"; break;
            case "exe": $typename = "application/octet-stream"; break;
            case "zip": $typename = "application/zip"; break;
            case "xls": $typename = "application/vnd.ms-excel"; break;
            case "ppt": $typename = "application/vnd.ms-powerpoint"; break;
            case "gif": $typename = "image/gif"; break;
            case "png": $typename = "image/png"; break;
            case "jpe": 
            case "jpeg": 
            case "jpg": $typename = "image/jpg"; break;
            default: $typename = ""; break;
        }

        return $typename;
	}


    public function validate(&$err, $userfile=null, $settings=array()) {
        
        $settings = array_merge(array(
            'max_size' => 100,
            'unit_size' =>'MB',
        ), $settings);


        if(!is_uploaded_file($userfile['tmp_name'])){
            // $err = "ส่งไฟล์ไม่สำเร็จ! ";

            if(($userfile['error']==UPLOAD_ERR_INI_SIZE) or ($userfile['error']==UPLOAD_ERR_FORM_SIZE)) $err = "ไฟลืมีขนาดใหญ่กว่าที่กำหนด";
            elseif($userfile['error']==UPLOAD_ERR_PARTIAL) $err = "ข้อมูลของไฟล์ถูกส่งมาไม่ครบ";
            elseif($userfile['error']==UPLOAD_ERR_NO_FILE) $err = "คุณไม่ได้เลือกไฟล์ที่จะส่ง";

        }
        elseif( $this->convert_size( $userfile['size'], $settings['unit_size'] ) > $settings['max_size'] ){
            $err = "ไฟล์มีขนาดเกินที่กำหนด ({$settings['max_size']} {$settings['unit_size']})";

        }

        if( isset($settings['type']) && !empty($userfile['name']) ){

            if( !in_array( $this->getType($userfile['name']), $this->getListType($settings['type']) ) ){
                $err = 'รูปแบบไฟล์ไม่ถูกต้อง';
            }
        }

        return !empty($err) ? false:true;
    }


    public function getListType($type){
        
        switch ($type) {
            case 'photo':
            case 'picture':
            case 'image':
            case 'img':
                return array('png','jpe','jpeg','jpg','gif','bmp');
                break;

            case 'video':
                return array('mp4');
                break;

            case 'file':
                return array(
                    'txt',

                    'zip','rar',

                    'pdf',

                    // office
                    'ppt','pptx','doc','docx','xls',
                    'odt','ods'
                );
                break;

            case 'pdf':
                return array('pdf');
                break;

            case 'word':
                return array('doc','docx');
                break;
            
            default:
                return array();
                break;
        }
    }
    public function getType($filename) {
        return strtolower(substr(strrchr($filename,"."),1));
    }
    public function getExtension($filename){
        return strtolower(strrchr($filename, '.'));
    }
    public function fix_file_extension($name){

        // Add missing file extension for known image types:
        if (strpos($name, '.') === false && 
                preg_match('/^image\/(gif|jpe?g|png)/', $type, $matches)) {
            $name .= '.'.$matches[1];
        }

        return $name;
    }
    public function formatSizeUnits($bytes) {
        if      ($bytes>=1073741824) {$bytes=round(($bytes/1073741824),2).' GB';}
        else if ($bytes>=1048576)    {$bytes=round(($bytes/1048576),2).' MB';}
        else if ($bytes>=1024)       {$bytes=round(($bytes/1024),2).' KB';}
        else if ($bytes>1)           {$bytes=$bytes.' bytes';}
        else if ($bytes==1)          {$bytes=$bytes.' byte';}
        else                         {$bytes='0 byte';}
        return $bytes;
    }
    public function convert_size( $size, $unit='MB'){
        switch ( strtoupper($unit) )  {
            case 'MB':
                return ($size/1024)/1024;
                break;
            
            default: // bytes
                return $size;
                break;
        }
    }
}