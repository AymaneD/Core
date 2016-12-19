athens.ajax_section = (function () {

    /**
     * The set of get variables which shall be included when requesting a section
     *
     * @type {{}}
     */
    var getVarRegistry = {};

    /**
     * A dictionary of sections, by name or handle
     *
     * @type []
     */
    var sectionRegistry = [];

    /**
     * An array of functions to call following every section load
     *
     * @type {Array}
     */
    var postSectionActions = [];

    /**
     * Represents a "get variable" to be encoded into a url string
     *
     * @param sectionName
     * @param filterName
     * @param argName
     * @param value
     * @returns {{sectionName: *, filterName: *, argName: *, value: *}}
     */
    var getVar = function (sectionName, filterName, argName, value) {
        return {
            sectionName: sectionName,
            filterName: filterName,
            argName: argName,
            value: value
        };
    };

    /**
     * Register a get var for inclusion in section requests
     *
     * @param getVar
     */
    var registerGetVar = function (getVar) {
        if (!(getVar.sectionName in getVarRegistry)) {
            getVarRegistry[getVar.sectionName] = {};
        }

        if (!(getVar.filterName in getVarRegistry[getVar.sectionName])) {
            getVarRegistry[getVar.sectionName][getVar.filterName] = {};
        }

        getVarRegistry[getVar.sectionName][getVar.filterName][getVar.argName] = getVar.value;
    };

    /**
     * De-register a getVar
     *
     * @param getVar
     */
    var unsetGetVar = function (getVar) {
        if (getVar.sectionName in getVarRegistry &&
            getVar.filterName in getVarRegistry[getVar.sectionName] &&
            getVar.argName in getVarRegistry[getVar.sectionName][getVar.filterName]
        ) {
            delete getVarRegistry[getVar.sectionName][getVar.filterName][getVar.argName];
        }
    };

    /**
     *
     * @param sectionName
     * @param filterName
     * @param argName
     * @returns {*}
     */
    var getGetVarValue = function (sectionName, filterName, argName) {

        if (sectionName in getVarRegistry &&
            filterName in getVarRegistry[sectionName] &&
            argName in getVarRegistry[sectionName][filterName]
        ) {
            return getVarRegistry[sectionName][filterName][argName];
        }
        return null;
    };

    /**
     * Render the registered get variables for a given AJAXSection into a URL-encoded string
     *
     * @param {string} name The name or handle of the AJAXSection for which we are encoding the get variables
     * @returns {string} The URL-encoded string of get variables
     */
    var renderGetVars = function (name) {
        if (!(name in getVarRegistry)) {
            return "";
        }

        var ret = "";
        for (var filterName in getVarRegistry[name]) {
            if (getVarRegistry[name].hasOwnProperty(filterName)) {
                for (var argName in getVarRegistry[name][filterName]) {
                    if (getVarRegistry[name][filterName].hasOwnProperty(argName)) {
                        ret += filterName + "-" + argName + "=" + getVarRegistry[name][filterName][argName] + "&";
                    }
                }
            }
        }

        return ret;
    };

    function getQueryParams(qs)
    {
        qs = qs.split('+').join(' ');

        var params = {},
            tokens,
            re = /[?&]?([^=]+)=([^&]*)/g;

        while (tokens = re.exec(qs)) {
            params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
        }

        return params;
    }

    function cleanQueryParams(url)
    {
        var qs = url.substr(url.indexOf("?") + 1);
        params = getQueryParams(qs);

        qs = '';
        for (var param in params) {
            if (params.hasOwnProperty(param)) {
                qs += encodeURIComponent(param) + '=' + encodeURIComponent(params[param]) + '&';
            }
        }

        return url.split('?')[0] + '?' + qs;
    }

    /**
     * Load a registered section by name, ignoring the query variables in its indicated path.
     *
     * @param {string} id The name or handle of the section, as registered in sectionRegistry by registerAJAXSection
     * @returns {*}
     */
    var bareLoadSection = function (id) {
        var targetDiv, targetUrl;

        targetDiv = $("#" + id);
        if (sectionRegistry.hasOwnProperty(id) && !targetDiv.data("request-uri")) {
            targetUrl = sectionRegistry[id];
        } else {
            targetUrl = targetDiv.data("request-uri");
            sectionRegistry[id] = targetUrl;
        }

        return doLoadSection(id, targetDiv[0], targetUrl.split("?")[0]);
    };

    /**
     * Load a registered section by name
     *
     * @param {string} id The name or handle of the section, as registered in sectionRegistry by registerAJAXSection
     * @returns {*}
     */
    var loadSection = function (id) {
        var targetDiv, targetUrl, result;

        targetDiv = $("#" + id);

        // There should be only one div with the given id...but in case there's not, reload all of them
        for (var i = 0; i < targetDiv.length; i++) {
            if (sectionRegistry.hasOwnProperty(id) && !targetDiv.data("request-uri")) {
                targetUrl = sectionRegistry[id];
            } else {
                targetUrl = targetDiv.data("request-uri");
                sectionRegistry[id] = targetUrl;
            }
            result = doLoadSection(id, targetDiv[0], targetUrl);
        }

        return result;

    };

    /**
     * Load a registered section by name
     *
     * @param {string} id The name or handle of the section, as registered in sectionRegistry by registerAJAXSection
     * @param {element} targetDiv
     * @param {string} targetUrl
     * @returns {*}
     */
    var doLoadSection = function (id, targetDiv, targetUrl) {
        targetDiv = $(targetDiv);
        targetDiv.css("opacity", 0.7).append("<div class='loading-gif class-loader'></div>");

        if (targetUrl.indexOf("?") === -1) {
            targetUrl += "?";
        }

        targetUrl = targetUrl + renderGetVars(id);

        targetUrl = cleanQueryParams(targetUrl);

        return $.get(
            targetUrl,
            function (data) {
                var targetResponse = $(data).find("#" + id);
                targetDiv.replaceWith(targetResponse);
                doPostSectionActions(targetDiv);
            }
        );
    };

    /**
     * Add a function to the array of callables to perform after every section load.
     *
     * @param f
     */
    var registerPostSectionAction = function (f) {
        postSectionActions.push(f);
    };

    /**
     * Execute those callables which should be called after every section load
     * @param target
     */
    var doPostSectionActions = function (target) {
        for (var i = 0; i < postSectionActions.length; i++) {
            postSectionActions[i](target);
        }
    };

    return {
        doPostSectionActions: doPostSectionActions,
        registerPostSectionAction: registerPostSectionAction,
        bareLoadSection: bareLoadSection,
        loadSection: loadSection,
        registerGetVar: registerGetVar,
        unsetGetVar: unsetGetVar,
        getVar: getVar,
        getGetVarValue: getGetVarValue
    };

}());
