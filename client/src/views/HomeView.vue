<script>
import createAxiosInstance from "../services/http";
import { useAuth } from '../stores/auth';

const http = createAxiosInstance();


export default {
    data() {
        return {
            date: new Date(),
            selectedActivities: [], // Armazena as atividades do dia selecionado
            attributes: [
                // {
                //     key: 'event1',
                //     content: {
                //         style: {
                //             color: 'brown',
                //         }
                //     },
                //     highlight: {
                //         start: {
                //             fillMode: 'outline',
                //             color: 'red' // Cor do contorno no início
                //         },
                //         base: {
                //             fillMode: 'light',
                //             color: 'red' // Cor de fundo ao passar o mouse
                //         },
                //         end: {
                //             fillMode: 'outline',
                //             color: 'red' // Cor do contorno no fim
                //         },
                //     },
                //     dot: {
                //         style: {
                //             backgroundColor: 'brown',
                //         }
                //     },
                //     popover: {
                //         label: 'Hoje é um dia importante!',
                //         visibility: 'click'
                //     },
                //     customData: { event: "Feriado", description: "Dia Nacional de algo" },
                //     dates: { start: new Date('2024-10-24'), end: new Date('2024-10-27') },
                //     order: 0
                // },
            ]
        };
    },
    methods: {

        formatDate(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        },

        handleDayClick(day) {
            const selectedDate = day.date;
            // Filtra as atividades para a data selecionada
            this.selectedActivities = this.attributes.filter(attribute => {
                const startDate = new Date(attribute.dates.start).setHours(0, 0, 0, 0);
                const endDate = new Date(attribute.dates.end).setHours(0, 0, 0, 0);
                return selectedDate.setHours(0, 0, 0, 0) >= startDate && selectedDate.setHours(0, 0, 0, 0) <= endDate;
            });
        },

        async getAtividades() {
            const auth = useAuth();
            try {
                const data = await http.get('/atividades/' + auth.user.id);
                this.filterDados(data.data)
            }
            catch (error) {
                console.log(error.response.data)
            }
        },

        getColor(prioridade){
            switch(prioridade){
                case 'baixa':
                    return 'green'
                case 'media':
                    return 'blue'
                case 'alta':
                    return 'red' 

            }
        },

        filterDados(dados) {
            this.attributes = dados.map((atividade, index) => 
            ({
                
                key: `atividade-${index}`,
                content: {
                    style: {
                        color: `#${atividade.color}`,
                    }
                },
                highlight: this.getColor(atividade.priority),
                dot: {
                    style: {
                        backgroundColor: `#${atividade.color}`,
                    }
                },
                popover: {
                    label: `${atividade.name} - Prioridade: ${atividade.priority}`,
                    visibility: 'click'
                },
                customData: {
                    event: atividade.name,
                    description: `Prioridade: ${atividade.priority}`,
                },
                dates: {
                    start: (() => {
                        const date = atividade.data.split('/').reverse().join('-');
                        const startDate = new Date(date);
                        startDate.setDate(startDate.getDate() + 1); // Adiciona um dia
                        return startDate;
                    })(),
                    end: (() => {
                        const date = atividade.data.split('/').reverse().join('-');
                        const endDate = new Date(date);
                        endDate.setDate(endDate.getDate() + 1); // Adiciona um dia
                        return endDate;
                    })()
                },
                order: index
            }));
        }

    },

    created() {
        this.getAtividades()
    }
};
</script>

<template>
    <section class="flex flex-col justify-center items-center h-screen">
        <VCalendar locale="pt-br" :attributes="attributes" v-model="date" @dayclick="handleDayClick" />
        <div v-if="selectedActivities.length">
            <h2>Atividades do dia {{ formatDate(date) }}:</h2>
            <ul>
                <li v-for="activity in selectedActivities" :key="activity.key">
                    {{ activity.customData.event }}: {{ activity.customData.description }}
                </li>
            </ul>
        </div>
        <div v-else>
            <p>Nenhuma atividade para este dia.</p>
        </div>
    </section>
</template>

<style>
/* Customizações de estilo */
.vc-container .vc-weekday-1,
.vc-container .vc-weekday-7 {
    color: black;
}
</style>
