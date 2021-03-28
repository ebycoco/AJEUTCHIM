<?php 

namespace App\Listener;

use App\Entity\Candidature;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs; 
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageCacheSubscriber implements EventSubscriber
{    
    /**
     * cacheManager
     *
     * @var mixed
     */
    private $cacheManager;
        
    /**
     * uploaderHelper
     *
     * @var mixed
     */
    private $uploaderHelper;
     
    /**
    *  __Construct method
    * @param $cacheManager
    * @param $uploaderHelper
    */
    public function __construct(CacheManager $cacheManager,UploaderHelper $uploaderHelper)
    {
        $this->cacheManager = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
    } 

    public function getSubscribedEvents()
    {
        return [
            'preRemove',
            'PreUpdate'
        ];
    }  

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Candidature) {
            return;
        }
        $this->cacheManager->remove($this->uploaderHelper->asset($entity,'imageFile')); 
    }

    public function PreUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Candidature) {
            return;
        }
        if ($entity->getImageFile() instanceof UploadedFile) {
            $this->cacheManager->remove($this->uploaderHelper->asset($entity,'imageFile')); 
        } 
    }

  
}