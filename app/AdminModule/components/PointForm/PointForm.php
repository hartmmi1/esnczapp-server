<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 06-Sep-17
 * Time: 23:03
 */

namespace App\AdminModule\Components;

use App\AdminModule\Presenters\PoisPresenter;
use App\Model\CityFacade;
use App\Model\ContentBlockEntity;
use App\Model\ContentFacade;
use App\Model\ImageEntity;
use App\Model\ImageFacade;
use App\Model\PoiEntity;
use App\Components\BaseControl;
use App\Model\PointFacade;
use App\Model\SubcFacade;
use App\Model\UniversityFacade;
use App\Model\UserEntity;
use Doctrine\DBAL\Driver\PDOException;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;
use WebChemistry\Images;
use Tracy\Debugger;

class PointForm extends BaseControl{
	
	/**
	 * @var UniversityFacade
	 */
	private $uniFacade;
	
	/**
	 * @var CityFacade
	 */
	private $cityFacade;
	
	/**
	 * @var SubcFacade
	 */
	private $subcFacade;
	
	/**
	 * @var PointFacade
	 */
	private $pointFacade;
	
	/**
	 * @var ImageFacade
	 */
	private $imgFacade;
	
	/**
	 * @var ContentFacade
	 */
	private $contentFacade;

	/**
	 * @var Images\IImageStorage|Images\Storages\LocalStorage
	 */
	private $localStorage;

	protected $base_url;
	
	/**
	 * PointForm constructor.
	 * @param $uniFacade UniversityFacade
	 * @param $cityFacade CityFacade
	 * @param $subcFacade SubcFacade
	 * @param $imageFacade ImageFacade
	 * @param $contentFacade ContentFacade
	 * @param $pointFacade PointFacade
	 */
	public function __construct(UniversityFacade $uniFacade,
								CityFacade $cityFacade,
								SubcFacade $subcFacade,
								ImageFacade $imageFacade,
								ContentFacade $contentFacade,
								PointFacade $pointFacade,
								Images\IImageStorage $storage
	){
		parent::__construct();
		$this->uniFacade = $uniFacade;
		$this->cityFacade = $cityFacade;
		$this->subcFacade = $subcFacade;
		$this->pointFacade = $pointFacade;
		$this->imgFacade = $imageFacade;
		$this->contentFacade = $contentFacade;
		$this->localStorage = $storage;


	}
	
	public function createComponentPointForm(){
		$form = new Form();

		$form->addHidden('id');
		$form->addText('name')->setAttribute('class','form-control')->setRequired(true);
		$form->addText('address')->setAttribute('class','form-control')->setAttribute('id','address')->setRequired(true);
		$form->addText('lat')
			->setAttribute('class','form-control')
			->addRule($form::FLOAT, 'Latitude (N). Just numbers, e.g. "49.975069450"')
			->setRequired(true)
			->setAttribute('placeholder','49.975069450')->setAttribute('id','lat');
		$form->addText('lon')
			->setAttribute('class','form-control')
			->addRule($form::FLOAT, 'Longitude (E). Just numbers, e.g. "12.975069450"')
			->setRequired(true)->setAttribute('placeholder','12.97506945')->setAttribute('id','lon');
		$form->addText('web_link')->setAttribute('class','form-control');
		$form->addText('position')->setAttribute('class','form-control');
		$form->addText('discount')->setAttribute('class','form-control');

		$form->addSelect('visible',null, [0 => 'Hidden', 1 => 'Visible'])->setAttribute('class','form-control');
		$form->addSelect('subcategory', null, $this->subcFacade->getSubcategories4SelectGroupedByCat())->setAttribute('class','form-control');

		// super admin moze menit mesta a uni ako chce, sekcny admin to ma predvyplnene podla svojej sekcie
		if($this->presenter->getUser()->isInRole(UserEntity::ROLE_SUPERADMIN)){
			$form->addSelect('city',null, $this->cityFacade->getItems4Select())->setAttribute('class','form-control');
			$form->addSelect('university', null, $this->uniFacade->getItems4Select())->setAttribute('class','form-control');
		}else{
			$form->addSelect('city',null, $this->cityFacade->getItems4Select())->setAttribute('class','form-control')->setDisabled();
			$form->addSelect('university', null, $this->uniFacade->getItems4Select())->setAttribute('class','form-control')->setDisabled();
		}


		$form->addText('preview_image_url')->setAttribute('class','form-control');
		$form->addUpload('preview_image_file')->setAttribute('class','form-control');

		$form->addText('cover_image_url')->setAttribute('class','form-control');
		$form->addUpload('cover_image_file')->setAttribute('class','form-control');

		$form->addText('content_title')->setAttribute('class','form-control');
		$form->addTextArea('content_block')->setAttribute('class','form-control')->setAttribute('id','contentbody');
		
		$form->addSubmit('save', 'Save')->setAttribute('class','btn btn-success');
		
		$form->onSuccess[] = [$this,'processForm'];

		return $form;
	}

