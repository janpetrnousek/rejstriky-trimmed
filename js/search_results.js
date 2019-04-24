$(function() {
    var scriptElement = $("script[src*='main.min.js']");

    var base_url = scriptElement.attr('data-base-url');   
    var search_id = scriptElement.attr('data-search-id');   
    var search_type_id = scriptElement.attr('data-search-type-id');   
    var search_type_relations = scriptElement.attr('data-search-type-relations');   

    var loadedIcs = [];
    var loadedIsirIds = [];
    var loadedOrIds = [];
    var loadedRelationIds = [];
    var loadedSimilarSubjects = [];

    if (search_id !== 'false') {
        if (search_type_relations == "false") {
            getDataSubjects('like_exact');
        } else {
            getDataRelations('like_exact');
        }
    }

    if (search_type_id != false) {
        $(".main_header__search__tabs input[value='" + search_type_id + "']").click();
    }

    function getDataRelations(type) {
        var search_url = base_url + 'search/results_relations_ajax/' + search_id + '/' + type;
        var request = $.post(search_url + '/o/' + type, { 'ignoredIds' : JSON.stringify(loadedRelationIds) });

        request.done(function(data) {
            processData(data, loadedRelationIds, type);

            var nextType = getNextType(type);
            handleNextType(nextType, loadedRelationIds.length === 0);

            if (nextType !== '') {
                getDataRelations(nextType);
            }
        });
    }

    function getDataSubjects(type) {
        var search_url = base_url + 'search/results_ajax/' + search_id;

        var postData = { 'ignoredIcs' : JSON.stringify(loadedIcs) };

        postData.ignoredIds = JSON.stringify(loadedOrIds);
        var o_request = $.post(search_url + '/o/' + type, postData);

        postData.ignoredIds = JSON.stringify(loadedIsirIds);
        var i_request = $.post(search_url + '/i/' + type, postData);

        $.when(o_request, i_request).done(function(o_data, i_data) {
            processData(o_data[0], loadedOrIds, type);
            processData(i_data[0], loadedIsirIds, type);

            var nextType = getNextType(type);
            handleNextType(nextType, loadedOrIds.length === 0 && loadedIsirIds.length === 0);

            if (nextType !== '') {
                getDataSubjects(nextType);
            }
        });
    }

    function processData(data, loadedIds, type) {
        try {
            JSON.parse(data).forEach(function(i) {
                if (loadedIds.indexOf(i.id) === -1 && (!i.ic || loadedIcs.indexOf(i.ic) === -1)) {
                    var template = $("#subject-template-" + (i.isProblematic ? "problematic" : "ok")).clone();

                    var templateContent = template.html();
                    templateContent = templateContent.replace('{subjectLink}', i.link);
                    templateContent = templateContent.replace('{subjectScreeningLink}', i.screeningLink ? i.screeningLink : '');
                    templateContent = templateContent.replace('{subjectName}', i.name);
                    templateContent = templateContent.replace('{subjectShortName}', i.name_short);
                    templateContent = templateContent.replace('{subjectIc}', i.formattedIc);
                    templateContent = templateContent.replace('{subjectAddress}', i.address);
                    templateContent = templateContent.replace('{subjectShortAddress}', i.address_short);

                    var target = type === 'like_exact' || type === 'fulltext_exact' || type === 'fulltext_free'
                        ? $("#found_subjects_indicator")
                        : $("#found_similar_subjects_indicator");

                    target.before(templateContent);

                    loadedIds.push(i.id);
                    loadedIcs.push(i.ic);

                    if (type === 'similar_like_a1' || type === 'similar_like_a2' || type === 'similar_fulltext_a1' || type === 'similar_fulltext_a2') {
                        loadedSimilarSubjects.push(i);
                    }
                }
            });
        }
        catch (e) {
            // do not care about exception, carry on with execution
        }
    }

    function getNextType(type) {
        var nextType = '';
        if (type === 'like_exact') {
            nextType = 'fulltext_exact';
        } else if (type === 'fulltext_exact') {
            nextType = 'fulltext_free';
        } else if (type === 'fulltext_free') {
            nextType = 'similar_like_a1';
        } else if (type === 'similar_like_a1') {
            nextType = 'similar_like_a2';
        } else if (type === 'similar_like_a2') {
            nextType = 'similar_fulltext_a1';
        } else if (type === 'similar_fulltext_a1') {
            nextType = 'similar_fulltext_a2';
        }

        return nextType;
    }

    function handleNextType(nextType, nothingLoaded) {
        if (nextType === 'similar_like_a1') {
            $("#found_subjects_indicator").remove();

            if (nothingLoaded) {
                $("#found_subjects_notfound").show();
            }
        }

        if (nextType === '') {
            $("#found_similar_subjects_indicator").remove();

            if (loadedSimilarSubjects.length === 0) {
                $("#found_similar_subjects_notfound").show();
            }
        }
    }
});