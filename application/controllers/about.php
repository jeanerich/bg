<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {
   
	function about()
	{

        $data['title'] = 'About us Page';
        $this->load->view('about', $data); //about is in views
  	}

      }

?>