C:\xampp\htdocs\mobilcomm-fb\application\config\config.php
//********************************************************

//$config['base_url'] = "https://mobilcomm.fourbrick.com/";
// $config['base_url'] = "https://mobilcomm.fourbrick.com/";
 $config['base_url'] = "https://localhost/mobilcomm-fb/";
 
 application\config\database.php
 //*********************************************************
 
   $db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'root',
//	'password' => 'openGH78f%#weh',  //***************** change *******************//
//	'database' => 'mepprojectv1',
	'password' => '',
	'database' => 'mepproject1',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,

C:\xampp\htdocs\mobilcomm-fb\application\config\routes.php
//********************************************************

$route['dashboardAtt'] = 'user/att';
$route['dashboardEme'] = 'user/eme';
$route['company'] = 'user';


C:\xampp\htdocs\mobilcomm-fb\application\controllers\EmailScheduler.php
//**********************************************************************

<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';
class EmailScheduler extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('siteInfo_model');
        $this->isLoggedIn();   
    }

    public function index()
    {
		 $this->global['pageTitle'] = 'MobileComm : EmailScheduler';
         $this->global['market_list'] =  $this->siteInfo_model->get_market_list();  
		 $this->global['templates'] =$this->siteInfo_model->get_templates();
		 $this->loadViews("emailscheduler/email-scheduler", $this->global, NULL , NULL);
    }   
     public function send_mail()
    {
		if(isset($_POST)){
			$marketNames = $this->input->post('market-names');
			$templeteId = $this->input->post('templete-id');
			$email_template = $this->siteInfo_model->get_template($templeteId);
			if($marketNames){
				$get_market = $this->siteInfo_model->get_market($marketNames);
				foreach($get_market as $gm) {
					$text = $gm['accessdetail'];
					$regexp = '/([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,4})+/i';
					preg_match_all($regexp, $text, $matches);
					//preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $text, $matches);
					$match_arr = $matches[0];
					if(count($match_arr)!= 0 ){
							
								foreach($match_arr as $email){
									$message = $email_template['message']; 
									$data['message'] = $message;
									$CI = setProtocol();  										
									$CI->email->from(EMAIL_FROM, FROM_NAME);
									$sub = 'Reguarding Survey Schedule';
									$CI->email->subject($sub);
									$CI->email->message($this->load->view('email/market_template', $data, TRUE));
									$CI->email->to($email);
									$status = $CI->email->send();
									
								}
							
						}
				}
			}			
        }
      
    } 

    public function change_tamplate_partial(){
		$templeteId= $this->input->post('value');
		if(!empty($templeteId)){
		$email_template = $this->siteInfo_model->get_template($templeteId);
		
		$data['message'] = $email_template['message'];
		$data['template_id'] =$email_template['id'];
	
		
		$template = 'Subject : Survey schedule message Format. </br> <p>';
		$template .= $this->load->view('email/market_template', $data, TRUE);
		$template .= '</p>';
		echo $template;
		}
    }
	/* public function manage_tamplate(){
		 $this->load->helper('form');
		$templeteId= $this->input->post('value');
		if(!empty($templeteId)){
		$email_template = $this->siteInfo_model->get_template($templeteId);
		$data['message'] = $email_template['message'];
		$data['template_id'] = $email_template['id'];
		$template = 'Subject : Survey schedule message Format. </br> <p>';
		$template .= $this->load->view('email/manage-template', $data, TRUE);
		$template .= '</p>';
		echo $template;
		}
    } 
	*/
	
    
}

?>

C:\xampp\htdocs\mobilcomm-fb\application\controllers\ManageTemplate.php
//*********************************************************************
@@ -0,0 +1,63 @@


