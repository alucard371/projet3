<?php

namespace MicroCMS\DAO;


use MicroCMS\Domain\Comment;

class CommentDAO extends DAO
{
    /**
     * @var \MicroCMS\DAO\ArticleDAO
     */
    private $articleDAO;

    /**
     * @var \MicroCMS\DAO\UserDAO
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
        $sql = "select * from t_comment where art_id=? and par_id = 0 and published=1 order by com_id DESC ";
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


    /**
     * Return a list of all comments for an comment, sorted by date (most recent last).
     *
     * @param $commentId
     * @return array A list of all comments for the comment.
     */
    public function findAllByCommentLast3($commentId) {

        // com_id is not selected by the SQL query
        // The comment won't be retrieved during domain objet construction
        $sql = "select * from t_comment where par_id=? AND published=1 order by com_id DESC limit 3";
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
        $sql = "select * from t_comment order by com_id DESC";
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
        $sql = "select * from t_comment where published=1 order by com_id DESC";
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
        $sql = "select * from t_comment where published=0 order by com_id DESC";
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
     * @param \MicroCMS\Domain\Comment
     */
    public function save(Comment $comment) {
        $commentData = array(
            'art_id' => $comment->getArticle()->getId(),
            'usr_id' => $comment->getAuthor()->getId(),
            'com_content' => $comment->getContent()
        );
        if ($comment->getId()) {
            // The comment has already been saved : update it
            $this->getDb()->update('t_comment', $commentData, array('com_id' => $comment->getId()));
        } else {
            // The comment has never been saved : insert it
            $this->getDb()->insert('t_comment', $commentData);
            // Get the id of the newly created comment and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $comment->setId($id);
        }
    }

    public function moderate($id)
    {
        //update the comment moderation status
        $this->getDb()->update('t_comment', array('published' => '0'),array('com_id' => $id));
    }

    public function accept($id)
    {
        //update the comment moderation status
        $this->getDb()->update('t_comment', array('published' => '1'),array('com_id' => $id));
    }

    public function published($id)
    {
        //get publish state of comment
        $sql = "select published from t_comment where com_id=?";
        $this->getDb()->fetchAssoc($sql, array($id));
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
            $this->getDb()->insert('t_comment', $commentData);
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
     * @return \MicroCMS\Domain\Comment
     * @throws \Exception
     */
    public function find($id) {
        $sql = "select * from t_comment where com_id=?";
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
        $this->getDb()->delete('t_comment', array('com_id' => $id));
    }

    /**
     * Removes all comments
     *
     * @param $articleId id of the article
     */
    public function deleteAllByArticle($articleId)
    {
        $this->getDb()->delete('t_comment', array('art_id' => $articleId));
    }

    /**
     * removes all comments attached to an article
     *
     * @param $commentId
     */
    public function deleteAllByComment($commentId)
    {
        $this->getDb()->delete('t_comment', array('par_id' => $commentId));
    }

    /**
     * removes all comments from a user
     *
     * @param $userId integer id of the user
     */
    public function deleteAllByUser($userId)
    {
        $this->getDb()->delete('t_comment', array('usr_id' => $userId));
    }

    /**
     * Creates an Comment object based on a DB row.
     *
     * @param array $row The DB row containing Comment data.
     * @return \MicroCMS\Domain\Comment
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

        return $comment;
    }
}