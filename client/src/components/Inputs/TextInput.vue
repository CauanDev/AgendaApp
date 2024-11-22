<template>
    <div class="flex flex-col gap-1 text-left text-sm">
        <label>{{ placeholder }}</label>
        <InputText v-model="internalValue" class="w-[260px] p-inputtext"></InputText>
    </div>
</template>
<script>
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
export default {
    props: {
        modelValue: {
            type: [String, null],
            required: true
        },
        required: {
            type: Boolean,
            default: false
        },
        placeholder: {
            required: false,
            type: String
        }
    },
    components: { FloatLabel, InputText },
    computed: {
        internalValue: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        }
    }
};
</script>