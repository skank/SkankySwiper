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
if (!defined('_PS_VERSION_')) {
	exit;
}

class skankyswiper extends Module {

	function __construct() {
		
		$this->name = 'skankyswiper';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'skankydev';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Skanky Swiper');
		$this->description = $this->l('module de galerie.');

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

		if (!Configuration::get('SKANKYSWIPER')) {//peux etre ajouter un _NAME a voir en temps utile
			$this->warning = $this->l('No name provided');
		}
	}

	public function install() {
		if (Shop::isFeatureActive()){
			Shop::setContext(Shop::CONTEXT_ALL);
		}
		$lang = Language::getLanguages();
		$tab = new Tab();
		$tab->active = 1;
		$tab->class_name = "AdminSkankySwiper";
		$tab->module = 'skankyswiper';
		$tab->name = array();
		$tab->id_parent = (int)Tab::getIdFromClassName('IMPROVE');
		$tab->position = 3;
		foreach ($lang as $l) {
			$tab->name[$l['id_lang']] = $this->l('Galerie d\'accueil');
		}

		$tab->add();

		if (!Db::getInstance()->execute('
			CREATE TABLE `'._DB_PREFIX_.'s_swiper` (
				`id_s_swiper` int(10) UNSIGNED NOT NULL,
				`url_a` varchar(255) NOT NULL,
				`style_a` tinyint(1) NOT NULL DEFAULT \'0\',
				`text_a` varchar(255) DEFAULT NULL,
				`url_b` varchar(255) DEFAULT NULL,
				`style_b` tinyint(1) NOT NULL DEFAULT \'0\',
				`text_b` varchar(255) DEFAULT NULL,
				`position` int(10) UNSIGNED NOT NULL
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;') || 
			!Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'s_swiper` ADD PRIMARY KEY (`id_s_swiper`);') || 
			!Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'s_swiper` MODIFY `id_s_swiper` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;')
		){
			return false;
		}

		if (!parent::install() ||
			!$this->registerHook('leftColumn') ||
			!$this->registerHook('header') ||
			!$this->registerHook('displayHome') ||
			!Configuration::updateValue('SKANKYSWIPER', 'salut salut')||
			!Configuration::updateValue('SKANKYSWIPER_SPEED', '1000')||
			!Configuration::updateValue('SKANKYSWIPER_DELAY', '3000')||
			!Configuration::updateValue('SKANKYSWIPER_NAV', 0) ||
			!Configuration::updateValue('SKANKYSWIPER_PAGIN', 0) ||
			!Configuration::updateValue('SKANKYSWIPER_RESIZE', '1') ||
			!Configuration::updateValue('SKANKYSWIPER_WIDTH', '800') ||
			!Configuration::updateValue('SKANKYSWIPER_HEIGHT', '600') ||
			!Configuration::updateValue('SKANKYSWIPER_BIG_WIDTH', '1920') ||
			!Configuration::updateValue('SKANKYSWIPER_BIG_HEIGHT', '1080') ||
			!Configuration::updateValue('SKANKYSWIPER_SPECIAL_CLASS', 'special-class')
		){
			return false;
		}
		return true;
	}

	public function uninstall(){
		if(!Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'s_swiper`;')){
			return false;
		}
		if (!parent::uninstall() ||
			!Configuration::deleteByName('SKANKYSWIPER') || 
			!Configuration::deleteByName('SKANKYSWIPER_AUTOSTART') ||
			!Configuration::deleteByName('SKANKYSWIPER_SPEED')||
			!Configuration::deleteByName('SKANKYSWIPER_DELAY')||
			!Configuration::deleteByName('SKANKYSWIPER_NAV') ||
			!Configuration::deleteByName('SKANKYSWIPER_PAGIN') ||
			!Configuration::deleteByName('SKANKYSWIPER_RESIZE') ||
			!Configuration::deleteByName('SKANKYSWIPER_WIDTH') ||
			!Configuration::deleteByName('SKANKYSWIPER_HEIGHT') ||
			!Configuration::deleteByName('SKANKYSWIPER_BIG_WIDTH') ||
			!Configuration::deleteByName('SKANKYSWIPER_BIG_HEIGHT') ||
			!Configuration::deleteByName('SKANKYSWIPER_SPECIAL_CLASS') 
		){
			return false;
		}
		$tab = new Tab((int)Tab::getIdFromClassName('AdminSkankySwiper'));
        $tab->delete();

