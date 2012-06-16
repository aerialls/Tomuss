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

class Note
{
    /**
     * The timestamp of the note
     *
     * @var string
     */
    protected $id;

    /**
     * The node
     *
     * @var string
     */
    protected $note;

    /**
     * The author
     *
     * @var string
     */
    protected $author;

    /**
     * The name of the UE
     *
     * @var string
     */
    protected $name;

    /**
     * The date
     *
     * @var \DateTime
     */
    protected $date;

    /**
     * Constructor
     *
     * @param string $id The ID
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Gets the ID
     *
     * @return string The timestamp
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Sets the date
     *
     * @param \DateTime $date The date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * Gets the date
     *
     * @return \DateTime The date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets the author
     *
     * @param string $author The author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Gets the author
     *
     * @return string The author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Sets the note
     *
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * Gets the note
     *
     * @return string The note
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Sets the note
     *
     * @param string $name The note
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the name
     *
     * @return string The name
     */
    public function getName()
    {
        return $this->name;
    }
}