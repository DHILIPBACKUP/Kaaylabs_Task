 <?php
function array_merge_recursive_custom($array1, $array2) {
    if (!is_array($array1) || !is_array($array2)) {
        return $array2;
    }
    
    foreach ($array2 as $key => $value) {
        if (isset($array1[$key]) && is_array($array1[$key]) && is_array($value)) {
            $array1[$key] = array_merge_recursive_custom($array1[$key], $value);
        } else {
            $array1[$key] = $value;
        }
    }
    
    return $array1;
}

$array1 = array("name" => "John", "age" => 30, "address" => array("city" => "New York"));
$array2 = array("age" => 35, "address" => array("zip" => 10001));

$result = array_merge_recursive_custom($array1, $array2);
print_r($result);

?>
