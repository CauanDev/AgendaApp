<script>
import TarefaForm from '../components/Forms/TarefaForm.vue';
import Button from 'primevue/button';
import createAxiosInstance from "../services/http";
const http = createAxiosInstance();
import { inject } from 'vue';

export default {
    components: {
        TarefaForm, Button
    },

    setup() {
        const toast = inject('toast');
        return {
            toast
        };
    },

    data() {
        return {
            loading: false
        }
    },

    methods: {
        async submit() {
            this.loading = true
            const dados = this.$refs.form.getValues()
            if (!dados.name || !dados.data) {
                this.toast.showToast("Preencha Corretamente", 'error');
            }
            else {
                try {
                    const data = await http.post('/create-atividade', dados)
                    this.toast.showToast(data.data.message, 'success')
                } catch (error) {
                    this.toast.showToast(error.response.data.message, 'error');
                }
            }

            this.loading = false
        }
    }
}
</script>

<template>
    <div class="flex flex-col justify-center items-center">
        <TarefaForm ref="form" />
        <Button label="Salvar Atividade" severity="contrast" icon="pi pi-calendar" class="mb-2" :loading="loading"
            @click="submit" />

    </div>
</template>