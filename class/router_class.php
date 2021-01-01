<?

class Router{
	
	protected $user_id;
	protected $req_uri;
	protected $modules = array();
	protected $mod;
	private $lvl_url = 3;
	public $db;
	private $url;
	private $params;
	private $tmp;
	public $dept_id;
	public $parent_id;
	
	public function __construct($req_uri=false,$user_id=false){

		$is_mobile_device = check_mobile_device();
		if($is_mobile_device){
			header('location: https://m.'.$_SERVER['HTTP_HOST']);
		}else{
			$this->db = new DB;
			$sql = "SET time_zone='+05:00'";
			$res = $this->db->query($sql);
			$this->req_uri = $req_uri;
			if(AJAX){
				if($_POST['action']){
					$this->loader_ajax_zapr();
				}else{
					$this->loader_ajax();
				}
			}else{
				
				$this->loader();
			}
		}
	}
	
	private function check_url(){
		
		if(AJAX){$this->req_uri = $_POST['link'];}
		$this->url = urldecode($this->req_uri);
		$this->url= explode('?', $this->url);
		$this->params = $this->url[1];
		if(isset($_POST['act_ax'])){
			$this->url[0] = $_POST['act_ax'];
		}
		$this->tmp = explode('/', trim($this->url[0], '/'));
		if(count($this->tmp) > $this->lvl_url){
			$this->er404();
		}
		
	}
	
	private function loader_ajax_zapr(){
		require_once(ROOT_DIR.'class/lang.php');
		
		$action = $_POST['action'];
		
		
		$sql  = "SELECT * FROM url_ajax";
		$sql .= " WHERE action = '{$action}'";
		
		$res = $this->db->query($sql);
		
		$z_c = dirname(__DIR__).$res[0]['url_req'];
		$title_url = $res[0]['title_url'];

		require_once($z_c);
		
	}
			
			
	private function loader(){
		$this->check_url();
		require_once(ROOT_DIR.'class/lang.php');
		require_once(ROOT_DIR.'class/seo.php');
	}
	
	private function loader_ajax(){
	
		$this->check_url();
		require_once(ROOT_DIR.'/class/lang.php');
		
		if(USER_ID){
			if(empty($this->tmp[0])){
				$content = $this->handler_url("id".USER_ID);
			}else{
				$content = $this->handler_url($this->tmp[0]);
			}
		}else{
			$content = $this->handler_url("id".USER_ID);
		}
			require_once(ROOT_DIR."/class/dop_func.php");
			
			if($this->mod == "mail" || $this->mod=='dg'){

			echo "<script>$('#text_footer').css('display','none');</script>";
			
			}
			else{
				echo "<script>$('#text_footer').css('display','block');</script>";
			}
		
		if($content){			
			require_once(ROOT_DIR.$content);
		}else{
			echo "<script>document.location.replace('".SITE_URL."/id".USER_ID."');</script>";die();
		}

	}
	
	private function handler_url($first){
		$path = "modules/";
		$ix = "/index.php";
		
		if(USER_ID){
			if($this->size_url($first,"id",true)){return $path."profile".$ix;}
			
			if($this->size_url($first,"visits")){return $path."inc".$ix;}   # Выезды
			if($this->size_url($first,"repairs")){return $path."inc".$ix;}
			if($this->size_url($first,"refills")){return $path."inc".$ix;}

		
			
			# Справочник

			if($this->size_url($first,"zakaz_request")){return $path."zakaz_zap/page/request.php";}
			
			
			if($this->size_url($first,"report_sla")){return $path."sla".$ix;}
			
		}else{
			if($this->size_url($first,"activate")){return $path."reg/activate.php";}	
			if($this->size_url($first,"help")){return $path."/help/index_no_auth.php";}
			if($this->size_url($first,"about")){return $path."/about/".$ix;}
			if($this->size_url($first,"reg")){return $path."/reg/reg.php";}
			
		}
		if($this->size_url($first,"logout")){return $path."logout".$ix;}
		if($this->size_url($first,"login")){return $path."login".$ix;}
		if($this->size_url($first,"404")){return "err/error404.php";}
	}
	
	private function size_url($first,$inc,$no=false){	
	
		if($no){
			if($this->sokr($first,strlen($inc))==$inc){
				$razd = explode('_', substr($first,strlen($inc)));
				if(is_numeric(substr($first,strlen($inc))) || empty(substr($first,strlen($inc)))){
					return true;
				}
				if(is_numeric($razd[0]) && is_numeric($razd[1])){
					return true;
				}
				
				
			}
		}else{
			if(strlen($first)==strlen($inc) && $first==$inc){
				return true;
			}
		}
		
		return false;
	}
	
	private function sokr($first,$size){	
			return substr($first, 0, $size);	
	}

	
	
	private function er404(){
		header("HTTP/1.0 404 Not Found");
		require_once(dirname(__DIR__)."/err/error404.php");
        exit();
	}
	
	
}

$apl = new Router(REQ_URI,USER_ID);

?>