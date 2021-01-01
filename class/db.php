<? 

class DB {
	
	private $link;
	
	public function __construct()
	{
		try{
			$this->connect();
		}	
		catch(PDOException $e){
			throw new Exception($e->getMessage());
		}
	}
	
	private function connect()
	{
		$config = require_once($_SERVER['DOCUMENT_ROOT'].'/class/db_config.php');
		$dsn = "mysql:host=".$config['host'].";dbname=".$config['db'].";charset=".$config['charset'];

		$this->link = new PDO($dsn, $config['user'], $config['pass']);
		$this->link->exec('SET NAMES utf8');							# Устанавливаем для БД кодировку UTF-8
		return $this;
	}
	
	public function execute($sql)
	{
		$sth = $this->link->prepare($sql); // prepare подготавливает запрос к выполнению
		
		return $sth->execute();
		
	}
	
	public function query($sql)
	{
		$sth = $this->link->prepare($sql); // prepare подготавливает запрос к выполнению
		
		$sth->execute();
		
		$res = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		if($res  === false) {
			
			return array();
			
		}
		
		return $res;
	}
	
	public function lastInsertId() {
		$sth = $this->link->lastInsertId();
		return $sth;
	}
	
	

}

 ?>