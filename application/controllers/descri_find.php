<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Descri_find extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('captcha');
		$this->load->model('console_imf');
	}

	private function create()	// 标准方法创建验证码，并将验证码的值存到session
	{
		$img_url = base_url()."captcha/";
		$ranNum = rand(1000,9999);
		$vals = array(
		'word' => $ranNum,
		'img_path' => './captcha/',
		'img_url' => $img_url,
		'expiration' => 60
		);

		$date = create_captcha($vals);
		$_SESSION['yanzhen_descri'] = sha1($ranNum.sha1("xunwu2014"));
		return $date['image'];
	}

	/**
	* 刷新验证码
	* @return img url
	*/

	public function refresh()
	{
		$img_url = base_url()."captcha/";
		$ranNum = rand(1000,9999);
		$vals = array(
		'word' => $ranNum,
		'img_path' => './captcha/',
		'img_url' => $img_url,
		'expiration' => 60,
		);

		$date = create_captcha($vals);
		$_SESSION['yanzhen_descri'] = sha1($ranNum.sha1("xunwu2014"));

		echo $img_url.$date['time'].".jpg";
	}

	/**
	* 判断验证码是否正确
	* @return Json-Boolean
	*/

	public function verify()
	{
		$verify = $_POST['verify-input'];
		$verify = sha1($verify.sha1("xunwu2014"));
		if (isset($_SESSION['yanzhen_descri'])) {
			if ($verify === $_SESSION['yanzhen_descri']) {
				echo '{"status": "1"}';
			}
			else{
				echo '{"status": "0"}';
			}
		}
		else{
			echo '{"status": "0"}';
		}
	}

	public function page_descri_f()
	{
		$data['image'] = $this->create();	// 验证码
		$data["title"] = "Find Something";
		$this->load->view('header',$data);
		$this->load->view('descri_find');
		$this->load->view('footer');
	}

	//向数据库插入捡到的物品信息
	public function imformation(){
	$data = array(
		'kind'  =>$this->input->post("kind_f"),
		'name'  =>$this->input->post("name_f"),
		'locale'=>$this->input->post("locale_f"),
		 'time'  =>date("Y-m-d"),
		'finder'=>$this->input->post("finder_f"),
		'telnum'=>$this->input->post("telnum_f"),
		'email' =>$this->input->post("email_f"),
		'qq'    =>$this->input->post("qq_f"),
		'descri'=>$this->input->post("descri_f")
		);
	$res = $this->console_imf->insert_things_f($data);
	return ("success");
	}
//返回14条信息给捡到页面
	public function im_to_find(){
		$res = $this->console_imf->imf_to_page();
		return $res;
	}
	public function im_to_ind_f(){
		$res = $this->console_imf->imf_to_ind_f();
		return ($res);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */