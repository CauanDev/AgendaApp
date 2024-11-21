<template>
    <section class="flex justify-center items-center w-full h-screen">
        <div class="flex flex-col text-center shadow-md  rounded-xl px-6 py-12 bg-white">
            <LoginForm ref="form" />
            <Button label="Acessar" severity="contrast" icon="pi pi-user" :loading="loading" @click="submit" />
            <p class="text-[11px] cursor-pointer mt-1" 
            @click="()=>this.$router.push({ path: '/register' })" 
            >Criar Conta</p>
        </div>

    </section>
</template>
<script>
import Button from 'primevue/button';
import LoginForm from '../components/Forms/LoginForm.vue';
import { inject } from 'vue';
import { useAuth } from '../stores/auth';
import createAxiosInstance from "../services/http";

const http = createAxiosInstance();


export default {
    components: { LoginForm, Button },

    setup() {
        const toast = inject('toast');
        return {
            toast
        };
    },

    beforeCreate() {
        this.$router.replace('/login');
        if (this.$route.params.toast === '0') this.toast.showToast('Faça o login novamente', 'error');
    },

    data() {
        return {
            loading: false
        }
    },

    methods: {
        async submit() {
            const auth = useAuth();
            const login = this.$refs.form.getValues();
            
            if (this.isValidEmail(login.email)) {
                try {
                    this.loading = true;
                    const data = await http.post('/auth/login', login);     
                    auth.setToken(data.data.token);
                    auth.setUser(data.data.user);
                    auth.setIsAuth(true)
                    this.toast.showToast(data.data.message, 'success')
                    this.$router.push({ path: '/home' })
                } catch (error) {
                    this.toast.showToast(error.response.data.message, 'error');
                } finally {
                    this.loading = false;
                }
            }


        },

        isValidEmail(value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(value);
        }
    },



}

</script>