<?php

namespace Brisum\Wordpress\PostPanel\Panel;

use Brisum\Lib\View;
use Brisum\Wordpress\PostPanel\Panel;
use WP_Post;
use WP_Term;

class TaxonomyEdit
{
    /**
     * @var Panel
     */
    protected $panel;

    /**
     * @var View
     */
    protected $view;

    /**
     * @constructor
     * @param Panel $panel
     * @param View $view
     */
    public function __construct(Panel $panel, View $view)
    {
        $this->panel = $panel;
        $this->view = $view;

        add_action('post_panel_render', array($this, 'render'));
        add_action('wp_footer', array($this, 'actionWpFooter'), 100);
        add_action('wp_ajax_post_panel_taxonomy_edit', array($this, 'actionWpAjaxTaxonomyEdit'));
        add_action('wp_ajax_post_panel_taxonomy_edit_taxonomy', array($this, 'actionWpAjaxTaxonomyEditTaxonomy'));
    }

    public function render(WP_Post $post)
    {
        if (!$this->panel->isUserCanPostPanel($post)) {
            return;
        }

        ?>
            <div class="post-panel-item">
                <a href="javascript:void(0)"
                   data-href="/wp-admin/admin-ajax.php?action=post_panel_taxonomy_edit&pid=<?php echo $post->ID; ?>"
                   class="post-panel-taxonomy-edit dotted">
                    Таксономия
                </a>
            </div>
        <?php
    }

    public function actionWpFooter()
    {
        ?>

        <link rel="stylesheet" href="<?php echo THEME_URI; ?>/vendor/brisum/wordpress/Module/PostPanel/assets/thirdparty/chosen/chosen.css"/>
        <script type="text/javascript" src="<?php echo THEME_URI; ?>/vendor/brisum/wordpress/Module/PostPanel/assets/thirdparty/chosen/chosen.jquery.js"></script>
        <script type="text/javascript" src="<?php echo THEME_URI; ?>/vendor/brisum/wordpress/Module/PostPanel/assets/javascript/script.js"></script>
        <?php
    }

    /**
     * @return void
     */
    public function actionWpAjaxTaxonomyEdit()
    {
        if (!isset($_GET['pid'])) {
            die('wrong request');
        }

        $post = get_post(intval($_GET['pid']));
        if (empty($post)) {
            die('wrong request');
        }

        if (!$this->panel->isUserCanPostPanel($post)) {
            die('access denied');
        }

        $taxonomies = get_object_taxonomies($post->post_type, 'object');
        unset($taxonomies['color']);
        unset($taxonomies['size']);
        usort($taxonomies, function ($a, $b) {
           return strcmp($a->labels->name, $b->labels->name);
        });

        $this->view->render(
            'template/panel/taxonomy-edit/taxonomies.php',
            [
                'postId' => $post->ID,
                'taxonomies' => $taxonomies
            ]
        );
        die();
    }

    /**
     * @return void
     */
    public function actionWpAjaxTaxonomyEditTaxonomy()
    {
        $post = isset($_GET['pid']) ? get_post(intval($_GET['pid'])) : null;
        $postTerms = [];
        $taxonomies = get_object_taxonomies($post->post_type, 'object');
        unset($taxonomies['color']);
        unset($taxonomies['size']);
        $taxonomy = isset($taxonomies[$_GET['taxonomy']]) ? $taxonomies[$_GET['taxonomy']] : null;
        $termsByLevel = [];
        $termLevel = 0;

        if (!$this->panel->isUserCanPostPanel($post)) {
            die('access denied');
        }
        if (empty($post)) {
            die('wrong request');
        }
        if (!$taxonomy) {
            die('wrong request');
        }

        if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
            $termIds = is_array($_POST['term']) ? array_filter(array_map('intval', $_POST['term'])) : [];
            $d = wp_set_post_terms($post->ID, $termIds, $taxonomy->name, false);
        }

        foreach (wp_get_post_terms($post->ID, $taxonomy->name) as $term) {
            $postTerms[$term->term_id] = $term;
        }

        /** @var WP_Term[] $terms */
        $terms = get_terms(['taxonomy' => $taxonomy->name, 'hide_empty' => false]);
        foreach ($terms as $termKey => $term) {
            if (0 == $term->parent) {
                $termsByLevel[$termLevel][$term->term_id] = $term;
                unset($terms[$termKey]);
            }
        }
        do {
            $termLevel++;
            foreach ($terms as $termKey => $term) {
                if (isset($termsByLevel[$termLevel-1][$term->parent])) {
                    $termsByLevel[$termLevel][$term->term_id] = $term;
                    unset($terms[$termKey]);
                }
            }
        } while (!empty($terms) && 100 >= $termLevel);
        foreach ($terms as $termKey => $term) {
            $termsByLevel[0][$term->term_id] = $term;
            unset($terms[$termKey]);
        }
        foreach ($termsByLevel as $termLevel => &$terms) {
            usort($terms, function ($a, $b) {
                return strcmp($a->name, $b->name);
            });
        }

        $this->view->render(
            'template/panel/taxonomy-edit/taxonomy.php',
            [
                'postId' => $post->ID,
                'taxonomy' => $taxonomy,
                'postTerms' => $postTerms,
                'termsByLevel' => $termsByLevel
            ]
        );
        die();
    }
}
