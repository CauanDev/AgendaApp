<template>
    <TokenForm ref="form" @submit="submit" />

    <Dialog v-model:visible="verificado" modal header="Verifique Sua Conta" :style="{ width: '25rem' }">
        <div class="p-6 text-center">
            <p class="mb-4 font-bold">
                Verificação realizada com sucesso. Faça login no sistema!
            </p>
            <div class="flex justify-center">
                <Button label="Logue aqui" icon="pi pi-check" severity="contrast" raised
                    @click="() => this.$router.push({ path: '/login' })"></Button>
            </div>
        </div>
    </Dialog>
</template>

<script>
import TokenForm from '../components/Forms/TokenForm.vue';
import createAxiosInstance from "../services/http";
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import { inject } from 'vue';

const http = createAxiosInstance();
export default {
    components: { TokenForm, Button, Dialog },

    setup() {
        const toast = inject('toast');
        return {
            toast
        };
    },

    data() {
        return {
            verificado: false
        }
    },

    methods: {
        async submit() {
            const token = this.$refs.form.getValues();
            try {
                this.loading = true;
                const data = await http.post('/authenticated-user', token);
                this.toast.showToast(data.data.message, 'success')
                this.verificado = true
            } catch (error) {
                console.error(error);
                this.toast.showToast('Usuário não encontrado.', 'error');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
