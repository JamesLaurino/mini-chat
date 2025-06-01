<script setup>
import {Head, useForm} from '@inertiajs/vue3';


const props = defineProps({
    flash: {
        type:Array
    }
});

const form = useForm({
    message: '',
    model:''
})

const submit = () => {
    console.log(form.model)
    console.log(form.message)
    form.post('/ask')
}

</script>

<template>
    <Head title="Bienvenue" />
    <p>Welcome</p>
</template>
