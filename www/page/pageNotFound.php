<?

require('functions.php');

$page = new Page();

$page->setTitle('Uh Oh..');

$page->setContent('page/pageNotFound');

$page->create();

?>