		return true;
	}

	public function getContent(){
		$output = null;

		if (Tools::isSubmit('submit'.$this->name)){
			Configuration::updateValue('SKANKYSWIPER_AUTOSTART', Tools::getValue('SKANKYSWIPER_AUTOSTART'));
			Configuration::updateValue('SKANKYSWIPER_SPEED', Tools::getValue('SKANKYSWIPER_SPEED'));
			Configuration::updateValue('SKANKYSWIPER_DELAY', Tools::getValue('SKANKYSWIPER_DELAY'));
			Configuration::updateValue('SKANKYSWIPER_NAV', Tools::getValue('SKANKYSWIPER_NAV'));
			Configuration::updateValue('SKANKYSWIPER_PAGIN', Tools::getValue('SKANKYSWIPER_PAGIN'));
			Configuration::updateValue('SKANKYSWIPER_RESIZE', Tools::getValue('SKANKYSWIPER_RESIZE'));
			Configuration::updateValue('SKANKYSWIPER_WIDTH', Tools::getValue('SKANKYSWIPER_WIDTH'));
			Configuration::updateValue('SKANKYSWIPER_HEIGHT', Tools::getValue('SKANKYSWIPER_HEIGHT'));
			Configuration::updateValue('SKANKYSWIPER_BIG_WIDTH', Tools::getValue('SKANKYSWIPER_BIG_WIDTH'));
			Configuration::updateValue('SKANKYSWIPER_BIG_HEIGHT', Tools::getValue('SKANKYSWIPER_BIG_HEIGHT'));
			Configuration::updateValue('SKANKYSWIPER_SPECIAL_CLASS', Tools::getValue('SKANKYSWIPER_SPECIAL_CLASS'));
			$output .= $this->displayConfirmation($this->l('Settings updated'));
			
		}
		return $output.$this->displayForm();
	}

	public function displayForm(){

		// Get default language
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		// Init Fields form array
		$fields_form[0]['form'] = [
			'legend' => [ 'title' => $this->l('Settings') ],
			'input'=> [
				[
					'type' => 'switch',
					'label' => $this->l('auto start'),
					'name' => 'SKANKYSWIPER_AUTOSTART',
					'values' => [
						[
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						],[
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						]
					]
				],[
					'type' => 'text',
					'label' => $this->l('animation delay'),
					'name' => 'SKANKYSWIPER_DELAY',
					'size' => 20,
					'required' => true
				],[
					'type' => 'text',
					'label' => $this->l('animation speed'),
					'name' => 'SKANKYSWIPER_SPEED',
					'size' => 20,
					'required' => true
				],[
					'type' => 'switch',
					'label' => $this->l('pagination'),
					'name' => 'SKANKYSWIPER_PAGIN',
					'values' => [
						[
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						],[
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						]
					]
				],[
					'type' => 'switch',
					'label' => $this->l('navigation buttons'),
					'name' => 'SKANKYSWIPER_NAV',
					'values' => [
						[
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						],[
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						]
					]
				],[
					'type' => 'text',
					'label' => $this->l('special class'),
					'name' => 'SKANKYSWIPER_SPECIAL_CLASS',
					'size' => 20,
					'required' => true
				],[
					'type' => 'switch',
					'label' => $this->l('auto resize'),
					'name' => 'SKANKYSWIPER_RESIZE',
					'values' => [
						[
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						],[
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						]
					]
				],[

					'type' => 'text',
					'label' => $this->l('img width'),
					'name' => 'SKANKYSWIPER_WIDTH',
					'size' => 20,
					'required' => true
				],[
					'type' => 'text',
					'label' => $this->l('img height'),
					'name' => 'SKANKYSWIPER_HEIGHT',
					'size' => 20,
					'required' => true
				],[

					'type' => 'text',
					'label' => $this->l('img big width'),
					'name' => 'SKANKYSWIPER_BIG_WIDTH',
					'size' => 20,
					'required' => true
				],[
					'type' => 'text',
					'label' => $this->l('img big height'),
					'name' => 'SKANKYSWIPER_BIG_HEIGHT',
					'size' => 20,
					'required' => true
				]

			],
			'submit' => [
				'title' => $this->l('Save'),
				'class' => 'btn btn-default pull-right'
			]
		];

		$helper = new HelperForm();

		// Module, token and currentIndex
		$helper->module = $this;
		$helper->name_controller = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

		// Language
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;

		// Title and toolbar
		$helper->title = $this->displayName;
		$helper->show_toolbar = true;  // false -> remove toolbar
		$helper->toolbar_scroll = true; // yes - > Toolbar is always visible on the top of the screen.
		$helper->submit_action = 'submit'.$this->name;
		$helper->toolbar_btn = [
			'save' =>[
				'desc' => $this->l('Save'),
				'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
				'&token='.Tools::getAdminTokenLite('AdminModules'),
			],
			'back' => [
				'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
				'desc' => $this->l('Back to list')
			]
		];
		// Load current value
		$helper->fields_value['SKANKYSWIPER'] = Configuration::get('SKANKYSWIPER');
		$helper->fields_value['SKANKYSWIPER_AUTOSTART'] = Configuration::get('SKANKYSWIPER_AUTOSTART');
		$helper->fields_value['SKANKYSWIPER_DELAY'] = Configuration::get('SKANKYSWIPER_DELAY');
		$helper->fields_value['SKANKYSWIPER_SPEED'] = Configuration::get('SKANKYSWIPER_SPEED');
		$helper->fields_value['SKANKYSWIPER_NAV'] = Configuration::get('SKANKYSWIPER_NAV');
		$helper->fields_value['SKANKYSWIPER_PAGIN'] = Configuration::get('SKANKYSWIPER_PAGIN');
		$helper->fields_value['SKANKYSWIPER_RESIZE'] = Configuration::get('SKANKYSWIPER_RESIZE');
		$helper->fields_value['SKANKYSWIPER_WIDTH'] = Configuration::get('SKANKYSWIPER_WIDTH');
		$helper->fields_value['SKANKYSWIPER_HEIGHT'] = Configuration::get('SKANKYSWIPER_HEIGHT');
		$helper->fields_value['SKANKYSWIPER_BIG_WIDTH'] = Configuration::get('SKANKYSWIPER_BIG_WIDTH');
		$helper->fields_value['SKANKYSWIPER_BIG_HEIGHT'] = Configuration::get('SKANKYSWIPER_BIG_HEIGHT');
		$helper->fields_value['SKANKYSWIPER_SPECIAL_CLASS'] = Configuration::get('SKANKYSWIPER_SPECIAL_CLASS');


		return $helper->generateForm($fields_form);
	}

	public function hookHome($params){
		$query = 'SELECT * FROM '._DB_PREFIX_.'s_swiper  ORDER BY position ASC';
		$results = Db::getInstance()->ExecuteS($query);
		$class = Configuration::get('SKANKYSWIPER_SPECIAL_CLASS');
		foreach ($results as $key => $value){
			if(!empty($value['url_a'])){
				$results[$key]['url_a_big'] =$this->creatBigUrl($value['url_a']);
			}
			if(!empty($value['url_b'])){
				$results[$key]['url_b_big'] =$this->creatBigUrl($value['url_b']);
			}
			$results[$key]['style_a'] = ((int)$value['style_a'])?$class:'';
			$results[$key]['style_b'] = ((int)$value['style_b'])?$class:'';
		}
		$forview['swipers'] = $results;
		$forview['swiperTitle'] = Configuration::get('SKANKYSWIPER');
		$forview['minWidth'] = Configuration::get('SKANKYSWIPER_WIDTH');
		$forview['bigWidth'] = Configuration::get('SKANKYSWIPER_BIG_WIDTH');
		$forview['swiperConf']['autoplay'] = (int)Configuration::get('SKANKYSWIPER_AUTOSTART')?(int)Configuration::get('SKANKYSWIPER_DELAY'):false;
		$forview['swiperConf']['speed'] = (int)Configuration::get('SKANKYSWIPER_SPEED');
		$forview['swiperConf']['nextButton'] = Configuration::get('SKANKYSWIPER_NAV')?'.swiper-button-next':'';
		$forview['swiperConf']['prevButton'] = Configuration::get('SKANKYSWIPER_NAV')?'.swiper-button-prev':'';
 		$forview['swiperConf']['pagination'] = Configuration::get('SKANKYSWIPER_PAGIN')?'.swiper-pagination':'';
		//var_dump($forview);
		return $this->fetch( 'module:skankyswiper/views/templates/hook/swiper.tpl', $forview);
	}
	
	public function hookdisplayHeader($params){
		$this->context->controller->registerStylesheet('modules-swiper-css', 'modules/'.strtolower($this->name).'/views/css/swiper.css');
        $this->context->controller->registerJavascript('modules-swiper-js', 'modules/'.strtolower($this->name).'/views/js/swiper.js');
    }
    
    public function creatBigUrl($url){
    	$a = explode('/',$url);
    	$i = count($a)-1;
    	$a[$i] = 'big-'.$a[$i];
    	return join('/',$a);
    }
}