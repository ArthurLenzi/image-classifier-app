<?php

namespace services;

use lib\CustomException;

class ImageService extends \Engine\Service
{
    public const IMAGE = 'Image';

    public function getImage($body, $jwtData)
    {
        $rules = array (
            'role' => REQUIRED . '|between:5,20'
        );
        $this->validator->validate($body, $rules);

        $imageModel = $this->loadModel(self::IMAGE);
        $userService = $this->loadService('User');
        $userService->checkUserRole($body['role'], $jwtData);

        return $imageModel->getRandomImage($body['role']);
    }

    public function showImage($body, $jwtData)
    {
        $rules = array (
            'hash' => REQUIRED . '|between:5,100'
        );
        $this->validator->validate($body, $rules);

        $imageModel = $this->loadModel(self::IMAGE);
        $imageInfo = $imageModel->getImage($body['hash']);
        if (empty($imageInfo[0])) {
            throw new CustomException("There is no image with this name in our database.", 422);
        }

        $imageRole = $imageInfo[0]['role'];
        $userService = $this->loadService('User');
        $userService->checkUserRole($imageRole, $jwtData);

        $file = $this->dir . "processed_images" . DIRECTORY_SEPARATOR . $body['hash'];
        if (file_exists($file . '.png')) {
            $image = file_get_contents($file . '.png');
        } elseif (file_exists($file . '.jpg')) {
            $image = file_get_contents($file . '.jpg');
        } else {
            throw new CustomException("There is no image with this name in our server.", 422);
        }
        if ($image === false) {
            throw new CustomException("Error getting image.", 422);
        }

        return $image;
    }

    public function processAllImages($role)
    {
        $directory = $this->dir . "unprocessed_images";
        $files = glob($directory . DIRECTORY_SEPARATOR . '*.*');
        $totalImages = $newImages = 0;
        foreach ($files as $file) {
            $file = explode(DIRECTORY_SEPARATOR, $file);
            if ($this->processImage(end($file), $role)) {
                $newImages += 1;
            }
            $totalImages += 1;
        }
        return array("new_images" => $newImages, "total_processed_images" => $totalImages);
    }

    public function processImage($imageName, $imageRole, $ignoreError = true)
    {
        $directory = $this->dir . "unprocessed_images";
        $imageNameArray = explode('.', $imageName);
        $extention = end($imageNameArray);

        if (!file_exists($this->dir . "unprocessed_images" . DIRECTORY_SEPARATOR . $imageName) && !$ignoreError) {
            throw new CustomException("There is no image with this name.", 422);
        } elseif (!file_exists($this->dir . "unprocessed_images" . DIRECTORY_SEPARATOR . $imageName)) {
            return false;
        }

        $fileHash = hash_file('md5', $directory . DIRECTORY_SEPARATOR . $imageName);

        if (!file_exists($this->dir . "processed_images" . DIRECTORY_SEPARATOR . $fileHash)) {
            rename(
                $directory . DIRECTORY_SEPARATOR . $imageName,
                $this->dir . "processed_images" . DIRECTORY_SEPARATOR . $fileHash . '.' . $extention
            );
        } else {
            unlink($this->dir . "unprocessed_images" . DIRECTORY_SEPARATOR . $imageName);
        }

        $imageModel = $this->loadModel(self::IMAGE);

        if (empty($imageModel->getImage($fileHash))) {
            $imageModel->saveImage($fileHash, $imageRole);
        } else {
            return false;
        }

        return true;
    }
}
