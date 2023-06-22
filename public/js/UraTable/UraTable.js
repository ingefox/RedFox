/**
 * IMPORT CONF
 */
import {getStyle, getBtn, getDate, formatSortDate} from './UraTable/conf/UraStyles.js';

/**
 * unique id
 */
var uniqueId = [];

/**
 * get unique id
 * @param {string} table identifier of table
 * @return {int} unique id
 */
function getUniqueId(table) {
    if (typeof uniqueId[table] === 'undefined') {
        uniqueId[table] = 0;
    }
    return uniqueId[table]++;
}

/**
 * sleep
 * @param {*} milliseconds
 * @returns
 */
function sleep(milliseconds) {
    return new Promise(resolve => setTimeout(resolve, milliseconds));
}

/**
 * add slashes
 * @param {*} str
 * @returns
 */
function addSlashes(str) {
    if (str !== undefined && str !== null) {
        if (Array.isArray(str)) {
            str = str[0];
        }
        str = str.replace(/\'/g, "\\\'");
        str = str.replace(/\"/g, "\\\"");
        return str;
    } else {
        return str;
    }

}

function removeSlashes(str) {
    if (str !== undefined && str !== null) {
        if (Array.isArray(str)) {
            str = str[0];
        }
        str = str.replace(/\\/g, "");
        return str;
    } else {
        return str;
    }
}

/**
 * format a date with an string format
 * @param {string} _date
 * @param {string} _format
 * @param {string} _delimiter
 * @returns Date
 */
function stringToDate(_date, _format, _delimiter) {
    var formatLowerCase = _format.toLowerCase();
    var formatItems = formatLowerCase.split(_delimiter);
    var dateItems = _date.split(_delimiter);
    var monthIndex = formatItems.indexOf("mm");
    var dayIndex = formatItems.indexOf("dd");
    var yearIndex = formatItems.indexOf("yyyy");
    var month = parseInt(dateItems[monthIndex]);
    month -= 1;
    var formatedDate = new Date(dateItems[yearIndex], month, dateItems[dayIndex]);
    return formatedDate;
}

/**
 * num format
 * @param {*} number
 * @param {*} decimals
 * @param {*} decPoint
 * @param {*} thousandsSep
 * @returns
 */
function num_format(number, decimals = 2, decPoint = ',', thousandsSep = ' ') {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
    const n = !isFinite(+number) ? 0 : +number
    const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
    const sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
    const dec = (typeof decPoint === 'undefined') ? '.' : decPoint
    let s = ''
    const toFixedFix = function (n, prec) {
        if (('' + n).indexOf('e') === -1) {
            return +(Math.round(n + 'e+' + prec) + 'e-' + prec)
        } else {
            const arr = ('' + n).split('e')
            let sig = ''
            if (+arr[1] + prec > 0) {
                sig = '+'
            }
            return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec)
        }
    }
    // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.')
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || ''
        s[1] += new Array(prec - s[1].length + 1).join('0')
    }
    return s.join(dec)
}

/**
 * transform date in string
 * @param {Date} _date
 * @param {string} _format
 * @param {string} _delimiter
 * @returns string
 */
function dateToString(_date, _format, _delimiter) {
    var formatLowerCase = _format.toLowerCase();
    var formatItems = formatLowerCase.split(_delimiter);
    var array = [];
    array[formatItems.indexOf("mm")] = 'm';
    array[formatItems.indexOf("dd")] = 'd';
    array[formatItems.indexOf("yyyy")] = 'y';

    var formatedDate = '';
    var day;
    var month;
    var line;
    for (let i = 0; i < array.length; i++) {
        line = array[i];
        if (line == 'm') {
            month = _date.getMonth() + 1;
            month = month < 10 ? '0' + month : '' + month;
            formatedDate += month;
        } else if (line == 'd') {
            day = _date.getDate();
            day = day < 10 ? '0' + day : '' + day;
            formatedDate += day;
        } else if (line == 'y') {
            formatedDate += _date.getFullYear();
        }
        if (i + 1 != array.length) {
            formatedDate += _delimiter;
        }

    }
    return formatedDate;
}

/**
 * display search in dataTavbble
 * @param {array} data It is a row display array
 * @param {string} id identifier for datatable
 */
