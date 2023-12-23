<?php
$appginilte_dir = dirname(__FILE__);
include("{$appginilte_dir}/../defaultLang.php");
include("{$appginilte_dir}/../language.php");
include("{$appginilte_dir}/../lib.php");
//call all the functions
echo "<ol id='attention-issues'>";
checkHeaderExtras();
checkLinksNavMenu();
checkHooksTvJsFiles();
echo "</ol>";
echo "<div id='no-issues'></div>";
echo "<script>const attentionIssues = document.getElementById('attention-issues'); const noIssues = document.getElementById('no-issues');  if (attentionIssues.childElementCount === 0) {noIssues.textContent = 'There are NO attention issues. Incase any issues are found with your code, you will see them here.'; } </script>";
//Functions definition
function checkHeaderExtras()
{
    $file = "hooks/header-extras.php";
    $comment1 = "AppginiLTE Detail View Logic";
    $comment2 = "Code inserted by AppginiLTE";
    $comment3="TableAction Code inserted by AdminLTE Plugin For Appgini";

    if (file_exists($file)) {
        $lines = file($file); // read file into an array of lines
        $found1 = array();
        $found2 = array();
        $found3 = array();

        //Check for existence of comment1
        foreach ($lines as $lineNumber => $line) {
            if (strpos($line, $comment1) !== false) {
                $found1[] = $lineNumber + 1; // store the line number (starting from 1)
            }
        }
        if (count($found1) > 0) {
            echo "<li>The <b>'$file'</b> file has outdated code for detail view logic,Please delete/remove this entire code starting from line: " . implode(", to line: ", $found1) . " including the comment '$comment1'</li>";
        }
        //Check for existence of comment2
        foreach ($lines as $lineNumber => $line) {
            if (strpos($line, $comment2) !== false) {
                $found2[] = $lineNumber + 1; // store the line number (starting from 1)
            }
        }
        if (count($found2) > 0) {
            echo "<li>The <b>'$file'</b> file has outdated code for Inline edit,Please delete/remove this entire code from line: " . implode(", to line: ", $found2) . " including the comments '$comment2'</li>";
        }
        //check for existance of comment3
        foreach ($lines as $lineNumber => $line) {
            if (strpos($line, $comment3) !== false) {
                $found3[] = $lineNumber + 1; // store the line number (starting from 1)
            }
        }
        if (count($found3) > 0) {
            echo "<li>The <b>'$file'</b> file has outdated code for Table actions,Please delete/remove this entire code from line: " . implode(", to line: ", $found3) . " including the comments '$comment3'</li>";
        }
       
    }
}

function checkLinksNavMenu()
{
    $file = "hooks/links-navmenu.php";
    $comment = "appginilte reports links";
    $comment2="AdminLTE For Appgini reports links";
    $comment3="AppginiLTE Custom Pages";
    if (file_exists($file)) {
        $lines = file($file); // read file into an array of lines
        $found = array();
        $found2= array();
        $found3=array();
        foreach ($lines as $lineNumber => $line) {
            if (strpos($line, $comment) !== false) {
                $found[] = $lineNumber + 1; // store the line number (starting from 1)
            }
        }
        if (count($found) > 0) {
            echo "<li>The <b>'$file'</b> file has outdated code for AdminLTE for Appgini reports navLinks code,Please delete/remove this entire code with array navLinks[] starting from line: " . implode(", ", $found) . " including the comment '$comment'</li>";
        }
        //Check for existence of comment2
        foreach ($lines as $lineNumber => $line) {
            if (strpos($line, $comment2) !== false) {
                $found2[] = $lineNumber + 1; // store the line number (starting from 1)
            }
        }
        if (count($found2) > 0) {
            echo "<li>The <b>'$file'</b> file has outdated code for AdminLTE for Appgini reports navLinks code,Please delete/remove this entire code with array navLinks[] from line: " . implode(", to line: ", $found2) . " including the comments '$comment2'</li>";
        }
         //Check for existence of comment3
         foreach ($lines as $lineNumber => $line) {
            if (strpos($line, $comment3) !== false) {
                $found3[] = $lineNumber + 1; // store the line number (starting from 1)
            }
        }
        if (count($found3) > 0) {
            echo "<li>The <b>'$file'</b> file has outdated code for AppginiLTE Custom Pages NavLinks,Please delete/remove this entire code starting from line: " . implode(", to line: ", $found3) . " including the comments '$comment3'</li>";
        }
    }
}

function checkHooksTvJsFiles()
{
    $directory = 'hooks'; // Replace with your directory name/path
    $searchString = 'InlineEdit Code inserted by AppginiLTE';
    if (is_dir($directory)) {
        if ($dir = opendir($directory)) {
            while (($file = readdir($dir)) !== false) {
                if (preg_match('/-tv\.js$/', $file)) { // Check if the file name ends with "-tv.js"
                    $filePath = $directory . '/' . $file;
                    $fileContent = file_get_contents($filePath);
                    $lines = explode("\n", $fileContent);
                    $matches = array();
                    foreach ($lines as $lineNumber => $line) {
                        if (strpos($line, $searchString) !== false) {
                            $matches[] = $lineNumber + 1;
                        }
                    }
                    if (!empty($matches)) {
                        echo "<li>We found deprecated code for Inline Edit in the file <b>'hooks/$file'</b> beween line(s): " . implode(', ', $matches) . ", Kindly remove/delete this code including the comments '$searchString'</li>";
                    }
                }
            }
            closedir($dir);
        }
    }
}