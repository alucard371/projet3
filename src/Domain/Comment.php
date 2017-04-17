<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 27/03/2017
 * Time: 14:42
 */

namespace MicroCMS\Domain;

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
     * @var \MicroCMS\Domain\User
     */
    private $author;

    /**
     * Associated article
     *
     * @var \MicroCMS\Domain\Article
     */
    private $article;

    /**
     * Comment content
     *
     * @var integer
     */
    private $content;

    /**
     * parent id for nested comments
     *
     * @var integer
     */
    private $parent_id;

    /**
     * @var integer
     */
    private $children;

    /**
     * moderation of comment
     *
     * @var boolean
     */
    private $publish;



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
     * @return int
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * @param int $id
     * @internal param int $parent_id
     */
    public function setParentId(int $id)
    {
        $this->parent_id = $id;
    }

    /**
     * @return int
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param int $children
     */
    public function setChildren(int $children)
    {
        $this->children = $children;
    }













}