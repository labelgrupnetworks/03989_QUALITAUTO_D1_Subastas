<!DOCTYPE html>
<html>

<head>
    <title>My first Vue app</title>
    {{--<script src="https://unpkg.com/vue"></script>--}}
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
</head>

<body>
    {{-- componente texto --}}
    <div id="app">
        <p>@{{ message }}</p>
        <input style="color: @{{color}}" v-model="message">
    </div>

    {{-- componente bucle --}}
    <div id="app-4">
		<p>@{{ messages }}</p>
        <ol>
            <li v-for="todo in todos">
                @{{ todo.text }}
            </li>
        </ol>

        {{-- Controles para editar bucle --}}
        <div>
            <label for="entrada"></label>
            <input v-model="messages" type="text" id="entrada">
            <button v-on:click="entrada_texto">añadir texto</button>
        </div>
    </div>



    <script>
        //componente texto
        var app = new Vue({
            el: '#app',
            data: {
                message: 'Hello Vue!',
				color: 'red'
            }
        });

        //componente bucle
        var app4 = new Vue({
            el: '#app-4',
            data: {
                todos: [
                    { text: 'Learn JavaScript' },
                    { text: 'Learn Vue' },
                    { text: 'Build something awesome' }
                ],
                messages: ''
            },
            methods: {
                entrada_texto: function(){
                    this.todos.push({ text: this.messages })
                    this.messages = '';
                }
            }
        });

    </script>
</body>

</html>
