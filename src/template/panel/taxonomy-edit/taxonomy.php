<form action="/wp-admin/admin-ajax.php?action=post_panel_taxonomy_edit_taxonomy&pid=<?php echo $postId; ?>&taxonomy=<?php echo $taxonomy->name; ?>"
      method="post">

    <div class="taxonomy-title">
        <?php echo $taxonomy->labels->name; ?>(<?php echo $taxonomy->name; ?>)
    </div>

    <?php foreach ($termsByLevel as $level => $terms) : ?>
        <div class="wrapper-select">
            <select class="chosen" data-level="<?php echo $level; ?>"
                    data-placeholder="Выберите значения"
                    multiple >
                <?php
                foreach ($terms as $term):?>
                    <option value="<?php echo $term->term_id ?>"
                            data-parent="<?php echo $term->parent; ?>"
                        <?php if (isset($postTerms[$term->term_id])) { echo "selected"; } ?>>
                        <?php echo "{$term->name}({$term->slug})"; ?>
                    </option>
                <?php endforeach;?>
            </select>
        </div>
    <?php endforeach; ?>

    <input type="submit" value="Сохранить">
</form>
