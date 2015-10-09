

uwdoem.search = (function() {

    var searchDiv = $(".search-div");



    /**
     * Fades in the table search div.
     */
    function fadeInSearch() {
        $(".search-div").fadeTo(1, 1);
    }

    /**
     * Fades out the table search div.
     */
    function fadeOutSearch() {

        searchDiv.fadeTo(150, 0,  function() {
            $(this).css("display", "none");
        });

    }

    var searchSubmitOnclick = function() {
        var handle = $(this).closest("div.search-table-content").data("handle-for");
        var ajaxSectionName = $("div.search-container[data-handle-for=" + handle + "]").closest("div.ajax-loaded-section").attr("id");
        var i = 0;

        var getVar = uwdoem.ajax_section.getVar;

        $(this).parent().find('tr').each(function() {
            var fieldname = $(this).find('td.fieldname select option:selected').val();
            var operation = $(this).find('td.operation select option:selected').text();
            var value = $(this).find('td.value input').val();
            if (fieldname && operation && value) {
                uwdoem.ajax_section.registerGetVar(getVar(ajaxSectionName, handle, 'fieldname' + i, fieldname));
                uwdoem.ajax_section.registerGetVar(getVar(ajaxSectionName, handle, 'operation' + i, operation));
                uwdoem.ajax_section.registerGetVar(getVar(ajaxSectionName, handle, 'value' + i, value));
            } else {
                uwdoem.ajax_section.unsetGetVar(getVar(ajaxSectionName, handle, 'fieldname' + i));
                uwdoem.ajax_section.unsetGetVar(getVar(ajaxSectionName, handle, 'operation' + i));
                uwdoem.ajax_section.unsetGetVar(getVar(ajaxSectionName, handle, 'value' + i));
            }
            i++;
        });
        uwdoem.ajax_section.registerGetVar(getVar(ajaxSectionName, 'pagination', 'page', 1));
        uwdoem.ajax_section.loadSection(ajaxSectionName);
        uwdoem.fadeOutMask();
        fadeOutSearch();
    };

    var searchIconOnclick = function() {
        var handle = $(this).data("handle-for");
        $("div.search-table-content[data-handle-for=" + handle + "]").prependTo("#search-criteria-area");


        uwdoem.fadeInMask();
        fadeInSearch();






        var searchSection = $(this).parents(".ajax-loaded-section").data("section-name");

        // Pre-select the existing search criteria
        //searchCriteriaArea.find('tr').each(function() {
        //    var rowNumber = $(this).attr("data-row");
        //
        //    var fieldName = uwdoem.ajax_section.getGetVar(searchSection, 'search', 'fieldname' + rowNumber);
        //    $(this).find("td.fieldname select").val(fieldName);
        //
        //    var operation = uwdoem.ajax_section.getGetVar(searchSection, 'search', 'operation' + rowNumber);
        //    $(this).find("td.operation select").val(operation);
        //
        //    var value = uwdoem.ajax_section.getGetVar(searchSection, 'search', 'value' + rowNumber);
        //    $(this).find("td.value input").val(value);
        //});
    };

    var setupSearchFilter = function (handle) {

        // Move the search icon to the label
        var searchContainer = $("div.search-container[data-handle-for=" + handle + "]");
        var label = searchContainer.closest("div.section-container").find("div.section-label");
        var searchIcon = $("div.search-icon[data-handle-for=" + handle + "]");

        if (label.find("div.search-icon[data-handle-for=" + handle + "]").length === 0) {
            searchIcon.prependTo(label);
        }

        searchIcon.click(searchIconOnclick);

        // This might suppose to be inside searchIconOnclick
        $("input.search-submit." + handle).click(searchSubmitOnclick);
    };

    return {
        setupSearchFilter: setupSearchFilter
    };
}());