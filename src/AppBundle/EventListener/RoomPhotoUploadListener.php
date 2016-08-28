<?php

namespace AppBundle\EventListener;

use AppBundle\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Entity\Room;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

class RoomPhotoUploadListener {
	
	private $uploader;
	
	public function __construct(FileUploader $uploader) {
		$this->uploader = $uploader;
	}
	
	public function prePersist(LifecycleEventArgs $args) {
		$entity = $args->getEntity();
		
		$this->uploadFile($entity);
	}
	
	public function preUpdate(PreUpdateEventArgs $args) {
		$entity = $args->getEntity();
		
		$this->uploadFile($entity);
	}
	
// 	public function postLoad(LifecycleEventArgs $args)
// 	{
		
// 		$entity = $args->getEntity();
		
// 		if (!$entity instanceof Room) {
// 			return;
// 		}
	
// 		$fileName = $entity->getRoomPhoto();
	
// 		$entity->setRoomPhoto(new File($this->uploader->getTargetDir().'/'.$fileName));
// 	}
	
	private function uploadFile($entity) {
		if (!$entity instanceof Room) {
			return;
		}
		
		$file = $entity->getRoomPhoto();
		
		if (!$file instanceof UploadedFile) {
			return;
		}
		
		$fileName = $this->uploader->upload($file);
		$entity->setRoomPhoto($fileName);
	}
}