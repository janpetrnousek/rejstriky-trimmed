$(function() {
    var scriptElement = $("script[src*='main.min.js']");

    var base_url = scriptElement.attr('data-base-url');   
    var isir_id = scriptElement.attr('data-isir-id');   

    var loadedSections = [];

    if (isir_id !== 'false') {
        $(".sectionheader").click(function() {
            getSectionData(parseInt($(this).attr("data-section-id")), $(this));
        })
    }

    function getSectionData(id, sectionHeader) {
        var parentLi = sectionHeader.closest('li');
        var url = base_url + 'detail/isirsection/' + isir_id + '/' + id;

        if (parentLi.length > 0 && loadedSections.indexOf(url) === -1) {
            loadedSections.push(url);
            $.get(url, function(data) {
                parentLi.find('.accordion__item__content').html(data);
            });
        }
    }

});