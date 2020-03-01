Step 1: Create Helper in side helper diorectory "general_helper.php"

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('document_upload'))
{
    function document_upload($upload_path = '',$allow_type = '',$file_name = '',$objectVar = '')
    {
    	$upload_file_name_id = "";
    	$file_ext_str = "";
    	
		$ci = get_instance();
    	foreach($allow_type as $data){
    		$file_ext_str .= $data."|";
    	}
    	$file_ext_str = trim($file_ext_str,"|");
    	
    	$config = array();
		$config['upload_path'] = './assets/'.$upload_path;
		$config['allowed_types'] = $file_ext_str;
		$config['max_size'] = 100000;
		$config['max_width'] = 1024;
		$config['max_height'] = 768;

		$ci->load->library('upload', $config, $objectVar);
		$ci->$objectVar->initialize($config);
    	$upload_document = $ci->$objectVar->do_upload($file_name);

    	if($upload_document){
			$imageDetailArray = $ci->$objectVar->data();
			$upload_file_name = $imageDetailArray['file_name'];
		}

        if(!empty($upload_file_name)){
            $ci->db->insert('documents',
            array("document_type " => 2,
                "document_url" => $upload_file_name,
                "document_name" => $upload_file_name,
                "document_status" => 1,
                "document_created_on" => date('Y-m-d H:i:s')
            ));
            $upload_file_name_id = $ci->db->insert_id();
        }
		return $upload_file_name_id;
    }   
}

Step 2: Add this code inside your controller
public function __construct() {
  parent::__construct();
  $this->load->model('General_model');
  $this->load->helper('general_helper');
}

Step 3: Add this code Inside submit function of controller.
$document = document_upload("Rules_Documents",array("pdf"),"rule_document","ruledocument");
