<?php
namespace App\Service;
use Exception;
use App\Entity\User;
use App\Utils\ServiceTrait;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UploadService
{
    use ServiceTrait;

    public const UPLOAD_IMAGE = 'image';
    
    /**
     * @var Filesystem $fs
     */
    private $fs;
    
    /**
     * @var User $user
     */
    private $user = null;

    public function __construct(
        private Security $security
    ) {
        $this->fs = new Filesystem;
        $this->user = $this->security->getUser();
    }
    
    /**
     * getUploadedDir
     *
     * @return string
     */
    private function getUploadedDir():string 
    {
        return UPLOAD_DIRECTORY;
    }
    
    /**
     * upload
     *
     * @param  mixed $file
     * @param  mixed $uploadDir
     * @param  mixed $type
     * @return mixed error message || upload path  
     */
    public function upload(UploadedFile $file, ?string $type = self::UPLOAD_IMAGE):mixed 
    {
        $uploadsErrors = [];
        if (!$this->checkMimeType($file)) {
            $uploadsErrors['fileType'] = 'Ce fichier n\'est pas valide !';
        }
        if (!$this->checkMaxFileSize($file)) {
            $uploadsErrors['size'] = 'Ce fichier est trop volumineux !';
        }
        if (count($uploadsErrors) > 0) {
            return ['success' => false, 'violations' => $uploadsErrors];
        }

        $dir = $this->getUploadedDir();
        $this->generateUploadsDir(DOCUMENT_ROOT . UPLOAD_DIRECTORY . PROFILE_IMAGE_DIRECTORY);
        $fileName = $this->generateToken(20) . '.' . $file->guessClientExtension();

        try {
            # Enregistrement OK
            $file->move(
                $dir . PROFILE_IMAGE_DIRECTORY,
                $fileName
            );

            return ['success' => true, 'path' => UPLOAD_DIRECTORY . PROFILE_IMAGE_DIRECTORY . $fileName];
        } catch (Exception $e) {
            return ['success' => false, 'violations' => ['system' => 'Une erreur est survenue lors de l\'enregistrement du fichier !']];
        }
    }
    
    /**
     * checkMaxFileSize
     *
     * @param  mixed $file
     * @return bool
     */
    private function checkMaxFileSize(UploadedFile $file):bool 
    {
        // Comparer la taille des fichier en octets
        return UPLOAD_MAX_SIZE > $file->getSize();
    }
    
    /**
     * checkMimeType
     *
     * @return void
     */
    private function checkMimeType(UploadedFile $file, ?string $uploadType = self::UPLOAD_IMAGE):bool 
    {
        // Vérifier le mimetype en fonction de type de fichier attendu 
        return match ($uploadType) {
            self::UPLOAD_IMAGE => in_array($file->getClientMimeType(), IMAGE_MIME_TYPE),
        };
    }
    
    /**
     * generateUploadsDir
     *
     * @param  mixed $dir
     * @return void
     */
    private function generateUploadsDir(?string $dir = ''):void{
        if (!$this->fs->exists($dir)) {
            $this->fs->mkdir($dir);
        }
    }
    
    /**
     * remove
     *
     * @param  mixed $path
     * @return void
     */
    public function remove(?string $path):void 
    {
        if ($this->fs->exists($path)) {
            $this->fs->remove($path);
        }
    }

}