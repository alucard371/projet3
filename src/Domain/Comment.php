<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 27/03/2017
 * Time: 14:42
 */

namespace microCMS\Domain;

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
     * @var \microCMS\Domain\User
     */
    private $author;


    /**
     * Comment content
     *
     * @var integer
     */
    private $content;

    /**
     * Associated article
     *
     * @var \MicroCMS\Domain\Article
     */
    private $article;

    /**
     * depth of nested comment
     *
     * @var integer
     */
    private $depth = 0;

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
     * @return Article
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
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @param int $depth
     */
    public function setDepth(int $depth)
    {
        $this->depth = $depth;
    }





}