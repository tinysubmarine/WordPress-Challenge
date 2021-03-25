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

            <?php get_header() ?>

            <div class="filters">
                <form method="GET">
                    <input type="search" placeholder="search..." name="s">

                    <?php if (!empty(get_terms(['taxonomy' => 'topic']))) : ?>
                        <select name="filter_topic" id="" onchange="this.form.submit()">
                            <option value="0">Topic</option>
                            <?php foreach (get_terms(['taxonomy' => 'topic']) as $topic) : ?>
                                <option value="<?php echo $topic->term_id ?>" <?php if (isset($_GET['filter_topic']) && $_GET['filter_topic'] == $topic->term_id) echo 'selected' ?>>
                                    <?php echo $topic->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                    <?php if (!empty(get_terms(['taxonomy' => 'audience']))) : ?>
                        <select name="filter_audience" id="" onchange="this.form.submit()">
                            <option value="0">Audience</option>
                            <?php foreach (get_terms(['taxonomy' => 'audience']) as $audience) : ?>
                                <option value="<?php echo $audience->term_id ?>" <?php if (isset($_GET['filter_audience']) && $_GET['filter_audience'] == $audience->term_id) echo 'selected' ?>>
                                    <?php echo $audience->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </form>
            </div>

            <?php if (have_posts()) : ?>

                <?php while (have_posts()) :the_post(); ?>

                    <div>
                        <h3><?php the_title() ?></h3>
                        <p><i><?php the_time('F jS, Y'); ?></i></p>
                        <a href="<?php the_permalink(); ?>">Learn More</a>
                    </div>

                <?php endwhile;

                global $wp_query;
                echo paginate_links([
                    'current' => max(1, get_query_var('paged')),
                    'total' => $wp_query->max_num_pages,
                    'type' => 'list', //default it will return anchor
                    'add_args' => [
                        'filter_topic' => $_GET['filter_topic'] ?? 0,
                        'filter_audience' => $_GET['filter_audience'] ?? 0
                    ]
                ]);

            else : ?>

                <h3>Try changing your filters or confirm you've added content.</h3>

            <?php endif; ?>

        </main><!-- #main -->
    </div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer(); ?>
<?php wp_footer(); ?>

</body>
</html>
