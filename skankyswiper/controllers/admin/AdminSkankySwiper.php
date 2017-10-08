<?php 
/**
 * Copyright (c) 2015 SCHENCK Simon
 * 
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 * @copyright     Copyright (c) SCHENCK Simon
 *
 */

class AdminSkankySwiperController extends ModuleAdminController {	

	public function __construct()
	{
		/*$this->table = 's_swiper';
		$this->className = 'Swiper';*/

		$this->context = Context::getContext();
		parent::__construct();
	}


	public function createTemplate($tpl_name) {
		if (file_exists($this->getTemplatePath() . $tpl_name) && $this->viewAccess())
			return $this->context->smarty->createTemplate($this->getTemplatePath() . $tpl_name, $this->context->smarty);
		return parent::createTemplate($tpl_name);
	}

	public function initContent(){
		parent::initContent();
		//var_dump($_GET['action']);die();
		if(isset($_GET['action'])&&!empty($_GET['action'])){
			$action = $_GET['action'];
			$this->$action();
		}
		$url = $this->context->link->getAdminLink('AdminSkankySwiper');
		$uri = __PS_BASE_URI__ ;
		$query = 'SELECT * FROM '._DB_PREFIX_.'s_swiper  ORDER BY position ASC';
		$results = Db::getInstance()->ExecuteS($query);

		$imgDir =  _PS_UPLOAD_DIR_.'skankyswiper/';
		if(!is_dir($imgDir)){
			mkdir($imgDir,775);
		}
		$imgList = scandir($imgDir);

		$this->context->smarty->assign('title', Configuration::get('SKANKYSWIPER'));
		$this->context->smarty->assign('imgList', $imgList);
		$this->context->smarty->assign('swipers', $results);
		$this->context->smarty->assign('ajaxUrl', $url);
		$this->context->smarty->assign('uri', $uri);
		$this->addJqueryUI('ui.sortable');
		$tpl = $this->createTemplate('content.tpl')->fetch();


	}

	public function save(){
		$result = [];
		$data = Tools::getValue('data');
		$data = json_decode($data);
		foreach ($data as $swiper) {

			if($swiper->id=='-1'){
				//it's a news
				Db::getInstance()->insert('s_swiper', [
						'url_a'    => $swiper->url_a,
						'style_a'  => $swiper->style_a,
						'text_a'   => $swiper->text_a,
						'url_b'    => $swiper->url_b,
						'style_b'  => $swiper->style_b,
						'text_b'   => $swiper->text_b,
						'position' => $swiper->position
				]);
			}else{
				//it's a update
				Db::getInstance()->update('s_swiper', [
						'url_a'    => $swiper->url_a,
						'style_a'  => $swiper->style_a,
						'text_a'   => $swiper->text_a,
						'url_b'    => $swiper->url_b,
						'style_b'  => $swiper->style_b,
						'text_b'   => $swiper->text_b,
						'position' => $swiper->position
				],'id_s_swiper = '.$swiper->id);
			}
		}
		Configuration::updateValue('SKANKYSWIPER', Tools::getValue('title'));
		//message tout va bien;
		//<div class="module_confirmation conf confirm alert alert-success">
        //    <button type="button" class="close" data-dismiss="alert">×</button>
        //    Settings updated
        //</div>
		die();
		
	}

	public function delete(){
		$result = [];
		$result['status'] = true;
		$id = Tools::getValue('id');
		if($id != '-1'){
			Db::getInstance()->delete('s_swiper', 'id_s_swiper = '.$id);
		}
		echo json_encode($result);
		die();

	}

