<?php
  class fly_fileRename {
    private $_originalFileName;
    private $temp;
    private $justFileName;
    private $extension;
    private $compiledName;
    public $fileStorage;
    public $includeTimestamp;
    public $whitespaceToUnderscores;
    public $overwriteExistingFiles;
    public $maxCharacterLimit;
    public $updatedFileName;

    function __construct($originalFileName, $fileStorage, $includeTimestamp = true, $whitespaceToUnderscores = true, $overwriteExistingFiles = false, $maxCharacterLimit = 150) {
      if ($includeTimestamp) {
        $this->includeTimestamp = $includeTimestamp;
      }
      if ($whitespaceToUnderscores) {
        $this->whitespaceToUnderscores = $whitespaceToUnderscores;
      }
      if ($overwriteExistingFiles) {
        $this->overwriteExistingFiles = $overwriteExistingFiles;
      }
      if ($maxCharacterLimit) {
        $this->maxCharacterLimit = $maxCharacterLimit;
      }

      $this::fileRename($originalFileName, $fileStorage);
    }

    private function fileRename($originalFileName, $fileStorage) {
      $this->_originalFileName = $originalFileName;
      $this->fileStorage = $fileStorage;
      $this->temp = explode('.', $this->_originalFileName);
      $this->extension = end($this->temp);

      // recompile file name
      $this::recompileFileName($this->temp);

      // remove nonletters and numbers
      $this::sanitize($this->justFileName, $this->compiledName);

      // if timestamp and no overwrite, convert to lowercase
      if ($this->includeTimestamp && $this->overwriteExistingFiles == false) {
        $this::toLowercase($this->justFileName);
      }

      // remove white spaces
      $this::whiteSpace($this->whitespaceToUnderscores, $this->justFileName);

      // if file name is empty, give default value
      if ($this->justFileName == '' || preg_match('/-|_/', $this->justFileName[0])) {
        $this::checkFileName($this->justFileName, $this->extension);
      }

      // litmit file name length
      $this::limitFileName($this->maxCharacterLimit);

      // add time stamp
      if ($this->includeTimestamp) {
        $this::addTimeStamp();
      }

      // overwrite existing files
      if ($this->overwriteExistingFiles == false) {
        $this::overwriteFiles($this->fileStorage, $this->justFileName, $this->extension);
      }

      $this::compile($this->justFileName, $this->extension);
    }

    private function recompileFileName($temp) {
      if (count($this->temp) > 2) {
        $this->compiledName = '';
        for ($i=0; $i < (count($this->temp)-1); $i++) {
          $this->compiledName .= $this->temp[$i];
        }
      } else {
       $this->compiledName = $this->temp[0];
      }
    }

    private function sanitize($justFileName, $compiledName) {
      $this->justFileName = preg_replace('/[^a-zA-Z0-9-_\s]/','',$this->compiledName);
    }

    private function toLowercase($justFileName) {
      $this->justFileName = strtolower($this->justFileName);
    }

    private function whiteSpace($whitespaceToUnderscores, $justFileName) {
      if ($this->whitespaceToUnderscores) {
        $whitespaceReplace = '_';
      } else {
        $whitespaceReplace = '';
      };

      $this->justFileName = preg_replace('/[\s]/', $whitespaceReplace, $this->justFileName);
    }

    private function checkFileName($justFileName, $extension) {
      switch ($this->extension) {
        case 'gif':
        case 'jpeg':
        case 'jpg':
        case 'tiff':
        case 'png':
          $this->justFileName = 'image'.$this->justFileName;
          break;

        default:
          $this->justFileName = 'file';
          break;
      }
    }

    private function limitFileName($maxCharacterLimit) {
      if (isset($this->maxCharacterLimit) && preg_match('/\d/', $this->maxCharacterLimit && ($this->maxCharacterLimit >= 5 && $this->maxCharacterLimit <=200))) {
        $this->justFileName = substr($this->justFileName, 0, $this->maxCharacterLimit);
      } else {
        $this->justFileName = substr($this->justFileName, 0, 150);
      }
    }

    private function addTimeStamp() {
      $this->justFileName .= '-'.time();
    }

    private function overwriteFiles($fileStorage, $justFileName, $extension) {
      // check if there is already a file with this name
      if (file_exists($this->fileStorage.$this->justFileName.'.'.$this->extension) && $this->overwriteExistingFiles == false) {
        $count = 1;
        while (file_exists($this->fileStorage.$this->justFileName.'-'.$count.'.'.$this->extension)) {
          $count++;
        }
        $this->justFileName .= '-'.$count;
      };
    }

    private function compile($justFileName, $extension) {
      // compile cleaned file name
      if ($extension == '') {
        $this->updatedFileName = '';
      } else {
        $this->updatedFileName = $this->justFileName.'.'.$this->extension;
      }
    }
  };
?>
