## Componente Animal

### Utilização

  ```html
    {!! ComponenteAnimal::init() !!} <!-- IMPORTANTE -->
    
    <button class="btnBuscaAnimal">Buscar Animal</button>
    <componente type="animal" name="codigoAnimal" dispatcher-button=".btnBuscaAnimal" />
    
    <script>
      const componente = Componente.AnimalFactory.get('codigoAnimal');
      componente.addEventListener(Componente.EVENTS.ON_FINISH, function(animal) {
        console.log('O animal selecionado foi: ', animal);
      });
    </script>
  ```
  
  ### Método findBy

  ```javascript
    const componente = Componente.AnimalFactory.get('nome-do-seu-componente');
    const animalEspecifico = await componente.findBy({registroAnimal: '1010-AX'}); 
  ```
