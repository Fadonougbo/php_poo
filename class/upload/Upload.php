<?php 

namespace Utils\upload;

use Psr\Http\Message\UploadedFileInterface;

class Upload
{


	public function __construct(
		
		private string $dirPath
	){

	}

	/**
	 * Move file
	 * @param  UploadedFileInterface $file [description]
	 * @return string                      [description]
	 */
	public function moveFile(UploadedFileInterface $file):string
	{
		$filename=$file->getClientFilename();

		if(!is_dir($this->dirPath))
		{
			mkdir($this->dirPath,0777,true);
		}

		$target=$this->addSuffix($this->dirPath.DIRECTORY_SEPARATOR.$filename);
		$file->moveTo($target);

		return pathinfo($target)['basename'];
		
	}

	/**
	 * Crée une copy si le fichier exist déja
	 * @param string $filePath [description]
	 */
	private function addSuffix(string $filePath):string
	{
		if(file_exists($filePath))
		{
			$info=pathinfo($filePath);

			$newPath=$info["dirname"].DIRECTORY_SEPARATOR.$info["filename"]."_copy.".$info['extension'];

			return $this->addSuffix($newPath);
		}

		return $filePath;
	}

}


?>