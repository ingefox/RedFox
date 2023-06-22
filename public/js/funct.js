/**
 * Method used to send AJAX requests
 * @param {Array} data Array containing the parameters for the AJAX request (url, data, type, dataType, callback)
 * @returns {{}} Response of the AJAX request
 */
function sendAjax(data) {
    let ret = {};
    if ("callBack" in data) {
        let dataToSend = {};
        if ("data" in data) dataToSend = data['data'];
        $.ajax({
            url: data['url'],
            type: data['type'],
            dataType: data['dataType'],
            data: dataToSend,
            success: function (response) {
                ret['status'] = "ok";
                ret['response'] = response;
                data["callBack"](ret);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                ret['status'] = "ko";
                ret['errorThrown'] = errorThrown;
                ret['textStatus'] = textStatus;
                data["callBack"](ret);
            }
        })
    } else {
        ret['status'] = "ko";
        ret['errorThrown'] = "No callback given";
        return ret;
    }
}

/**
 * Display a "Feature is currently in development" alert message
 */
function unavailableFeature(){
    displayToast('En cours de développement','Cette fonctionnalité est en cours de développement et sera disponible prochainement.',3);
}

/**
 * Display a toast message
 * @param icon {string|null} Icon to display in the header of the toast
 * @param title {string} Title of the toast
 * @param message {string} Actual message of the toast
 * @param type {int} Type of toast (see TOAST_* PHP constants)
 * @param delay {int} Delay before hiding the toast element (0 = no auto hiding)
 */
function displayToast(title,message,type,delay = 0,icon = null)
{
    var getUrl = window.location;
    var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

    toastIndex++;

    let data = {
        icon        : icon,
        title       : title,
        message     : message,
        type        : type,
        autoHide    : (delay !== 0),
        toastIndex  : toastIndex
    }

    let options = {
        'url': baseUrl+'/displayToast',
        'type': 'post',
        "dataType": "json",
        "data": data,
        'callBack': function (ret) {
            if (ret['status'] === 'ok') {
                $('#toast-container').append(ret['response']);
                let newToast = $('#toast'+toastIndex);
                newToast.toast({
                    animation : true,
                    delay : delay
                });
                newToast.toast('show');
            } else alert("Une erreur s'est produite : " + ret['errorThrown'] + " \n" + ret['textStatus'])
        }
    };
    sendAjax(options);
}

/**
 * Load a View retrieved from an AJAX request into the modal content div of the V_layout view and toggle the modal display
 * @param {string} url AJAX request URL
 * @param {string} title Title of the modal
 * @param {Array} data Data needed to be sent in the AJAX request
 * @param {boolean} large Large modal
 */
function openModal(url, title, data = null, large = true) {
    let modal = $('#RF_MODAL');
    let modalBody = $('#RF_MODAL_BODY');
    let modalTitle = $('#RF_MODAL_TITLE');
    let modalDialog = $('#RF_MODAL_DIALOG');
    let options = {
        'url': url,
        'type': 'post',
        "dataType": "json",
        "data": data,
        'callBack': function (ret) {
            if (ret['status'] === 'ok') {
                if (large) {
                    modalDialog.addClass('modal-lg');
                } else {
                    modalDialog.removeClass('modal-lg');
                }
                modal.modal('handleUpdate');
                modalTitle.html(title);
                modalBody.html(ret['response']);
                modal.modal('show');
            } else alert("Une erreur s'est produite : " + ret['errorThrown'] + " \n" + ret['textStatus'])
        }
    };
    sendAjax(options)
}

/**
 * Display a modal with title and content in parameters
 * @returns void
 * @param title
 * @param content
 */
function openModalText(title, content) {
    let modal = $('#RF_MODAL');
    let modalBody = $('#RF_MODAL_BODY');
    let modalTitle = $('#RF_MODAL_TITLE');
    modalTitle.html(title);
    modalBody.html(content);
    modal.modal('show');
}

/**
 * Load a View retrieved from an AJAX request into a specific HTML element
 * @param {string} url AJAX request URL
 * @param {Array} data Data needed to be sent in the AJAX request
 * @param {jQuery} element jQuery object representing the HTML element to be filled
 */
function loadHTML(url, data, element) {
    let options = {
        'url': url,
        'type': 'post',
        "dataType": "json",
        "data": data,
        'callBack': function (ret) {
            if (ret['status'] === 'ok') {
                element.html(ret['response']);
            } else alert("Une erreur s'est produite : " + ret['errorThrown'] + " \n" + ret['textStatus'])
        }
    };
    sendAjax(options);
}

/**
 * Check phone number format
 * @param phoneNumber Phone number to verify
 * @param {boolean} required - is field required ?
 * @param {boolean} mustBeCellPhone - boolean - number required to be a cellphone number ?
 * @returns {boolean} Valid format ?
 */
