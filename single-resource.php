<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div class="wrap">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <p>
                <a href="<?php echo get_post_type_archive_link('resource') ?>">Go back to Resources</a>
            </p>

            <h1><?php the_title() ?></h1>
            <p><i><?php the_time('F jS, Y') ?></i></p>

            <?php echo get_the_post_thumbnail() ?>

            <div class="the-content">
                <?php the_content() ?>
            </div>

            <?php if (wp_get_post_terms(get_the_ID(), 'topic')) : ?>
                <p><b>Topics: </b>
                    <?php foreach (wp_get_post_terms(get_the_ID(), 'topic') as $topic) : ?>
                        <span class="tag topic"><?php echo $topic->name; ?></span>
                    <?php endforeach; ?>
                </p>
            <?php endif; ?>


            <?php if (wp_get_post_terms(get_the_ID(), 'audience')) : ?>
                <p><b>Audiences: </b>
                    <?php foreach (wp_get_post_terms(get_the_ID(), 'audience') as $audience) : ?>
                        <span class="tag audience"><?php echo $audience->name; ?></span>
                    <?php endforeach; ?>
                </p>
            <?php endif; ?>

            <?php if (get_field('download_url')) : ?>
                <a href="<?php echo get_field('download_url') ?>" download>Download</a>
            <?php endif; ?>

        </main><!-- #main -->
    </div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer(); ?>
<?php wp_footer(); ?>

</body>
</html>
