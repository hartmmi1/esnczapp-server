<?php
/**
 * @author Martin Herich
 * Date: 12. 7. 2017
 */

namespace App\ApiModule\Presenters;

use App\Model\CategoryEntity;
use App\Model\CityEntity;
use App\Model\CityFacade;
use App\Model\PoiEntity;
use App\Model\SubcategoryEntity;
use App\Model\UniversityEntity;
use App\Model\UniversityFacade;
use App\Presenters\BasePresenter;
use Nette;
use Symfony\Component\Console\Output\Output;
use Tracy\Debugger;
use Tracy\OutputDebugger;


class ApiPresenter extends BasePresenter{

	// todo prekopat radenie podla order, presunut to do findBy()

	private $requested_data;

	private $IS_PRODUCTION = true;

	// unique id for logging this query
	private $logId = 0;

	public function startup(){
		parent::startup();

		$this->logId = mt_rand(1,9999);
		$this->logger->addDebug('[API] [request] ['.$this->logId.']',[
			'url' => $this->getHttpRequest()->getUrl()->getPath(),
			'query' => $this->getHttpRequest()->getUrl()->getQuery(),
			'parameters' => $this->getRequest()->getParameters()
		]);
	}

	/**
	 * Sends requested data as json. Data are prepared in actions.
	 */
	public function renderDefault(){
		// todo header
		$this->sendJson($this->requested_data);
	}

	/**
	 * Prepares list of all cities and universities. Calls facades.
	 */
	public function actionSections(){
		// get array of entities representing city (one item is basically row in db table)
		$cities = $this->cityFacade->getAllEntities();
		// merge with uni
		foreach($cities as $city){
			/* @var $city CityEntity */
			// get all univeristies in city
			$unis = $city->getUniversities();
			// var init for university entities converted to array
			$unisArr = [];
			foreach($unis as $uni){
				/* @var $uni UniversityEntity */
				// converts entity to array to be sent as json
				$unisArr[] = $this->uniFacade->convertToArr($uni);
			}
			// converts entity to array to be sent as json
			$cityArr = $this->cityFacade->convertToArr($city);
			// add array of univeristies (as arrays) to city
			$cityArr['universities'] = $unisArr;
			// put city with unis to result array
			$this->requested_data[] = $cityArr;
		}
		// set render to send json
		$this->setView('default');
	}

	public function actionEnvironments(){
		$this->requested_data = [
			'id' => 1,
			'is_production' => $this->IS_PRODUCTION,
		];
		$this->setView('default');
	}

	public function actionCategories(){
		$categories = $this->categoryFacade->getAllEntities();
		$categoriesArr = [];
		foreach($categories as $cat){
			/* @var $cat CategoryEntity */
			// order as index will sort items by ascending order
			$categoriesArr[$cat->getOrder()] = $this->categoryFacade->convertToArr($cat);
		}
		// remove keys from array, so they will be not converted to json
		$this->requested_data = array_values($categoriesArr);

		$this->setView('default');
	}

	public function actionPoi($id){
		if(!empty($id)){
			$poi = $this->pointFacade->getEntity($id);
			/* @var $poi PoiEntity */
			$poiArr = $this->pointFacade->convertToArr($poi);

			if(empty($poiArr['preview_image'])){
				$poiArr['preview_image'] = $poi->getSubcategory()->getIconUrl();
			}

			// zistavanie a formatovanie pola obrazkov presunute do fasady
			$poiArr['image_list'] = $this->imgFacade->getImagesForPointAsArr($poi,false);
			// zistavanie a formatovanie pola obsahu presunute do fasady
			$poiArr['content'] = $this->contentFacade->getContentForPointAsArr($poi,false);

			$this->requested_data = $poiArr;
			$this->setView('default');
		}else{
			$this->requested_data = null;
			$this->setView('default');
		}
	}

	public function actionSubcategories(){
		$params = $this->getParameters();
		if(isset($params['category'])){
			$category_id = (int)$params['category'];
			if(isset($params['universities'])){
				// remove brackets from iOS query
				$params['universities'] = str_replace('[','',$params['universities']);
				$params['universities'] = str_replace(']','',$params['universities']);
				$uniArr = explode(',',$params['universities']);
			}else{
				$uniArr = null;
			}

			// get *all* subcategories under specified category
			$subcategories = $this->subcFacade->getSubcategoriesByCat($category_id);
			$subcategories_arr = [];
			foreach($subcategories as $subc){
				/* @var $subc SubcategoryEntity */
				$subcArr = $this->subcFacade->convertToArr($subc);
				//if there is specified from which university(ies)
				$subcArr['pois'] = $this->pointFacade->getPointsBySubcAndUni($subc,$uniArr);
				$subcategories_arr[] = $subcArr;
			}

			$this->requested_data = $subcategories_arr;
			$this->setView('default');
		}else{
			$this->requested_data = null;
			$this->setView('default');
		}

	}

	public function actionHomepage(){
		// dummy data
		$this->requested_data = [
			'full_name' => 'Michael Hartman',
			'user_photo' => 'http://app2.esn-cz.cz/data/sections/test/app_welcome.jpg',
			'esn_section' => 'President of Erasmus Student Network Czech Republic',
			'welcome_description' => "Dear exchange students, it is my pleasure to welcome you all to the beautiful land of the Czech Republic. During the next few months, you will meet lots of great people, build life long lasting friendships and discover amazing places. The volunteers from ESN are here to help you enjoy it, and trust me - you are going to have the time of your life. Cheers!",
			'news' => "<strong>Welcome week is around the corner.</strong> During the entire week you will learn how things work in the Czech Republic and in your city. You will explore the university, meet fellow exchange students and locals. Do not forget to attend presentations and activities prepared for you by the university and volunteers from Erasmus Student Network or International Student Clubs. Our partner Vodafone prepared special sim cards with 3GB of data for you.",
			'news_images' => [
				'left' => 'http://app2.esn-cz.cz/www/data/sections/test/news1.jpg',
				'right' => 'http://app2.esn-cz.cz/www/data/sections/test/news2.jpg'
			]
		];
		$this->setView('default');
	}



}