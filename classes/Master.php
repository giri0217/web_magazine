<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_category(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `category_list` set {$data} ";
		}else{
			$sql = "UPDATE `category_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `category_list` where `name`='{$name}' ".($id > 0 ? " and id != '{$id}'" : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Category Name Already Exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Category details was successfully added.";
				else
					$resp['msg'] = "Category details was successfully updated.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_category(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `category_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Category has successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_magazine() {
		$_POST['status'] = isset($_POST['status']) && $_POST['status'] == 'on' ? 1 : 0;
		if (empty($_POST['id'])) {
			$_POST['user_id'] = $this->settings->userdata('id');
		}
		$_POST['description'] = htmlentities($_POST['description']);
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				if (!is_numeric($v)) {
					$v = $this->conn->real_escape_string($v);
				}
				if (!empty($data)) {
					$data .= ",";
				}
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if (empty($id)) {
			$sql = "INSERT INTO `magazine_list` set {$data} ";
		} else {
			$sql = "UPDATE `magazine_list` set {$data} where id = '{$id}' ";
		}
	
		$save = $this->conn->query($sql);
		if ($save) {
			$mid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['id'] = $mid;
			$resp['status'] = 'success';
			if (empty($id)) {
				$resp['msg'] = "Magazine details were successfully added.";
			} else {
				$resp['msg'] = "Magazine details were successfully updated.";
			}
	
			// Upload image
			if (isset($_FILES['banner']) && $_FILES['banner']['tmp_name'] != '') {
				$fname = 'uploads/banners/' . basename($_FILES["banner"]["name"]);
				$dir_path = base_app . $fname;
				move_uploaded_file($_FILES["banner"]["tmp_name"], $dir_path);
				$this->conn->query("UPDATE magazine_list set `banner_path` = '{$fname}' where id = '{$mid}' ");
			}
	
			// Upload PDF
			if (isset($_FILES['pdf']) && $_FILES['pdf']['tmp_name'] != '') {
				$fname = 'uploads/pdf/' . basename($_FILES["pdf"]["name"]);
				$dir_path = base_app . $fname;
				move_uploaded_file($_FILES["pdf"]["tmp_name"], $dir_path);
				$this->conn->query("UPDATE magazine_list set `pdf_path` = '{$fname}' where id = '{$mid}' ");
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred.";
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		if ($resp['status'] == 'success') {
			$this->settings->set_flashdata('success', $resp['msg']);
		}
		return json_encode($resp);
	}
	
	function delete_comment(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `comment_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Comment has successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	public function verify_comment(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `comment_list` set `status` = 1 where id = $id");
		if($update){
			$this->settings->set_flashdata('success','Comment has successfully verified.');
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_category':
		echo $Master->save_category();
	break;
	case 'delete_category':
		echo $Master->delete_category();
	break;
	case 'save_magazine':
		echo $Master->save_magazine();
	break;
	case 'delete_magazine':
		echo $Master->delete_magazine();
	break;
	case 'save_comment':
		echo $Master->save_comment();
	break;
	case 'delete_comment':
		echo $Master->delete_comment();
	break;
	default:
	case 'verify_comment':
		echo $Master->verify_comment();
	break;
		// echo $sysset->index();
		break;
}