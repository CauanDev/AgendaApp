<template>
    <div class="flex flex-col gap-4 p-4 text-center mt-[60px]">
        <h1 class="font-jetbrains font-bold text-4xl mb-4">Cadastrar<br>Atividade</h1>
        <div class="flex flex-col gap-4 w-full max-w-md">
            <TextInput id="name" v-model="name" placeholder="Digite o nome da Atividade" required />

            <label for="priority" class="font-bold mb-1">Selecione uma Prioridade</label>
            <select id="priority" v-model="priority" class="border p-2 rounded">
                <option value="baixa">Baixa</option>
                <option value="media">Média</option>
                <option value="alta">Alta</option>
            </select>

            <label for="color" class="font-bold mb-1">Selecione a cor da Atividade</label>
            <ColorPicker id="color" v-model="color" />

            <label for="date" class="font-bold mb-1">Data da Atividade</label>
            <DatePicker id="date" v-model="date" :minDate="minDate" showIcon fluid iconDisplay="input"
                dateFormat="dd/mm/yy" />

            <label for="participants" class="font-bold mb-1">Digite os participantes da Atividade</label>
            <AutoComplete id="participants" v-model="participants" multiple fluid optionLabel="name"
                :suggestions="suggestions" @complete="search" />
        </div>
    </div>
</template>

<script>
import TextInput from '../Inputs/TextInput.vue';
import AutoComplete from 'primevue/autocomplete';
import ColorPicker from 'primevue/colorpicker';
import DatePicker from 'primevue/datepicker';
import createAxiosInstance from "../../services/http";
import { useAuth } from '../../stores/auth';


const http = createAxiosInstance();
export default {
    components: { TextInput, AutoComplete, ColorPicker, DatePicker },
    data() {
        const auth = useAuth();

        return {
            auth,
            name: "",
            priority: "media",
            color: "#ff0000",
            date: null,
            participants: [],
            suggestions: [],
            allUsers: [],
            minDate: new Date()
        };
    },
    methods: {

        getValues() {

            return {
                name: this.name,
                priority: this.priority,
                color: this.color,
                participants: this.participants,
                usuarioLogado: this.auth.getUser(),
                data: this.date
            }
        },

        async fetchInitialUsers() {
            try {
                const data = await http.get('/get-users/'+this.auth.user.id)
                this.allUsers = data.data;
            } catch (error) {
                console.error('Erro ao buscar usuários iniciais:', error);

            }

        },


        search(event) {
            const query = event.query.toLowerCase();
            this.suggestions = this.allUsers.filter(user =>
                user.name.toLowerCase().includes(query)
            );
        },
    },
    mounted() {
        this.fetchInitialUsers();
    },
};
</script>
