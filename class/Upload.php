<?php
/**
* TheBouqs Company - Custom Class
* @author Kydz
*/
class Upload{

    /*associated system e.g. customer message attachment*/
    public $category;

    /*path on the file*/
    public $root = './img';
    public $tmpRoot = './img/_tmp';
    public $dir;
    public $path;
    public $id;

    /*hashed file name*/
    public $name;

    /*original file name*/
    public $original_name;

    /*file extension*/
    public $ext;

    /*file size*/
    public $size;

    /*MINE tyoe*/
    public $mime;

    /*upload date*/
    public $date_add;

    public $folderPath = '';

    public function __construct($cate, $id)
    {
        $this->category = $cate;
        $this->dir = '/'.$cate;
        $this->id = $id;
    }

    /**
     * [getExt get file extention]
     * @return [string] [extention]
     * @author Kydz 2014.12.25
     */
    public function getExt()
    {
        if (strpos($this->original_name, '.') !== false) {
            $split = explode('.', $this->original_name);
            $ext = array_pop($split);
        } else {
            switch ($this->mime) {
                case 'jpeg':
                case 'image/jpeg':
                    $ext = 'jpg';
                    break;
                case 'gif':
                case 'image/gif':
                    $ext = 'gif';
                    break;
                case 'png':
                case 'image/png':
                    $ext = 'png';
                    break;
                case 'zip':
                case 'application/zip':
                    $ext = 'zip';
                    break;
                case 'audio/wav':
                case 'x-wav':
                    $ext = 'wav';
                    break;
                default:
                    $ext = 'jpg';
                    break;
            }
        }
        return $ext;
    }

    /**
     * [setUploadProperty copy temp property data]
     * @author Kydz 2014.12.25
     */
    public function setUploadProperty()
    {
        $this->size = $this->tmpFile['size'];
        $this->mime = strtolower($this->tmpFile['type']);
        $this->original_name = $this->tmpFile['name'];
        $this->ext = $this->getExt();
    }

    public function upload($file)
    {
        $this->tmpFile = $file;
        $this->setUploadProperty();
        $this->path = $this->tmpRoot.$this->dir.'/'.$this->id;
        if (!$this->checkFloder($this->path, true)) {
            throw new Exception("folder check failed", 1);
        }
        
        $filename = $this->generateFileName($this->tmpFile['tmp_name']);
        if (!move_uploaded_file($this->tmpFile['tmp_name'], $this->path.'/'.$filename.'.'.$this->getExt())) {
            throw new Exception("move to hash folder failed", 1);
        }
        return $this->dir.'/'.$this->id.'/'.$filename.'.'.$this->getExt();
    }

    /**
     * [remove delete file]
     * @return [bool] [description]
     * @author Kydz 2014.12.25
     */
    public function remove()
    {
        if (file_exists($this->getDownloadPath())) {
            unlink($this->getDownloadPath());
        }
        return true;
    }



    /**
     * [init set path and check folder]
     * @param  [string] $cate [category]
     * @return [type]       [description]
     * @author Kydz 2014.12.25
     */
    public function init($file)
    {
        $this->folderPath = $this->path.$this->category.'/';
        $this->checkFloder($this->folderPath, true);
    }

    /**
     * [generateFileName  use md5 to get a hashed file name]
     * @param  [string]  $file   [path or file content]
     * @param  boolean $isPath [description]
     * @return [string]          [hashed file as name]
     * @author Kydz 2014.12.25
     */
    public function generateFileName($file, $isPath = true)
    {
        if ($isPath) {
            return md5_file($file);
        } else {
            return md5($file);
        }
    }

    /**
     * [checkFloder if folder exists, return ture, if not, create one]
     * @param  [string]  $folder [folder path]
     * @param  boolean $create [description]
     * @return [bool]          [description]
     * @author Kydz 2014.12.25
     */
    public function checkFloder($folder, $create = true)
    {
        if (file_exists($folder)) {
            return true;
        }
        if ($create) {
            return mkdir($folder) & chmod($folder, 0755);
        }
        return false;
    }

    public function move($from, $to)
    {
        return rename($from, $to);
    }

    public function save($id)
    {
        $oldFolder = $this->tmpRoot.$this->dir.'/'.$this->id;
        $newFolder = $this->root.$this->dir.'/'.$id;

        $list = scandir($oldFolder);
        $list = array_slice($list, 2);
        foreach ($list as $key => $img) {
            $list[$key] = $this->dir.'/'.$id.'/'.$img;
        }

        if (!$this->checkFloder($oldFolder)) {
            throw new Exception("hash dir not found", 1);
        }
        if (!$this->move($oldFolder, $newFolder)) {
            throw new Exception("move file failed", 1);
        }

        return $list;
    }

    public function getList()
    {
        $folder = $this->root.$this->dir.'/'.$this->id;
        if (!$this->checkFloder($folder)) {
            return [];
        }
        $list = scandir($folder);
        $list = array_slice($list, 2);
        foreach ($list as $key => $img) {
            $list[$key] = $this->dir.'/'.$this->id.'/'.$img;
        }
        return $list;
    }
}
