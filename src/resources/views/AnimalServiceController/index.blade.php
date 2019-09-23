{!! ComponentePessoa::init() !!}
@extends('layout.blank.index')

@section('content')
    <fieldset>
        <legend>Filtros</legend>
        <div class="row form">
            <div class="col-md-12">
                <label class="col-md-4 control-label">Proprietário</label>
                <div class="col-md-8 input-group">
                    <input type="text" class="form-control {!! $name !!}nnnnnomePessoa" value="{!! $usuario->nomePessoa !!}">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-white {!! $name !!}_subcompCodigoPessoa__componenteCriador" data-toggle="tooltip">
                                <span class="fa fa-search"></span>&nbsp;
                        </button>
                        <button type="button" class="btn btn-white {!! $name !!}btnlimpar" data-toggle="tooltip">
                                <span class="fa fa-trash-o"></span>&nbsp;
                        </button>
                        <componente filter-isCriador="1" type="pessoa" name="{!! $name !!}_subcompCodigoPessoa" dispatcher-button=".{!! $name !!}_subcompCodigoPessoa__componenteCriador" />
                    </span>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="companimal_{!! $name !!}">
        <table class="table tooltip-demo table-stripped table-hover comp-tbl-search-animal" data-page-size="10" data-filter=#filter>
            <thead>
            @if(isset($multiple) && $multiple)
                <th>#</th>
            @endif
            <th>Animal</th>
            <th>G. Sang.</th>
            <th>Registro</th>
            <th>Botton Nr. Part.</th>
            <th>Idade</th>

            @if(!(isset($multiple) && $multiple))
                <th>Selecionar</th>
            @endif
            </thead>
        </table>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        var tabela = null;

        Componente.scope(function(){ //escopando as variáveis para não conflitarem com possíveis outros componentes do mesmo tipo abertos na tela
            Componente.PessoaFactory.initialize();
            var componente = Componente.AnimalFactory.get('{!! $name !!}');
            var componentePessoa = Componente.PessoaFactory.get('{!! $name !!}_subcompCodigoPessoa');
            var pessoaSelecionada = {!! $usuario->codigoPessoa !!};

            @if ($usuario->is('Funcionario'))
                pessoaSelecionada = null;
                $(".{!! $name !!}nnnnnomePessoa").val('');
            @endif

            componentePessoa.addEventListener(Componente.EVENTS.ON_FINISH, function(pessoa) {
                if (!pessoa) return;
                console.log('PESSOA SELECIONADA => ', pessoa);
                pessoaSelecionada = pessoa.id;
                $(".{!! $name !!}nnnnnomePessoa").val(pessoa.nomePessoa);
                System.beginLoading($('.companimal_{!! $name !!}').parent().parent());
                componente.dataTableInstance.draw();
            });

            $(".{!! $name !!}btnlimpar").on('click', function() {
                pessoaSelecionada = null;
                $(".{!! $name !!}nnnnnomePessoa").val('');
                System.beginLoading($('.companimal_{!! $name !!}').parent().parent());
                componente.dataTableInstance.draw();
            });

            var colunas = [
                {
                    name : 'nomeAnimal',
                    data : function(obj){
                        if(!obj.nomeAnimal) return ' - ';
                        if(obj.statusAnimal == 0){
                            obj.nomeAnimal = '<span class="label label-danger" data-toggle="tooltip" data-placement="top" data-original-title="' + obj.descTipoBaixa + '">' + obj.nomeAnimal + '</b></span>';
                        }
                        return '<label for="_companimal_{!! $name !!}_' + obj.id + '">' + obj.nomeAnimal + '</label>';
                    }
                },
                {name : 'idTipoSangue', data : 'idTipoSangue'},
                {name : 'registro', data: function(animal) {
                    var tipo = 'OUTROS';
                    switch (animal.tipoRegistro) {
                        case 'BASE': tipo = 'REGISTRO BASE'; break;
                        case 'RGDGD': tipo = 'DEFINITIVO GD'; break;
                        case 'RGD': tipo = 'DEFINITIVO'; break;
                        case 'RF': tipo = 'FUNDAÇÃO'; break;
                        case 'RGN': tipo = 'NASCIMENTO'; break;
                        case 'RGNANT': tipo = 'NASCIMENTO ANTIGO'; break;
                    }
                    return '<span data-toggle="tooltip" data-placement="top" data-original-title="' + tipo + '">' + animal.registro + '</span>';
                }},
                {name : 'numeroParticular', data : 'numeroParticular'},
                {name : 'idadeAnimalAreviada', data : function(obj){
                    if(!obj.idadeAnimal) return 'N/D';
                    return '<span data-toggle="tooltip" data-placement="top" data-original-title="' + obj.idadeAnimal + '">' + obj.idadeAnimalAbreviada + '</span>';
                }}
            ];

            @if(isset($multiple) && $multiple)
                colunas.unshift({
                name : '{!! $tableName !!}.id',
                data : function(obj){
                    var idfield = '_companimal_{!! $name !!}_' + obj.id;
                    if(componente.dataTableInstance.DataTableQuery().isItemChecked(obj.id)) {
                        return '<input id="' + idfield + '" class="checkbox checkbox-primary chkSelecionarAnimal" type="checkbox" checked="checked" value="' + obj.id + '">';
                    }
                    return '<input id="' + idfield + '" class="checkbox checkbox-primary chkSelecionarAnimal" type="checkbox" value="' + obj.id + '">';
                }
            });
            @else
                colunas.push({
                name : '{!! $tableName !!}.id',
                data : function(obj){
                    var idfield = '_companimal_{!! $name !!}_' + obj.id;
                    return '<button id="' + idfield + '" class="btn btn-sm btn-primary btnSelecionarAnimal" codigo="' + obj.id + '">Selecionar</button>';
                }
            });
            @endif


                    componente.dataTableInstance = $(".comp-tbl-search-animal")
                    .on('xhr.dt', function(){
                        System.stopLoading();
                        setTimeout(function(){
                            $("[data-toggle=tooltip]").tooltip();
                            if (componente.dataTableInstance.rows().data().length === 1) {
                                @if(!(isset($multiple) && $multiple))
                                    $(".btnSelecionarAnimal", componente.modalInstance).trigger('click');  
                                @endif
                            }
                        }, 0);
                    })
                    .CustomDataTable({
                        name : '_dataTableQuery{!! $name !!}',
                        queryParams : {
                            idField : '{!! $tableName !!}.id',
                            filtersCallback : function(obj){
                                if (pessoaSelecionada) {
                                    obj.idProprietario = pessoaSelecionada;
                                    console.log('setei o proprietario na busca: ', obj);
                                } else {
                                    delete obj.idProprietario;
                                }
                                @if($_attrFilters)
                                        @foreach($_attrFilters as $attr => $val)
                                        obj['{!! $attr !!}'] = '{!! $val !!}';
                                @endforeach
                                @endif
                            }
                        },
                        columns : colunas,
                        ajax : {
                            url : '/vendor-girolando/componentes/animal',
                            data : function(obj){
                                obj.name = '{!! $name !!}';
                            }
                        }
                    });
                    $(".companimal_{!! $name !!} .dataTables_filter input").unbind().on('keydown', function(e) {
                        if (e.keyCode == 13) {
                            System.beginLoading($('.companimal_{!! $name !!}').parent().parent());
                            componente.dataTableInstance.search($(this).val());
                            componente.dataTableInstance.draw();
                        }
                    });


            @if(isset($multiple) && $multiple)
                componente.modalInstance.delegate('.chkSelecionarAnimal', 'change', function(){
                    var val = $(this).val();
                    var obj = componente.dataTableInstance.row($(this).closest('tr'));
                    if(!componente.dataTableInstance.DataTableQuery().isChecked(val)){
                        componente.selectedItems.put(val, obj.data());
                        return componente.dataTableInstance.DataTableQuery().addItem(val);
                    }
                    componente.selectedItems.remove(val);
                    return componente.dataTableInstance.DataTableQuery().removeItem(val);
                });
            @else
                componente.modalInstance.delegate('.btnSelecionarAnimal', 'click', function(){
                var animal = componente.dataTableInstance.row($(this).closest('tr')).data();
                componente.selectedItems.clear();
                componente.selectedItems.put($(this).attr('codigo'), animal);
                componente.modalInstance.modal('hide');
                componente.triggerEvent(Componente.EVENTS.ON_FINISH, animal);
            });
            @endif
        });
    </script>
@endsection
