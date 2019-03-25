<?php

//create blank arrays for armor types
$boots = array();
$helmets = array();
$leggings = array();
$chests = array();
$all = array();

//file pointer to armor.csv containing the armor pieces
$file = "armor.csv";
//create abd array from the csv
$csv = array_map('str_getcsv', file($file));
array_walk($csv, function(&$a) use ($csv) {
  $a = array_combine($csv[0], $a);
});
array_shift($csv);

//parse through array and fill the armor type arrays
foreach ($csv as $row) {
    if ($row['Type'] == "Boots") {
        array_push($boots, $row);
        array_push($all, $row);
    }
    
    if ($row['Type'] == "Helmet") {
        array_push($helmets, $row);
        array_push($all, $row);
    }
    
    if ($row['Type'] == "Leggings") {
        array_push($leggings, $row);
        array_push($all, $row);
    }
    
    if ($row['Type'] == "Chest") {
        array_push($chests, $row);
        array_push($all, $row);
    }
}

/*function combinations, create cartesian product of all arrays
*@param $array array
*@return $result array
*/
function combinations($arrays) 
{
    $result = array();
    $arrays = array_values($arrays);
    $sizeIn = sizeof($arrays);
    $size = $sizeIn > 0 ? 1 : 0;
    
    foreach ($arrays as $array) {
        $size = $size * sizeof($array);
    }
    
    for ($i = 0; $i < $size; $i ++) 
    {
        $result[$i] = array();
        
        for ($j = 0; $j < $sizeIn; $j ++) 
        {
            array_push($result[$i], current($arrays[$j]));
        }
        
        for ($j = ($sizeIn -1); $j >= 0; $j --)
        {
            if (next($arrays[$j])) {
                break;
            }
            elseif (isset ($arrays[$j])) {
                reset($arrays[$j]);
            }
        }
    }
    
    return $result;
    
}

/*
*function sort_cost value, loop through array and total the cost and value of each set. Find all armor sets less than or equal to 300 and then from the remaining sets
*find the one with the highest value and return that array
*@param $arrays array
*@return $array
*/
function sort_cost_value($arrays) {
    $sort_array = array();
    foreach($arrays as $armor_set) {
        $totalCost = 0;
        $totalValue = 0;
        
        foreach($armor_set as $armor) {
            $totalCost += $armor['Cost'];
            $totalValue += $armor['Value'];
        }
        $armor_set['TotalCost'] = $totalCost;
        $armor_set['TotalValue'] = $totalValue;
        
        if ($armor_set['TotalCost'] <= '300') {
            array_push($sort_array, $armor_set); 
        }
    }
    
    $maxValue = max(array_column($sort_array, 'TotalValue'));

    foreach($sort_array as $array) {
        if ($array['TotalValue'] == $maxValue) {
            return $array;
        }
    }
    
}

/*
*function unique_arrays, remove any arrays that contain a duplicate object in them and return a new array of the unique armor sets
*@param $multi_array array
*@return $final_array array
*/
function unique_arrays($multi_array) {
    $dupe = false;
    $temp_array = array();
    $final_array = array();
    foreach ($multi_array as $array) {
        $nameCheck = $array[4]['Name'];
        for ($i = 0; $i < 4; $i++) {
            if ($array[$i]['Name'] == $nameCheck) {
               $dupe = true;            
            }
        }

        if ($dupe) {
            array_pop($array);
            array_push($temp_array, $array);
            $dupe = false;
        } else {
            array_push($temp_array, $array);
            $dupe = false;
        }          
        
    }
    
    foreach ($temp_array as $array) {
        if (sizeof($array) == 5) {
            array_push($final_array, $array);
        }
    }
    
    return $final_array;
}

//set geralts best armor set
$geralt_armor_array =  sort_cost_value(unique_arrays(combinations(array($boots, $chests, $helmets, $leggings, $all))));

/*
*function display_armor, create html to display geralts armor set
*@param $armor_set array
*
*/
function display_armor($armor_set) {

    
    for ($i = 0; $i < 5; $i++) {
        
        $type = $armor_set[$i]['Type'];
        $name = $armor_set[$i]['Name'];
        $cost = $armor_set[$i]['Cost'];
        $value = $armor_set[$i]['Value'];
        
        if ($type == "Boots") $img = "img/icons/ffffff/000000/1x1/lorc/boots.svg";
        if ($type == "Chest") $img = "img/icons/ffffff/000000/1x1/lorc/breastplate.svg";
        if ($type == "Helmet") $img = "img/icons/ffffff/000000/1x1/lorc/visored-helm.svg";
        if ($type == "Leggings") $img = "img/icons/ffffff/000000/1x1/delapouite/knee-pad.svg";
        
        echo "
            <div class=\"col-sm-2 armor-piece m-2 p-2\">
                <h4 class=\"armor-type\">$type</h4>
                <p class=\"armor-name\">$name</p>
                <p class=\"armor-cost\">Cost: $cost Crowns</p>
                <p class=\"armor-value\">Value: $value</p>
            </div>
        ";
        
    }
}

/*
*function display_totals, create html to display geralt's armor total cost and total value
*@param $armor_set array
*
*/
function display_totals($armor_set) {
    $totalCost = $armor_set['TotalCost'];
    $totalValue = $armor_set['TotalValue'];
    
    echo "
        <div class=\"armor-totals\">
            <table>
                <tr scope=\"row\">
                    <td class=\"pr-1\">Total Cost:</td>
                    <td class=\"\">$totalCost Crowns</td>
                </tr>
                <tr scope=\"row\">
                    <td class=\"pr-1\">Total Value:</td>
                    <td class=\"\">$totalValue</td>
                </tr>
            </table>
        </div>
    ";
}

?>
<!doctype html>
<html>
<head>
    <meta name="Geralt's Best Armor Set" content="The best armor set that Geralt can purchase with 300 crowns">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geralt's Best Armor Set</title>
    <!-- Custom Style Sheets -->
    <link rel="stylesheet" type="text/css" href="css/style.css" media="all">
    <!-- Bootstrap -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous"> 
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="heading">
            <h1>Geralt's Best Armor Set for 300 Crowns</h1>
        </div>
        <hr>
        <div class="armor-set row">
            <?php display_armor($geralt_armor_array); ?>
        </div>
        <?php display_totals($geralt_armor_array); ?>
        <div class="explaination-container mt-4">
            <p id="explaination">I used the following process to determine the best possible armor set that Geralt can afford for 300 crowns.</p>
            <ol id="step-list">
                <li>Create different arrays for each armor type and fill them with the different pieces of armor.</li>
                <li>Create a single array containing all pieces of armor to account for the final piece of the armor set being able to be any type.</li>
                <li>Create a cartesian product of the five arrays.</li>
                <li>Parse through the cartesian product and remove all armor sets where the fifth piece of armor is a duplicate of another piece of armor in the set. Assuming that I can only buy one of each item.</li>
                <li>Loop through each armor set and determine their total cost and total value.</li>
                <li>Remove any armor set that is greater than 300 crowns in total cost.</li>
                <li>Choose the armor set with the highest value from the remaining armor sets.</li>
            </ol>
            <p id="other-inventories">I believe this process would work effectively for other inventories. In particular, I think this process could be useful for building a grocery list. If I know my budget and the cost of the different ingredients at the nearby grocery stores, I could determine the most cost effective places to purchase those ingredients.</p>
        </div>
    </div>
</body>
</html>


 