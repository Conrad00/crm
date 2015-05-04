<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cronjob extends CI_Controller {

	public function index()
	{
          
	}
        function cron_task_overdue(){
            $this->functions->overdue_task_sms();
        }
	
}