function verifyPhone(phoneNumber, required = false, mustBeCellPhone = false) {
    let valid = true;
    phoneNumber = phoneNumber.replace(/[^0-9]/g, '');
    let startsWith06or07 = (phoneNumber.startsWith('6', 1) || phoneNumber.startsWith('7', 1));
    if ((phoneNumber !== "") && ((!phoneNumber.match(/^0[0-9]{9}$/)))) {
        // phone number is not phone number
        valid = false;
    } else if ((phoneNumber !== "") && (mustBeCellPhone && !startsWith06or07)) {
        // Cell phone has not initial number
        valid = false;
    } else if (required && !(phoneNumber.length > 0)) {
        // field empty but required
        valid = false;
    }
    return valid;
}

/**
 * non disponiblible function
 */
 function nonDisponible(){
    openModalText('En cours de developpement','<div class="alert alert-info"><p>Cet est en cours de développement et sera disponible prochainement.</p></div>');
}

function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function (e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) {
            return false;
        }
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                /*create a DIV element for each matching element:*/
                b = document.createElement("DIV");
                /*make the matching letters bold:*/
                b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].substr(val.length);
                /*insert a input field that will hold the current array item's value:*/
                b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function (e) {
                    /*insert the value for the autocomplete text field:*/
                    inp.value = this.getElementsByTagName("input")[0].value;
                    /*close the list of autocompleted values,
                    (or any other open lists of autocompleted values:*/
                    closeAllLists();
                });
                a.appendChild(b);
            }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function (e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
            increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 38) { //up
            /*If the arrow UP key is pressed,
            decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x) x[currentFocus].click();
            }
        }
    });

    function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }

    function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
        except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }

    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
}

/**
 * Validate all the inputs in a container based on the rules given in their data-validation attribute
 * @param {jQuery} parentContainer
 * @return {array} Array containing the results of the validation process for each input
 */
function validateInputs(parentContainer)
{
    let validationResult = [];

    // Iterating through all inputs of the container
    parentContainer
        .find('input')
        .each(
            function() {
                // Retrieving the data-validation attribute
                let rules = $(this).data('validation');

                if (rules !== undefined) {
                    // Splitting data-validation attribute value in an array
                    rules = rules.split('|');

                    let inputName = $(this).attr('name').toString();

                    // Instantiating an array for the validation results
                    let inputValidity = [];

                    let value = $(this).val();

                    let input = $(this);
                    let isRequired = jQuery.inArray('required', rules);

                    // For each rules set for the current input
                    $.each(rules, function (index, rule) {
                        let valueIsNotEmpty = (value !== '' && value != null && value !== false);
                        isRequired = rule === 'required';

                        switch (true) {
                            // Fails if the field is an empty array, empty string, null or false
                            case rule === 'required':
                                inputValidity.push({
                                    rule: 'required',
                                    result: valueIsNotEmpty
                                });
                                break;
                            // Fails if field contains anything other than numeric characters
                            case rule === 'numeric':
                                if ((isRequired || (!isRequired && valueIsNotEmpty))) {
                                    inputValidity.push({
                                        rule: 'numeric',
                                        result: $.isNumeric(value)
                                    });
                                }
                                break;
                            // Fails if field does not contain a valid email address
                            case rule === 'valid_email':
                                if ((isRequired || (!isRequired && valueIsNotEmpty))) {
                                    let emailRegexp = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                                    inputValidity.push({
                                        rule: 'valid_email',
                                        result: emailRegexp.test(value)
                                    });
                                }
                                break;
                            // Fails if field contain more character than passed in params
                            case rule.indexOf('max_length') >= 0:
                                let max_length = rule.substr(11);
                                max_length = parseInt(max_length.replace(']', ''));
                                if ((isRequired || (!isRequired && valueIsNotEmpty))) {
                                    let isTooLong = value.length > max_length ? false : true;
                                    inputValidity.push({
                                        rule: 'max_length',
                                        length: max_length,
                                        result: isTooLong
                                    });
                                }
                                break;
                            // Fails if field contain less character than passed in params
                            case rule.indexOf('min_length') >= 0:
                                let min_length = rule.substr(11);
                                min_length = parseInt(min_length.replace(']', ''));
                                if ((isRequired || (!isRequired && valueIsNotEmpty))) {
                                    let isTooSmall = value.length < min_length ? false : true;
                                    inputValidity.push({
                                        rule: 'min_length',
                                        length: min_length,
                                        result: isTooSmall
                                    });
                                }
                                break;

                            // Fails if number is superior than the one passed in params
                            case rule.indexOf('more_than') >= 0:
                                let more_than = rule.substr(10);
                                more_than = parseInt(more_than.replace(']', ''));
                                if ((isRequired || (!isRequired && valueIsNotEmpty))) {
                                    let isTooSmall = value < more_than ? false : true;
                                    inputValidity.push({
                                        rule: 'more_than',
                                        length: more_than,
                                        result: isTooSmall
                                    });
                                }
                                break;
                             // Fails if number is inferior than the one passed in params
                             case rule.indexOf('less_than') >= 0:
                                let less_than = rule.substr(10);
                                less_than = parseInt(less_than.replace(']', ''));
                                if ((isRequired || (!isRequired && valueIsNotEmpty))) {
                                    let isTooBig = value > less_than ? false : true;
                                    inputValidity.push({
                                        rule: 'less_than',
                                        length: less_than,
                                        result: isTooBig
                                    });
                                }
                                break;
                            // Fails if field does not contain a valid phone number
                            case rule === 'valid_phone':
                                if ((isRequired || (!isRequired && valueIsNotEmpty))) {
                                    var validPhone = true;

                                    if (valueIsNotEmpty) {
                                        value = value.replace(/[^0-9 \+]/g, '');
                                        value = value.replaceAll(' ', '');
                                        value = value.replaceAll('-', '');

                                        input.val(value);
                                    }

                                    if (isRequired && !valueIsNotEmpty) {
                                        // field empty but required
                                        validPhone = false;
                                    } else if (!value.match(/^(0|\+33|0033)[1-9][0-9]{8}$/)) {
                                        // Phone number is invalid
                                        validPhone = false;
                                    }

                                    inputValidity.push({
                                        rule: 'valid_phone',
                                        result: validPhone
                                    });
                                }
                                break;
                            default:
                                break;
                        }
                    });

                    validationResult.push({
                        name: inputName,
                        validationArray: inputValidity
                    })
                }
            });

    return validationResult;
}

/**
 * Display errors on invalid inputs
 * @param {array} validationArray
 * @return {boolean} Are all inputs valid?
 * TODO : Find a way to replace hardcoded strings by calls to CI language files or equivalent
 */
function displayErrors(validationArray)
{
    // Default return value
    let validInputs = true;

    $.each(validationArray, function(){
        // Preparation of the input name to be interpretable by the JQuery selector
        let name = this.name.replace('[','\\[');
        name = name.replace(']','\\]');

        // Retrieving JQuery selectors of the corresponding input and its 'invalid-feedback' div
        let currentInput = $('[name='+name+']');
        let invalidFeedback = currentInput.parent().find('.invalid-feedback');

        $.each(this.validationArray, function () {
            if (!this.result)
            {
                switch (true){
                    // Rule 'required' not verified
                    case this.rule === 'required':
                        invalidFeedback.html('Ce champ est requis.');
                        break;
                    // Rule 'numeric' not verified
                    case this.rule === 'numeric':
                        invalidFeedback.html('Ce champ ne peut contenir que des chiffres de 0 à 9.');
                        break;
                    // Rule 'valid_email' not verified
                    case this.rule === 'valid_email':
                        invalidFeedback.html('Adresse email invalide.');
                        break;
                    // Rule 'valid_phone' not verified
                    case this.rule === 'valid_phone':
                        invalidFeedback.html('Numéro de téléphone invalide.');
                        break;
                    // Rule 'max_length' not verified
                    case this.rule === 'max_length':
                        invalidFeedback.html('Ce champ ne peut pas comporter plus de '+ this.length +' caractères.');
                        break;
                    // Rule 'more_than' not verified
                    case this.rule === 'more_than':
                        invalidFeedback.html('La valeur du champ ne doit pas être inférieure à '+ this.length +'.');
                        break;
                    // Rule 'less_than' not verified
                    case this.rule === 'less_than':
                        invalidFeedback.html('La valeur du champ ne doit pas être supérieure à '+ this.length +'.');
                        break;
                            // Rule 'min_length' not verified
                    case this.rule === 'min_length':
                        invalidFeedback.html('Ce champ ne peut pas comporter moins de '+ this.length +' caractères.');
                        break;
                    // Unknown rule
                    default :
                        invalidFeedback.html('Champ invalide.');
                        break;
                }

                // At least one input is invalid
                validInputs = false;
                // Displaying the 'invalid-feedback' div
                invalidFeedback.removeClass('d-none');
                // Adding 'is-invalid' class to the input
                currentInput.addClass('is-invalid');
                return false;
            }
        })
    });

    return validInputs;
}


/**
 * language fr to datatatable
 */
 function languageFr(){
    return {
        paginate: {
            previous: "Précédent",
            next: "Suivant"
        },
        lengthMenu : "_MENU_",
        emptyTable: "Aucune donnée disponible dans le tableau",
        info: "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
        infoEmpty: "Affichage de l'élément 0 à 0 sur 0 élément",
        infoFiltered: "(filtré à partir de _MAX_ éléments au total)",
        infoThousands: ",",
        loadingRecords: "Chargement...",
        processing: "Traitement...",
        search: "Rechercher :",
        zeroRecords:"Pas de données trouvées.",
        zeroRecords: "Aucun élément correspondant trouvé",
    };
}
