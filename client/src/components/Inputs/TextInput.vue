<template>
    <FloatLabel>
        <InputText id="username" v-model="internalValue" class="w-[260px]" :invalid="invalid"></InputText>
        <label for="username">{{ placeholder }}</label>
    </FloatLabel>
</template>
<script>
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
export default {
    props: {
        modelValue: {
            type: [String,null],
            required: true
        },
        placeholder: {
            required: true,
            type: String
        },
        invalid: {
            type: Boolean
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