
    window.onload = function() {
        if ($('.is-invalid').length > 0) {
            selectModuleType('<?php echo $selectedModuleType ?>', '<?php echo $selectedModule ?>', '<?php echo $selectedRevision ?>');

        }
    }

    function selectModuleType(typeSelected = '', moduleSelected = '', revisionSelected = '') {
        let button = $('#button_dl');
        button.attr('disabled', true);

        let dd_type = $('#dd_type');
        let type = $('#dd_type').val();
        let module = '';
        let revision = '';
        if (typeSelected != '') {
            type = typeSelected;
            module = moduleSelected;
            revision = revisionSelected;
        } else {
            emptyFields();
        }

        let dd_modules = $('#dd_modules');
        let dd_rev = $('#dd_revisions');
        let input_name = $('#text_name');
        let input_rl = $('#text_relnote');
        let input_version = $('#text_version');


        if (type != 0) {

            let data = {
                'type': type
            };
            let options = {
                'url': '<?php echo base_url('RF-BackOffice/selectModuleType')?>',
                'type': 'post',
                'data': data,
                'dataType': 'json',
                'callBack': function(ret) {
                    if (ret['status'] === 'ok') {

                        dd_modules.removeAttr('disabled');
                        dd_rev.attr('disabled', true);

                        let modules = ret['response']['modules'];



                        dd_modules.find('option')
                            .remove()
                            .end();

                        dd_rev.find('option')
                            .remove()
                            .end();

                        var i = 0;
                        $.each(modules, function(key, value) {
                            dd_modules.append($('<option></option>')
                                .attr('value', key)
                                .text(value));
                            i++;
                        });

                        if (i == 0) {
                            dd_modules.attr('disabled', true);
                            dd_modules.append($('<option></option>')
                                .text('Aucun module disponible'));
                        } else if (i === 1) {
                            selectModule(type, module, revision);
                        } else {
                            dd_modules.prepend($('<option></option>')
                                .attr('selected', true)
                                .text('Veuillez sélectionner un module')
                                .attr('value', 0));

                            if (typeSelected != '') {
                                dd_modules.val(module);
                                //$('#dd_modules option[value=module]').attr('selected', 'selected');
                                selectModule(type, module, revision);
                            }
                        }

                    } else {
                        alert("Une erreur s'est produite : " + ret['errorThrown'] + " \n " + ret['textStatus'])
                        }
                    }
                };
                sendAjax(options);
            }
            else {
                dd_modules.attr('disabled', true);
                dd_modules.val('');
                dd_rev.attr('disabled', true);
                dd_rev.val('');
            }
        }

        function selectModule(typeSelected = '', moduleSelected = '', revisionSelected = '') {
            let button = $('#button_dl');
            let dd_rev = $('#dd_revisions');
            let input_name = $('#text_name');
            let input_rl = $('#text_relnote');

            let type = $('#dd_type').val();
            let module = $('#dd_modules').val();
            if (moduleSelected != '') {
                type = typeSelected;
                module = moduleSelected;
                revision = revisionSelected;
            } else {
                emptyFields();
            }

            if (module != 0) {
                let data = {
                    'module': module,
                    'type': type
                };
                let options = {
                    'url': '<?php echo base_url('RF-BackOffice/selectModule')?>',
                    'type': 'post',
                    'data': data,
                    'dataType': 'json',
                    'callBack': function(ret) {
                        if (ret['status'] === 'ok') {
                            button.attr('disabled', false)
                            dd_rev.removeAttr('disabled');
                            let revisions = ret['response']['revisions'];

                            dd_rev.find('option')
                                .remove()
                                .end();

                            var i = 0;
                            $.each(revisions, function(key, value) {

                                dd_rev.append($('<option></option>')
                                    .attr('value', value.num)
                                    .text(value.msg));
                                i++;
                            });

                            if (i == 0) {
                                dd_rev.attr('disabled', true);
                                dd_rev.append($('<option></option>')
                                    .text('Aucune révision disponible'));
                            } else if (i === 1) {
                                fillInputs(moduleSelected, revisionSelected);
                            } else {
                                fillInputs(moduleSelected, revisionSelected);
                            }

                        } else {
                            alert("Une erreur s'est produite : " + ret['errorThrown'] + " \n " + ret['textStatus'])
                            }
                        }
                    };
                    sendAjax(options);
                }
                else {
                    button.attr('disabled', true)
                    dd_rev.attr('disabled', true);
                    dd_rev.val('');
                }
            }

            function fillInputs(moduleSelected = '', revisionSelected = '') {
                var input_name = $('#text_name');
                var input_rl = $('#text_relnote');
                let dd_revisions = $('#dd_revisions');

                let revision = dd_revisions.val();
                let module = $('#dd_modules').val();

                if (moduleSelected != '') {
                    module = moduleSelected;
                    revision = revisionSelected;
                } else {
                    emptyFields();
                }

                let data = {
                    'module': module,
                    'revision': revision
                };

                let options = {
                    'url': '<?php echo base_url('RF-BackOffice/fillInputs')?>',
                    'type': 'post',
                    'data': data,
                    'dataType': 'json',
                    'callBack': function(ret) {
                        if (ret['status'] === 'ok') {
                            if (moduleSelected != '') {
                                dd_revisions.val(revision);
                            } else {
                                input_name.val(ret['response']['infos']['name']);
                                input_rl.val(ret['response']['infos']['rl']);
                            }
                        } else {
                            alert("Une erreur s'est produite : " + ret['errorThrown'] + " \n " + ret['textStatus'])
                            }
                        }
                    };
                    sendAjax(options);
                }

                function emptyFields() {
                    let name = $('#text_name');
                    let desc = $('#text_desc');
                    let version = $('#text_version');
                    let rl = $('#text_relnote');
                    let m_rf = $('#multi_rf_dep');
                    let m_pd = $('#multi_proj_dep');

                    name.val('');
                    desc.val('');
                    version.val('');
                    rl.val('');

                    m_rf[0].selectedIndex = -1;

                    m_pd[0].selectedIndex = -1;
                }

                function downloadZip() {
                    window.location.assign('<?php echo base_url('RF-BackOffice/ModuleDownloader')?>');
                }