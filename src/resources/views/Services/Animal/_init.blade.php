@section('ComponentJavascript')
    @parent

    <script type="text/javascript">
        (function(){
            if(Componente.Animal) return; //hack porco até eu descobrir pq esse script é carregado 2x não importa o q eu faça...

            //aki é certeza que o namespace Componente existe, portanto:
            Componente.Animal = function(name, attributes){
                this.attributes             = attributes;
                this.name                   = name;
                this.dataTableInstance      = null;
                this.urlFindBy              = '/vendor-girolando/componentes/animal/findby';
                var self = this;
                var titulo = attributes['data-title'] || '{!! trans('ComponenteAnimal::Services/Componentes/AnimalService._init.titModal') !!}';

                /*** Lógica de construtor do componente ****/


                /**** Lógica customizada do componente ******/
                /**
                 * Esse é o método principal do componente. Ele é disparado quando a pessoa clicar no Search Button e não for impedida por algum evento.
                 */
                this.onSearchButtonClick = function(){
                    System.beginLoading($("body"), '{!! trans('ComponenteAnimal::Services/Componentes/AnimalService._init.msgBuscando') !!}');
                    self.selectedItems.clear();
                    $.get('/vendor-girolando/componentes/animal', this.getAttributes(), function(response){
                        System.stopLoading();
                        self.modalInstance = Alert.bigConfirm(response, function(ok){
                            if(!ok){
                                self.modalInstance.modal('hide');
                                self.triggerEvent(Componente.EVENTS.ON_FINISH, null);
                                return;
                            }
                            //se chegou aqui é pq é multiple. No single o botão de ok não aparece
                            if(!self.isUsingQuery){
                                self.modalInstance.modal('hide');
                                self.triggerEvent(Componente.EVENTS.ON_FINISH, self.selectedItems.values());
                                return;
                            }
                        }, titulo);


                        if(!self.getAttributes().multiple){
                            $("[data-bb-handler=confirm]", self.modalInstance).hide();
                        }

                    }).fail(function(){
                        System.stopLoading();
                        Alert.error('{!! trans('ComponenteAnimal::Services/Componentes/AnimalService._init.errOpenModal') !!}');
                    });
                }

            };

            Componente.AnimalFactory = Componente.newFactory({
                initialize : function(uniqueItem){
                    var self = this;
                    $("componente[type=animal]").each(function(){
                        self._initialize($(this), Componente.Animal); //método do pai, adicionado pelo newFactory, inicializa o componente
                    })
                }
            });

            Componente.AnimalFactory.initialize();
        })();
    </script>
@endsection
