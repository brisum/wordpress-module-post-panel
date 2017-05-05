(function($){
    var $reveal = $('#post-panel-popup');

    function initTaxonomyEdit() {
        $(".chosen", $reveal).chosen({
            //disable_search_threshold: 10,
            width:"100%",
            search_contains:true,
            enable_split_word_search: false,
            no_results_text: "Ничего не найдено!"
            //width: "95%"
        });

        var $selectByLevel = {},
            maxLevel = 0;

        $('select[data-level]', $reveal).each(function () {
            var $select = $(this),
                $wrapSelect = $select.closest('div'),
                level = parseInt($select.attr('data-level'));

            $selectByLevel[level] = $select;
            maxLevel = Math.max(maxLevel, level);

            $select.on('change', function () {
                var $this = $(this),
                    level = parseInt($this.attr('data-level')),
                    values = $this.val(),
                    $nextSelect = $selectByLevel[level+1],
                    nextValues = [];

                if ($nextSelect) {
                    $nextSelect.find('option').attr('disabled', true);
                    $.each(values, function (i, parent) {
                        $nextSelect.find('option[data-parent="' + parent + '"]').each(function () {
                            var $option = $(this);

                            if ($option.is(':selected')) {
                                nextValues.push($option.attr('value'));
                            }
                            $option.removeAttr('disabled');
                        });
                    });
                    $nextSelect.val(nextValues).trigger("chosen:updated").trigger("change");
                }
            });

            $wrapSelect.on('focus', '.search-field input', function () {
                var $this = $(this);

                $wrapSelect.find('.chosen-results li').show();
                $wrapSelect.find('.chosen-results .disabled-result').hide();
                setTimeout(function () {
                    $wrapSelect.find('.chosen-results .disabled-result').hide();
                }, 50);
                setTimeout(function () {
                    $wrapSelect.find('.chosen-results .disabled-result').hide();
                }, 100);
            });
        });

        $(".taxonomy-form form", $reveal).submit(function () {
            var $this = $(this),
                data = {term: []};

            $('select[data-level]').each(function () {
                var values = $(this).val();
                if (values) {
                    $.each(values, function (i, value) {
                        data['term'].push(value);
                    });
                }
            });

            $reveal.find('.taxonomy-form').html('Сохранение...');
            $.ajax({
                method: "post",
                url: $this.attr('action'),
                data: data
            }).done(function (response) {
                $reveal.find('.taxonomy-form').html(response);
                initTaxonomyEdit();
            });

            return false;
        });
    }

    $(document).on("click", ".post-panel-taxonomy-edit", function () {
        var $this = $(this);

        $.ajax({
            method: "get",
            url: $this.attr('data-href')
        }).done(function (response) {
            $reveal.find('.content').html(response);
            $reveal.foundation('open');
        });

        return false;
    });

    $(document).on("click", ".post-panel-taxonomy-edit-taxonomy", function () {
        var $this = $(this);

        $reveal.find('.taxonomy-form').html('Загрузка...');
        $.ajax({
            method: "get",
            url: $this.attr('data-href')
        }).done(function (response) {
            $reveal.find('.taxonomy-form').html(response);
            initTaxonomyEdit();
        });

        return false;
    });
}(jQuery));
