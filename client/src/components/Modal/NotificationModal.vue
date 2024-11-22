<template>
    <div class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" 
    role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
    tabindex="-1">
        <p>Quantidade de Tarefas para Hoje: {{this.count}}</p>
    </div>


</template>
<script>
import createAxiosInstance from '../../services/http';
import { useAuth } from '../../stores/auth';
const http = createAxiosInstance(); 
export default{
    data(){
        return{
            count: 0
        }
    },
    methods:{
        async getAtividades(){
            const auth = useAuth();
            try {
                const data = await http.get('/get-all-tarefas/'+auth.user.id);
                if(data.data&&data.data>0) this.count = data.data
               
            } catch (error) {
                console.log(error)
            }
        }
    },
    created(){
        this.getAtividades()
    }
}
</script>