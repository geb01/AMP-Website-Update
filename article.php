<?php
    require_once 'php/global.php';
    $meta = json('meta');

    $articles = json('articles');
    $article = NULL;
    foreach($articles as $a) {
        if($a['id'] == $_GET['article_id']){
            $article = $a;
            break;
        };
    };
?>

<!DOCTYPE html>
<html>
    <head>
        <?php source('article.css'); ?>
        <title>Ateneo Musician's Pool - Tube Magazine</title>
        <meta charset='utf-8'>
		<meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta name='viewport' content='maximum-scale=1, initial-scale=1'>
        <meta property="og:url"                content="http://ateneomusicianspool.com/article.php?article_id=<?php echo $_GET['article_id']?>" />
        <meta property="og:type"               content="article" />
        <meta property="og:title"              content="<?php echo $article['title'] ?>"/>
        <meta property="og:description" content="Click to read now!">
        <meta property="og:image"              content="http://ateneomusicianspool.com<? echo $article['image'] ?>" />
		<link rel="icon" href="/images/icons/logo.ico" type="image/x-icon">
		<?php source('global.css'); ?>
		<?php source('header.css'); ?>
		<?php source(jquery()); ?>
		<?php source('global.js'); ?>
    <head>
    <body id='holy-grail'>
        <div class='article'>
            <div class='wrapper'>
                <a href='/amplitube.php'><img id='tube' src='/../images/icons/tube_logo.png'></a>
            </div>
            <div class='wrapper'>
                <div class='main-content'><?php
                    $articles = json('articles');
                    $article = NULL;
                    foreach($articles as $a) {
                        if($a['id'] == $_GET['article_id']){
                            $article = $a;
                            break;
                        };
                    };?>
                    <h4><?php echo $article['title'] ?></h4>
                    <div class = subheading>
                        <p><?php echo $article['date_published']?></p>
                        <p>|</p>
                        <p><?php echo $article['credits']?></p>
                    </div>
                    <div class='main-image'>
                        <img src ='..<?php echo $article['image']?>'>
                    </div>
                    <?php echo $article['content']?>
                </div>
            </div>
        </div>
    </body>
    <?php createFooter('article') ?>
</html>