@extends('layout.blank.index')

@section('content')
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
@endsection

@section('javascript')
    <script type="text/javascript">

        Componente.scope(function(){ //escopando as variáveis para não conflitarem com possíveis outros componentes do mesmo tipo abertos na tela
            var componente = Componente.AnimalFactory.get('{!! $name !!}');

            var colunas = [
                {
                    name : 'nomeAnimal',
                    data : function(obj){
                        if(!obj.nomeAnimal) return ' - ';
                        return '<label for="_companimal_{!! $name !!}_' + obj.codigoAnimal + '">' + obj.nomeAnimal + '</label>';
                    }
                },
                {name : 'siglaTipoSangue', data : 'siglaTipoSangue'},
                {name : 'mascaraRegistro', data : 'mascaraRegistro'},
                {name : 'nroPartTransferenciaProprietario', data : 'nroPartTransferenciaProprietario'},
                {name : 'idadeAnimalAreviada', data : function(obj){
                    if(!obj.idadeAnimal) return 'N/D';
                    return '<span data-toggle="tooltip" data-placement="top" data-original-title="' + obj.idadeAnimal + '">' + obj.idadeAnimalAbreviada + '</span>';
                }}
            ];

            @if(isset($multiple) && $multiple)
                colunas.unshift({
                    name : 'AnimalConsulta.codigoAnimal',
                    data : function(obj){
                        var idfield = '_companimal_{!! $name !!}_' + obj.codigoAnimal;
                        if(componente.dataTableInstance.DataTableQuery().isItemChecked(obj.codigoAnimal)) {
                            return '<input id="' + idfield + '" class="checkbox checkbox-primary chkSelecionarAnimal" type="checkbox" checked="checked" value="' + obj.codigoAnimal + '">';
                        }
                        return '<input id="' + idfield + '" class="checkbox checkbox-primary chkSelecionarAnimal" type="checkbox" value="' + obj.codigoAnimal + '">';
                    }
                });
            @else
                colunas.push({
                    name : 'AnimalConsulta.codigoAnimal',
                    data : function(obj){
                        var idfield = '_companimal_{!! $name !!}_' + obj.codigoAnimal;
                        return '<button id="' + idfield + '" class="btn btn-sm btn-primary btnSelecionarAnimal" codigo="' + obj.codigoAnimal + '">Selecionar</button>';
                    }
                });
            @endif


            componente.dataTableInstance = $(".comp-tbl-search-animal")
                    .on('xhr.dt', function(){
                        setTimeout(function(){
                            $("[data-toggle=tooltip]").tooltip();
                        }, 0);
                    })
                    .CustomDataTable({
                        name : '_dataTableQuery{!! $name !!}',
                        queryParams : {
                            idField : 'codigoAnimal',
                            filtersCallback : function(obj){
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