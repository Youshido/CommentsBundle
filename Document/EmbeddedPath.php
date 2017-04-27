<?php

namespace Youshido\CommentsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


/**
 * Class EmbeddedPath
 * @package Youshido\CommentsBundle\Document
 * @ODM\EmbeddedDocument()
 */
class EmbeddedPath
{

    /** @ODM\Id() */
    private $id;

    /** @ODM\Field() */
    private $path;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }



    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

}