	/**
	 * @param PoiEntity|null $poi
	 */
	public function render($point = null){
		$template = $this->getTemplate();
		$template->setFile(__DIR__.DIRECTORY_SEPARATOR.'pointForm.latte');
		$template->point = $point;
		// editacia
		if($point){
			$this['pointForm']->setDefaults([
				'id' => $point->getId(),
				'name' => $point->getName(),
				'address' => $point->getFullAddress(),
				'lat' => $point->getLat(),
				'lon' => $point->getLon(),
				'preview_image_url' => $point->getPreviewImage(),
				'web_link' => $point->getWebsiteLink(),
				'position' => $point->getPosition(),
				'discount' => $point->getDiscount(),
				'visible' => $point->isVisible(),
				'subcategory' => $point->getSubcategory()->getId(),
				'city' => $point->getCity()->getId(),
				'university' => $point->getUniversity()->getId()
				
			]);

			if($point->getImages()->count() > 0){
				$this['pointForm']->setDefaults([
					'cover_image_url' => $point->getImages()->first()->getImageUrl(),
				]);
			}

			if($point->getContent()->count() > 0){
				$this['pointForm']->setDefaults([
					'content_title' => $point->getContent()->first()->getContentTitle(),
					'content_block' => $point->getContent()->first()->getContentBlock(),
				]);
			}
		//novy bod
		}else{
			// pred vyplnene mesto a uni pre sekciu
			if($this->getPresenter()->getUser()->isInRole(UserEntity::ROLE_SUPERADMIN)){
				// nic, superadmin vie co klika
			}else{
				/**
				 * @var PoisPresenter
				 */
				$presenter = $this->getPresenter();
				$lockedUni = $this->uniFacade->getEntity($presenter->getUserUniId());
				$this['pointForm']->setDefaults([
					'city' => $lockedUni->getParentCity()->getId(),
					'university' => $presenter->getUserUniId()
				]);
			}
		}
		$template->render();
	}
	
