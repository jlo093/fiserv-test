<?php

use Fiserv\Repositories\FileRepository;
use Fiserv\Services\DatabaseService;

require __DIR__ . '/../vendor/autoload.php';

$fileRepo = new FileRepository(
    new DatabaseService()
);

if ($_POST) {
    $filesFound = $fileRepo->findByPath($_POST['name']);
}

?>
<html>
<head>
    <title>Files</title>
</head>
<body>
<form method="post" action="index.php">
    <input type="text" name="name" placeholder="File/directory name" />
    <input type="submit" value="Submit" />
</form>
<?php if (!empty($filesFound)): ?>
<table>
    <?php foreach ($filesFound as $file): ?>
    <tr>
        <td><?php echo $file['path']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
</body>
</html>