function displaySearch(data, id) {
    var conf;
    //search the datatable and edit settings display array.
    for (let i = 0; i < $.fn.DataTable.settings.length; i++) {
        if ($.fn.DataTable.settings[i].nTable.id == id) {
            $.fn.DataTable.settings[i].aiDisplay = data;

            for (let j = 0; j < $.fn.DataTable.settings[i].aoData.length; j++) {
                $.fn.DataTable.settings[i].aoData[j].nTr.className = $.fn.DataTable.settings[i].aoData[j].nTr.className.replace('ura-search-row', '');
            }
            for (let j = 0; j < data.length; j++) {
                $.fn.DataTable.settings[i].aoData[data[j]].nTr.className = $.fn.DataTable.settings[i].aoData[data[j]].nTr.className + ' ura-search-row ';
            }
            //draw the datatable
            conf = $.fn.DataTable.settings[i];
            $.fn.DataTable.ext.internal._fnDraw(conf);
        }
    }
}

/**
 * compare one value with the string search by an comparator
 * @param {string} comparator comparator operator =/!c/!= ....
 * @param {*} value the value of the column on which we are checking
 * @param {*} search the search value
 * @param {string} type type of data
 * @return {boolean}
 */
function selectComparator(comparator, value = "", search = "", type) {
    let bool = true;
    if (value !== null) {
        switch (type) {
            case "int":
                value = parseInt(value);
                search = parseInt(search);
                break;
            case "float":
                value = parseFloat(value);
                search = parseFloat(search);
                break;
            case "string":
                value = value.toLowerCase();
                search = search.toLowerCase();
                break;
            case "date":
                value = stringToDate(value, getDate('array', 'format'), getDate('array', 'delimiter'));
                search = stringToDate(search, getDate('search', 'format'), getDate('search', 'delimiter'));
                break;
        }
    } else {
        value = "";
    }

    switch (comparator) {
        case "=":
        case "cdul":
            if (!(value == search)) bool = false;
            break;
        case "!=":
        case "!cdul":
            if (!(value != search)) bool = false;
            break;
        case "<":
            if (!(value < search)) bool = false;
            break;
        case ">":
            if (!(value > search)) bool = false;
            break;
        case "<=":
            if (!(value <= search)) bool = false;
            break;
        case ">=":
            if (!(value >= search)) bool = false;
            break;
        case "c":
            if (!(value.indexOf(search) != -1)) bool = false;
            break;
        case "!c":
            if ((value.indexOf(search) != -1)) bool = false;
            break;
        case "v":
            if (value.length != 0) bool = false;
            break;
        case "!v":
            if (value.length == 0) bool = false;
            break;
    }
    return bool;
}

/**
 * array represent search sequence
 */
let uraSequenceArray = [];

/**
 * edit array sequence for one datatable
 * @param {string} table identifier of datatable
 * @param {array} arr array sequence
 */
function setSequenceArr(table, arr) {
    uraSequenceArray[table] = arr;
}

/**
 * get array sequence for one datatable
 * @param {string} table identifier of datatable
 */
function getSequenceArr(table) {
    return uraSequenceArray[table];
}

/**
 * add pluggin sort date
 */
/*$.fn.dataTable.moment = function ( format, locale ) {
   var types = $.fn.dataTable.ext.type;

   // Add type detection
   types.detect.unshift( function ( d ) {
       return moment( d, format, locale, true ).isValid() ?
           'moment-'+format :
           null;
   } );

   // Add sorting method - use an integer for the sorting
   types.order[ 'moment-'+format+'-pre' ] = function ( d ) {
       return moment( d, format, locale, true ).unix();
   };
};*/
(function (factory) {
    if (typeof define === "function" && define.amd) {
        define(["jquery", "moment", "datatables.net"], factory);
    } else {
        factory(jQuery, moment);
    }
}(function ($, moment) {

    $.fn.dataTable.moment = function (format, locale) {
        var types = $.fn.dataTable.ext.type;

        // Add type detection
        types.detect.unshift(function (d) {
            if (d) {
                // Strip HTML tags and newline characters if possible
                if (d.replace) {
                    d = d.replace(/(<.*?>)|(\r?\n|\r)/g, '');
                }

                // Strip out surrounding white space
                d = $.trim(d);
            }

            // Null and empty values are acceptable
            if (d === '' || d === null) {
                return 'moment-' + format;
            }

            return moment(d, format, locale, true).isValid() ?
                'moment-' + format :
                null;
        });

        // Add sorting method - use an integer for the sorting
        types.order['moment-' + format + '-pre'] = function (d) {
            if (d) {
                // Strip HTML tags and newline characters if possible
                if (d.replace) {
                    d = d.replace(/(<.*?>)|(\r?\n|\r)/g, '');
                }

                // Strip out surrounding white space
                d = $.trim(d);
            }

            return !moment(d, format, locale, true).isValid() ?
                Infinity :
                parseInt(moment(d, format, locale, true).format('x'), 10);
        };
    };

}));
//add moment format date
for (let k = 0; k < formatSortDate.length; k++) {
    $.fn.dataTable.moment(formatSortDate[k]);
}
/**
 * METHOD ADD TO JQUERY OBJECT $()
 * CREATE AN URATABLE => DATATABLE WITH A SEARCHING FORMULIARE
 * @param {Object} config It is a datatable object config
 * @return {Object} return the datatable create
 */
