<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 27/03/2017
 * Time: 14:42
 */

namespace Projet3\Domain;

class Comment
{
    /**
     * Comment id
     *
     * @var integer
     */
    private $id;

    /**
     * Comment author
     *
     * @var \Projet3\Domain\User
     */
    private $author;

    /**
     * Associated article
     *
     * @var \Projet3\Domain\Article
     */
    private $article;

    /**
     * Associated comment id
     *
     * @var integer
     */
    private $parent;

    /**
     * Comment content
     *
     * @var integer
     */
    private $content;

    /**
     * moderation of comment
     *
     * @var boolean
     */
    private $published;

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }


    public function setAuthor(User $author)
    {
        $this->author = $author;
    }

    /**
     * @return int
     */
    public function getContent()
    {
        return $this->content;
    }


    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return Article $article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     */
    public function setArticle(Article $article)
    {
        $this->article = $article;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return boolean
     */
    public function isPublished ()
    {
        return $this->published;
    }

    /**
     * @param boolean $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    /**
     * @return int
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param int $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }


}