<?php

namespace Projet3\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Projet3\Domain\User;


class UserDAO extends DAO implements UserProviderInterface
{
    /**Returns a user matching the supplied id
     * @param integer $id The user id
     * @return User
     * @throws \Exception
     */
    public function find($id)
    {
        $sql = "select * from user where usr_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("Pas d'utilisateur avec cet identifiant" . $id);
    }

    public function findAll()
    {
        $sql = "select * from user order by usr_role, usr_name";
        $result = $this->getDb()->fetchAll($sql);

        //Convert query results to an array of objects
        $entities = array();
        foreach ($result as $row)
        {
            $id = $row['usr_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    /**
     * Saves a user into the database.
     *
     * @param User $user The user to save
     */
    public function save(User $user) {
        $userData = array(
            'usr_name' => $user->getUsername(),
            'usr_salt' => $user->getSalt(),
            'usr_password' => $user->getPassword(),
            'usr_role' => $user->getRole()
        );
        if ($user->getId()) {
            // The user has already been saved : update it
            $this->getDb()->update('user', $userData, array('usr_id' => $user->getId()));
        } else {
            // The user has never been saved : insert it
            $this->getDb()->insert('user', $userData);
            // Get the id of the newly created user and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $user->setId($id);
        }
    }

    /**
     * Removes an user from the database.
     *
     * @param integer $id The user id.
     */
    public function delete($id) {
        // Delete the user
        $this->getDb()->delete('user', array('usr_id' => $id));
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $sql = "select * from user where usr_name=?";
        $row = $this->getDb()->fetchAssoc($sql, array($username));
        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
    }
    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }
        return $this->loadUserByUsername($user->getUsername());
    }
    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return 'Projet3\Domain\User' === $class;
    }
    /**
     * Creates a User object based on a DB row.
     *
     * @param array $row The DB row containing User data.
     * @return \Projet3\Domain\User
     */
    protected function buildDomainObject(array $row) {
        $user = new User();
        $user->setId($row['usr_id']);
        $user->setUsername($row['usr_name']);
        $user->setPassword($row['usr_password']);
        $user->setSalt($row['usr_salt']);
        $user->setRole($row['usr_role']);
        return $user;
    }

}