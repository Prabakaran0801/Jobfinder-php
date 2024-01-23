<?php
/*
 *Get the base path
 *@param string $path
 */

function basePath($path = ''){
    return __DIR__ . '/' . $path;
}

/*
*Load a view
*@param string $name
*@return void
*/

function lodeView($name){
    $viewPath = basePath("views/{$name}.view.php");

    if(file_exists($viewPath)){
        require $viewPath;
    }else{
        echo"View'{$name} not found!'";
    }
}

/*
*Load a partials
*@param string $name
*@return void
*/

function loadPartial($name){
    $partialPath = basePath("views/partials/{$name}.php");
    
    if(file_exists($partialPath)){
        require $partialPath;
    }else{
        echo"Partial'{$name} not found!'";
    }
}