$.fn.UraTable = function (config) {

    const baseConf = config;
    //if config.uratable,.style
    if (typeof config.uratable === 'undefined') {
        config.uratable = false;
    }
    //TABLE IDENTIFIER
    let idDiv = $(this).attr('id');
    //THIS VARIABLE IS ARAY OF DIFFERETN OPERATOR
    let uraOperator = [];
    uraOperator["="] = {label: "égalité", text: "est égal(e) à", symbol: "=", except: false};
    uraOperator["!="] = {label: "différence", text: "est différent(e) de", symbol: "!=", except: false};
    uraOperator["<"] = {label: "infériorité", text: "est inférieur(e) à", symbol: "<", except: false};
    uraOperator[">"] = {label: "supériorité", symbol: ">", text: "est supérieur(e) à", except: false};
    uraOperator["<="] = {label: "infériorité ou égalité", text: "est inférieur(e) ou égal(e) à", symbol: "<=", except: false};
    uraOperator[">="] = {label: "supériorité ou égalité", text: "est supérieur(e) ou égal(e) à", symbol: ">=", except: false};
    uraOperator["v"] = {label: "Est vide", text: "est vide", symbol: "v", except: true};
    uraOperator["!v"] = {label: "N'est pas vide", text: "n'est pas vide", symbol: "!v", except: true};
    uraOperator["c"] = {label: "Contient", text: "contient", symbol: "c", except: false};
    uraOperator["!c"] = {label: "Ne contient pas", text: "ne contient pas", symbol: "!c", except: false};
    uraOperator["cdul"] = {label: "est le choix dans une liste", text: "est égal(e) à", symbol: "cdul", except: true};
    uraOperator["!cdul"] = {
        label: "n'est pas le choix dans une liste",
        text: "est différent(e) de",
        symbol: "!cdul",
        except: true
    };
    //THIS ARRAY IS LINK INTO DATA TYPE AND OPERATOR
    let uraType = [];
    uraType['int'] = ["=", "!=", "<", ">", "<=", ">=", "v", "!v", "cdul", "!cdul"];
    uraType['float'] = ["=", "!=", "<", ">", "<=", ">=", "v", "!v", "cdul", "!cdul"];
    uraType['string'] = ["=", "!=", "v", "!v", "c", "!c", "cdul", "!cdul"];
    uraType['date'] = ["=", ">", "<", "<=", ">=", "v", "!v", "cdul", "!cdul"];

    //add sum columns
    config.footerCallback = function (row, data, start, end, display) {
        var api = this.api();
        api.columns('.sum', {
            page: 'current'
        }).every(function () {
            var sum = this
                .data()
                .reduce(function (a, b) {
                    var x = parseFloat(a) || 0;
                    var y = parseFloat(b) || 0;
                    return x + y;
                }, 0);
            if (config.sumFormat !== undefined) {
                $(this.footer()).html(num_format(sum, config.sumFormat.nb, config.sumFormat.decSep, config.sumFormat.centSep));
            } else {
                $(this.footer()).html(num_format(sum));
            }

        });
    }
    //init search
    var funct = config.initComplete;
    config.initComplete = function (settings, json) {
        initAdvancedSearch(idDiv, config);
        funct(settings, json);
        launchEarlySearch();
    }
    //list of number line
    config.lengthMenu = [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tous"]];
    //add type columns;
    var theType
    if (typeof config.columnDefs === 'undefined') {
        config.columnDefs = [];
    }
    for (let u = 0; u < config.columns.length; u++) {
        var col = config.columns[u];
        if (col.type != null && typeof col.type != 'undefined') {
            switch (col.type) {
                case 'int':
                case 'float':
                    theType = 'int;'
                    break;
                case 'date':
                    theType = 'date';
                    break;
                case 'string':
                default:
                    theType = 'string';
                    break;
            }

            config.columnDefs.push({type: theType, targets: u});
        }
    }
    //CREATE DATATABLE

    var uraTable = $(this).DataTable(config);
    //take columns searchable and type of column data
    let array = [];
    for (let i = 0; i < config.columns.length; i++) {
        if (typeof config.columns[i].search !== 'undefined' && config.columns[i].search) {
            array.push({type: config.columns[i].type, prop: config.columns[i].data, label: config.columns[i].title});
        }
    }
    //reload datatable with search
    uraTable.ajax.reloadWithSearch = function () {
        uraTable.ajax.reload(
            function () {
                searchSequence();
                search();
            }
        );

    }

    return uraTable;


    /**
     * INTERNAL FUNCTION
     */
    /**
     * early search
     */
    async function launchEarlySearch() {
        //add search early if activ
        if (config.defEarlySearchActiv) {
            await generateForm();
            searchSequence();
            search();
        }
    }

    //manage the early search generate form
    async function generateForm() {
        let idFormSearch = idDiv + "_formsearch";
        //event click into title
        $("#" + idFormSearch + "_header").trigger("click");
        //data for build search
        var or = config.defEarlySearchData;
        //save the link into two input (or / and)
        var orand = false;
        var isFirst = true;
        for (let v = 0; v < or.length; v++) {
            //array of or group
            var line = or[v];
            if (Array.isArray(line)) {
                //array of and group
                for (let w = 0; w < line.length; w++) {
                    var column = line[w];
                    await earlySearch(column, orand);
                    orand = false;
                    isFirst = false;
                }
                orand = true;
            } else {
                await earlySearch(line, orand);
                orand = true;
            }
            isFirst = false;
        }
    }

    //exec the early search function
    async function earlySearch(search, isOr, isFirst = false) {
        let idFormSearch = idDiv + "_formsearch";
        return new Promise(function (resolve, reject) {
            let id = -1;
            //change selectec column in list
            $("#" + idFormSearch + "_select_column option:selected").removeAttr("selected");
            $("#" + idFormSearch + "_select_column option[data-uraprop='" + addSlashes(search.column) + "']").attr('selected', true);
            $.when($("#" + idFormSearch + "_select_column").trigger("change")).done(function () {
                //change operator select in list
                $("#" + idFormSearch + "_select_operator option:selected").removeAttr("selected");
                $("#" + idFormSearch + "_select_operator option[value='" + search.operator + "']").attr('selected', true);
                //event click
                $.when($(document).on('click', "#" + idFormSearch + "_add_search", function (event) {
                    id = event.result;
                    if (search.value !== undefined && id != -1) {
                        if ($("#" + idFormSearch + "_search_inp_" + id).is("input")) {
                            $("#" + idFormSearch + "_search_inp_" + id).val(search.value);
                        }
                    }
                    //if not first input, change orand select value
                    if (!isFirst) {
                        if (isOr) {
                            var valueSel = 1;
                        } else {
                            var valueSel = 0;
                        }
                        $("#" + idFormSearch + "_search_sel_" + id + " option:selected").removeAttr("selected");
                        $("#" + idFormSearch + "_search_sel_" + id + " option[value='" + valueSel + "']").attr('selected', true);

                    }
                })).done(function () {
                    //trigger the click into add element button
                    $("#" + idFormSearch + "_add_search").trigger('click');

                    resolve();
                });
            });
        });
    }

    //add search item
    function addSearchItem() {
        let idFormSearch = idDiv + "_formsearch";
        let strInput = "";
        let symbol = $("#" + idFormSearch + "_select_operator option:selected").val();
        let value = $("#" + idFormSearch + "_select_column option:selected").text();
        let type = $("#" + idFormSearch + "_select_column option:selected").val();
        let prop = $("#" + idFormSearch + "_select_column option:selected").attr("data-uraprop");

        let inpId = getUniqueId(idDiv);
        //no except comparator
        strInput = "<span class='ura_row " + getStyle("span", config.uratable, "default") + "' id='ura_row_" + idDiv + "_" + inpId + "' data-uratar='" + inpId + "'>";
        var childs = $("#" + idFormSearch + "_footer").children();
        if (childs.length > 0) {
            strInput += "<select id='" + idFormSearch + "_search_sel_" + inpId + "' class='ura_formsearch_select ura_formsearch_inpsel ura_formsearch_orand " + getStyle("select", config.uratable, "andor") + "'><option value='0'>et</option><option value='1'>ou</option></select>&nbsp;";
        }
        if (!uraOperator[symbol].except) {
            strInput += "<label class='ura_formsearch_label " + getStyle("label", config.uratable) + "'><span class='font-bold'>" + value + "</span>&nbsp;" + uraOperator[symbol].text + "</label>&nbsp;<input id='" + idFormSearch + "_search_inp_" + inpId + "' class='ura_formsearch_input ";
            //add style of input
            if (type == "int" || type == "float") {
                strInput += getStyle("input", config.uratable, "number");
            } else if (type == "date") {
                strInput += getStyle("input", config.uratable, "date");
            } else {
                strInput += getStyle("input", config.uratable, "default");
            }
            strInput += " ' data-uraope='" + symbol + "' data-uracol=\"" + addSlashes(value) + "\" data-uratype='" + type + "' type='";
            //define type of input
            if (type == "int" || type == "float") {
                strInput += "number' step='";
                if (type == "int") {
                    strInput += "1";
                } else {
                    strInput += "0.01";
                }
            } else if (type == "date") {
                strInput += "date";
            } else {
                strInput += "text";
            }
            strInput += "'/>";
            //delete button
            strInput += "<button class='ura_btn_del " + getStyle("button", config.uratable, "delete") + "' data-uratar='" + inpId + "'>" + getBtn("minus") + "</button>";

            //if empty comparator
        } else if (symbol == "v" || symbol == "!v") {
            strInput += "<label  class='ura_formsearch_label " + getStyle("label", config.uratable) + "'>" + value + " " + uraOperator[symbol].text + "</label><input id='" + idFormSearch + "_search_inp_" + inpId + "'";
            strInput += " class='ura_formsearch_input " + getStyle("input", config.uratable) + "' data-uraope='" + symbol + "' data-uracol='" + value + "' type='hidden'/>";
            strInput += "<button class='ura_btn_del " + getStyle("button", config.uratable, "delete") + "' data-uratar='" + inpId + "'>" + getBtn("minus") + "</button>";

            //cdul comparator
        } else if (symbol == "cdul" || symbol == "!cdul") {

            strInput += "<label  class='ura_formsearch_label " + getStyle("label", config.uratable) + "'>" + value + " " + uraOperator[symbol].text + "</label>";
            strInput += "<select data-uraope='" + symbol + "'  data-uratype='" + type + "' data-uracol='" + value + "' id='" + idFormSearch + "_search_inp_" + inpId + "' class='ura_formsearch_select ura_formsearch_inpsel ura_formsearch_input " + getStyle("select", config.uratable) + "'>";
            let uniqueArray = new Set();
            let table = $("#" + idDiv).DataTable();
            for (let w = 0; w < table.data().length; w++) {
                uniqueArray.add(table.data()[w][prop]);
            }
            for (let item of uniqueArray) {
                strInput += "<option data-uraope='" + symbol + "' data-uracol='" + value + "' value='" + item + "'>" + item + "</option>";
            }
            strInput += "</select>";
            strInput += "<button class='ura_btn_del " + getStyle("button", config.uratable, "delete") + "' data-uratar='" + inpId + "'>" + getBtn("minus") + "</button>";
        }
        strInput += "</span>";
        $("#" + idFormSearch + "_footer").append(strInput);
        $("#" + idFormSearch + "_submit_button").show();
        $("#" + idFormSearch + "_header_separator").show();
        $("#" + idFormSearch + "_footer_wrapper").show();
        $("#" + idFormSearch + "_footer").show();
        $("#" + idFormSearch + "_view").show();
        $("#" + idFormSearch + "_view_wrapper").show();
        searchSequence();
        return inpId;
    }

    //generate in word sequence the search
    function searchSequence() {
        let idFormSearch = idDiv + "_formsearch";
        let val = "", id = "", sym = "";
        let spans = $("#" + idFormSearch + "_footer").children(".ura_row");
        let sequence = "<span class='ura-sequence-or ura-first-sequence " + getStyle("span", config.uratable, "orItem") + "'><span class='ura-sequence-and " + getStyle("span", config.uratable, "andItem") + "'>";
        var andArr = []; // one group with more search element
        var orArr = []; // all groups and
        var arr = []; //one search element
        //for every search property divisions
        spans.each(function (i) {
            arr = [];
            id = $(this).attr("data-uratar");
            sym = $(this).children(".ura_formsearch_input").first().attr("data-uraope");
            arr['ope'] = sym;
            arr['col'] = $(this).children(".ura_formsearch_input").first().attr("data-uracol");
            if (sym != "v" && sym != "!v") {
                if (sym != "cdul" && sym != "!cdul") {
                    arr['val'] = $(this).children(".ura_formsearch_input", "option:selected").first().val();
                } else {
                    arr['val'] = $(this).children(".ura_formsearch_input").first().val();
                }
            }

            if (typeof $(this).children("#" + idFormSearch + "_search_sel_" + id)[0] !== 'undefined') {
                val = $(this).children("#" + idFormSearch + "_search_sel_" + id).first().children("option:selected").val();
                if (val == 0) { //add element in the and group
                    sequence += "<span class='" + getStyle("span", config.uratable, "andConnector") + "'>&nbsp; ET &nbsp;</span><span class='ura-sequence-and " + getStyle("span", config.uratable, "andItem") + "'>";
                    andArr.push(arr);
                } else if (val == 1) {//create a new group and / add elder and group in or array
                    sequence += " </span> <span class='" + getStyle("span", config.uratable, "orConnector") + "'> OU </span> <span class='ura-sequence-or " + getStyle("span", config.uratable, "orItem") + "'><span class='ura-sequence-and " + getStyle("span", config.uratable, "andItem") + "'> ";
                    orArr.push(andArr);
                    andArr = [];
                    andArr.push(arr);
                }
            } else {
                andArr.push(arr);
            }
            //add the element search in the string sequence
            sequence += '<span class="font-bold">'+removeSlashes($(this).children(".ura_formsearch_input").first().attr("data-uracol")) + "</span> " + uraOperator[sym].text;
            if (sym != "v" && sym != "!v") {
                var typetop = $(this).children(".ura_formsearch_input").first().attr('data-uratype');
                var texttop = $(this).children(".ura_formsearch_input", "option:selected").first().val();
                if (typetop == 'date') {
                    /*var myDate = new Date(texttop);
                    texttop = getFormat(myDate);*/
                    var myDate = stringToDate(texttop, getDate('search', 'format'), getDate('search', 'delimiter'));
                    texttop = dateToString(myDate, getDate('array', 'format'), getDate('array', 'delimiter'));
                }
                sequence += " <span class=\"font-bold\">" + texttop+ "</span>";
            }
            sequence += "</span>";
        });
        sequence += " </span>";
        //display sequence
        $("#" + idFormSearch + "_view").html(sequence);
        //save the search in array
        orArr.push(andArr);
        setSequenceArr(idDiv, orArr);
    }

    //launch the searching
    function search() {
        searchSequence();
        let arrayDisplay = [];
        let table = $("#" + idDiv).DataTable();
        table.search("").draw();
        let ope, col, val, value, type;
        //hide all row
        var seqArr = getSequenceArr(idDiv);
        table.rows().every(function (rowIdx, tableLoop, rowLoop) {
            var data = this.data();
            //every or group
            var orItem, andItem, bool;
            var boolArr = [];
            for (let i = 0; i < seqArr.length; i++) {
                orItem = seqArr[i];
                bool = true;
                //every and in this group
                for (let j = 0; j < orItem.length; j++) {
                    andItem = orItem[j];
                    //if error => bool = false
                    ope = andItem["ope"];
                    col = andItem["col"];
                    val = andItem["val"];
                    for (let m = 0; m < config.columns.length; m++) {
                        if (addSlashes(config.columns[m].title) == col) {
                            value = data[config.columns[m].data];
                            type = config.columns[m].type;
                        }
                    }
                    //use good comparator
                    if (selectComparator(ope, value, val, type) == false) bool = false;
                }
                boolArr[i] = bool;
            }
            //if one or group return true show row
            if (boolArr.includes(true)) {
                arrayDisplay.push(rowIdx);
            }
        });
        displaySearch(arrayDisplay, idDiv);
    }

    //  active collapse
    function activeCollapse() {
        let idFormSearch = idDiv + "_formsearch";
        if ($("#" + idFormSearch + "_body").is(":visible")) {
            //hide advanced search
            $("#" + idFormSearch + "_body").hide();
            $("#" + idFormSearch + "_footer_wrapper").hide();
            $("#" + idFormSearch + "_header_separator").hide();
            $("#" + idFormSearch + "_view").hide();
            $("#" + idFormSearch + "_view_wrapper").hide();
            $("#" + idFormSearch + "_submit").hide();
            $("#" + idFormSearch + "_header_btn").html(getBtn("collapse-down"));
            //show search
            $("#" + idDiv + "_filter").show();
        } else {
            //show advanced search
            $("#" + idFormSearch + "_body").show();

            if ($("#" + idFormSearch + "_footer").html().length > 0)
            {
                $("#" + idFormSearch + "_header_separator").show();
                $("#" + idFormSearch + "_footer_wrapper").show();
                $("#" + idFormSearch + "_footer").show();
                $("#" + idFormSearch + "_view_wrapper").show();
                $("#" + idFormSearch + "_view").show();
            }

            $("#" + idFormSearch + "_submit").show();
            $("#" + idFormSearch + "_header_btn").html(getBtn("collapse-up"));
            //hide search
            $("#" + idDiv + "_filter").hide();
        }
    }

    /*
    * Init your datatable with advanced search
    * @param {string} idDiv table div id
    * @param {object} config datatable config object
    */
    function initAdvancedSearch(idDiv, config) {

        // Setting up the advanced search divs
        let idWrapper = idDiv + "_wrapper";
        let idFormSearch = idDiv + "_formsearch";
        let str = "<div class='ura_formsearch " + getStyle("div", config.uratable, "default") + "' id='" + idFormSearch + "'><div id='" + idFormSearch + "_header' class='ura_formsearch_header " + getStyle("div", config.uratable, "header");

        // HEADER
        str += "'><span id='" + idFormSearch + "_header_btn' class='ura_formsearch_header_btn'>" + getBtn("collapse-down") + "</span> <span class='font-bold'>&nbsp; Recherche avancée</span></div>";

        // BODY
        str += "<div id='" + idFormSearch + "_body' class='ura_formsearch_body " + getStyle("div", config.uratable, "body") + "' style='display:none;'></div>";

        // --------------------------------------------------------------------------------- //
        str += '<div class="row col-sm-12 vertical-divider">'; // Div wrapper for footer + view divs

        // FOOTER (Search filters config)
        str += "<div class='col-sm-6' id='" + idFormSearch + "_footer_wrapper' style='display: none'><h5 class='font-bold pb-3'>Configuration des filtres</h5><div  id='" + idFormSearch + "_footer' class='ura_formsearch_footer " + getStyle("div", config.uratable, "footer") + "'></div></div>";

        // VIEW (Search summary)
        str += "<div class='col-sm-6' id='" + idFormSearch + "_view_wrapper' style='display: none'><h5 class='font-bold pb-3'>Récapitulatif de votre recherche</h5><div id='" + idFormSearch + "_view' class='ura_formsearch_view " + getStyle("div", config.uratable, "view") + "'></div></div>";

        str += '</div>';
        // --------------------------------------------------------------------------------- //

        str += "<div id='" + idFormSearch + "_submit' class='ura_formsearch_submit " + getStyle("div", config.uratable, "submit") + "' style='display:none;'><button id='" + idFormSearch + "_submit_button' class='" + getStyle("button", config.uratable, "search") + "' style='display:none;'><i class=\"fas fa-search\"></i>&nbsp;&nbsp;Rechercher</button></div></div>";

        $("#" + idWrapper).prepend(str);

        // ADD FILTER DIV
        var strForm = '<label class="font-bold">Ajouter un filtre</label><br>';

        // FIRST SELECT : Column selection
        strForm += "<select id='" + idFormSearch + "_select_column' class='ura_formsearch_select  " + getStyle("select", config.uratable) + "'>";
        for (let i = 0; i < array.length; i++) {
            strForm += "<option data-uraprop='" + array[i].prop + "' value='" + array[i].type + "'";
            if (i == 0) strForm += " selected ";
            strForm += ">" + array[i].label + "</option>";
        }
        strForm += "</select>";

        // SECOND SELECT : Operator selection
        strForm += "<select id='" + idFormSearch + "_select_operator' class='ura_formsearch_select " + getStyle("select", config.uratable) + "'>";
        let arr0 = uraType[array[0].type];
        for (let i = 0; i < arr0.length; i++) {
            var operator = arr0[i];
            strForm += "<option value='" + uraOperator[operator].symbol + "'";
            if (i == 0) strForm += " selected ";
            strForm += ">" + uraOperator[operator].label + "</option>";
        }
        strForm += "</select> &nbsp;";

        // ADD FILTER BUTTON
        strForm += "<button title='Ajouter' id='" + idFormSearch + "_add_search' class='ura_formsearch_btn " + getStyle("button", config.uratable, "add") + "'>" + getBtn("plus") + "</button> &nbsp;";

        // RESET FILTERS BUTTON
        strForm += "<button title='Réinitialiser le formulaire de recherche' id='" + idFormSearch + "_reset_search' class='ura_formsearch_btn " + getStyle("button", config.uratable, "reset") + "'>" + getBtn("reset") + "</button><hr id='" + idFormSearch + "_header_separator' style='display: none'>"

        $("#" + idFormSearch + "_body").prepend(strForm);

        /**
         * EVENTS
         */
        //collapse advanced searching
        $(document).on("click", "#" + idFormSearch + "_header", function () {
            activeCollapse();
        });
        //change list operator when list column change
        $(document).on("change", "#" + idFormSearch + "_select_column", function () {
            var datatype = $(this, "option:selected").val();
            var arr0 = uraType[datatype];
            var strSel = "";
            for (let i = 0; i < arr0.length; i++) {
                var operator = arr0[i];
                strSel += "<option data-uraprop='" + datatype + "' value='" + uraOperator[operator].symbol + "'";
                if (i == 0) strSel += " selected ";
                strSel += ">" + uraOperator[operator].label + "</option>";
            }
            $("#" + idFormSearch + "_select_operator").html(strSel);
        });
        //event add search
        $(document).on("click", "#" + idFormSearch + "_add_search", function () {
            return addSearchItem();
        });
        //reset search
        $(document).on("click", "#" + idFormSearch + "_reset_search", function () {
            $("#" + idFormSearch + "_footer").html("");
            $("#" + idFormSearch + "_view").html("");
            $("#" + idFormSearch + "_footer").hide();
            $("#" + idFormSearch + "_header_separator").hide();
            $("#" + idFormSearch + "_footer_wrapper").hide();
            $("#" + idFormSearch + "_view").hide();
            $("#" + idFormSearch + "_view_wrapper").hide();
            $("#" + idFormSearch + "_submit_button").hide();
            $("#" + idDiv).DataTable().search("").draw();
        });
        //delete an input
        $(document).on("click", ".ura_btn_del", function () {
            $("#ura_row_" + idDiv + "_" + $(this).attr("data-uratar")).remove();//remove element
            $($("#" + idFormSearch + "_footer")[0].firstChild).children(".ura_formsearch_orand").remove();//if element is first remove second element and/or select
            var childs = $("#" + idFormSearch + "_footer").children();
            if (childs.length == 0) {//if no elements hide button
                $("#" + idFormSearch + "_submit_button").hide();
                $("#" + idFormSearch + "_view").hide();
                $("#" + idFormSearch + "_view_wrapper").hide();
                $("#" + idFormSearch + "_footer").hide();
                $("#" + idFormSearch + "_footer_wrapper").hide();
                $("#" + idFormSearch + "_header_separator").hide();
            }
            searchSequence();
        });
        //when value of input change
        $(document).on("keyup", ".ura_formsearch_input", function () {
            console.log('search');
            //alert("keyup")
            searchSequence();
        });
        $(document).on("focusout", ".ura_formsearch_input", function () {
            console.log('search');
            //alert("keyup")
            searchSequence();
        });
        //when value of select change
        $(document).on("change", ".ura_formsearch_inpsel", function () {
            //alert("change")
            console.log('change');
            searchSequence();
        });
        // when click search button
        $(document).on("click", "#" + idFormSearch + "_submit_button", function () {
            //alert("button")
            search();
        });

        //after tri column @TODO
        var timeee = 1000;
        $(document).on("click", ".sorting", function () {
            sleep(timeee).then(function () {
                search();
            });
        });
        $(document).on("click", ".sorting_desc", function () {
            sleep(timeee).then(function () {
                search();
            });
        });
        $(document).on("click", ".sorting_asc", function () {
            sleep(timeee).then(function () {
                search();
            });
        });

    }
}