	public function upload(){

		$media = [];
		$result = ['statu'=>false];

		$h = getallheaders();
		$dir =  _PS_UPLOAD_DIR_.'skankyswiper/';
		if(!is_dir($dir)){
			mkdir($dir,775);
		}
		$source = file_get_contents('php://input');
		$fileName = $dir.'/'.$h['X-File-Name'];
		if(!file_exists($fileName)){
			file_put_contents($fileName,$source);
			$media['type'] = $h['X-File-Type'];
			$media['name'] = $h['X-File-Name'];
			$media['size'] = $h['X-File-Size'];
			$result['statu'] = true;
			$result['message'] = '<div class="img-select"><span class="img-trash" data-img="/upload/skankyswiper/'.$h['X-File-Name'].'"><i class="material-icons">delete</i></span><img src="'.__PS_BASE_URI__ .'upload/skankyswiper/'.$h['X-File-Name'].'" alt="'.$h['X-File-Name'].'" width="100" height="100"><br>'.$h['X-File-Name'].'</div>' ;

			if(Configuration::get('SKANKYSWIPER_RESIZE')){
				$dWidth = Configuration::get('SKANKYSWIPER_WIDTH');
				$dHeight = Configuration::get('SKANKYSWIPER_HEIGHT');
				$this->resizeImg($h['X-File-Name'],$dWidth ,$dHeight);

				$dWidth = Configuration::get('SKANKYSWIPER_BIG_WIDTH');
				$dHeight = Configuration::get('SKANKYSWIPER_BIG_HEIGHT');
				$this->resizeImg($h['X-File-Name'],$dWidth ,$dHeight,'big-');
			}
			

		}else{
			$result['message'] = _('ce fichier exist deja : ').$h['X-File-Name'];
		}
		echo json_encode($result);
		die();
	}

	public function delImg(){
		$img = Tools::getValue('img');
		$result = ['statu'=>false];
		$result['statu'] = unlink(_PS_UPLOAD_DIR_.'skankyswiper/'.$img);
		if(file_exists(_PS_UPLOAD_DIR_.'skankyswiper/big-'.$img)){
			$result['statu-big'] = unlink(_PS_UPLOAD_DIR_.'skankyswiper/big-'.$img);
		}
		echo json_encode($result);
		die();
	}

	private function resizeImg($imgName,$dWidth ,$dHeight,$prefix = ''){

		$dir =  _PS_UPLOAD_DIR_.'skankyswiper/';
		$fileName = $dir.$imgName;
		$outputFile =  $dir.$prefix.$imgName;
		$dimension = getimagesize($fileName);
		$oWidth  = $dimension[0];
		$oHeight = $dimension[1];
		$oRatio =$oWidth/$oHeight;

		$info = [];
		$info['dst_x'] = 0;
		$info['dst_y'] = 0;
		$dRation = $dWidth/$dHeight;
		$info['dst_w'] = $dWidth;
		$info['dst_h'] = $dHeight;

		$info['src_x'] = 0; 
		$info['src_y'] = 0;

		$info['src_x'] = 0; 
		$info['src_y'] = 0;
		$info['src_w'] = $oWidth;
		$info['src_h'] = $oHeight;

		if($oRatio < $dRation){
			$info['dst_w'] = ($oWidth * $dHeight)/$oHeight;
		}else if($oRatio > $dRation){
			$info['dst_h']= ($oHeight * $dWidth)/$oWidth;
		}


		$miniature = imagecreatetruecolor($info['dst_w'],$info['dst_h']);
		$ext = explode('.',$imgName);
		$ext = end($ext);
		$ext = strtolower($ext);
		if($ext==='jpeg'){$ext = 'jpg';}
		//selon le type de fichier
		switch ($ext) {
			case 'jpg':
				$image = imagecreatefromjpeg($fileName); 
			break;
			case 'png':
				$image = imagecreatefrompng($fileName); 
				imagealphablending( $miniature, false);
				imagesavealpha($miniature,true);
				imagealphablending($image, true);
				imagecolorallocatealpha($miniature,255,255,255,127);
			break;
			case 'gif':
				$image = imagecreatefromgif($fileName); 
			break;
			default:
				return false; 
			break;
		}
		//redimentionne l image
		imagecopyresampled($miniature,$image,$info['dst_x'] ,$info['dst_y'] ,$info['src_x'] ,$info['src_y'] ,$info['dst_w'] ,$info['dst_h'] ,$info['src_w'] ,$info['src_h']);

		//creation du nouveau fichier
		switch ($ext) {
			case 'jpg':
				imagejpeg($miniature,$outputFile,100);
			break;
			case 'png':
				imagepng($miniature,$outputFile); 
			break;
			case 'gif':
				imagegif($miniature,$outputFile);
			break;
			default:
				return false; 
			break;
		}
		return true;		
	}

}
