<?php 

include_once '../Mysql/Mysql.php';

class Api{

	public function __construct(){
	
	$this->mysqlConn = new MysqlConn("iot", "");	
	$this->initApi();
	}
	
	public function getRequestMethod(){
		return $_SERVER["REQUEST_METHOD"];	
	}
	
	private function responseHeader($responseType){
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");	
		echo $this->getRequestMethod()."\n";		
		if ($responseType == "GET"){
			header("Allow-Control-Allow-Headers: access");
			header("Access-Control-Allow-Credentials: true");	
		}
		elseif("POST") {
			header("Access-Control-Allow-Methods: POST");
			header("Access-Control-Max-Age: 3600");
			header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");		
		}
		
	}
	
	private function initApi(){
		switch($this->getRequestMethod()) {
			case "GET":
				$this->getProcess();
				break;
			
			case "POST":
				$this->postProcess();
				break;
		}	
	}
	
	private function getProcess(){
		$this->responseHeader("GET");
		$value = strtolower(trim(str_replace("/", "", $_REQUEST['request'])));
		if ((int)method_exists($this, $value) > 0){
			$this->$value();		
		}
		else {
			echo "Invalid Entry";		
		}
	}
	
	private function all(){
		//echo"In all";
		$data = $this->mysqlConn->readTable($tableName = "iotTable", $columns = "*",  $condition=1);
		echo $data;
	}
	
	private function single(){
		$id = $_GET["id"];
		//echo $id;
		if ($id){
			$data = $this->mysqlConn->readTable($tableName = "iotTable", $columns = "*",  $condition="id=$id");
			if ($data){
				echo $data;
			}
			else {
				echo "No data";		
			}
		}
		else {
			echo "Enter Valid Data";		
		}
			
	}
	
	private function put(){
	$value = $_GET["value"];
	$dateTime = $_GET["dateTime"];
	if ($value and $dateTime){
		$status = $this->mysqlConn->writeTable($tableName="iotTable",  $columns="(value, DateTime)", $data="($value, $dateTime)");	
		if ($status == 1){
			echo "Creation Success";		
		}
		else {
			echo "Creation Error";		
		}	
	}
	else	{
		echo "Enter Valid Data";	
		}
	}
	
	private function postProcess(){
			$this->responseHeader("POST");	
			$data = json_decode(file_get_contents("php://input"));
			$value = $data->Value;
			$dateTime = $data->dateTime;
			if ($value and $dateTime){
				$status = $this->mysqlConn->writeTable($tableName="iotTable",  $columns="(value, DateTime)", $data="($value, $dateTime)");	
				if ($status == 1){
					echo "Creation Success";		
				}
				else {
					echo "Creation Error";		
				}	
			}
			else	{
				echo "Enter Valid Data";	
			}
		} 
}


$test = new Api;
?>