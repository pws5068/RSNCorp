<?

require('functions.php');
$items = Item::getNew(2);

print_r($items);

die(" Done.");
?>