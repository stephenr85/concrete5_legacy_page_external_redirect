<?php  defined('C5_EXECUTE') or die("Access Denied.");

class PageExternalRedirect {
	/**
	 * Redirect to a page based on a page attribute
	 */
	public function check() {
		//get the current page
		$page = Page::getCurrentPage();
		//get attribute
		$external_url = trim($page->getCollectionAttributeValue('page_external_redirect'));
		//start checking if its a valid page
		if(!empty($external_url)) {
			//redirect
			header("HTTP/1.1 301 Moved Permanently"); 
			header('Location: '.$external_url);
			exit;
		}
	}
}

?>