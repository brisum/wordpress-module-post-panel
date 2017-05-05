<div class="row collapse">
    <div class="xsmall-12 columns title">
        Редактирование Таксономии
    </div>

    <div class="medium-3 columns">
        <ul class="taxonomies">
            <?php foreach ($taxonomies as $taxonomy) : ?>
                <li>
                    <a href="javascript:void(0)"
                       data-href="/wp-admin/admin-ajax.php?action=post_panel_taxonomy_edit_taxonomy&pid=<?php echo $postId; ?>&taxonomy=<?php echo $taxonomy->name; ?>"
                       class="post-panel-taxonomy-edit-taxonomy dotted">
                        <?php echo $taxonomy->labels->name; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="medium-9 columns taxonomy-form">
        <p class="text-center">
            Выберите таксономию
        </p>
    </div>
</div>