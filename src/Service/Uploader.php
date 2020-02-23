<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    private $uploadPath;

    /**
     * @return string
     */
    public function getUploadPath(): string
    {
        return $this->uploadPath;
    }

    /**
     * @param string $uploadPath
     * @return Uploader
     */
    public function setUploadPath(string $uploadPath): Uploader
    {
        $this->uploadPath = $uploadPath;
        return $this;
    }
    public function __construct(string $uploadPath)
    {
        $this->uploadPath = $uploadPath;
    }

    public function uploadImage(?UploadedFile $uploadedFile ){
        if($uploadedFile!=null){
            $destination = $this->uploadPath.'/user';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            return $newFilename;
        }
        return false;
    }
}