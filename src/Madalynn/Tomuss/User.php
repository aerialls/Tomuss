<?php

/*
 * This file is part of the Tomuss application.
 *
 * (c) Julien Brochet <mewt@madalynn.eu>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Madalynn\Tomuss;

use Madalynn\Tomuss\Notifier\Notifier;
use Madalynn\Tomuss\Storage\StorageInterface;
use Madalynn\Tomuss\Note;

/**
 * The user
 */
class User
{
    /**
     * Notifiers
     *
     * @var array
     */
    protected $notifiers;

    /**
     * The storage
     *
     * @var Madalynn\Tomuss\Storage\StorageInterface
     */
    protected $storage;

    /**
     * The username
     *
     * @var string
     */
    protected $username;

    /**
     * The password
     *
     * @var string
     */
    protected $password;

    /**
     * Array of notes
     *
     * @var array
     */
    protected $notes;

    /**
     * Constructor
     *
     * @param string $username The username
     * @param string $password The password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;

        $this->notes = array();
        $this->notifiers = array();
    }

    /**
     * Adds a notifier.
     *
     * @param Notifier $notifier A Notifier instance
     */
    public function addNotifier(Notifier $notifier)
    {
        $this->notifiers[] = $notifier;
    }

    /**
     * Gets the notifiers associated with this project.
     *
     * @return array An array of Notifier instances
     */
    public function getNotifiers()
    {
        return $this->notifiers;
    }

    /**
     * Sets the storage
     *
     * @param StorageInterface $storage A storage instance
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Gets the storage
     *
     * @return StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Sets the username
     *
     * @param string $username The username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Gets the username
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the user password
     *
     * @param string $password The password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Gets the user password
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Adds a note
     *
     * @param Note $note
     */
    public function addNote(Note $note)
    {
        if (null === $note) {
            return;
        }

        $this->notes[$note->getId()] = $note;
    }

    /**
     * Gets the notes
     *
     * @return array An array of Note
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Loads the note from the the storage
     */
    public function load()
    {
        if (null === $this->storage) {
            return;
        }

        $notes = $this->storage->load($this->username);

        foreach ($notes as $note) {
            $this->addNote($note);
        }
    }

    /**
     * Checks if the note is already registered
     *
     * @param Note $note
     */
    public function contains(Note $note)
    {
        if (null === $note) {
            return false;
        }

        return isset($this->notes[$note->getId()]);
    }

    /**
     * Dumps all notes into the storage
     */
    public function dump()
    {
        if (null === $this->storage) {
            return;
        }

        $this->storage->dump($this->username, $this->notes);
    }
}