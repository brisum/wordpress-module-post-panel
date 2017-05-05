<?php

namespace Brisum\Wordpress\PostPanel;

use WP_Post;

class Panel
{
	/**
	 * @constructor
	 */
	public function __construct()
	{
        add_action('wp_footer', array($this, 'actionWpFooter'));
        add_action('post_panel_render', array($this, 'renderEditLink'), 10, 1);
    }

    public function isUserCanPostPanel($post = null)
    {
        return apply_filters('current_user_can_post_panel', current_user_can('edit_post', $post), $post);
    }

	public function render(WP_Post $post)
	{
		if (!$this->isUserCanPostPanel($post)) {
			return;
		}

        ?>

        <div class="post-panel clearfix">
            <?php do_action('post_panel_render', $post); ?>
        </div>

        <?php
	}

	public function renderEditLink($post)
    {
        ?>
        <div class="post-panel-item">
            <a href="<?php echo get_edit_post_link($post->ID); ?>" target="_blank" class="dotted">
                Ред.
            </a>
        </div>
        <?php
    }

    public function actionWpFooter()
    {
        if ($this->isUserCanPostPanel()) {
            ?>
                <div class="reveal" id="post-panel-popup"
                     data-reveal
                     data-v-offset="50"
                     data-animation-in="fade-in" data-animation-out="fade-out">

                    <div class="content"></div>

                    <button class="close-button" data-close aria-label="Close modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php
        }
    }
}
