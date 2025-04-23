<?php get_header(); ?>

<div class="projects-archive-container">
    <h1><?php post_type_archive_title(); ?></h1>

    <div class="projects-grid">
        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $args = array(
            'post_type' => 'projects',
            'posts_per_page' => 3,
            'paged' => $paged,
        );

        $projects_query = new WP_Query($args);

        if ($projects_query->have_posts()) :
            while ($projects_query->have_posts()) : $projects_query->the_post(); ?>
                <div class="project-item">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php if (has_post_thumbnail()) {
                        the_post_thumbnail('medium');
                    } ?>
                    <div class="excerpt"><?php the_excerpt(); ?></div>
                </div>
            <?php endwhile; ?>

            <div class="pagination">
                <?php
                global $projects_query;

                $big = 999999999; // need an unlikely integer

                echo paginate_links(array(
                    'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'format'    => '?paged=%#%',
                    'current'   => max(1, get_query_var('paged')),
                    'total'     => $projects_query->max_num_pages,
                    'prev_text' => '« Prev',
                    'next_text' => 'Next »',
                    'type'      => 'list',
                ));
                ?>
            </div>


        <?php else : ?>
            <p>No projects found.</p>
        <?php endif;

        wp_reset_postdata();
        ?>
    </div>
</div>

<style>
    .projects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }

    .project-item {
        border: 1px solid #ddd;
        padding: 1rem;
        border-radius: 8px;
        background: #f9f9f9;
    }

    .pagination {
        display: flex;
        justify-content: space-between;
        margin: 2rem 0;
    }

    .pagination ul {
        list-style: none;
        padding: 0;
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .pagination li {
        display: inline-block;
    }

    .pagination li a,
    .pagination li span {
        padding: 8px 12px;
        border: 1px solid #ccc;
        text-decoration: none;
        color: #333;
        border-radius: 4px;
    }

    .pagination li .current {
        background: #333;
        color: #fff;
        border-color: #333;
    }
</style>

<?php get_footer(); ?>