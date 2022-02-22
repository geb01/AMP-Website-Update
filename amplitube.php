<?php
    require_once 'php/global.php';
    $meta = json('meta');
?>

<!DOCTYPE html>
<html>
    <head>
        <?php globalLinks(); ?>
        <?php source('amplitube.css'); ?>
        <title>Ateneo Musician's Pool - Tube Magazine</title>
    <head>
    <body id='holy-grail'>
        <?php createHeader('Tube'); ?>
        <div class='amplitube'>
            <div class='wrapper'>
                <a href='/amplitube.php'><img id='tube' src='/../images/icons/tube_logo.png'></a>
            </div>
            <div class='wrapper' style="text-align:center;padding-bottom:100px">
                <img src='/../images/icons/tube_logo_big.png' width="580" height="205">
                <p>Ateneo Musician's Pool Official Publication</p>
            </div>
            <div id='chevron'>
                <img src='../images/icons/chevron_down.png'>
            </div>
            <div class='wrapper'>
                <div class='card-wrapper'><?php
                $articles = json('articles');
                foreach ($articles as $article) { 
                    $image = $article['image'];
                    if ($image) $image = "src='$image'"; ?>
                    <div class="card" index=<?php echo $article['id']; ?>>
                        <a href='/article.php?article_id=<?php echo $article['id'] ?>'><h5><?php echo $article['title']; ?></h5></a>
                        <?php echo paragraph($article['date_published']); ?>
                        <?php echo paragraph($article['credits']); ?>
                        <a href='/article.php?article_id=<?php echo $article['id'] ?>'><img <?php echo $image; ?><a>
                    </div> 
                <?php } unset($articles); ?>
                </div>
            </div>
        </div>
    </body>
    <?php createFooter('Tube') ?>
</html>
        