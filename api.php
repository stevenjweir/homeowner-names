<?php
    require './lib/NameProcessor.php';
    use Lib\NameProcessor;

    // Check if there is a file before we proceed
    if(is_array($_FILES) && is_countable($_FILES)) {
        $csv = $_FILES['file'];
    }
    // Otherwise return null
    else {
        return null;
    }

    // Initialise the processor class with the csv file.
    $process = new NameProcessor($csv);

    // Generate results
    $results = $process->getResults();

    // Return JSON encoded results
    return json_encode($results);

?>