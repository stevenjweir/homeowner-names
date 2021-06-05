<?php

namespace Lib;
require './lib/Person.php';

class NameProcessor
{
    public $csv;
    public $results;

    /**
     * NameProcessor constructor.
     *
     * @param $file
     */
    public function __construct($file)
    {
        $this->csv = $file;
    }

    /**
     * Load the CSV from the temporary file uploaded
     *
     * @return array
     */
    public function loadCSVFile(): array
    {

        // Check if the file is an array and has a tmp_name index
        if(!is_array($this->csv) && !isset($this->csv['tmp_name'])) {
            $this->returnError( 'File not found.');
        }

        // Load/open the file
        $loadedFile = fopen($this->csv['tmp_name'], 'r');

        // If this file didn't load, return.
        if($loadedFile === FALSE) {
            $this->returnError( 'File failed to load.');
        }

        $rows = [];

        // Get the data within the loaded file and return
        while (($data = fgetcsv($loadedFile, 0, ",")) !== FALSE) {
            $rows[] = $data[0];
        }

        return $rows;
    }

    /**
     * Loads and Parses rows from CSV into correct schema.
     *
     * @return void
     */
    public function getResults(): void
    {
        // First we load the csv data from the uploaded file
        $rows = $this->loadCSVFile();

        // Now we process the name fields
        $this->results = $this->parseNames($rows);

        if(!$this->results) {
            $this->returnError('No Results found');
        }
        else {
            $this->returnResults($this->results);
        }

    }

    /**
     * Parses each row and checks for multiple people
     *
     * @param array $rows
     *
     * @return array|null
     */
    private function parseNames(array $rows): ?array {

        $results = [];

        if(!is_array($rows) && !is_countable($rows)) {
            return null;
        }

        // Loop through each row
        foreach($rows as $i => $row) {

            // Ignore the first header line
            if($i !== 0) {

                // Clean any white space from the beginning and end of the row
                $string = trim($row);

                // Break the string up into individual words
                $words = explode(' ', $string);

                $people = [];

                // Detect if there is more than one name in this row
                if($multiName = array_keys(array_intersect(array_map('strtolower',$words), ['and', '&', '/']))){

                    // Get the index value for the split-word (eg: and/&)
                    $splitIndex = $multiName[0];

                    // Split up each person on splitIndex position.
                    $firstPerson = array_slice($words, 0, $splitIndex);
                    $secondPerson = array_slice($words, $splitIndex + 1);

                    // If the first person has only one word, this must be a prefix
                    if(is_array($firstPerson) && count($firstPerson) < 3) {

                        if(is_array($secondPerson) && count($secondPerson) > 1) {

                            // Now we must take the First Name of the second person (if available) and give it to the first person
                            // (Note: This would rely on the convention that the first persons name is used in this way every time)
                            if(count($secondPerson) > 2) {
                                $firstPerson[] = $secondPerson[1];
                                // Then remove this name from the second person
                                array_splice($secondPerson, 1, 1);
                            }

                            // Give the first person the same last name as the second person
                            $firstPerson[] = end($secondPerson);
                        }

                    }

                    // Create a new person for each of the people we've parsed and add to results.
                    $people[] = $this->createPerson($firstPerson);
                    $people[] = $this->createPerson($secondPerson);

                }
                // Otherwise if only one person, proceed to generate a single person.
                else {
                    $people[] = $this->createPerson($words);
                }

                $results[] = $people;

            }

        }

        return $results;
    }

    /**
     * Generates a Person from provided array of strings
     *
     * @param $words
     *
     * @return Person
     */
    private function createPerson(array $words): Person {

        // Create a new Person
        $person = new Person();

        // Set the title using any matching prefixes
        $person->setTitle($words[0]);

        // If there are more than two words the second word in array is greater than 2 characters (initial + dot), set as first name
        if(count($words) > 2) {
            if (strlen($words[1]) > 2) {
                $person->setFirstName($words[1]);
            }
            // Otherwise set as initial (removing any dots in process)
            else {
                $person->setInitial(preg_replace('/\./', '', $words[1]));
            }
        }

        // Set the last name
        $person->setLastName(end($words));

        // Return the completed person
        return $person;
    }

    /**
     * Returns Results;
     *
     * @param array $response
     */
    public function returnResults(array $response): void {
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    /**
     * Returns an error message
     *
     * @param $msg
     *
     * @return false|string
     */
    public function returnError(string $msg): void {
        header('Content-Type: application/json');
        echo json_encode($msg);
    }

}