<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';
class ManageTemplate extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('siteInfo_model');
        $this->isLoggedIn();   
    }

    public function index()
    {
		 $this->global['pageTitle'] = 'MobileComm : ManageTemplate';  
		 $this->global['templates'] =$this->siteInfo_model->get_templates();
		 $this->loadViews("managetemplate/managetemplate", $this->global, NULL , NULL);
    }   
     
	/*
    public function change_tamplate_partial(){
		$templeteId= $this->input->post('value');
		if(!empty($templeteId)){
		$email_template = $this->siteInfo_model->get_template($templeteId);
		
		
		$data['message'] = $email_template['message'];
		$data['template_id'] = $email_template['id'];
		$template = 'Subject : Survey schedule message Format. </br> <p>';
		$template .= $this->load->view('email/market_template', $data, TRUE);
		$template .= '</p>';
		echo $template;
		}
    }*/
	public function update_tamplate(){
		 $this->load->helper('form');
		$template_id= $this->input->post('template_id');
		$data['message'] = $this->input->post('message');
		if(!empty($template_id)){	
		$this->db->where('id' , $template_id);
		$this->db->update('email_template',$data);
		}
		$this->global['templates'] =$this->siteInfo_model->get_templates();
		$this->loadViews("managetemplate/managetemplate", $this->global, NULL , NULL);
    }
	public function manage_tamplate(){
		 $this->load->helper('form');
		$templeteId= $this->input->post('value');
		if(!empty($templeteId)){
		$email_template = $this->siteInfo_model->get_template($templeteId);
		$data['message'] = $email_template['message'];
		$data['template_id'] = $email_template['id'];
		$template = 'Subject : Survey schedule message Format. </br> <p>';
		$template .= $this->load->view('email/manage-template', $data, TRUE);
		$template .= '</p>';
		echo $template;
		}
    }


    
}

?>



//C:\xampp\htdocs\mobilcomm-fb\application\controllers\Siteinfo.php
//****************************************************************

@@ -56,15 +56,31 @@ class Siteinfo extends BaseController
	public function siteassigne()
	{
		$user_id = $this->input->post('user_id');
		$get_user_name = $this->smodel->get_user_detail($user_id);
		$assigne_date = $this->input->post('ass_date');
		//for email
		$templeteId = 3;
		$email_template = $this->smodel->get_template($templeteId);
		$msg = $email_template['message'];
	
		$message = str_replace("#name#",$get_user_name['name'],$msg);
		$message = str_replace("#mobile#",$get_user_name['mobile'],$message);
		
		//for email
		
		if(!empty($this->input->post('site')))
		{
			$this->send_mail($this->input->post('site'),$message);
			
			foreach($this->input->post('site') as $site_id)
			{
				//$assigne_site = "id=".$site_id;				
				$assigne_to_suer = array('site_info_id'=>$site_id,"user_id"=>$user_id);
				$assigne_to_suer['created_on'] = date("Y-m-d H:i:s");
				$assigne_to_suer['assigne_date'] = $assigne_date;

				
				
				$this->App_model->App_insert('mep_user_siteinfo_mapping',$assigne_to_suer);
			
			}
@ -123,6 +139,46 @@ class Siteinfo extends BaseController
            else { echo(json_encode(array('status'=>FALSE, 'status'=>$result))); }
        }
    }
	 public function send_mail($market_id,$message)
    {
			if($market_id){
				$get_market = $this->smodel->get_market_by_id($market_id);
					
				foreach($get_market as $gm) {
					$text = $gm['accessdetail'];
					$regexp = '/([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,4})+/i';
					preg_match_all($regexp, $text, $matches);
					//preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $text, $matches);
					$match_arr = $matches[0];
				
					
					if(count($match_arr)!= 0 ){
							$value[] = $match_arr;
								foreach($match_arr as $email){
									 
									$data['message'] = $message;
									$CI = setProtocol();  										
									$CI->email->from(EMAIL_FROM, FROM_NAME);
									$sub = 'Person asigned for Survey Schedule';
									$CI->email->subject($sub);
									$CI->email->message($this->load->view('email/market_template', $data, TRUE));
									$CI->email->to($email);
									$status = $CI->email->send();
									
								}
								
							//print_r($value);
						}  
						
						
				}
				
				
				//die();
			}			
       // }
      
    } 
	
}



//C:\xampp\htdocs\mobilcomm-fb\application\controllers\User.php
//*************************************************************

