<?php

namespace Projet3\DAO;

use Projet3\Domain\Comment;

class CommentDAO extends DAO
{
    /**
     * @var \Projet3\DAO\ArticleDAO
     */
    private $articleDAO;

    /**
     * @var \Projet3\DAO\UserDAO
     */
    private $userDAO;

    public function setArticleDAO(ArticleDAO $articleDAO) {
        $this->articleDAO = $articleDAO;
    }

    public function setUserDAO(UserDAO $userDAO) {
        $this->userDAO = $userDAO;
    }

    /**
     * Return a list of all comments for an article, sorted by date (most recent last).
     *
     * @param integer $articleId The article id.
     *
     * @return array A list of all comments for the article.
     */
    public function findAllByArticle($articleId) {
        // The associated article is retrieved only once
        $article = $this->articleDAO->find($articleId);

        // art_id is not selected by the SQL query
        // The article won't be retrieved during domain objet construction
        $sql = "select * from comment where art_id=? and published=1 order by com_id DESC ";
        $result = $this->getDb()->fetchAll($sql, array($articleId));

        // Convert query result to an array of domain objects
        $comments = array();
        foreach ($result as $row) {
            $comId = $row['com_id'];
            $comment = $this->buildDomainObject($row);
            // The associated article is defined for the constructed comment
            $comment->setArticle($article);
            $comments[$comId] = $comment;
        }
        return $comments;
    }

    public function findNestedByArticle ($articleId)
    {
        //retrieve associated article
        $article = $this->articleDAO->find($articleId);

        $sql = "SELECT * FROM `comment` WHERE `art_id`=? and (`par_id` == `com_id`)AND published=1 ORDER BY com_id DESC  LIMIT 4 ";
        $result = $this->getDb()->fetchAll($sql, array($articleId));

        $nestedComments = array();

        foreach ($result as $row)
        {
            $comId = $row['com_id'];
            $nestedComment = $this->buildDomainObject($row);
            // The associated article is defined for the constructed comment
            $nestedComment->setArticle($article);
            $nestedComments[$comId] = $nestedComment;
        }
        return $nestedComments;
    }


    /**
     * Return a list of all comments for an comment, sorted by date (most recent last).
     *
     * @param $commentId
     * @return array A list of all comments for the comment.
     */
    public function findAllByCommentLast3($commentId) {

        // com_id is not selected by the SQL query
        // The comment won't be retrieved during domain objet construction
        $sql = "select * from comment where par_id=? AND published=1 order by com_id DESC limit 3";
        $result = $this->getDb()->fetchAll($sql, array($commentId));

        // Convert query result to an array of domain objects
        $comments = array();
        foreach ($result as $row) {
            $comId = $row['com_id'];
            $comment = $this->buildDomainObject($row);
            // The associated comment is defined for the constructed comment

            $comments[$comId] = $comment;
        }
        return $comments;
    }

    /**
     * @return array a list of all approved comments
     */
    public function findAll()
    {
        $sql = "select * from comment order by com_id DESC";
        $result = $this->getDb()->fetchAll($sql);

        //convert query to an array of domain objects
        $entities = array();
        foreach ($result as $row)
        {
            $id = $row['com_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    /**
     * @return array a list of all approved comments
     */
    public function findApprovedComments()
    {
        $sql = "select * from comment where published=1 order by com_id DESC";
        $result = $this->getDb()->fetchAll($sql);

        //convert query to an array of domain objects
        $entities = array();
        foreach ($result as $row)
        {
            $id = $row['com_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    /**
     * @return array a list of all comments in moderation
     */
    public function findModerateComments()
    {
        $sql = "select * from comment where published=0 order by com_id DESC";
        $result = $this->getDb()->fetchAll($sql);

        //convert query to an array of domain objects
        $entities = array();
        foreach ($result as $row)
        {
            $id = $row['com_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    /**
     * @param \Projet3\Domain\Comment
     */
    public function save(Comment $comment) {
        $commentData = array(
            'art_id' => $comment->getArticle()->getId(),
            'usr_id' => $comment->getAuthor()->getId(),
            'com_content' => $comment->getContent()
        );
        if ($comment->getId()) {
            // The comment has already been saved : update it
            $this->getDb()->update('comment', $commentData, array('com_id' => $comment->getId()));
        } else {
            // The comment has never been saved : insert it
            $this->getDb()->insert('comment', $commentData);
            // Get the id of the newly created comment and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $comment->setId($id);
        }
    }

    public function moderate($id)
    {
        //update the comment moderation status
        $this->getDb()->update('comment', array('published' => '0'),array('com_id' => $id));
    }

    public function accept($id)
    {
        //update the comment moderation status
        $this->getDb()->update('comment', array('published' => '1'),array('com_id' => $id));
    }

    /**
     * @param Comment $comment
     * @param $commentId
     * @param $articleId
     */
    public function saveNestedComment(Comment $comment, $commentId, $articleId) {

        $commentData = array(
            'art_id' => $articleId,
            'usr_id' => $comment->getAuthor()->getId(),
            'com_content' => $comment->getContent(),
            'par_id' => $commentId
        );

            // The comment has never been saved : insert it
            $this->getDb()->insert('comment', $commentData);
            // Get the id of the newly created comment and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $comment->setId($id);
            $comment->isPublished();
            $comment->getParent();

    }

    /**
     * Returns a comment matching the supplied id.
     *
     * @param integer $id The comment id
     * @return \Projet3\Domain\Comment
     * @throws \Exception
     */
    public function find($id) {
        $sql = "select * from comment where com_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No comment matching id " . $id);
    }

    /**
     * Removes a comment from the database.
     *
     * @param integer $id The comment id
     */
    public function delete($id) {
        // Delete the comment
        $this->getDb()->delete('comment', array('com_id' => $id));
    }

    /**
     * Removes all comments
     *
     * @param $articleId id of the article
     */
    public function deleteAllByArticle($articleId)
    {
        $this->getDb()->delete('comment', array('art_id' => $articleId));
    }

    /**
     * removes all comments attached to an article
     *
     * @param $commentId
     */
    public function deleteAllByComment($commentId)
    {
        $this->getDb()->delete('comment', array('par_id' => $commentId));
    }

    /**
     * removes all comments from a user
     *
     * @param $userId integer id of the user
     */
    public function deleteAllByUser($userId)
    {
        $this->getDb()->delete('comment', array('usr_id' => $userId));
    }

    /**
     * Creates an Comment object based on a DB row.
     *
     * @param array $row The DB row containing Comment data.
     * @return \Projet3\Domain\Comment
     */
    protected function buildDomainObject(array $row) {
        $comment = new Comment();
        $comment->setId($row['com_id']);
        $comment->setContent($row['com_content']);

        if (array_key_exists('art_id', $row)) {
            // Find and set the associated article
            $articleId = $row['art_id'];
            $article = $this->articleDAO->find($articleId);
            $comment->setArticle($article);
        }
        if (array_key_exists('usr_id', $row)) {
            // Find and set the associated author
            $userId = $row['usr_id'];
            $user = $this->userDAO->find($userId);
            $comment->setAuthor($user);
        }
        if (array_key_exists('par_id', $row)) {
            $comment->setParent($row['par_id']);
        }
        if (array_key_exists('published', $row)) {
            $comment->setPublished($row['published']);
        }

        return $comment;
    }
}