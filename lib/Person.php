<?php

namespace Lib;

class Person {

    public $title;
    public $first_name;
    public $initial;
    public $last_name;

    function __construct() {

    }

    /**
     * Set's this persons title.
     *
     * @param String $title
     */
    function setTitle(string $title): void {
        $this->title = $title;
    }

    /**
     * Get's persons title
     *
     * @return string
     */
    function getTitle(): string {
        return $this->title;
    }

    /**
     * Set's this persons first name.
     *
     * @param string $firstName
     */
    function setFirstName(string $firstName): void {
        $this->first_name = $firstName;
    }

    /**
     * Get's persons first name
     *
     * @return string
     */
    function getFirstName(): string {
        return $this->first_name;
    }

    /**
     * Set's this persons initial.
     *
     * @param String $initial
     */
    function setInitial(String $initial): void {
        $this->initial = $initial;
    }

    /**
     * Get's persons initial
     *
     * @return string
     */
    function getInitial(): string {
        return $this->initial;
    }

    /**
     * Set's this persons last name.
     *
     * @param String $lastName
     */
    function setLastName(String $lastName): void {
        $this->last_name = $lastName;
    }

    /**
     * Get's persons last name
     *
     * @return string
     */
    function getLastName(): string {
        return $this->last_name;
    }

}