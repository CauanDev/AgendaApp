import { computed, ref } from 'vue';
import { defineStore } from 'pinia';
import createAxiosInstance from '../services/http';

export const useAuth = defineStore('auth', () => {

    // Ref para armazenar token e user (começando como null)
    const token = ref(false);
    const user = ref(false);
    const isAuth = ref(false);
    const http = createAxiosInstance();

    // Função para obter o token do localStorage
    function getToken() {
        const storedToken = localStorage.getItem("token");
        return storedToken ? storedToken : null;
    }

    // Função para obter o user do localStorage
    function getUser() {
        const storedUser = localStorage.getItem("user");
        return storedUser ? JSON.parse(storedUser) : null;
    }

    // Função para definir o token no localStorage
    function setToken(tokenValue) {
        localStorage.setItem('token', tokenValue);
        token.value = tokenValue;
    }

    // Função para definir o user no localStorage
    function setUser(userValue) {
        localStorage.setItem('user', JSON.stringify(userValue));
        user.value = userValue;
    }

    // Função para definir o status de autenticação
    function setIsAuth(auth) {
        isAuth.value = auth;
    }

    function isAuthenticated() {
        user.value = getUser()
        token.value = getToken()
        return token.value !== null && user.value !== null;
    }

    // Computed para obter o nome completo do usuário
    const fullName = computed(() => {
        if (user.value) {
            return user.value.firstName + ' ' + user.value.lastName;
        }
        return '';
    });

    // Função para verificar o token
    async function checkToken() {
        try {
            const storedToken = getToken();
            if (!storedToken) {
                throw new Error('Token não encontrado');
            }

            const { data } = await http.post('/auth/me', { 'token': storedToken });
            return data;
        } catch (error) {
            clear();
            console.error('Erro ao verificar o token:', error);
        }
    }

    // Função para limpar os dados do usuário e token
    async function clear() {
        try {
            if (token.value) {
                await http.post('/auth/logout', { token: token.value });
            }
        } catch (error) {
            console.error('Erro ao fazer logout na API:', error);
        } finally {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            token.value = null;
            user.value = null;
            isAuth.value = false;
        }
    }
    

    return {
        token,
        user,
        setToken,
        setUser,
        checkToken,
        isAuthenticated,
        fullName,
        clear,
        setIsAuth,
        isAuth,
        getToken,
        getUser
    }

});