@@ -24,13 +24,25 @@ class User extends BaseController
        $this->load->helper('string');
        $this->load->helper('common');
        $this->load->library('excel');
		$this->load->model('eme/DashboardModel' , 'emeDasboard');
		$this->load->model('eme/Survey_model','emeSurvey_model');
		
        $this->isLoggedIn();   
    }
	
	/*
	 * This gives select to choose company 
	 */
	public function index()
    {
		$this->global['pageTitle'] = 'MobileComm : Dashboard';
		$this->load->view('company',$this->global);
	}
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    public function att()
    {
		// print_r($this->session->userdata()); die();
		$userId = $this->session->userdata()['userId'];
@ -125,7 +137,103 @@ class User extends BaseController
            $this->loadViews("dashboard_report_team", $this->global, $data , NULL);
        }
    }
    
    public function eme(){
		
		// print_r($this->session->userdata()); die();
		$userId = $this->session->userdata()['userId'];
        $roleId = $this->session->userdata()['role'];
        $this->global['pageTitle'] = 'MobileComm : Dashboard';

        // echo $roleId;die();
        if($roleId == 1 || $roleId ==5)
        {
            $data['overalldata'] = $this->emeDasboard->getOverALlReport();
            $data['weeklyreport'] = $this->emeDasboard->getWeeklyReport();
            $data['dailyreport'] = $this->emeDasboard->getDailyReport();

            $data['totaluser'] = $this->emeDasboard->getUserCount();
            $data['totalsite'] = $this->emeDasboard->getSiteCount();
            
            $data['totalnarda_device'] = $this->emeDasboard->getnarda_device();
            $data['totaltransmitter'] = $this->emeDasboard->getTransmitterCount();
            $cond = array();
            $data['totalsurvey'] = count($this->emeSurvey_model->get_survey_list('','',$cond,'',''));
            
            $cond = array('isreportmade'=>1);
            $data['count'] = $this->emeDasboard->getReportCount($cond);//$count = count($this->Survey_model->get_survey_list_assigned('','',''));
            $data['liveDashboard'] = $this->emeDasboard->getLiveDashboardData();

            $data['dailyReportTracker'] = $this->emeDasboard->getDailyReportTracking(); #by market 
            $date = new DateTime("now");
            $curr_date = $date->format('Y-m-d');

            $data['GDCteamPerformance'] = $this->emeDasboard->getDailyGDCTeamPerformance(); #by Team Performance
             
            $data['GDCQuantityEngineerPerformance'] = $this->emeDasboard->getQEdailyGDCdata(); #by Quality 
             // ============== Weekly Report ==================
            $week = $date->format("W");
            $data['currentWeekData']  = $this->emeDasboard->getCurrentWeekReport_group( $week -1 );

            $data['allWeekData']  = $this->emeDasboard->getAllWeekReport();

            /** REPORT MAKER WEEKLY PERFORMANCE**/
            $data['week'] = $this->emeDasboard->getAllWeekWork();
            $data['users'] = $this->emeDasboard->getUserList();
            $repdata = $this->emeDasboard->getWeeklyReportmakerPerformance();
            $dataArray = array();
            foreach ($repdata as $key => $value) 
            {
                foreach ($value as $key1 ) {
                    $dataArray[$key][$key1['name']] = $key1['totalreportmade'];
                }
            }
            $data['weeklyReportMakerPf'] = $dataArray;

            /** REPORT MAKER WEEKLY PERFORMANCE END**/
            /** QC WEEKLY PERFORMANCE**/
            $data['usersqc'] = $this->emeDasboard->getUserListQc();
            $repdata = $this->emeDasboard->getWeeklyQCPerformance();
            $dataArrayQC = array();
            foreach ($repdata as $key => $value) 
            {
                foreach ($value as $key1 ) {
                    $dataArrayQC[$key][$key1['name']] = $key1['totalreportmade'];
                }
            }
            $data['weeklyQcPf'] = $dataArrayQC;
            
            /** QC WEEKLY PERFORMANCE END**/
            // $data['weeklyReportMakerPf']  =  $this->DashboardModel->getWeeklyReportmakerPerformance();

            $this->loadViewsEme("eme/dashboard", $this->global, $data , NULL);
        }
        elseif($roleId == 2)
        {
            redirect('tech');
        }
        elseif($roleId == 3)
        {
            $condassigned = array('report_status'=>$userId);
            $data['assigned'] = $this->emeDasboard->getReportCount($condassigned);
            $condcompleted = array('isreportmade'=>1,'report_status'=>$userId);
            $data['completed'] = $this->emeDasboard->getReportCount($condcompleted);
            $condpending = array('isreportmade'=>0,'report_status'=>$userId);
            $data['pending'] = $this->emeDasboard->getReportCount($condpending);
            $this->loadViews("dashboard_report_team", $this->global, $data , NULL);
        }
        elseif($roleId == 4)
        {
            $condassigned = array('qc_team'=>$userId);
            $data['assigned'] = $this->emeDasboard->getReportCount($condassigned);
            $condcompleted = array('isreportqced'=>1,'qc_team'=>$userId);
            $data['completed'] = $this->emeDasboard->getReportCount($condcompleted);
            $condpending = array('isreportqced'=>0,'qc_team'=>$userId); 
            $data['pending'] = $this->emeDasboard->getReportCount($condpending);
            $this->loadViews("dashboard_report_team", $this->global, $data , NULL);
        }
		
		
	}
    function test()
    {
        $data = $this->DashboardModel->getLiveDashboardDataTest();




//**********************************************************************
//
//C:\xampp\htdocs\mobilcomm-fb\application\libraries\BaseController.php
//
//
//*************************************************************************


@ -140,7 +140,16 @@ class BaseController extends CI_Controller {
        $this->load->view($viewName, $pageInfo);
        $this->load->view('includes/footer', $footerInfo);
    }
	
	/*
	 Load views with header and footer
    
	*/
	 function loadViewsEme($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){

        $this->load->view('eme/includes/header', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('eme/includes/footer', $footerInfo);
    }
	/**
	 * This function used provide the pagination resources
	 * @param {string} $link : This is page link


	 
	 
/* *********************************************************************	 
	 
	
	C:\xampp\htdocs\mobilcomm-fb\application\models\eme\DashboardModel.php
	 
	 
	 
************************************************************************ */


@@ -0,0 +1,409 @@
<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class DashboardModel extends CI_Model
{
    function getOverALlReport()
    {
        $query = 'alpha_1 as market, count(*) as surveycompleted, sum(case when isreportmade = 1 then 1 else 0 end) as totalreportmade, sum(case when isreportqced = 1 then 1 else 0 end) as totalreportqced, 0 as repupfn, 0 as newsignage';
        $this->db->select($query);
        $this->db->from('site_survey');
        $this->db->group_by('alpha_1');
        $query = $this->db->get();
        //echo $this->db->last_query();
        $result = $query->result_array();   
        return $result;
    }


    function getWeeklyReport()
    {
        $query = "WEEK(datepicker_29) as week, sum(case when alpha_31 = 'No' then 0 else 1 end) as surveycompleted, sum(case when alpha_31 = 'No' then 1 else 0 end) as sitefailed, sum(case when tri_272 = 'Yes' AND numeric_273 > 40 AND tri_274 = 'No' then 1 else 0 end) as site_canceled, sum(case when isreportmade = 1 then 1 else 0 end) as totalreportmade, sum(case when SSEI.RFSSRuploaded  = 1 then 1 else 0 end) as uploadedtofn, sum(case when isreportqced = 1 then 1 else 0 end) as totalreportqced, 0 as repupfn";
        $this->db->select($query);
        $this->db->from('site_survey');
        $this->db->join('site_survey_extra_info SSEI','SSEI.fanumber=site_survey.FAnumber','left');
        $this->db->group_by('WEEK(datepicker_29)');
        $query = $this->db->get();
        //echo $this->db->last_query();
        $result = $query->result_array();   
        return $result;
    }




    function getUserCount()
    {
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where_not_in('roleId',1);
        $query = $this->db->get();
        return $query->num_rows();
    }
    function getTransmitterCount()
    {
        $this->db->select('*');
        $this->db->from('mep_transmitter');
        $query = $this->db->get();
        return $query->num_rows();
    }
    function getSiteCount()
    {
        $this->db->select('*');
        $this->db->from('mep_siteinfo');
        $query = $this->db->get();
        return $query->num_rows();
    }
	public function getnarda_device()
	{
		$this->db->select('*');
		$this->db->from('mep_technarda');
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function getSurveyCount()
	{
		$this->db->select('*');
		$this->db->from('site_survey');
		$query = $this->db->get();
		return $query->num_rows();
	}


	public function getDailyReport()
	{
    	$date = new DateTime("now");

    	$curr_date = $date->format('d-m-y');
        $query = 'alpha_1 as market, count(*) as surveycompleted, sum(case when isreportmade = 1 then 1 else 0 end) as totalreportmade, sum(case when isreportqced = 1 then 1 else 0 end) as totalreportqced, 0 as repupfn, 0 as newsignage';
        $this->db->select($query);
        $this->db->from('site_survey');
		$this->db->where('datepicker_29',$curr_date);
        $this->db->group_by('alpha_1');
        $query = $this->db->get();
        //echo $this->db->last_query();
        $result = $query->result_array();   
        return $result;
	}


    /*public function getLiveDashboardData()
    {
        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d');
        
         $query = "alpha_1 as market, count(*) as site_scheduled, sum(case when isreportmade = 1 then 1 else 0 end) as site_surveyed, sum(case when alpha_30 = 'No' OR alpha_31 = 'No'  then 1 else 0 end) as sitefailed, sum(listpicker_1 + listpicker_292 + listpicker_2 + listpicker_3 + listpicker_4) as signagepost";
        $this->db->select($query);
        $this->db->select('email,name');
        $this->db->from('site_survey');
        $this->db->join('tbl_users u', 'u.apikey =site_survey.apikey ');
        $this->db->where('datepicker_29',$curr_date);
        $this->db->group_by('site_survey.apikey');
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        $result = $query->result_array();   
        return $result;
    }*/
	
    public function testData()
    {
         $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d');
        
         $query = "alpha_1 as market, count(*) as site_scheduled, sum(case when isreportmade = 1 then 1 else 0 end) as report_made, sum(case when isreportqced  = 1 then 1 else 0 end ) as report_qced , count(FAnumber) as survery_completed, sum(listpicker_1 + listpicker_292 + listpicker_2 + listpicker_3 + listpicker_4) as signagepost, sum(case when SSEI.RFSSRuploaded  = 1 then 1 else 0 end) as uploadedtofn,";
        $this->db->select($query);
        $this->db->from('site_survey');
        $this->db->join('site_survey_extra_info SSEI','SSEI.fanumber=site_survey.FAnumber','left');
        $this->db->where('datepicker_29',$curr_date);
        $this->db->group_by('site_survey.alpha_1');
        $query = $this->db->get();
        echo $this->db->last_query(); die();
        $result = $query->result_array();   
        return $result;
    }

    public function getLiveDashboardDataTest()
    {
        $date = new DateTime("now");
        // $date->modify('-1 day');
        $curr_date = $date->format('Y-m-d');

        $query = "ss.alpha_1 as market, count(MUSM.user_id) as site_scheduled, count(ss.FAnumber) as site_surveyed, sum(case when ss.alpha_31 = 'No' then 1 else 0 end) as sitefailed, sum(case when ss.tri_272 = 'Yes' AND ss.numeric_273 > 40 AND ss.tri_274 = 'No'  then 1 else 0 end) as site_canceled, sum(ss.listpicker_1 + ss.listpicker_292 + ss.listpicker_2 + ss.listpicker_3 + ss.listpicker_4) as signagepost";
        // $query = "ss.alpha_1 as market, count(MUSM.user_id) as site_scheduled, count(ss.FAnumber) as site_surveyed, sum(case when ss.alpha_31 = 'No' then 1 else 0 end) as sitefailed, sum(case when ss.tri_272 = 'Yes' AND ss.numeric_273 > 40 AND ss.tri_274 = 'No'  then 1 else 0 end) as site_canceled, sum(ss.listpicker_1 + ss.listpicker_292 + ss.listpicker_2 + ss.listpicker_3 + ss.listpicker_4) as signagepost";
        $this->db->select($query);
        $this->db->select('email,name');
        $this->db->from('mep_user_siteinfo_mapping MUSM');
        $this->db->join('tbl_users u', 'u.userId =MUSM.user_id', 'left');
        $this->db->join('mep_siteinfo MSI', 'MSI.id =MUSM.site_info_id', 'left');
        $this->db->join('site_survey ss', 'ss.FAnumber =MSI.fanumber', 'left');
        $this->db->where('MUSM.assigne_date',$curr_date);
        // $where_con = "(ss.datepicker_29 ='".$curr_date."' or ss.datepicker_29 is null)"; 
        // $this->db->where($where_con);
        // $this->db->where('ss.datepicker_29',$curr_date);
        // $this->db->or_where('ss.datepicker_29',null);
        $this->db->group_by('MUSM.user_id');
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        $result = $query->result_array();   
        return $result;
    }

    public function getLiveDashboardData()
    {
        $date = new DateTime("now");
        // $date->modify('-1 day');
        $curr_date = $date->format('Y-m-d');
        $query = "ss.alpha_1 as market, count(MUSM.user_id) as site_scheduled, count(ss.FAnumber) as site_surveyed, sum(case when ss.alpha_31 = 'No' then 1 else 0 end) as sitefailed, sum(case when ss.tri_272 = 'Yes' AND ss.numeric_273 > 40 AND ss.tri_274 = 'No' then 1 else 0 end) as site_canceled, sum(ss.listpicker_1 + ss.listpicker_292 + ss.listpicker_2 + ss.listpicker_3 + ss.listpicker_4) as signagepost, email, name FROM mep_user_siteinfo_mapping MUSM LEFT JOIN tbl_users u ON u.userId = MUSM.user_id LEFT JOIN mep_siteinfo MSI ON MSI.id = MUSM.site_info_id LEFT JOIN site_survey ss ON ss.FAnumber = MSI.fanumber AND (ss.datepicker_29 = '". $curr_date."' or ss.datepicker_29 is null) WHERE MUSM.assigne_date = '". $curr_date."' GROUP BY MUSM.user_id";
        $this->db->select($query);
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        $result = $query->result_array();   
        return $result;
    }

     public function getDailyReportTracking()
    {
        $date = new DateTime("now");
        // $date->modify('-1 day');
        $curr_date = $date->format('Y-m-d');
        
         $query = "alpha_1 as market, count(*) as site_scheduled, sum(case when isreportmade = 1 then 1 else 0 end) as report_made, sum(case when isreportqced  = 1 then 1 else 0 end ) as report_qced , sum(case when alpha_31 = 'No' then 0 else 1 end) as survery_completed, sum(case when alpha_31 = 'No' then 1 else 0 end) as sitefailed, sum(listpicker_1 + listpicker_292 + listpicker_2 + listpicker_3 + listpicker_4) as signagepost, sum(case when SSEI.RFSSRuploaded  = 1 then 1 else 0 end) as uploadedtofn";
        $this->db->select($query);
        $this->db->from('site_survey');
        $this->db->join('site_survey_extra_info SSEI','SSEI.fanumber=site_survey.FAnumber','left');
        $this->db->where('datepicker_29',$curr_date);
        $this->db->group_by('site_survey.alpha_1');
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        $result = $query->result_array();   
        return $result;
        
    }


    public function getDailyGDCTeamPerformance(){
        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d');
        
         $query = "report_status,alpha_1 as market, count(*) as site_completed, sum(case when isreportmade = 1 then 1 else 0 end) as report_made, sum(case when alpha_31  = 'No' then 1 else 0 end) as sitefailed  ";
        $this->db->select($query);
        $this->db->select("count(sm.id) as site_assigned");
        $this->db->select('email,name');
        $this->db->from('site_survey');
        $this->db->join('tbl_users u', "u.userId =site_survey.report_status", "left");
        $this->db->join('mep_user_siteinfo_mapping sm', "sm.user_id = site_survey.report_status", "left");
        $this->db->where('datepicker_29',$curr_date);
        $this->db->group_by('site_survey.report_status');
        $this->db->where("report_status >", 0);

        $query = $this->db->get();
        // dd($this->db->last_query());
        $result = $query->result_array();   
        return $result;
    }

    public function getQEdailyGDCdata(){
        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d');
        
         $query = "report_status,alpha_1 as market, count(*) as site_completed, sum(case when isreportqced = 1 then 1 else 0 end) as report_made, sum(case when alpha_31  = 'No' then 1 else 0 end) as sitefailed  ";
        $this->db->select($query);
        $this->db->select("count(sm.id) as site_assigned");
        $this->db->select('email,name');
        $this->db->from('site_survey');
        $this->db->join('tbl_users u', "u.userId =site_survey.qc_team", "left");
        
        $this->db->join('mep_user_siteinfo_mapping sm', "sm.user_id = site_survey.qc_team", "left");
      
        $this->db->where('datepicker_29', $curr_date);
        $this->db->group_by('site_survey.qc_team');
        $this->db->where("qc_team > " , 0);
        $query = $this->db->get();
        $result = $query->result_array();   
        return $result;
    }

    public function getCurrentWeekReport($week){
        $date = new DateTime("now");
        $curr_date = $date->format('d-m-y');

         $query = "report_status,alpha_1 as market, sum(case when alpha_31 = 'No'  then 0 else 1 end) as survery_completed, sum(case when isreportmade = 1 then 1 else 0 end) as report_made, sum(case when alpha_31  = 'No' then 1 else 0 end) as sitefailed  ,sum(case when isreportqced = 1 then 1 else 0 end) as totalreportqced, sum(case when SSEI.RFSSRuploaded  = 1 then 1 else 0 end) as uploadedtofn, sum(listpicker_1 + listpicker_292 + listpicker_2 + listpicker_3 + listpicker_4) as signagepost ";
        $this->db->select($query);
        $this->db->from('site_survey');
        $this->db->join('site_survey_extra_info SSEI','SSEI.fanumber=site_survey.FAnumber','left');
        $this->db->where('Week(datepicker_29) =', $week);
        $query = $this->db->get();
        // dd($this->db->last_query());
        $result = $query->result_array();   
        return $result;
    }

    public function getCurrentWeekReport_group($week){
        $date = new DateTime("now");
        $curr_date = $date->format('d-m-y');

         $query = "report_status,alpha_1 as market, sum(case when alpha_31 = 'No' then 0 else 1 end) as survery_completed, sum(case when tri_272 = 'Yes' AND numeric_273 > 40 AND tri_274 = 'No' then 1 else 0 end) as site_canceled, sum(case when isreportmade = 1 then 1 else 0 end) as report_made, sum(case when alpha_31  = 'No' then 1 else 0 end) as sitefailed  ,sum(case when isreportqced = 1 then 1 else 0 end) as totalreportqced, sum(case when SSEI.RFSSRuploaded  = 1 then 1 else 0 end) as uploadedtofn, sum(listpicker_1 + listpicker_292 + listpicker_2 + listpicker_3 + listpicker_4) as signagepost ";
        $this->db->select($query);
        $this->db->from('site_survey');
        $this->db->join('site_survey_extra_info SSEI','SSEI.fanumber=site_survey.FAnumber','left');
        $this->db->where('Week(datepicker_29) =', $week);
        $this->db->group_by('alpha_1');
        $query = $this->db->get();
        // dd($this->db->last_query());
        $result = $query->result_array();   
        return $result;
    }

    public function getAllWeekReport(){
       $this->db->select('datepicker_29, id, Week(datepicker_29) as week');
       $this->db->order_by('datepicker_29');
       $this->db->from('site_survey');
       $this->db->group_by('week');
       $query = $this->db->get();
       $result = $query->result_array();
       // dd($result);
       $counter = 0;
       $final = array();
       foreach($result as $res){
        $particularWeekData = $this->getCurrentWeekReport($res['week']);
        $final[$counter] = $particularWeekData[0];
        $final[$counter]['week'] = $res['week'];
        $counter++;
       }
       return $final;

    }

    // REPORT TEAM DASHBOARD

    public function getReportCount($where = array())
    {
        $this->db->select('id');
        $this->db->from('site_survey');
        $this->db->where($where);
        $query = $this->db->get();
        // echo $this->db->last_query(); 
        return $query->num_rows();
    }

     public function getWeeklyReportmakerPerformance()
     {
      $allWeeks =  $this->getAllWeekWork();
      $userWeeklyPerformace =  $this->getUserWeeklyPerformace();
      $userList = $this->getUserList();
      $finalArray = array();
      $inputObjU = new stdClass();

      $returnArray = array();
      foreach($allWeeks as $week)
      {
        $inputObjU->week = $week['week'];
        $finalArray[$week['week']] =  $this->getUserWorkOnWeek($inputObjU);
      }

      return $finalArray;
    }


    public function getWeeklyQCPerformance()
    {
      $allWeeks =  $this->getAllWeekWork();
      $userWeeklyPerformace =  $this->getUserWeeklyPerformace();
      $userList = $this->getUserList();
      $finalArray = array();
      $inputObjU = new stdClass();

      $returnArray = array();
      foreach($allWeeks as $week)
      {
        $inputObjU->week = $week['week'];
        $finalArray[$week['week']] =  $this->getQCWorkOnWeek($inputObjU);
      }

      return $finalArray;
    }

    public function getAllWeekWork(){
        $query =  $this->db->select('WEEK(datepicker_29) as week')
                ->from('site_survey ss')
                ->group_by('week')
                ->get();
      $result =   $query->result_array();    
      // dd($result);  
      return $result;       
    }

    function getUserList(){
        $query = 'u.name, site_survey.report_status';
        $this->db->select($query);
        $this->db->from('site_survey');
        $this->db->join( 'tbl_users u', 'u.userId = site_survey.report_status', 'left');
        $this->db->where('report_status <> 0');
        $this->db->where('site_survey.isreportmade = 1');
        $this->db->group_by('site_survey.report_status');
        $query = $this->db->get();
        // echo $this->db->last_query();die();
        $result = $query->result_array();   
        return $result;
    }


     function getUserListQc(){
        $query = 'u.name, site_survey.qc_team';
        $this->db->select($query);
        $this->db->from('site_survey');
        $this->db->join( 'tbl_users u', 'u.userId = site_survey.qc_team', 'left');
        $this->db->where('qc_team <> 0');
        $this->db->where('site_survey.isreportqced = 1');
        $this->db->group_by('site_survey.qc_team');
        $query = $this->db->get();
        // echo $this->db->last_query();die();
        $result = $query->result_array();   
        return $result;
    }

   

    function getUserWeeklyPerformace(){
        $query = 'u.userId,  WEEK(datepicker_29) as week,u.name,site_survey.report_status';
        $this->db->select($query);
        $this->db->from('site_survey');
        $this->db->join( 'tbl_users u', 'u.userId = site_survey.report_status', 'left');
        $this->db->where('report_status <> 0');
        $this->db->group_by('site_survey.report_status');
        $query = $this->db->get();
        // echo $this->db->last_query();die();
        $result = $query->result_array();   
        return $result;
    }

    public function getUserWorkOnWeek($inputObj){
        $query = 'WEEK(datepicker_29) as week,sum(case when isreportmade = 1 then 1 else 0 end) as totalreportmade,u.name';
        $this->db->select($query);
        $this->db->from('site_survey ss');
          $this->db->join( 'tbl_users u', 'u.userId = ss.report_status', 'left');
        $this->db->where('WEEK(datepicker_29)' , $inputObj->week);
        $this->db->where(array('ss.report_status !='=>0, 'isreportmade'=>1));
        $this->db->group_by('ss.report_status');
        $query = $this->db->get();
        $result = $query->result_array();
        // ndd($this->db->last_query()) ;  
        return $result;
    }

    public function getQCWorkOnWeek($inputObj){
        $query = 'WEEK(datepicker_29) as week,sum(case when isreportqced = 1 then 1 else 0 end) as totalreportmade,u.name';
        $this->db->select($query);
        $this->db->from('site_survey ss');
          $this->db->join( 'tbl_users u', 'u.userId = ss.qc_team', 'left');
        $this->db->where('WEEK(datepicker_29)' , $inputObj->week);
        $this->db->where(array('ss.qc_team !='=>0, 'isreportqced'=>1));
        $this->db->group_by('ss.qc_team');
        $query = $this->db->get();
        $result = $query->result_array();
        // ndd($this->db->last_query()) ;  
        return $result;
    }
}

?>