	/**
	 * @param $form \Nette\Application\UI\Form
	 */
	public function processForm($form, $values){
		$this->base_url = substr($this->getPresenter()->base_path,0,-1);

		$id = $values['id'];
		if(empty($id)){
			$poi = new PoiEntity();

		}else{
			$poi = $this->pointFacade->getEntity($id);
		}
		
		// basic
		$poi->setName($values['name']);
		$poi->setFullAddress($values['address']);
		$poi->setLat($values['lat']);
		$poi->setLon($values['lon']);
		$poi->setWebsiteLink($values['web_link']);
		$poi->setPosition($values['position']);
		$poi->setDiscount($values['discount']);
		$poi->setVisible($values['visible']);
		
		// sorting and assignments
		$poi->setPosition($values['position']);
		$poi->setSubcategory($this->subcFacade->getEntity($values['subcategory']));
		if($this->presenter->getUser()->isInRole(UserEntity::ROLE_SUPERADMIN)){
			$poi->setUniversity($this->uniFacade->getEntity($values['university']));
			$poi->setCity($this->cityFacade->getEntity($values['city']));
		}else{
			$presenter = $this->getPresenter();
			$lockedUni = $this->uniFacade->getEntity($presenter->getUserUniId());

			$poi->setUniversity($this->uniFacade->getEntity($presenter->getUserUniId()));
			$poi->setCity($lockedUni->getParentCity());
		}

		// content
		// temporary!!!

		// there is some content block

		if($poi->getContent()->count() > 0){
			/**
			 * @var $content ContentBlockEntity
			 */
			$content = $poi->getContent()->first();
			$content->setPosition(1); // natvrdo fuckit
			$content->setContentTitle($values['content_title']);
			$content->setContentBlock($values['content_block']);
		}else{
			/**
			 * @var $content ContentBlockEntity
			 */
			$content = new ContentBlockEntity();
			$content->setPosition(1); // natvrdo fuckit
			$content->setContentTitle($values['content_title']);
			$content->setContentBlock($values['content_block']);
			$content->setPoi($poi);
		}
		// todo save content?? vola sa cez poi->saveNow kde je flush() (a bez persist)
		$this->contentFacade->saveLater($content);




		// files

		// preview
		/**
		 * @var $previewFile FileUpload
		 */
		$previewFile = $values['preview_image_file'];

		if(!$previewFile->isOk()){
			// save only change in url
			$poi->setPreviewImage($values['preview_image_url']);
		}else{
			// uploaded file

			if($previewFile->isImage() && $previewFile->isOk()){
				$resource = $this->localStorage->createUploadResource($previewFile);
				$resource->setNamespace('points/'.$poi->getId().'/preview/');
				//$resource->getId();
				$resource->setAlias('preview');
				$saved = $this->localStorage->save($resource);
				$poi->setPreviewImage($this->base_url.$this->localStorage->link($saved));
			}
		}

		// cover
		/**
		 * @var $coverFile FileUpload
		 */
		$coverFile = $values['cover_image_file'];

		if(!$coverFile->isOk()){
			// save only change in url

			if($poi->getImages()->count() > 0){
				// there is already one img
				/**
				 * @var $image ImageEntity
				 */
				$image = $poi->getImages()->first();
				$image->setImageUrl($values['cover_image_url']);
			}else{
				$image = new ImageEntity();
				$image->setImageUrl($values['cover_image_url']);
				$image->setPosition(1);
				$image->setPoi($poi);
			}
			// save image
			try{
				$this->imgFacade->saveLater($image);
			}catch(PDOException $exception){
				$this->getPresenter()->flashMessage('Saving cover image url failed', 'danger');
			}
			// ulozi sa cez poi->saveNow kde je em->flush
			// todo prepisat, prasarna

		}else{
			// uploaded file

			if($coverFile->isImage() && $coverFile->isOk()){

				$resource = $this->localStorage->createUploadResource($coverFile);
				$resource->setNamespace('points/'.$poi->getId().'/cover/');
				//$resource->getId();
				$resource->setAlias('detail');
				$saved = $this->localStorage->save($resource);
				if($poi->getImages()->count() > 0){
					// there is already one img
					/**
					 * @var $image ImageEntity
					 */
					$image = $poi->getImages()->first();
				}else{
					$image = new ImageEntity();

				}
				$image->setImageUrl($this->base_url.$this->localStorage->link($saved));
				$image->setPosition(1);
				$image->setPoi($poi);
				$this->imgFacade->saveLater($image);
			}
		}
		// todo po uploadnuti sa neprepise url aderesa obrazkov



		// SAVE ENTITY TO DB
		try{
			$this->pointFacade->saveNow($poi);
			$this->presenter->flashMessage('Point '.$poi->getName().' saved successfully', 'success');
		}catch(PDOException $e){
			$this->presenter->flashMessage($e->getMessage(),'danger');
			$form->addError($e->getMessage()); // ?? je to dobre
		}finally{
			if(empty($id)){
				$id = $poi->getId();
			}
			$this->getPresenter()->redirect(':Admin:Pois:edit',['id'=>$id]);
		}

	}
	

}

interface IPointFormFactory {
	/**
	 * @return PointForm
	 */
	public function create();
}