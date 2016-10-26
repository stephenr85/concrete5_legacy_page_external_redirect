<?php  defined('C5_EXECUTE') or die(_("Access Denied."));

/**
 * Displays the page name, a custom attribute, or a specific override.
 * @package Page Title
 * @author Stephen Rushing
 * @category Packages
 * @copyright  Copyright (c) 2011 Stephen Rushing. (http://www.esiteful.com)
 */
class PageExternalRedirectPackage extends Package {

	protected $pkgHandle = 'page_external_redirect';
	protected $appVersionRequired = '5.4.0';
	protected $pkgVersion = '1.0';
	
	public function getPackageDescription() {
		return t("Page attribute and functionality for a URL redirect.");
	}
	
	public function getPackageName() {
		return t("Page External Redirect");
	}
	
	public function install() {
		$pkg = parent::install();
		
		$this->configureAttributes($pkg);
	}
	
	public function upgrade(){
		parent::upgrade($this);

		$this->configureAttributes($pkg);
	}

	public function on_start() {
		$env = Environment::get();
		Events::extend('on_start', 'PageExternalRedirect', 'check', $env->getPath('libraries/page_external_redirect.php', $this->pkgHandle));
	}

	public function configureAttributes($pkg){
		$externalRedirectKey = $this->addCollectionAttributeKey(array(
			'akHandle'=>'page_external_redirect',
			'akName'=>t('Redirect URL'),
			'akType'=>'text',
			'akIsSearchable'=>true
		), NULL, $pkg);
	}

	private function addCollectionAttributeKey($data, $attributeSet, $pkg){
		$attributeKey = CollectionAttributeKey::getByHandle($data['akHandle']);
		if(!is_object($attributeKey)){
			$attributeType = AttributeType::getByHandle($data['akType']);
			if(!is_object($attributeType)){
				throw new Exception(t('AttributeType "%s" not available for CollectionAttributeKey "%s".', $data['akType'], $data['akHandle']));	
			}else{
				$attributeKey = CollectionAttributeKey::add($attributeType, $data, $pkg);
			}
		}
		//Add key to set
		if(is_string($attributeSet)){
			$attributeSet = AttributeSet::getByHandle($attributeSet);
		}
		if(is_object($attributeKey) && is_object($attributeSet)){
			$attributeKey->setAttributeSet($attributeSet);
		}
		return $attributeKey;
	}

	
}